<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\{PaymentMethodEnum, SituationProductEnum, StatusEnum};
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\{Product\UpdateSituationProductRequest, StoreProductRequest, UpdateProductRequest};
use App\Http\Requests\Dashboard\Checkout\StoreCheckoutRequest;
use App\Jobs\Dashboard\Members\RemoveRelationCourseJob;
use App\Jobs\Dashboard\Products\UploadFileLocalToS3Job;
use App\Mail\Products\{ProductAlreadyPublishedUpdated, ProductInAnalysis};
use App\Models\{CategoryProduct, Checkout, NotificationAction, PixelService, Product, ProductType, User, UtmLink};
use App\Repositories\NotificationActionRepository;
use App\Services\Members\SuitMembersApiService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};

class ProductController extends Controller
{
    public function index(): View
    {
        $productsShop = user()?->shop()?->products()
            ? QueryBuilder::for(user()->shop()->products())
                ->isProduct()
                ->with(['category', 'media', 'offers:id,parent_id,name,price'])
                ->allowedFilters([
                    'name',
                    AllowedFilter::partial('client_product_uuid'),
                    AllowedFilter::exact('category_id'),
                    AllowedFilter::callback('created_at', fn ($query, $value) => $query->whereDate('created_at', $value)),
                    AllowedFilter::exact('situation'),
                ])
                ->latest('id')
                ->paginate()
                ->withQueryString()
            : collect();

        $categories = CategoryProduct::active()->toBase()->get(['id', 'name']);
        $types      = ProductType::get();

        return view('dashboard.products.index', compact('productsShop', 'categories', 'types'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $shop    = user()->shop();
        $product = $shop->products()->create($request->validated());

        $checkout = $shop->checkouts()->create([
            'name'       => 'Checkout padrão',
            'product_id' => $product->id,
            'default'    => true,
            'settings'   => [
                'origin'                => 'SYSTEM',
                'allowCouponsDiscounts' => true,
            ],
        ]);

        $product->update(['checkout_id' => $checkout->id]);

        return to_route('dashboard.products.edit', $product->client_product_uuid)
            ->with('success', 'Produto criado e agora você completar as informações.');
    }

    public function edit(string $productUuid): View
    {
        $product = Product::where('client_product_uuid', $productUuid)->firstOrFail();

        $this->authorize('edit', $product);

        $user = user();
        $product->load([
            'media',
            'category:id,name',
            'type:id',
            'offersPaymentUnique',
            'offersPaymentRecurring',
            'couponsDiscount' => fn ($query) => $query->with([
                'offers:id,name',
            ]),
            'pixels' => function ($query) {
                $query->with('pixelService')->whereNull('user_id');
            },
            'orderBumps' => fn ($query) => $query->with([
                'product:id,name',
                'product_offer:id,name',
            ]),
            'upSells' => fn ($query) => $query->with([
                'product:id,name',
                'product_offer:id,name,price',
            ]),
            'domains',
            'checkout',
            'coproducers' => fn ($query) => $query->latest('id'),
        ]);

        $categories = CategoryProduct::active()->toBase()->get(['id', 'name']);

        $checkoutsShop = $user->shop()->checkouts()
            ->active()
            ->whereRaw("JSON_EXTRACT(`settings`, '$.origin') IS NULL OR JSON_EXTRACT(`settings`, '$.origin') != 'SYSTEM' OR (`product_id` = ? AND JSON_EXTRACT(`settings`, '$.origin') = 'SYSTEM')", [$product->id])
            ->orderByRaw('CASE WHEN product_id IS NOT NULL THEN 1 ELSE 2 END ASC, checkouts.created_at DESC')
            ->toBase()
            ->get(['id', 'name', 'default']);

        $productsShop = $user->shop()->products()
            ->isProduct()
            ->isPublished()
            ->isPaymentUnique()
            ->where('id', '!=', $product->id)
            ->with(['offers:id,name,parent_id'])
            ->get(['id', 'name', 'parent_id']);

        $pixelServices = PixelService::toBase()->get(['id', 'name', 'image_url']);

        $activeOffers = $product->activeOffers($product->paymentType ?? '')->get();

        $utmLinks = UtmLink::with('product:id,name,parent_id,code')
            ->whereIntegerInRaw('product_id', $activeOffers->pluck('id'))
            ->orderByDesc('created_at')
            ->get();

        $this->checkCreateProducerSuitMembers($product, $user);
        $categoriesMembers = $this->getCategoriesSuitMembers($product, $user);
        $courseSuitMembers = $this->getCourseSuitMembersData($product, $user);

        return view('dashboard.products.form', compact(
            'product',
            'categories',
            'productsShop',
            'pixelServices',
            'activeOffers',
            'checkoutsShop',
            'categoriesMembers',
            'courseSuitMembers',
            'utmLinks'
        ));
    }

    private function checkCreateProducerSuitMembers(Product $product, User $user): void
    {
        if ($product->isTypeSuitMembers) {
            (new MemberController)->createProduceSuitMembers($user);
        }
    }

    private function getCategoriesSuitMembers(Product $product, User $user): array
    {
        if (! $product->isTypeSuitMembers) {
            return [];
        }

        $secret                = $user->shop()->client_secret_members;
        $suitMembersApiService = new SuitMembersApiService(30, $secret);
        $route                 = 'categories';

        return $suitMembersApiService->get($route)['data'];
    }

    private function getCourseSuitMembersData(Product $product, User $user): array
    {
        if (! $product->isTypeSuitMembers) {
            return [];
        }

        $secret                = $user->shop()->client_secret_members;
        $suitMembersApiService = new SuitMembersApiService(30, $secret);
        $route                 = 'courses/ref/' . $product->client_product_uuid;

        try {
            return $suitMembersApiService->get($route)['data'];
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse|JsonResponse
    {
        $product->update($request->input('product'));

        $hasOffersChanges = false;
        $hasNewOffer      = false;

        if ($request->filled('product.offersPaymentUnique')) {
            foreach ($request->input('product.offersPaymentUnique') as $offerPaymentUnique) {
                $offerId = $offerPaymentUnique['id'] ?? null;

                if ($product->isPublished && $offerId) {
                    $existingOffer = $product->offersPaymentUnique()->find($offerId);

                    if ($existingOffer) {
                        $inputData = array_intersect_key($offerPaymentUnique, $existingOffer->getAttributes());

                        $originalData = array_intersect_key($existingOffer->toArray(), $inputData);
                        $changesOffer = array_diff_assoc($inputData, $originalData);

                        if (! empty($changesOffer)) {

                            $originalData = array_filter(array_intersect_key($originalData, $changesOffer));

                            if (isset($changesOffer['price'])) {
                                $value = $changesOffer['price'];
                                $value = preg_replace('/[^\d.,]/', '', $value);
                                $value = str_replace(['.', ','], ['', '.'], $value);
                                $value = number_format((float) $value, 2, '.', '');

                                $changesOffer['price'] = $value;
                            }

                            $product->revisions()->create([
                                'user_id'   => auth()->id(),
                                'offer_id'  => $offerId,
                                'key'       => 'oferta',
                                'old_value' => $originalData,
                                'new_value' => $changesOffer,
                            ]);
                        }
                    }

                    continue;
                }
                $offerPaymentUnique['type_id'] = $product->type_id;
                $offer                         = $product->offersPaymentUnique()->updateOrCreate(['id' => $offerId], $offerPaymentUnique);

                if ($product->isPublished && $offer->wasRecentlyCreated) {
                    $offer->update([
                        'status'    => StatusEnum::INACTIVE->name,
                        'situation' => SituationProductEnum::IN_ANALYSIS->name,
                    ]);

                    unset($offerPaymentUnique['parent_id'], $offerPaymentUnique['shop_id']);

                    $product->revisions()->create([
                        'user_id'   => auth()->id(),
                        'offer_id'  => $offer->id,
                        'key'       => 'novaOferta',
                        'old_value' => [],
                        'new_value' => $offerPaymentUnique,
                    ]);
                } else if ($offer->wasRecentlyCreated) {
                    $hasOffersChanges = $hasNewOffer = true;
                }
            }
        }

        if ($request->filled('product.offersPaymentRecurring')) {
            foreach ($request->input('product.offersPaymentRecurring') as $offerPaymentRecurring) {
                $offerId = $offerPaymentRecurring['id'] ?? null;

                if ($product->isPublished && $offerId) {
                    $existingOffer = $product->offersPaymentRecurring()->find($offerId);

                    if ($existingOffer) {
                        $inputData = array_intersect_key($offerPaymentRecurring, $existingOffer->getAttributes());

                        $originalData = array_intersect_key($existingOffer->toArray(), $inputData);
                        $changesOffer = array_diff_assoc($inputData, $originalData);

                        if (! empty($changesOffer)) {

                            $originalData = array_filter(array_intersect_key($originalData, $changesOffer));

                            foreach (['price', 'priceFirstPayment'] as $key) {
                                if (isset($changesOffer[$key])) {
                                    $value              = preg_replace('/[^\d.,]/', '', $changesOffer[$key]);
                                    $value              = str_replace(['.', ','], ['', '.'], $value);
                                    $changesOffer[$key] = number_format((float) $value, 2, '.', '');
                                }
                            }

                            $product->revisions()->create([
                                'user_id'   => auth()->id(),
                                'offer_id'  => $offerId,
                                'key'       => 'oferta',
                                'old_value' => $originalData,
                                'new_value' => $changesOffer,
                            ]);
                        }
                    }

                    continue;
                }
                $offerPaymentRecurring['type_id'] = $product->type_id;
                $offer                            = $product->offersPaymentRecurring()
                    ->updateOrCreate(['id' => $offerPaymentRecurring['id'] ?? null], $offerPaymentRecurring);

                if ($product->isPublished && $offer->wasRecentlyCreated) {
                    $offer->update([
                        'status'    => StatusEnum::INACTIVE->name,
                        'situation' => SituationProductEnum::IN_ANALYSIS->name,
                    ]);

                    unset($offerPaymentRecurring['parent_id'], $offerPaymentRecurring['shop_id']);

                    foreach (['price', 'priceFirstPayment'] as $key) {
                        if (isset($offerPaymentRecurring[$key])) {
                            $value              = preg_replace('/[^\d.,]/', '', $offerPaymentRecurring[$key]);
                            $value              = str_replace(['.', ','], ['', '.'], $value);
                            $offerPaymentRecurring[$key] = number_format((float) $value, 2, '.', '');
                        }
                    }

                    $product->revisions()->create([
                        'user_id'   => auth()->id(),
                        'offer_id'  => $offer->id,
                        'key'       => 'novaOferta',
                        'old_value' => [],
                        'new_value' => $offerPaymentRecurring,
                    ]);
                } else if ($offer->wasRecentlyCreated) {
                    $hasOffersChanges = $hasNewOffer = true;
                }
            }
        }

        if ($request->input('tab') == 'info') {
            if ($product->getMedia('attachment')->isNotEmpty()
                && ($product->getValueSchemalessAttributes('allowAttachment') != $request->input('product.attributes.allowAttachment'))) {
                $hasOffersChanges = true;
            }
        }

        if ($request->input('tab') == 'config') {
            $existingOrderBumpsIds  = $product->orderBumps()->pluck('order_bumps.id')->toArray();
            $submittedOrderBumpsIds = array_column($request->input('product.orderBumps', []), 'id');
            $orderBumpsToRemove     = array_diff($existingOrderBumpsIds, array_filter($submittedOrderBumpsIds));

            $product->orderBumps()->whereIntegerInRaw('order_bumps.id', $orderBumpsToRemove)->delete();

            if ($request->filled('product.orderBumps')) {
                foreach ($request->input('product.orderBumps') as $orderBump) {
                    $product->orderBumps()
                        ->updateOrCreate(['order_bumps.id' => $orderBump['id'] ?? null], $orderBump);
                }
            }

            $existingPixelsIds  = $product->pixels()->pluck('pixels.id')->toArray();
            $submittedPixelsIds = array_column($request->input('product.pixels', []), 'id');
            $pixelsToRemove     = array_diff($existingPixelsIds, array_filter($submittedPixelsIds));

            $product->pixels()->whereIntegerInRaw('pixels.id', $pixelsToRemove)->delete();

            if ($request->filled('product.pixels')) {
                foreach ($request->input('product.pixels') as $pixelData) {
                    $pixel = $product->pixels()->updateOrCreate(['pixels.id' => $pixelData['id'] ?? null], $pixelData);

                    if (! empty($pixelData['attributes'])) {
                        $pixel->attributes->set($pixelData['attributes'] ?? []);
                        $pixel->save();
                    }
                }
            }

            $existingUpSellsIds  = $product->upSells()->pluck('up_sells.id')->toArray();
            $submittedUpSellsIds = array_column($request->input('product.upSells', []), 'id');
            $UpSellsToRemove     = array_diff($existingUpSellsIds, array_filter($submittedUpSellsIds));

            $product->upSells()->whereIntegerInRaw('up_sells.id', $UpSellsToRemove)->delete();

            if ($request->filled('product.upSells')) {
                foreach ($request->input('product.upSells') as $upSellData) {
                    $upSell = $product->upSells()->updateOrCreate(['up_sells.id' => $upSellData['id'] ?? null], $upSellData);

                    if (! empty($upSellData['attributes'])) {

                        if (! isset($upSellData['attributes']['showProductTitle'])) {
                            $upSell->attributes->forget('showProductTitle');
                        }

                        if (! isset($upSellData['attributes']['showProductPrice'])) {
                            $upSell->attributes->forget('showProductPrice');
                        }

                        if (! isset($upSellData['attributes']['showProductImage'])) {
                            $upSell->attributes->forget('showProductImage');
                        }

                        $upSell->attributes->set($upSellData['attributes'] ?? []);
                        $upSell->save();
                    }
                }
            }

            if ($request->has('checkout.settings.allowCouponsDiscounts')) {
                $checkout           = $product->checkout;
                $checkout->settings = array_merge($checkout->settings ?? [], ['allowCouponsDiscounts' => $request->input('checkout.settings.allowCouponsDiscounts')]);
                $checkout->save();
            }

            if ($request->isNotFilled('product.attributes.paymentMethods')) {
                $product->attributes->forget('paymentMethods');
            }

            if ($request->isNotFilled('product.attributes.redirectToTelegramLink')) {
                $product->attributes->forget('redirectToTelegramLink');
            }
        }

        if ($request->filled('media.attachmentFromChuncking')) {
            UploadFileLocalToS3Job::dispatch(
                $product,
                auth()->id(),
                $request->input('media.attachmentFromChuncking'),
                $request->input('media.attachment.description') ?? '',
                'attachment'
            );
        }

        if ($request->files->has('media')) {

            if ($product->getMedia('attachment')->isEmpty()) {
                foreach ($request->files->get('media') as $collectionName => $files) {
                    $this->handleMediaFiles($product, $collectionName, $files);
                }
            } else if ($request->hasFile('media.attachment')) {
                $currentAttachment = $product->getMedia('attachment')->last();

                $revision = $product->revisions()->create([
                    'user_id'   => auth()->id(),
                    'key'       => 'anexo',
                    'old_value' => $currentAttachment,
                ]);

                $mediaAttachmentProduct = $revision->addMedia($request->file('media.attachment'))
                    ->usingName($currentAttachment->name)
                    ->withCustomProperties(['description' => $currentAttachment->custom_properties['description'] ?? ''])
                    ->toMediaCollection('attachmentProduct');

                $revision->update(['new_value' => $mediaAttachmentProduct]);
            }
        }

        if ($product->getMedia('attachment')->isNotEmpty() && $request->filled('media.attachment')) {
            $currentAttachment = $product->getMedia('attachment')->last();

            $dataCurrentAttachment = [
                'name'        => $currentAttachment->name,
                'description' => $currentAttachment->custom_properties['description'] ?? '',
            ];

            $originalData      = array_intersect_key($dataCurrentAttachment, $request->input('media.attachment', []));
            $changesAttachment = array_diff_assoc($request->input('media.attachment', []), $originalData);

            if (! empty($changesAttachment)) {
                $product->revisions()->create([
                    'user_id'   => auth()->id(),
                    'key'       => 'dadosAnexo',
                    'old_value' => array_intersect_key($originalData, $changesAttachment),
                    'new_value' => $changesAttachment,
                ]);
            }
        }

        if ($request->filled('removeMedia')) {
            foreach ($request->input('removeMedia') as $mediaId) {
                $product->deleteMedia($mediaId);
            }

            $hasOffersChanges = true;
        }

        $product->attributes->set($request->input('product.attributes', []));

        $shouldNotifyUserShop = $product->isPublished && (
            $product->wasChanged(['name', 'paymentType']) && $product->shop->owner->email || $hasOffersChanges
        );

        if ($product->isPublished && ($hasNewOffer || $hasOffersChanges)) {
            $product->situation = SituationProductEnum::DRAFT->name;
            $product->offers()->update(['situation' => SituationProductEnum::DRAFT->name]);
        }

        if (($hasNewOffer || $hasOffersChanges) and $product->isRecurring and ! $product->hasPaymentMethod(PaymentMethodEnum::CREDIT_CARD->name)) {
            $product->attributes->forget('paymentMethods');
        }

        $product->save();

        if ($shouldNotifyUserShop) {
            Mail::to($product->shop->owner->email)->queue(new ProductAlreadyPublishedUpdated($product));
        }

        $this->updateSuitMembersCategory($product);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'As atualizações do produto foram salvas.']);
        }

        return to_route('dashboard.product.index');
    }

    private function updateSuitMembersCategory(Product $product): void
    {
        if (! $product->isTypeSuitMembers) {
            return;
        }

        try {
            $route                 = 'courses/' . $product->client_product_uuid . '/category';
            $body                  = ['categoryId' => $product->category_id];
            $tokenAdmin            = config('services.members.token');
            $suitMembersApiService = new SuitMembersApiService(15, $tokenAdmin, 'admin');
            $suitMembersApiService->put($route, $body);
        } catch (\Throwable $th) {
            return;
        }
    }

    public function show(Product $product): View
    {
        $this->authorize('show', $product);

        $product->load('category');

        return view('dashboard.products.show', compact('product'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        if (
            $product->isPublished
            and $product->isOffer
            and $product->parentProduct->offers()->count() == 1
        ) {
            return back()->with('error', 'Remoção indisponível! O produto precisa de pelo menos 1 oferta ativa.');
        }

        foreach ($product->offers()->withTrashed()->get() as $offer) {
            $offer->abandonedCarts()->delete();
            $offer->delete();
        }

        $product->delete();

        $this->checkNeedDisableNotification($product);

        return back()->with('success', request()->input('message', 'Produto excluído com sucesso.'));
    }

    private function checkNeedDisableNotification(Product $product): void
    {
        $notificationRepository = new NotificationActionRepository(new NotificationAction);
        $notificationAction     = $notificationRepository
            ->getByProductId($product->id)->first();

        if (is_null($notificationAction)) {
            return;
        }

        $notificationRepository->update(['status' => false], $notificationAction->id);
    }

    public function toogleStatus(Product $product, Request $request): RedirectResponse
    {
        $status = $product->isActive ? StatusEnum::INACTIVE->name : StatusEnum::ACTIVE->name;

        $product->update(['status' => $status]);
        $product->refresh();
        $user = auth()->user();
        RemoveRelationCourseJob::dispatch($product, $user);
        $message = $product->isActive ? 'Link ativado com sucesso.' : 'Link desativado com sucesso.';
        return back()->withFragment('tab=tab-links')->with('success', $message);
    }

    public function updateSituation(UpdateSituationProductRequest $request, Product $product): RedirectResponse
    {
        $product->attributes->set(['lastSituation' => $product->situation]);
        $product->situation = $request->situation;
        $product->save();

        match ($product->situation) {
            SituationProductEnum::IN_ANALYSIS->name => Mail::to($product->shop->owner->email)->send(new ProductInAnalysis($product)),
            default                                 => null,
        };

        return to_route('dashboard.products.index')
            ->with('modalMessage', 'Situação atualizada com sucesso! <br> Aguarde a análise da equipe.');
    }

    public function disable(Product $product): RedirectResponse
    {
        $product->attributes->set(['lastSituation' => $product->situation]);
        $product->situation = SituationProductEnum::DISABLE->name;
        $product->saveQuietly();

        return to_route('dashboard.products.index')
            ->with('modalMessage', 'Produto desativado.');
    }

    public function enable(Product $product): RedirectResponse
    {
        $product->situation = $product->getValueSchemalessAttributes('lastSituation');
        $product->saveQuietly();

        return to_route('dashboard.products.index')
            ->with('modalMessage', 'Produto reativado.');
    }

    public function search(): JsonResponse
    {
        $products = Product::select(['id', 'name'])
            ->isProduct()
            ->when(request('q'), function (Builder $query, $search) {
                $query->whereAny(['name', 'description'], 'LIKE', "%$search%");
            })
            ->cursor();

        return response()->json($products);
    }

    public function storeCheckout(StoreCheckoutRequest $request, Product $product): RedirectResponse
    {
        $checkout = user()->shop()
            ->checkouts()
            ->create($request->only(['name', 'default']) + ['product_id' => $product->id]);

        if ($request->validated('default')) {
            $checkout->update(['default' => true]);
            $product->update(['checkout_id' => $checkout->id]);
        }

        return back()
            ->withFragment('tab=tab-checkout')
            ->with('success', 'Modelo de checkout criado e vinculado ao produto.');
    }

    public function updateCheckout(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'checkout_id' => ['required', Rule::exists('checkouts', 'id')->where('shop_id', user()->shop()->id)],
        ]);

        $product->update($request->only('checkout_id'));
        Checkout::find($request->checkout_id)->update(['default' => true]);

        return back()
            ->withFragment('tab=tab-checkout')
            ->with('success', 'Modelo de checkout vinculado ao produto.');
    }

    public function checkUniqueProductName(Request $request): JsonResponse
    {
        $shop = user()->shop();

        $request->validate([
            'name'      => ['required'],
            'id'        => ['sometimes'],
            'parent_id' => ['required', function ($attribute, $value, $fail) use ($shop) {
                if (! $shop->products()->where('id', $value)->exists()) {
                    $fail('produto não existe.');
                }
            }],
        ]);

        $productName = $request->input('name');
        $productId   = $request->input('id');
        $parentId    = $request->input('parent_id');

        $exists = $shop->products()
            ->where('parent_id', $parentId)
            ->where('name', $productName)
            ->when($productId, fn ($query) => $query->where('id', '!=', $productId))
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function handleMediaFiles($product, $fileCollectionName, $requestFiles): void
    {
        $existingMedia     = $product->getMedia($fileCollectionName);
        $existingFileNames = $existingMedia->pluck('file_name')->toArray();
        $requestFiles      = is_array($requestFiles) ? $requestFiles : [$requestFiles];
        $requestFiles      = array_filter($requestFiles);

        foreach ($existingMedia as $media) {
            if (! in_array($media->file_name, $requestFiles)) {
                $media->delete();
            }
        }

        foreach ($requestFiles as $file) {
            if (! in_array($file, $existingFileNames)) {
                $product->addMedia($file)
                    ->usingName(request()->input("media.$fileCollectionName.name", pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)))
                    ->withCustomProperties(['description' => request()->input("media.$fileCollectionName.description", '')])
                    ->toMediaCollection($fileCollectionName);
            }
        }
    }
}

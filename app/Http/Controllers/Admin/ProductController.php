<?php

namespace App\Http\Controllers\Admin;

use App\Enums\{SituationProductEnum, StatusEnum};
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\{MassDestroyProductRequest, StoreProductRequest, UpdateProductRequest};
use App\Mail\Products\{ProductPublished, ProductReproved};
use App\Models\{CategoryProduct, Product, RevisionsProduct, Shop};
use App\Services\Members\SuitMembersApiService;
use Gate;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Log, Mail};
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = QueryBuilder::for(Product::class)
            ->isProduct()
            ->with([
                'media',
                'category:id,name',
                'shop:id,owner_id,name,username_banking,link',
                'shop.owner:id,name,email',
            ])
            ->withCount(['revisions' => fn ($query) => $query->where('status', '!=', 'approved')])
            ->allowedFilters([
                'name',
                'category.name',
                AllowedFilter::partial('shop', 'shop.name'),

                AllowedFilter::callback('ownerShop', function ($query, $value) {
                    $query->whereHas('shop', function ($query) use ($value) {
                        $query->where('username_banking', 'like', "%{$value}%")
                            ->orWhereHas('owner', function ($query) use ($value) {
                                $query->where('name', 'like', "%{$value}%");
                            });
                    });
                }),
                AllowedFilter::exact('situation'),
                AllowedFilter::callback(
                    'withRevisionPending',
                    fn ($query) => $query->whereHas('revisions', fn ($query) => $query->where('status', 'pending'))
                ),
            ])
            ->orderByDesc('updated_at')
            ->paginate()
            ->withQueryString();

        $quantityProductsWithRevisionsPending = RevisionsProduct::distinct('product_id')
            ->where('status', '=', 'pending')
            ->count('product_id');

        return view('admin.products.index', compact('products', 'quantityProductsWithRevisionsPending'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = CategoryProduct::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shops = Shop::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.products.form', compact('categories', 'shops'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());

        if ($request->input('photo', false)) {
            $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $product->id]);
        }

        return to_route('admin.products.index');
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = CategoryProduct::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shops = Shop::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product->load('category');

        return view('admin.products.form', compact('categories', 'shops', 'product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        if ($request->input('photo', false)) {
            if (! $product->photo || $request->input('photo') !== $product->photo->file_name) {
                if ($product->photo) {
                    $product->photo->delete();
                }
                $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($product->photo) {
            $product->photo->delete();
        }

        $this->checkAproveSuitMembers($product);

        return to_route('admin.products.index');
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load([
            'category',
            'shop.owner',
            'pixels.pixelService',
            'couponsDiscount',
            'coproducers',
            'orderBumps' => fn ($query) => $query->with([
                'product:id,name',
                'product_offer:id,name',
            ]),
            'upSells' => fn ($query) => $query->with([
                'product:id,name',
                'product_offer:id,name',
            ]),
        ]);

        $activeOffers = $product->activeOffers($product->paymentType ?? '')->get();

        return view('admin.products.show', compact('product', 'activeOffers'));
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        $products = Product::find(request('ids'));

        foreach ($products as $product) {
            $product->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_create') && Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Product;
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function review(Product $product): View
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.products.approve', compact('product'));
    }

    public function updateSituation(Request $request, Product $product): RedirectResponse
    {
        $product->situation = $request->situation;
        $product->attributes->set($request->input('product.attributes', []));
        $product->save();

        match ($product->situation) {
            SituationProductEnum::PUBLISHED->name => Mail::to($product->shop->owner->email)->send(new ProductPublished($product)),
            SituationProductEnum::REPROVED->name  => Mail::to($product->shop->owner->email)->send(new ProductReproved($product)),
            default                               => null,
        };

        $this->checkAproveSuitMembers($product);

        return back()->with('success', 'Situação atualizada com sucesso!');
    }

    public function revisions(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load(['category']);

        $activeOffers = $product->activeOffers($product->paymentType ?? '')->get();

        return view('admin.products.revisions', compact('product', 'activeOffers'));
    }

    public function updateRevision(Product $product, RevisionsProduct $revisionsProduct, Request $request): RedirectResponse
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $revisionsProduct->update($request->only('status'));

        if ($revisionsProduct->isApproved) {
            if ($revisionsProduct->key === 'oferta') {
                $revisionsProduct->offer()->update($revisionsProduct->new_value);
            }

            if ($revisionsProduct->key === 'novaOferta') {
                $revisionsProduct->offer()
                    ->update($revisionsProduct->new_value + ['status' => StatusEnum::ACTIVE->name, 'situation' => SituationProductEnum::PUBLISHED->name]);
            }

            if ($revisionsProduct->key === 'anexo' && $revisionsProduct->getMedia('attachmentProduct')->isNotEmpty()) {
                $revisionsProduct->product->clearMediaCollection('attachment');
                $mediaRevision = $revisionsProduct->getMedia('attachmentProduct')->last();
                $mediaRevision->copy($product, 'attachment');
            }

            if ($revisionsProduct->key === 'dadosAnexo') {
                $lastAttachment = $revisionsProduct->product->getMedia('attachment')->last();

                foreach ($revisionsProduct->new_value as $key => $value) {
                    if ($key === 'description') {
                        $lastAttachment->setCustomProperty('description', $value)->save();
                    }

                    if ($key === 'name') {
                        $lastAttachment->update(['name' => $value]);
                    }
                }
            }

            if ($revisionsProduct->key === 'orderBump') {
                $revisionsProduct->orderBump?->update($revisionsProduct->new_value);
            }

            if ($revisionsProduct->key === 'novoOrderbump') {
                $revisionsProduct->orderBump
                    ?->update($revisionsProduct->new_value + ['status' => StatusEnum::ACTIVE->name]);
            }
        }

        return back()->with('success', 'Revisão atualizada com sucesso!');
    }

    private function checkAproveSuitMembers(Product $product): void
    {
        if (! $product->isTypeSuitMembers) {
            return;
        }

        try {
            $route                 = 'courses/' . $product->client_product_uuid . '/enable';
            $tokenAdmin            = config('services.members.token');
            $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
            $suitMembersApiService->put($route);
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao habilitar curso na api de membros.',
                [
                    'error'    => $th->getMessage(),
                    'function' => 'ProductController.checkAproveSuitMembers',
                    'route'    => $route,
                ]
            );
        }
    }

}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UpdateAppRequest;
use App\Models\{App, User};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppController extends Controller
{
    public function index(): View
    {
        $user     = user();
        $shopUser = user()->shop();

        $tokensUserShop = $user->tokens->filter(fn ($token) => $token->name !== User::NAME_TOKEN_FROM_BANKING && ! str_contains($token->name, 'Java'));

        $webHooksShopUser = $shopUser->webhooks()->with('products:id,name')->get();

        $appsShopUser = App::query()
            ->where('apps.status', '=', StatusEnum::ACTIVE->name)
            ->leftJoin('app_shop', function ($join) use ($shopUser) {
                $join->on('apps.id', '=', 'app_shop.app_id')
                    ->where('app_shop.shop_id', '=', $shopUser->id);
            })
            ->selectRaw('apps.*, app_shop.data as dataShopUser')
            ->groupBy('apps.id')
            ->with('appShop.products:id,name')
            ->get()
            ->keyBy('slug');

        $productsShop = $shopUser->products()
            ->isProduct()
            ->isPublished()
            ->toBase()
            ->get(['id', 'name']);

        return view('dashboard.apps.index', compact('appsShopUser', 'webHooksShopUser', 'tokensUserShop', 'productsShop'));
    }

    public function update(App $app, UpdateAppRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $appShop = user()->shop()->apps()->updateOrCreate([
            'app_id' => $app->id,
        ], [
            'data'   => $data,
            'status' => $data['status'],
        ]);

        if ($request->filled('product_id')) {
            $productIds = $request->collect('product_id')->filter()->toArray();

            $appShop->products()->sync($productIds);
        }

        return back()->with('success', 'Salvo com sucesso');
    }
}

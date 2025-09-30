<?php

namespace App\Http\Controllers\Dashboard;


use App\Enums\StatusEnum;
use App\Http\Requests\Dashboard\Checkout\StoreCheckoutRequest;
use App\Http\Requests\Dashboard\Checkout\UpdateCheckoutRequest;
use App\Models\Checkout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CheckoutController
{
    public function index(): View
    {
        $checkouts = user()->shop()->checkouts()->latest('id')->get();

        return view('dashboard.checkout.index', compact('checkouts'));
    }

    public function create(): View
    {
        return view('dashboard.checkout.form');
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $checkout = user()->shop()->checkouts()->create($request->all());

        if ($request->files->has('media')) {
            foreach ($request->files->get('media') as $collectionName => $files) {
                handleMediaFiles($checkout, $collectionName, $files);
            }
        }

        return to_route('dashboard.checkouts.edit', $checkout)->with('success', 'Checkout criado com sucesso.');
    }

    public function edit(Checkout $checkout): View
    {
        Gate::authorize('edit', $checkout);

        $checkout->load('product:id,name,paymentType,client_product_uuid');

        return view('dashboard.checkout.form', compact('checkout'));
    }

    public function update(UpdateCheckoutRequest $request, Checkout $checkout): RedirectResponse
    {
        $checkout->update($request->all());

        if ($request->files->has('media')) {
            foreach ($request->files->get('media') as $collectionName => $files) {
                handleMediaFiles($checkout, $collectionName, $files);
            }
        }

        $existingMediaIds  = $checkout->getMedia('*')->pluck('id')->toArray();
        $submittedMediaIds = $request->input('media', []);
        $mediasToRemove    = array_diff($existingMediaIds, array_filter($submittedMediaIds));

        $checkout->media()->whereIntegerInRaw('id', $mediasToRemove)->delete();

        return back()->with('success', 'Checkout atualizado com sucesso.');
    }

    public function destroy(Checkout $checkout): RedirectResponse
    {
        $checkout->update(['status' => StatusEnum::INACTIVE->name]);
        $checkout->delete();

        return back()
            ->withFragment('tab=tab-checkout')
            ->with('success', 'Checkout removido com sucesso.');
    }

}

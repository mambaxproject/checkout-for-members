<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreUTMLinkRequest;
use App\Models\UtmLink;
use Illuminate\Http\RedirectResponse;

class ProductUtmLinkController extends Controller
{
    public function store(StoreUTMLinkRequest $request): RedirectResponse
    {
        UtmLink::create($request->validated());

        return back()
            ->withFragment('tab=tab-links')
            ->with('success', 'Link criado com sucesso');
    }

    public function update(StoreUTMLinkRequest $request, UtmLink $utmLink): RedirectResponse
    {
        $utmLink->update($request->validated());

        return back()
            ->withFragment('tab=tab-links')
            ->with('success', 'Link atualizado com sucesso');
    }

    public function destroy(UtmLink $utmLink): RedirectResponse
    {
        $utmLink->delete();

        return back()
            ->withFragment('tab=tab-links')
            ->with('success', 'Link deletado com sucesso');
    }
}

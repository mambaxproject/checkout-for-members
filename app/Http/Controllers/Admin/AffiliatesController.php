<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAffiliateRequest;
use App\Http\Requests\StoreAffiliateRequest;
use App\Http\Requests\UpdateAffiliateRequest;
use App\Models\Affiliate;
use App\Models\Product;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AffiliatesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('affiliate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $affiliates = Affiliate::with('user', 'products')
            ->latest()
            ->get();

        return view('admin.affiliates.index', compact('affiliates'));
    }

    public function create()
    {
        abort_if(Gate::denies('affiliate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id');

        return view('admin.affiliates.form', compact('products'));
    }

    public function store(StoreAffiliateRequest $request)
    {
        $userAffiliate = User::firstOrCreate(
            ['document_number' => $request->input('document_number')],
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('document_number')),
            ]
        );

        $affiliate = Affiliate::create($request->all() + ['user_id' => $userAffiliate->id]);

        $affiliate->products()->sync($request->input('products', []));

        return redirect()->route('admin.affiliates.index');
    }

    public function edit(Affiliate $affiliate)
    {
        abort_if(Gate::denies('affiliate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.affiliates.form', compact('affiliate', 'products'));
    }

    public function update(UpdateAffiliateRequest $request, Affiliate $affiliate)
    {
        $affiliate->update($request->all());

        $affiliate->products()->sync($request->input('products', []));

        return redirect()->route('admin.affiliates.index');
    }

    public function show(Affiliate $affiliate)
    {
        abort_if(Gate::denies('affiliate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.affiliates.show', compact('affiliate'));
    }

    public function destroy(Affiliate $affiliate)
    {
        abort_if(Gate::denies('affiliate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $affiliate->delete();

        return back();
    }

    public function massDestroy(MassDestroyAffiliateRequest $request)
    {
        $affiliates = Affiliate::find(request('ids'));

        foreach ($affiliates as $affiliate) {
            $affiliate->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

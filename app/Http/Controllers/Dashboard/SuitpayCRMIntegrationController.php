<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCRMRuleRequest;
use App\Models\CRMRule;
use App\Services\SuitPay\Endpoints\SuitpayCRMService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SuitpayCRMIntegrationController extends Controller
{
    public function index(): View
    {
        $shop        = Auth::user()->shop();
        $rules       = $shop->crmRules;
        $isActivated = $shop->getValueSchemalessAttributes('suitpay_crm');

        $pipelines = (new SuitpayCRMService(
            $shop->client_id_banking,
            $shop->client_secret_banking,
        ))->getPipelines();

        return view('dashboard.suitpayCRMIntegration.index', compact('pipelines', 'rules', 'isActivated'));
    }

    public function activeCRM(): RedirectResponse
    {
        $shop   = Auth::user()->shop();
        $status = $shop->getValueSchemalessAttributes('suitpay_crm');

        $shop->attributes->set('suitpay_crm', !boolval($status));
        $shop->save();

        return !$status ? back()->with('success', 'CRM ativado com sucesso.') : back()->with('success', 'CRM desativado com sucesso.');
    }

    public function store(StoreCRMRuleRequest $request): RedirectResponse
    {
        $rule = Auth::user()->shop()->crmRules()->create($request->validated());

        if (! $rule) {
            back()->with('error', 'Erro ao criar regra.');
        }

        return back()->with('success', 'Regra criada com sucesso.');
    }

    public function update(StoreCRMRuleRequest $request , CRMRule $suitpayCrmIntegration): RedirectResponse
    {
        $suitpayCrmIntegration->update($request->validated());

        return back()->with('success', 'Regra atualizada com sucesso.');
    }

    public function updateStatus(CRMRule $suitpayCrmIntegration): RedirectResponse
    {
        $suitpayCrmIntegration->update([
            'status' => $suitpayCrmIntegration->status == StatusEnum::ACTIVE->name ? StatusEnum::INACTIVE->name : StatusEnum::ACTIVE->name]);

        return $suitpayCrmIntegration->isActive ? back()->with('success', 'Regra foi ativada com sucesso.') : back()->with('success', 'Regra foi desativada com sucesso.');
    }

    public function destroy(CRMRule $suitpayCrmIntegration): RedirectResponse
    {
        $suitpayCrmIntegration->delete();

        return back()->with('success', 'Regra deletada com sucesso.');
    }
}

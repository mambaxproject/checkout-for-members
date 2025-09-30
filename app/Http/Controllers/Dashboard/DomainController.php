<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreDomainRequest;
use App\Models\{Domain, Product};
use App\Services\SingularCDN\SingularCDNService;
use Illuminate\Http\{RedirectResponse};

class DomainController extends Controller
{
    public function __construct(
        public SingularCDNService $singularCDNService
    ) {}

    public function store(Product $product, StoreDomainRequest $request): RedirectResponse
    {
        $domain = $product->domains()->create($request->validated());

        $responseNewDomain = $this->singularCDNService->addDomain($request->validated('domain'));

        $domain->attributes->set('singularCDN', $responseNewDomain);
        $domain->save();

        return back()
            ->withFragment('tab=tab-links')
            ->with('domainCreated', true)
            ->with('success', 'Domínio cadastrado com sucesso.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        if ($singularCdnId = $domain->getValueSchemalessAttributes('singularCDN.detalhes.id')) {
            $this->singularCDNService->deleteDomain($singularCdnId);
        }

        $domain->forceDelete();

        return back()
            ->withFragment('tab=tab-links')
            ->with('success', 'Domínio excluído com sucesso.');
    }

    public function checkDns(Domain $domain): RedirectResponse
    {
        $recordsDnsVerify = array_filter(
            dns_get_record($domain['dns']['url'], DNS_CNAME),
            fn ($record) => $record['target'] == $domain->dns['value']
        );

        $message = $domain->verified
            ? ['info' => 'Domínio já verificado.']
            : (count($recordsDnsVerify)
                ? ['success' => 'DNS verificado com sucesso. Aguarde até 24 horas para que o DNS seja atualizado.']
                : ['info' => 'DNS ainda não está verificado. Revise suas configurações de DNS do seu domínio e tente novamente, por favor.']);

        if (count($recordsDnsVerify) && ! $domain->verified) {
            $domain->update(['verified' => true, 'verified_at' => now()]);
            $this->singularCDNService->apply();
        }

        return back()
            ->withFragment('tab=tab-links')
            ->with($message);
    }

}

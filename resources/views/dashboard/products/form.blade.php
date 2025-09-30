@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex flex-col items-center justify-between gap-3 md:flex-row">

            <div class="flex items-center gap-4">

                <h1>{{ $product->name }}</h1>
                <span class="copyClipboard cursor-pointer rounded-full bg-neutral-200 px-3 py-[3px] text-sm font-semibold"
                    data-tooltip-text="Click para copiar o ID: <br> {{ $product->client_product_uuid }}"
                    data-tooltip-position="bottom" data-clipboard-text="{{ $product->client_product_uuid }}">
                    <span>
                        ID:
                        <i class="ti ti-key text-1xl"></i>
                        <i class="ti ti-copy text-1xl"></i>
                    </span>
                </span>
            </div>

            @if ($product->canSubmitForApproval)
                <form method="POST" id="productApprovalForm"
                    action="{{ route('dashboard.products.updateSituation', $product) }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="situation"
                        value="{{ \App\Enums\SituationProductEnum::IN_ANALYSIS->name }}" />

                    <button type="submit"
                        class="buttonSubmitApprove button button-primary relative h-12 gap-2 rounded-full shadow-xl shadow-primary/30 hover:shadow-none">
                        Submeter para aprovação
                        @include('components.icon', [
                            'icon' => 'arrow_forward',
                            'custom' => 'text-xl text-white',
                        ])
                    </button>
                </form>
            @else
                <div class="flex items-center">

                    @unless ($product->canSubmitForApproval)
                        <div @class([
                            \App\Enums\SituationProductEnum::getClassBackground($product->situation) .
                            ' flex h-12 items-center justify-center gap-2 rounded-full px-6',
                            '-mr-10 pl-6 pr-14' => $product->situation === 'REPROVED',
                        ])>
                            @include('components.icon', [
                                'icon' => 'edit_document',
                                'custom' =>
                                    'text-xl ' . \App\Enums\SituationProductEnum::getClass($product->situation),
                            ])
                            <span
                                class="{{ \App\Enums\SituationProductEnum::getClass($product->situation) }} text-sm font-medium">
                                {{ $product->situationTranslated }}
                            </span>
                        </div>
                    @endunless

                    @if ($product->canSubmitForNewApproval)
                        <form method="POST" action="{{ route('dashboard.products.updateSituation', $product) }}">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="situation"
                                value="{{ \App\Enums\SituationProductEnum::IN_ANALYSIS->name }}" />

                            <button type="submit"
                                class="buttonSubmitProductApproval button button-primary relative h-12 gap-2 rounded-full shadow-xl shadow-primary/30 hover:shadow-none">
                                Submeter nova aprovação
                                @include('components.icon', [
                                    'icon' => 'arrow_forward',
                                    'custom' => 'text-xl text-white',
                                ])
                            </button>
                        </form>
                    @endif

                </div>
            @endif

        </div>

        @if ($product->isInAnalysis)
            <div class="mb-4 flex items-center gap-2 border-t-2 border-info-200 bg-info-100 p-4 text-info-800"
                role="alert">

                @include('components.icon', [
                    'icon' => 'info',
                    'custom' => 'text-xl',
                ])

                <div class="text-sm font-medium">
                    <strong>Atenção!</strong>
                    Seu produto está em análise e enquanto isso você não pode alterar as informações.
                </div>

            </div>
        @endif

        <nav class="no-scrollbar flex items-center overflow-x-auto border-b border-neutral-300"
            data-tabs-toggle="#page-tab-content">
            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-info" aria-selected="false" role="tab" type="button">
                Informações gerais
            </button>

            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-config" aria-selected="false" role="tab" type="button">
                Configurações
            </button>

            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-checkout" aria-selected="false" role="tab" type="button">
                Checkout
            </button>

            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-participations" aria-selected="false" role="tab" type="button">
                Coprodutores
            </button>
            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-area-members" aria-selected="false" role="tab" type="button">
                Área de membros
            </button>
            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-affiliations" aria-selected="false" role="tab" type="button">
                Afiliados
            </button>

            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-links" aria-selected="false" role="tab" type="button">
                Links
            </button>

        </nav>

        <div id="page-tab-content">

            @include('partials.dashboard.products.form.tab-info')
            @include('partials.dashboard.products.form.tab-config')
            @include('partials.dashboard.products.form.tab-checkout')
            @include('partials.dashboard.products.form.tab-participations')
            @include('partials.dashboard.products.form.tab-affiliations')
            @include('partials.dashboard.products.form.tab-links')
            @include('partials.dashboard.products.form.tab-area-members')
        </div>

    </div>
@endsection

@section('style')
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
@endsection

@section('script')
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <script src="{{ asset('js/dashboard/dropzone-config.js') }}"></script>
    <script src="{{ asset('js/dashboard/validation/currency.js') }}"></script>
    <script src="{{ asset('js/dashboard/copyToClipboard.js') }}"></script>

    @if ($errors->any())
        <script>
            let errors = @json($errors->all());

            errors.forEach(error => {
                notyf.error(error, "Erro");
            });
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const checkIfProductExistsForNewApproval = `productId{{ $product->id }}SubmittedForNewApproval`;

            if (localStorage.getItem(checkIfProductExistsForNewApproval)) {
                const productApprovalForm = document.querySelector("#productApprovalForm");
                const buttonSubmitProductApproval = document.querySelector(".buttonSubmitApprove");

                if (productApprovalForm && buttonSubmitProductApproval) {
                    buttonSubmitProductApproval.click();
                    localStorage.removeItem(checkIfProductExistsForNewApproval);
                }
            }
        });
    </script>

    <script>
        function checkIfProductHasOffersToSaveProduct() {
            let paymentType = $('input[name="product[paymentType]"]:checked').val();
            let contentSelector = `.payment_${paymentType}_content`;

            if ($(contentSelector).find('tbody tr').length === 0) {
                notyf.info("Adicione pelo menos uma oferta para continuar.");
                return false;
            }

            return true;
        }

        function checkIfProductHasPaymentMethodsToSaveProduct() {
            if ($('input[name="product[attributes][paymentMethods][]"]:checked').length === 0) {
                notyf.info("Selecione pelo menos um método de pagamento para continuar.");
                return false;
            }

            return true;
        }

        function validateRequiredFieldsToAffiliates() {
            const affiliateEnabled = document.querySelector('input[name="product[attributes][affiliate][enabled]"]')
                .checked;

            if (affiliateEnabled) {
                const affiliateDefaultValue = document.querySelector(
                    'input[name="product[attributes][affiliate][defaultValue]"]').value;

                if (!affiliateDefaultValue) {
                    notyf.warning("Defina um valor de comissão antes de gerar o link de afiliação", "Aviso");
                    return false;
                }
            }

            return true
        }

        $(document).on('submit', '.formDataProduct', function(event) {
            event.preventDefault();

            let tab = $(this).find('input[name="tab"]').val();

            if (tab === 'info' && !checkIfProductHasOffersToSaveProduct()) {
                return;
            }

            if (tab === 'config' && !checkIfProductHasPaymentMethodsToSaveProduct()) {
                return;
            }

            if (tab === 'affiliations' && !validateRequiredFieldsToAffiliates()) {
                return;
            }

            let form = $(this);
            let formData = new FormData(form[0]);
            let currentTab = document.querySelector('button[role="tab"][aria-selected="true"]').dataset.tabsTarget
                .replace('#', "#tab=");

            formData.append('_method', 'PUT'); // necessary for update method

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    form.find('button[type="submit"]')
                        .addClass('cursor-wait')
                        .text('Aguarde...')
                        .attr('disabled', true);
                },
                success: (response) => {
                    if (response.success) {
                        let nextTab = form.closest('.tab-content').next('.tab-content');

                        if (nextTab.length) {
                            window.location.href = document.querySelector(
                                    `button[data-tabs-target="#${nextTab.data('tab')}"]`).dataset
                                .tabsTarget.replace('#', "#tab=");
                            document.querySelector(`button[data-tabs-target="#${nextTab.data('tab')}"]`)
                                .click();
                        } else {
                            window.location.href = currentTab;
                        }

                        // Armazenar a mensagem no sessionStorage antes do reload
                        sessionStorage.setItem('notyfMessage', response.message);

                        // Forçar o reload da página
                        window.location.reload();
                        // notyf.success(response.message);
                    }
                },
                error: function(response) {
                    form.find('button[type="submit"]').removeClass('cursor-wait');

                    if (response.responseJSON.message) {
                        notyf.error(response.responseJSON.message);
                    }

                    let errors = response.responseJSON.errors || {};

                    for (let key in errors) {
                        let input = form.find('input[name="' + key + '"]');

                        input.addClass('border-pf-danger');
                        input.parent().find('.invalid-feedback').remove();
                        input.parent().append('<div class="text-danger-600 font-italic">' + errors[key][
                            0
                        ] + '</div>');
                    }
                },
                complete: function() {
                    form.find('button[type="submit"]')
                        .text('Salvar')
                        .attr('disabled', false);
                }
            });
        });

        // Verificar se há uma mensagem armazenada no sessionStorage
        const notyfMessage = sessionStorage.getItem('notyfMessage');
        if (notyfMessage) {
            notyf.success(notyfMessage);
            sessionStorage.removeItem('notyfMessage');
        }
    </script>

    <script>
        $(document).on('click', '.deleteRow', function(event) {
            event.preventDefault();

            let url = $(this).data('url');

            if (!confirm('Tem certeza?')) {
                return;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    window.location.reload();
                },
            });
        });
    </script>

    <script>
        function loadOnTab() {
            let hash = window.location.hash;

            if (hash.includes('tab=')) {
                let tab = hash.split('tab=')[1];
                let tabButton = document.querySelector(`button[data-tabs-target="#${tab}"]`);

                if (tabButton) {
                    tabButton.click();
                }
            }
        }

        window.addEventListener("load", (event) => {
            loadOnTab();
        });
    </script>

    <script>
        let productInAnalysis = "{{ boolval($product->isInAnalysis) }}";
        let productPublished = "{{ boolval($product->isPublished) }}";

        window.addEventListener("load", (event) => {
            if (productInAnalysis) {
                document.querySelectorAll('.formDataProduct button[type="submit"]').forEach(button => {
                    button.remove();
                });

                document.querySelectorAll(
                        'button[data-drawer-target], button[data-drawer-show], button[data-drawer-placement]')
                    .forEach(button => {
                        const parentDiv = button.closest('div');
                        if (parentDiv) {
                            parentDiv.remove();
                        }
                    });

                document.querySelectorAll('button[data-modal-target], button[data-modal-toggle]').forEach(
                    button => {
                        const parentDiv = button.closest('div');
                        if (parentDiv) {
                            parentDiv.remove();
                        }
                    });

                document.querySelectorAll('.formDataProduct input, select, textarea').forEach((element) => {
                    element.classList.add('cursor-not-allowed');
                    element.setAttribute('disabled', true);
                });

                document.querySelectorAll('#page-tab-content button').forEach((element) => {
                    element.classList.add('cursor-not-allowed');
                    element.setAttribute('disabled', true);
                });
            }

            if (productPublished) {
                let elementsToDisable = [
                    '.formDataProduct input[name="product[attributes][externalSalesLink]"]',
                    '.formDataProduct input[name="product[attributes][emailSupport]"]',
                    '.formDataProduct input[name="product[paymentType]',
                ];

                document.querySelectorAll(elementsToDisable.join(', ')).forEach((element) => {
                    element.classList.add('cursor-not-allowed');
                    element.setAttribute('disabled', true);
                });

            }
        });
    </script>

    <script>
        let paymentTypeProduct = '{{ $product->paymentType }}';
        let hasOffers = "{{ boolval(count($activeOffers)) }}";

        $(document).on("change", "input[name='product[paymentType]']", function() {
            let paymentTypeSelected = $(this).val();

            if (hasOffers && (paymentTypeSelected !== paymentTypeProduct)) {
                $("input[name='product[paymentType]'][value='" + paymentTypeProduct + "']")
                    .prop('checked', true)
                    .trigger('change');

                notyf.info("Escolha apenas um tipo de oferta para continuar.");
                return false;
            }

            let initialValue = paymentTypeSelected === 'UNIQUE' ? 'RECURRING' : 'UNIQUE';

            if (!hasOffers && $(`.payment_${initialValue}_content`).find('tbody tr').length) {
                $("input[name='product[paymentType]'][value='" + initialValue + "']")
                    .prop('checked', true)
                    .trigger('change');

                notyf.info("Escolha apenas um tipo de oferta para continuar.");
                return false;
            }
        });
    </script>

    @if ($product->isReproved)
        @component('components.modal', [
            'id' => 'modalProductReject',
            'title' => '',
        ])
            <div class="pb-12">
                <div id="lottie-animation" style="width: 180px; height: 180px; margin: 0 auto 12px;"></div>

                <h3 class="mb-8 text-center font-semibold">
                    Produto Reprovado
                </h3>

                <ul class="mx-auto max-w-lg space-y-4 rounded-xl bg-neutral-100 px-6 py-6">
                    <li>
                        <p class="mb-1 font-bold">
                            {{ $product->rejectReasonTranslated }}:
                        </p>

                        <p class="text-sm">
                            {{ $product->getValueSchemalessAttributes('rejectDescription') }}
                        </p>
                    </li>
                </ul>

                <button type="button" class="button button-light mx-auto mt-8 h-12 w-fit rounded-full" title="Fechar"
                    data-modal-hide="modalProductReject">
                    @include('components.icon', [
                        'icon' => 'close',
                        'custom' => 'text-xl',
                    ])
                    Fechar
                </button>
            </div>
        @endcomponent

        <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.0/lottie.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('modalProductReject');
                const modalInstance = new Modal(modal);
                modalInstance.show();
            });

            // Caminho gerado pelo Laravel
            const lottieAnimationPath = "{{ asset('images/dashboard/animates/error-product.json') }}";

            // Inicializar a animação
            lottie.loadAnimation({
                container: document.getElementById('lottie-animation'), // Elemento onde a animação será renderizada
                renderer: 'svg', // Renderizador (svg, canvas, html)
                loop: true, // Define se a animação deve ser reproduzida em loop
                autoplay: true, // Define se a animação começa automaticamente
                path: lottieAnimationPath // Caminho para o arquivo JSON
            });
        </script>
    @endif
@endsection

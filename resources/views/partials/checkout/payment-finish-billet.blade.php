<div class="mx-auto max-w-4xl">
    <div class="rounded-3xl bg-neutral-100 p-4 md:p-8">

        <div class="space-y-4 md:space-y-6">
            @component('components.card', ['custom' => '!bg-neutral-700 p-6 md:p-8'])
                <div class="space-y-6">

                    @include('checkout.thanks-order-details')

                    <div class="space-y-2">
                        <h3 class="text-white">Pague seu boleto até dia {{ $payment->due_date->format('d/m/Y')}} e receba seu produto</h3>
                        <p class="text-sm text-white">Assim que o pagamento for aprovado seu produto vai ser enviado pelo e-mail {{$order->user->email}}. Caso não encontre o e-mail, confira na sua caixa de spam.</p>
                    </div>

                    <p class="rounded-xl bg-white/10 p-4 text-center text-xs text-white md:text-sm"><strong>{{$payment->external_content}}</strong></p>

                    <div class="flex flex-col items-center gap-6 md:flex-row">

                        <a
                            class="button setButtonColor setButtonTextColor h-10 w-full gap-2 rounded-full md:h-12"
                            href="{{$payment->external_url}}"
                        >
                            @include('components.icon', [
                                'icon' => 'download',
                                'custom' => 'text-xl',
                            ])
                            Baixar boleto
                        </a>

                        <input
                                id="external-content"
                                value="{{ $payment->external_content }}"
                                type="hidden"
                        >

                        <a
                            class="button setButtonColor setButtonTextColor h-10 w-full gap-2 rounded-full md:h-12"
                            href="#"
                            onclick="copyToClipboard()"
                        >
                            @include('components.icon', [
                                'icon' => 'content_copy',
                                'custom' => 'text-xl',
                            ])
                            Copiar o código de barras
                        </a>

                    </div>

                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

                        <div class="col-span-1">
                            @component('components.card', ['custom' => 'h-full p-6 md:p-8'])
                                <div class="space-y-2">
                                    <h3>Pague até a data de vencimento</h3>
                                    <p class="text-sm text-neutral-400">Faça o pagamento do seu boleto até a data de vencimento para garantir o acesso ao produto.</p>
                                </div>
                            @endcomponent
                        </div>

                        <div class="col-span-1">
                            @component('components.card', ['custom' => 'h-full p-6 md:p-8'])
                                <div class="space-y-2">
                                    <h3>Aguarde a aprovação do pagamento</h3>
                                    <p class="text-sm text-neutral-400">Pagamentos por boleto podem levar até <strong>3 dias úteis</strong> para serem aprovados e compensados.</p>
                                </div>
                            @endcomponent
                        </div>

                        <div class="col-span-1">
                            @component('components.card', ['custom' => 'h-full p-6 md:p-8'])
                                <div class="space-y-2">
                                    <h3 class="textPrimaryColor">Pague com Pix e receba agora</h3>
                                    <p class="text-sm text-neutral-400">O pagamento via pix leva apenas alguns segundos para ser processado. Pague nessa modalidade aqui.</p>
                                </div>
                            @endcomponent
                        </div>

                    </div>

                </div>
            @endcomponent

            @component('components.card', ['custom' => 'p-6 md:p-8'])
                <div class="flex items-center justify-center gap-8">

                    <div class="flex items-center gap-1">

                        @include('components.icon', [
                            'icon' => 'verified_user',
                            'custom' => 'textPrimaryColor text-4xl md:text-5xl',
                        ])

                        <p class="flex flex-col">
                            <span class="text-xs uppercase md:text-sm">Compra</span>
                            <span class="text-xs font-semibold uppercase md:text-sm">100% Segura</span>
                        </p>

                    </div>

                    <div class="flex items-center gap-1">

                        @include('components.icon', [
                            'icon' => 'encrypted',
                            'custom' => 'textPrimaryColor text-4xl md:text-5xl',
                        ])

                        <p class="flex flex-col">
                            <span class="text-xs uppercase md:text-sm">Privacidade</span>
                            <span class="text-xs font-semibold uppercase md:text-sm">Protegida</span>
                        </p>

                    </div>

                </div>
            @endcomponent

            <div class="">

                <h4>Precisa de ajuda?</h4>

                <ul class="mb-4">
                    <li>
                        <a
                        class="textPrimaryColor text-xs md:text-sm"
                        target="_blank"
                        href="https://intercom.help/suitsales/pt-BR/articles/10721552-como-acessar-o-conteudo-pos-compra"
                        title="Como acessar o conteúdo pós-compra"
                    >
                        Como acessar o conteúdo
                    </a>
                </li>
                <li>
                    <a
                        class="textPrimaryColor text-xs md:text-sm"
                        target="_blank"
                        href="https://intercom.help/suitsales/pt-BR/articles/11048710-nao-recebi-o-acesso"
                        title="Não recebi o acesso"
                    >
                        Não recebi o acesso
                    </a>
                    </li>
                </ul>

                <p class="linkPrimaryColor text-xs md:text-sm">
                    Esta transação foi processada pela SuitPay em nome de <strong>{{$product->shop->name }}</strong>. 
                    Em caso de dúvidas sobre o conteúdo do produto, entre em contato diretamente com o produtor. 
                    Para mais informações, consulte nossos 
                    <a href="https://web.suitpay.app/static/pdf/TERMOS_E_CONDICOES.pdf" target="_blank" rel="noopener noreferrer" title="Termos de Uso" >Termos de Uso</a>, 
                    <a href="https://web.suitpay.app/static/pdf/POLITICA_DE_PRIVACIDADE_E_PROTECAO_DE_DADOS.pdf" target="_blank" rel="noopener noreferrer" title="Política de Privacidade" >Política de Privacidade</a> 
                    ou acesse nosso 
                    <a href="https://intercom.help/suitsales/pt-BR/" target="_blank" rel="noopener noreferrer" title="Help Center" >Help Center</a>.<br><br>
                    SuitPay © 2025 – Todos os direitos reservados.
                  </p>

            </div>

        </div>

    </div>
</div>

@section('script')
    <script>
        function copyToClipboard() {
            const input = document.getElementById('external-content');
            const tempInput = document.createElement('input');

            tempInput.value = input.value;
            document.body.appendChild(tempInput);

            tempInput.select();
            document.execCommand('copy');

            document.body.removeChild(tempInput);

            notyf.success("Copiado com sucesso!");
        }
    </script>
@endsection

@section('style')
    <style id="customCheckoutStyle">
        #backgroundColor {
            background-color: #fafafa;
        }

        .textPrimaryColor,
        .linkPrimaryColor a {
            color: #33cc33;
        }

        .setButtonColor {
            background-color: #33cc33;
        }

        .setButtonTextColor {
            color: #ffffff;
        }

        .selectPaymentMethod input:checked~.content {
            border-color: #33cc33;
        }

        .selectPaymentMethod input:checked~.content .radio {
            background-color: #33cc33;
            border-color: #33cc33;
        }
    </style>
@endsection

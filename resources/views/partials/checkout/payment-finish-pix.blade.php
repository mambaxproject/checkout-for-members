@if ($order->paymentExpired)
    <div class="mx-auto max-w-xl text-center">
        @include('components.icon', [
            'icon' => 'cancel',
            'type' => 'fill',
            'custom' => 'text-5xl text-danger-500',
        ])
        <h3 class="mb-2 text-xl text-danger-500">Ops! Seu pix expirou</h3>
        <p class="text-neutral-600">Sua compra não foi efetuada. Para garantir seus produtos, efetue uma nova compra, clicando no botão abaixo:</p>

        <a
            class="button button-primary mx-auto mt-8 h-12 w-fit rounded-full"
            href="{{ $order->items->first()->product->url }}"
        >
            Refazer compra
        </a>
    </div>
@else
    <div class="mx-auto max-w-4xl">
        <div class="rounded-3xl bg-neutral-100 p-4 md:p-8">

            <div class="space-y-4 md:space-y-6">

                @component('components.card', ['custom' => '!bg-neutral-700 p-6 md:p-8'])
                    <div class="space-y-6">
                        @include('checkout.thanks-order-details')

                        <p class="text-sm text-white">Agora só falta você concluir o pagamento Pix no aplicativo do seu banco, escanceando o QR Code abaixo ou copiando o Pix Copia e Cola. Assim que o pagamento for aprovado seu produto vai ser enviado pelo seu e-mail!</p>

                        @component('components.card', ['custom' => 'p-6 md:p-8'])
                            <div class="space-y-1 message-timer">

                                <h4 class="font-semibold">Quase lá...</h4>
                                <p class="flex items-center gap-2">
                                    Pague seu pix em
                                    <span class="flex items-center gap-1 rounded-full bg-primary py-0.5 pl-2 pr-3 text-white">
                                        @include('components.icon', [
                                            'icon' => 'timer',
                                            'custom' => 'text-lg',
                                        ])
                                        <span
                                            id="countdown"
                                            class="text-sm"
                                        >15:00</span>
                                    </span>
                                    para garantir sua compra.
                                </p>

                            </div>

                            <div class="mt-3">
                                <a
                                        class="button setButtonColor check-payment setButtonTextColor h-10 gap-2 rounded-full md:h-12"
                                        href="#"
                                        onclick="checkPayment()"
                                >
                                    Já paguei!
                                </a>
                            </div>
                        @endcomponent

                        @if (!$browser->isMobile())
                            @component('components.card', ['custom' => 'p-6 md:p-8'])
                                <div class="space-y-4">

                                    <h4 class="font-semibold">Escaneie o QR Code para completar o pagamento</h4>

                                    <div class="flex flex-col gap-8 md:flex-row md:items-center">

                                        <div class="mx-auto h-[180px] w-[180px] rounded-xl bg-neutral-100 md:mx-0">
                                            <img
                                                src="https://quickchart.io/qr?text={{ $payment->external_content }}"
                                                style="display: block; height: auto; border: 0; width: 227px; max-width: 100%;"
                                                width="227"
                                                alt="QR Code pix para pagamento"
                                                title="QR Code pix para pagamento"
                                            >
                                        </div>

                                        <ol class="ml-4 list-decimal space-y-2 pl-4">
                                            <li class="text-sm">Acesse o app do seu banco</li>
                                            <li class="text-sm">Escolha o pagamento via Pix QR Code</li>
                                            <li class="text-sm">Escaneie o código ao lado</li>
                                        </ol>

                                    </div>

                                </div>
                            @endcomponent
                        @endif

                        @component('components.card', ['custom' => 'p-6 md:p-8'])
                            <div class="space-y-4">

                                <h4 class="font-semibold">Ou copie a chave pix copia e cola</h4>
                                <p class="text-sm">Clique no botão ao lado para copiar o código e cole ele na sessão “Pix Copia e Cola” dentro do seu aplicativo de banco</p>

                                <input
                                    id="external-content"
                                    value="{{ $payment->external_content }}"
                                    type="hidden"
                                >
                                <a
                                    class="button setButtonColor setButtonTextColor h-10 gap-2 rounded-full md:h-12"
                                    href="#"
                                    onclick="copyToClipboard()"
                                >
                                    @include('components.icon', [
                                        'icon' => 'content_copy',
                                        'custom' => 'text-xl',
                                    ])
                                    Copiar chave Pix
                                </a>

                            </div>
                        @endcomponent

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
                            >
                                Como acessar o conteúdo
                            </a>
                        </li>
                        <li>
                            <a
                                class="textPrimaryColor text-xs md:text-sm"
                                target="_blank"
                                href="https://intercom.help/suitsales/pt-BR/articles/11048710-nao-recebi-o-acesso"
                            >
                                Não recebi o acesso
                            </a>
                        </li>

                    </ul>

                    <p class="linkPrimaryColor text-xs md:text-sm">
                        Esta transação foi processada pela SuitPay em nome de <strong>{{$product->shop->name }}</strong>. 
                        Em caso de dúvidas sobre o conteúdo do produto, entre em contato diretamente com o produtor. 
                        Para mais informações, consulte nossos 
                        <a href="https://web.suitpay.app/static/pdf/TERMOS_E_CONDICOES.pdf" target="_blank" rel="noopener noreferrer">Termos de Uso</a>, 
                        <a href="https://web.suitpay.app/static/pdf/POLITICA_DE_PRIVACIDADE_E_PROTECAO_DE_DADOS.pdf" target="_blank" rel="noopener noreferrer">Política de Privacidade</a> 
                        ou acesse nosso 
                        <a href="https://intercom.help/suitsales/pt-BR/" target="_blank" rel="noopener noreferrer">Help Center</a>.<br><br>
                        SuitPay © 2025 – Todos os direitos reservados.
                      </p>

                </div>

            </div>

        </div>
    </div>
@endif

@section('script')
    <script>
        let paymentIntervalId;

        function trackPurchase() {
            let facebookPixels = getFacebookPixels();

            if (!facebookPixels.length) return;

            let products = window.order.products.map(function(item) {
                return {
                    id: item.product.id.toString(),
                    name: item.product.name,
                    quantity: item.quantity,
                    value: item.amount,
                }
            });

            facebookPixels.forEach(function (pixel) {
                if (pixel.attributes.backend_purchase) return;

                trackFacebookPurchase(
                    pixel.pixel_id,
                    products,
                    products.length,
                    window.order.total,
                    window.order.payment_method,
                );
            })
        }

        function successMessage() {
            document.querySelector('.message-timer').innerHTML = '<h3 class="font-semibold textPrimaryColor">Pagamento recebido com sucesso!</h3>'
            document.querySelector('.check-payment').classList.add('hidden')
            notyf.success("Pagamento recebido com sucesso!");

            if (window.order.has_custom_page) {
                window.location = window.order.thanks_page;
            } else if (window.order.has_telegram_group && window.order.redirectToTelegramInviteLink) {
                setInterval(function() {
                    redirectToTelegramInviteLink()
                }, 2000)
            }
        }

        function checkPayment(auto = false) {

            $.get('{{route('api.public.checkout.checkPayment', $order->orderHash)}}', function(data) {
                if (data.is_paid) {
                    successMessage()
                    trackPurchase()
                    clearInterval(paymentIntervalId);
                } else {
                    if (!auto) notyf.error("Pagamento não recebido ainda!");
                }
            })
        }

        document.addEventListener("DOMContentLoaded", function() {
            paymentIntervalId =  setInterval(function () {
                checkPayment(true)
            }, 30000);
        })
    </script>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data de criação vinda do MySQL
            const createdAt = "{{ $payment->created_at }}";

            // Converte para objeto Date e adiciona 15 minutos
            const createdDate = new Date(createdAt.replace(/-/g, "/")); // Ajusta compatibilidade com Safari
            const expirationTime = new Date(createdDate.getTime() + 15 * 60000); // 15 minutos após

            function updateCountdown() {
                const now = new Date();
                const diff = expirationTime - now;

                if (diff <= 0) {
                    document.getElementById("countdown").innerHTML = `<i class="mdi mdi-close-circle text-lg"></i> Expirado`;
                    window.location.reload();
                    return;
                }

                const minutes = Math.floor(diff / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);

                document.getElementById("countdown").innerHTML = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
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

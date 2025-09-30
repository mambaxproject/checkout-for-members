<div class="mx-auto max-w-4xl">
    <div class="rounded-3xl bg-neutral-100 p-4 md:p-8">

        <div class="space-y-4 md:space-y-6">

            @include('checkout.thanks-order-details')

            @component('components.card', ['custom' => '!bg-neutral-700 p-6 md:p-8'])
                <div class="space-y-6">

                    <div class="flex items-center justify-center gap-4">

                        <div class="setButtonColor setButtonTextColor flex h-10 w-10 items-center justify-center rounded-full md:h-12 md:w-12">
                            @include('components.icon', [
                                'icon' => 'check',
                                'custom' => 'text-2xl md:text-3xl',
                            ])
                        </div>
                        <div class="">
                            <h3 class="text-white">Parabéns!</h3>
                            <p class="text-xs text-white md:text-sm">Sua compra foi aprovada.</p>
                        </div>

                    </div>

                    <p class="rounded-xl bg-white/10 p-4 text-center text-xs text-white md:text-sm">O código da sua transação é: <strong>{{ $payment->external_identification }}</strong></p>
                    <p class="text-xs text-white md:text-sm">Em alguns instantes você vai receber no seu e-mail {{ $order->user->email }} os dados para acessar este produto sempre que precisar. Caso não encontre a mensagem, não deixe de conferir a caixa de Spam.</p>

                </div>
            @endcomponent

            @if ($upSells->isNotEmpty())
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="col-span-12 mt-10">
                        <div class="rounded-lg border border-dashed border-green-300 bg-green-100 p-4">
                            <div class="space-y-3">
                                @foreach ($upSells as $upsell)
                                    <div class="rounded-lg bg-white p-4">

                                        <div style="display: flex; flex-direction: column; gap: 0.75rem; background: #fff; padding: 20px 24px 16px 20px; border-radius: 1rem; font-family: sans-serif;">

                                            @if($upsell->getValueSchemalessAttributes('showProductImage'))
                                            <figure style="height: 160px; width: 280px; margin: auto; position: relative">
                                                <img
                                                    src="{{ $upsell->product_offer->featuredImageUrl }}"
                                                    alt="{{ $upsell->product_offer->name }}"
                                                    loading="lazy"
                                                    style="border-radius: 0.5rem; position: absolute; width: 100%; height: 100%; object-fit: cover; object-position: center;"
                                                />
                                            </figure>
                                            @endif

                                            <div style="display: flex; flex-direction: column; gap: 0.7rem">
                                                <div style="display: flex; flex-direction: column; gap: 0.125rem">
                                                    @if($upsell->getValueSchemalessAttributes('showProductTitle'))
                                                    <h3 style="text-align: center; font-size: 1.125rem; line-height: 1.75rem; margin: 0;">
                                                        {{ $upsell->product_offer->name }}
                                                    </h3>
                                                    @endif
                                                    @if($upsell->getValueSchemalessAttributes('showProductPrice'))
                                                    <p style="text-align: center; font-size: 0.875rem; color: #9ca3af; margin: 0;">
                                                        {{ $upsell->product_offer->brazilianPrice }}
                                                    </p>
                                                    @endif
                                                </div>
                                                <div style="display: flex; flex-direction: column; gap: 0.25rem">
                                                    <form
                                                        method="post"
                                                        action="{{ route('public.checkout.payUpSell', ['order' => $order, 'upSell' => $upsell]) }}"
                                                        style="margin: 0"
                                                    >
                                                        <button
                                                            type="submit"
                                                            style="width: fit-content; margin: auto; display: flex; align-items: center; justify-content: center; padding-left: 1.5rem; padding-right: 1.5rem; font-size: 0.875rem; font-weight: 500; height: 3rem; border: none; border-radius: 9999px; color: white; background-color: {{ $upsell->color_button_accept }}; cursor: pointer; text-decoration: none;"
                                                        >
                                                            {{ $upsell->text_accept }}
                                                        </button>
                                                    </form>
                                                    <a
                                                        href="{{$upsell->thanksPageRejectLink}}"
                                                        style="width: fit-content; margin: auto; display: flex; align-items: center; justify-content: center; padding-left: 1.5rem; padding-right: 1.5rem; font-size: 0.875rem; font-weight: 500; height: 3rem; border-radius: 9999px; color: #111827; cursor: pointer; text-decoration: none;"
                                                        onmouseover="this.style.color='#ef4444'"
                                                        onmouseout="this.style.color='#111827'"
                                                    >
                                                        {{$upsell->text_reject}}
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endcomponent
            @endif

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
                            href="https://intercom.help/suitsales/pt-BR/articles/10721552-como-acessar-o-conteudo-pos-compra"
                            title="Como acessar o conteúdo"
                            target="_blank"
                        >
                            Como acessar o conteúdo
                        </a>
                    </li>

                    <li>
                        <a
                            class="textPrimaryColor text-xs md:text-sm"
                            href="https://intercom.help/suitsales/pt-BR/articles/11048710-nao-recebi-o-acesso"
                            title="Não recebi o acesso"
                            target="_blank"
                        >
                            Não recebi o acesso
                        </a>
                    </li>
                </ul>

                <p class="linkPrimaryColor text-xs md:text-sm">
                    Esta transa&ccedil;&atilde;o foi processada pela SuitPay em nome de {{ $order->shop->name }}. Em caso de d&uacute;vidas sobre o conte&uacute;do do produto, entre em contato diretamente com o produtor. Para mais informa&ccedil;&otilde;es, consulte nossos <a
                        href="https://web.suitpay.app/static/pdf/TERMOS_E_CONDICOES.pdf"
                        rel="noopener noreferrer"
                        target="_blank"
                    >Termos de Uso</a>, <a
                        href="https://web.suitpay.app/static/pdf/POLITICA_DE_PRIVACIDADE_E_PROTECAO_DE_DADOS.pdf"
                        rel="noopener noreferrer"
                        target="_blank"
                    >Pol&iacute;tica de Privacidade</a> ou acesse nosso <a
                        href="https://intercom.help/suitsales/pt-BR/"
                        rel="noopener noreferrer"
                        target="_blank"
                    >Help Center</a>.
                </p>

            </div>

        </div>

    </div>
</div>

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.order.has_telegram_group && window.order.redirectToTelegramInviteLink) {
                setInterval(function() {
                    redirectToTelegramInviteLink()
                }, 2000)
            } else if (window.order.has_custom_page) {
                window.location = window.order.thanks_page;
            }
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

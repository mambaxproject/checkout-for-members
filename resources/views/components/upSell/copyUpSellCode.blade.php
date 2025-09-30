<div class="">

    <div style=" display: flex; flex-direction: column; gap: 0.75rem; background: #fff; padding: 20px 24px 16px 20px; border-radius: 1rem; font-family: sans-serif;">
        @if($upsell->getValueSchemalessAttributes('showProductImage'))
        <figure style="height: 160px; width: 280px; margin: auto; position: relative">
            <img
                src="{{ $upsell->product->featuredImageUrl }}"
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
                    id="form-upsell-{{ $upsell->id }}"
                    action="{{ route('public.checkout.payUpSell', ['upSell' => $upsell->id, 'order' => 'order_id']) }}"
                    style="margin: 0;"
                >
                    <button
                        type="submit"
                        style="width: fit-content; margin: auto; display: flex; align-items: center; justify-content: center; padding-left: 1.5rem; padding-right: 1.5rem; font-size: 0.875rem; font-weight: 500; height: 3rem; border: none; border-radius: 9999px; color: white; background-color: {{$upsell->color_button_accept}}; cursor: pointer; text-decoration: none;"
                    >
                        {{$upsell->text_accept}}
                    </button>
                </form>
                <a
                    href="{{$upsell->getValueSchemalessAttributes('urlDownSell') ?? '#'}}"
                    style=" width: fit-content; margin: auto; display: flex; align-items: center; justify-content: center; padding-left: 1.5rem; padding-right: 1.5rem; font-size: 0.875rem; font-weight: 500; height: 3rem; border-radius: 9999px; color: #111827; cursor: pointer; text-decoration: none;"
                    onmouseover="this.style.color='#ef4444'"
                    onmouseout="this.style.color='#111827'"
                >
                    {{$upsell->text_reject}}
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const url = new URL(window.location.href);
            const orderId = url.searchParams.get("order_id");
            const form = document.getElementById("form-upsell-{{ $upsell->id }}");
            const currentAction = form.getAttribute("action");
            const newAction = currentAction.replace("order_id", orderId);
            form.setAttribute('action', newAction);
        })
    </script>

</div>

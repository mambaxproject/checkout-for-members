@extends('layouts.dashboard')
@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center gap-3">

            <h1 class="mr-auto">
                <span class="text-neutral-400">Whatsapp ></span>
                <span>Jornada do Cliente</span>
            </h1>
        </div>
        <div class="space-y-4 md:space-y-10">
            <div class="">
                <div class="flex items-center gap-2">
                    <h3>Defina as ações para:</h3>
                    <div class="flex items-center gap-2">
                        <h3>{{ $data['nameProduct'] }}</h3>
                    </div>
                </div>
                <p class="text-neutral-500">Elabore o fluxo de acontecimentos do momento de compra do seu produto.</p>
            </div>
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('dashboard.notification.create') }}" method="POST" enctype="multipart/form-data"
                id="formSubmit">
                @csrf

                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="flex flex-col md:flex-row gap-4 mt-3 pb-8 border-b border-gray-300">
                        <div class="w-full md:w-1/2">
                            <label for="nameAction">Nome da Ação</label>
                            <input class="border p-2 w-full rounded" name="nameAction" type="text" id="actionName"
                                value="{{ $data['nameAction'] }}" />
                        </div>

                        <input type="hidden" name="productId" value="{{ $data['productId'] }}" />

                        <div class="w-full md:w-1/2">
                            <label for="descAction">Descrição da Ação</label>
                            <input placeholder="Digite uma descrição pra ação" class="border p-2 w-full rounded"
                                name="descAction" type="text" id="descAction" value="{{ $data['descAction'] ?? '' }}" />
                        </div>
                    </div>
                @endcomponent
                <div class="mt-10 mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <input type="hidden" name="type" value="whatsapp" />
                    <div class="space-y-8" id="toggle-component-abandonedcart">

                        <label class="mb-0 flex cursor-pointer items-center justify-between" for="abandonedcart">

                            <input class="peer hidden" id="abandonedcart" type="checkbox" name="Notifications[0][status]"
                                checked />

                            <div class="">
                                <h3>Carrinho abandonado</h3>
                                <p class="text-sm text-neutral-500">Defina as ações para quando o cliente não tiver finalizado o
                                    ato de compra</p>
                            </div>

                            <div
                                class="animate relative h-6 w-[44px] rounded-full bg-gray-300 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full">
                            </div>

                        </label>

                        <div class="toggleContent">
                            <div class="grid grid-cols-12 gap-6">

                                <div class="mensagem-container col-span-12">
                                    <label for="eventId1">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="eventId1" name="Notifications[0][eventId]" value="1">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." value=""
                                        name="Notifications[0][text]"></textarea>
                                    <div class="flex justify-end">
                                        <button type="button"
                                            class="button button-primary mt-5 mx-3 h-12 rounded-full set-default-text"
                                            data-target="Notifications[0][text]"
                                            data-default="{{ __('Olá, {nome_cliente} {sobrenome_cliente}! 👋 Percebemos que você deixou o produto {nome_produto} no carrinho, mas não finalizou a compra. Para te ajudar a concluir seu pedido, reservamos um desconto especial para você! 🎉  Finalize sua compra agora: {link_checkout} Se precisar de qualquer ajuda, estamos à disposição! 😊  Equipe SuitSales 💚') }}">
                                            Usar mensagem padrão
                                        </button>
                                        <button type="button"
                                            class="button button-primary mt-5 h-12 rounded-full set-default-text"
                                            data-target="Notifications[0][text]"
                                            data-default="{{ __('Olá, {nome_cliente} {sobrenome_cliente}! 👋 Percebemos que você deixou o produto {nome_produto} no carrinho, mas não finalizou a compra. Para te ajudar a concluir seu pedido, reservamos um desconto especial para você! 🎉 Use o cupom {cupom} até {validade_cupom} e garanta um preço exclusivo. 💰 Finalize sua compra agora: {link_checkout} Se precisar de qualquer ajuda, estamos à disposição! 😊  Equipe SuitSales 💚') }}">
                                            Usar mensagem padrão com cupom
                                        </button>
                                    </div>
                                    <p class="mb-1 mt-3 text-sm font-medium">Variáveis que podem ser utilizadas:</p>
                                    <div class="variables-list flex items-center gap-1">
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{sobrenome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_produto}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{email_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{link_checkout}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{cupom}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{validade_cupom}</pre>
                                    </div>

                                </div>

                                <div class="col-span-12">

                                    @include('components.dropzone', [
                                        'id' => 'Notifications[0][image]',
                                        'name' => 'Notifications[0][image]',
                                        'accept' => 'image/*,application/pdf',
                                        'required' => false,
                                    ])

                                </div>
                                <div class="col-span-12">
                                    <label for="abandonedcartSelect">Enviar a mensagem:</label>
                                    <select id="abandonedcartSelect" name="Notifications[0][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="5">5 minutos após a ação</option>
                                        <option value="10">10 minutos após a ação</option>
                                        <option value="15">15 minutos após a ação</option>
                                        <option value="20">20 minutos após a ação</option>
                                        <option value="25">25 minutos após a ação</option>
                                        <option value="30">30 minutos após a ação</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                @endcomponent
                <div class="mt-10 mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8" id="toggle-component-IssuanceOfBoletoAndPix">

                        <label class="mb-0 flex cursor-pointer items-center justify-between" for="IssuanceOfBoletoAndPix">

                            <input class="peer hidden" id="IssuanceOfBoletoAndPix" type="checkbox"
                                name="Notifications[1][status]" />

                            <div class="">
                                <h3>Emissão de Boleto/PIX</h3>
                                <p class="text-sm text-neutral-500">Defina as ações para quando o cliente emitir o boleto ou QR
                                    Code do PIX, mas não tiver finalizado o ato de compra</p>
                            </div>

                            <div
                                class="animate relative h-6 w-[44px] rounded-full bg-gray-300 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full">
                            </div>

                        </label>

                        <div class="toggleContent">

                            <div class="grid grid-cols-12 gap-6">
                                <div class="mensagem-container col-span-12">

                                    <label for="eventId2">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="eventId2" name="Notifications[1][eventId]" value="2">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." name="Notifications[1][text]"></textarea>
                                    <div class="flex justify-end">
                                        <button type="button"
                                            class="button button-primary mt-5 mx-3 h-12 rounded-full set-default-text"
                                            data-target="Notifications[1][text]"
                                            data-default="{{ __('Olá, {nome_cliente} {sobrenome_cliente}! 👋 Percebemos que você iniciou a compra do produto {nome_produto}, mas ainda não finalizou o pagamento. Enviamos as instruções de pagamento para o seu e-mail : {email_cliente}. 📩 Dá uma olhadinha por lá! Se precisar de ajuda, estamos à disposição. 😊 Equipe SuitSales 💚') }}">
                                            Usar mensagem padrão
                                        </button>
                                    </div>
                                    <p class="mb-1 mt-3 text-sm font-medium">Variáveis que podem ser utilizadas:</p>
                                    <div class="variables-list flex items-center gap-1">
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{sobrenome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_produto}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{email_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{link_checkout}</pre>
                                    </div>

                                </div>

                                <div class="col-span-12">
                                    @include('components.dropzone', [
                                        'id' => 'Notifications[1][image]',
                                        'name' => 'Notifications[1][image]',
                                        'accept' => 'image/*,application/pdf',
                                        'required' => false,
                                    ])
                                </div>

                                <div class="col-span-12">
                                    <label for="IssuanceOfBoletoAndPixSelect">Enviar a mensagem:</label>
                                    <select id="IssuanceOfBoletoAndPixSelect" name="Notifications[1][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="0">Imediatamente após a ação</option>
                                        <option value="5">5 minutos após a ação</option>
                                        <option value="10">10 minutos após a ação</option>
                                        <option value="15">15 minutos após a ação</option>
                                        <option value="20">20 minutos após a ação</option>
                                        <option value="25">25 minutos após a ação</option>
                                        <option value="30">30 minutos após a ação</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                @endcomponent
                <div class="mt-10 mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8" id="toggle-component-PaymentConfirmationBoletoAndPix">

                        <label class="mb-0 flex cursor-pointer items-center justify-between"
                            for="PaymentConfirmationBoletoAndPix">
                            <input class="peer hidden" id="PaymentConfirmationBoletoAndPix" type="checkbox"
                                name="Notifications[2][status]" />

                            <div class="">
                                <h3>Confirmação de Pagamento (Boleto/Pix)</h3>
                                <p class="text-sm text-neutral-500">Defina as ações para quando o cliente finalizar o ato de
                                    compra</p>
                            </div>

                            <div
                                class="animate relative h-6 w-[44px] rounded-full bg-gray-300 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full">
                            </div>

                        </label>

                        <div class="toggleContent">

                            <div class="grid grid-cols-12 gap-6">
                                <div class="mensagem-container col-span-12">
                                    <label for="eventId3">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="eventId3" name="Notifications[2][eventId]" value="3">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." name="Notifications[2][text]"></textarea>
                                    <div class="flex justify-end">
                                        <button type="button"
                                            class="button button-primary mt-5 mx-3 h-12 rounded-full set-default-text"
                                            data-target="Notifications[2][text]"
                                            data-default="{{ __('Parabéns, {nome_cliente} {sobrenome_cliente}! 🎉 Sua compra do produto {nome_produto} foi confirmada com sucesso! ✅ O seu produto já está disponível no anexo que enviamos para o e-mail: {email_cliente}. 📩 Qualquer dúvida, estamos por aqui. Aproveite! 😊 Equipe SuitSales 💚') }}">
                                            Usar mensagem padrão
                                        </button>
                                    </div>
                                    <p class="mb-1 mt-3 text-sm font-medium">Variáveis que podem ser utilizadas:</p>
                                    <div class="variables-list flex items-center gap-1">
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{sobrenome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_produto}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{email_cliente}</pre>
                                    </div>

                                </div>

                                <div class="col-span-12">

                                    @include('components.dropzone', [
                                        'id' => 'Notifications[2][image]',
                                        'name' => 'Notifications[2][image]',
                                        'accept' => 'image/*,application/pdf',
                                        'required' => false,
                                    ])

                                </div>

                                <div class="col-span-12">
                                    <label for="PaymentConfirmationBoletoAndPixSelect">Enviar a mensagem:</label>
                                    <select id="PaymentConfirmationBoletoAndPixSelect" name="Notifications[2][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="0">Imediatamente após a ação</option>
                                        <option value="5">5 minutos após a ação</option>
                                        <option value="10">10 minutos após a ação</option>
                                        <option value="15">15 minutos após a ação</option>
                                        <option value="20">20 minutos após a ação</option>
                                        <option value="25">25 minutos após a ação</option>
                                        <option value="30">30 minutos após a ação</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                @endcomponent
                <div class="mt-10 mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8" id="toggle-component-PaymentConfirmationCreditCard">

                        <label class="mb-0 flex cursor-pointer items-center justify-between"
                            for="PaymentConfirmationCreditCard">

                            <input class="peer hidden" id="PaymentConfirmationCreditCard" type="checkbox"
                                name="Notifications[3][status]" />

                            <div class="">
                                <h3>Confirmação de Pagamento (Cartão)</h3>
                                <p class="text-sm text-neutral-500">Defina as ações para quando o cliente finalizar o ato de
                                    compra</p>
                            </div>

                            <div
                                class="animate relative h-6 w-[44px] rounded-full bg-gray-300 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full">
                            </div>

                        </label>

                        <div class="toggleContent">

                            <div class="grid grid-cols-12 gap-6">

                                <div class="mensagem-container col-span-12">

                                    <label for="eventId4">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="eventId4" name="Notifications[3][eventId]" value="4">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." name="Notifications[3][text]"></textarea>
                                    <div class="flex justify-end">
                                        <button type="button"
                                            class="button button-primary mt-5 mx-3 h-12 rounded-full set-default-text"
                                            data-target="Notifications[3][text]"
                                            data-default="{{ __('Parabéns, {nome_cliente} {sobrenome_cliente}! 🎉 O pagamento da sua compra com cartão foi aprovado com sucesso! 💳✅ O produto {nome_produto} já está disponível no anexo que enviamos para o e-mail: {email_cliente}. 📩 Se precisar de qualquer ajuda, é só chamar. Aproveite! 😊 Equipe SuitSales 💚') }}">
                                            Usar mensagem padrão
                                        </button>
                                    </div>
                                    <p class="mb-1 mt-3 text-sm font-medium">Variáveis que podem ser utilizadas:</p>
                                    <div class="variables-list flex items-center gap-1">
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{sobrenome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_produto}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{email_cliente}</pre>
                                    </div>

                                </div>

                                <div class="col-span-12">

                                    @include('components.dropzone', [
                                        'id' => 'Notifications[3][image]',
                                        'name' => 'Notifications[3][image]',
                                        'accept' => 'image/*,application/pdf',
                                        'required' => false,
                                    ])

                                </div>

                                <div class="col-span-12">
                                    <label for="PaymentConfirmationCreditCardSelect">Enviar a mensagem:</label>
                                    <select id="PaymentConfirmationCreditCardSelect" name="Notifications[3][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="0">Imediatamente após a ação</option>
                                        <option value="5">5 minutos após a ação</option>
                                        <option value="10">10 minutos após a ação</option>
                                        <option value="15">15 minutos após a ação</option>
                                        <option value="20">20 minutos após a ação</option>
                                        <option value="25">25 minutos após a ação</option>
                                        <option value="30">30 minutos após a ação</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                @endcomponent
                <div class="mt-10 mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8" id="toggle-component-PaymentErrorCreditCard">

                        <label class="mb-0 flex cursor-pointer items-center justify-between" for="PaymentErrorCreditCard">

                            <input class="peer hidden" id="PaymentErrorCreditCard" type="checkbox"
                                name="Notifications[4][status]" />

                            <div class="">
                                <h3>Erro de pagamento (Cartão)</h3>
                                <p class="text-sm text-neutral-500">Defina as ações para quando o cliente finalizar o ato de
                                    compra</p>
                            </div>

                            <div
                                class="animate relative h-6 w-[44px] rounded-full bg-gray-300 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full">
                            </div>

                        </label>

                        <div class="toggleContent">

                            <div class="grid grid-cols-12 gap-6">

                                <div class="mensagem-container col-span-12">

                                    <label for="eventId5">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="eventId5" name="Notifications[4][eventId]" value="5">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." name="Notifications[4][text]"></textarea>
                                    <div class="flex justify-end">
                                        <button type="button"
                                            class="button button-primary mt-5 mx-3 h-12 rounded-full set-default-text"
                                            data-target="Notifications[4][text]"
                                            data-default="{{ __('Olá, {nome_cliente} {sobrenome_cliente}! 😕 Tentamos processar o pagamento do produto {nome_produto} no cartão, mas algo deu errado. 💳❌ Por favor, verifique os dados do cartão e tente novamente. Se precisar de ajuda, estamos por aqui para te apoiar! 💬 Equipe SuitSales 💚') }}">
                                            Usar mensagem padrão
                                        </button>
                                    </div>
                                    <p class="mb-1 mt-3 text-sm font-medium">Variáveis que podem ser utilizadas:</p>
                                    <div class="variables-list flex items-center gap-1">
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{sobrenome_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{nome_produto}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{email_cliente}</pre>
                                        <pre
                                            class="variable animate w-fit cursor-pointer rounded-md bg-neutral-700 px-1.5 py-1 text-xs text-white hover:bg-neutral-500">{link_checkout}</pre>
                                    </div>

                                </div>

                                <div class="col-span-12">

                                    @include('components.dropzone', [
                                        'id' => 'Notifications[4][image]',
                                        'name' => 'Notifications[4][image]',
                                        'accept' => 'image/*,application/pdf',
                                        'required' => false,
                                    ])

                                </div>

                                <div class="col-span-12">
                                    <label for="PaymentErrorCreditCardSelect">Enviar a mensagem:</label>
                                    <select id="PaymentErrorCreditCardSelect" name="Notifications[4][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="0">Imediatamente após a ação</option>
                                        <option value="5">5 minutos após a ação</option>
                                        <option value="10">10 minutos após a ação</option>
                                        <option value="15">15 minutos após a ação</option>
                                        <option value="20">20 minutos após a ação</option>
                                        <option value="25">25 minutos após a ação</option>
                                        <option value="30">30 minutos após a ação</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                @endcomponent
                <div class="mb-5"></div>
                <div class="w-full flex justify-end">
                    <button class="button button-primary mt-8 h-12 w-full max-w-[200px]  rounded-full" type="submit"
                        id="submit-button">
                        Salvar
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('custom-script')
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <script src="{{ asset('js/dashboard/dropzone-config.js') }}"></script>
    <script>
        document.querySelectorAll('.variables-list').forEach(list => {
            list.querySelectorAll('.variable').forEach(variable => {
                variable.addEventListener('click', function() {
                    const container = this.closest('.mensagem-container');
                    const textarea = container.querySelector('textarea');
                    if (!textarea) return;

                    const textToInsert = ` ${this.textContent} `;

                    if (textarea.selectionStart || textarea.selectionStart === 0) {
                        const startPos = textarea.selectionStart;
                        const endPos = textarea.selectionEnd;
                        textarea.value = textarea.value.substring(0, startPos) + textToInsert +
                            textarea.value.substring(endPos);
                        textarea.selectionStart = textarea.selectionEnd = startPos + textToInsert
                            .length;
                    } else {
                        textarea.value += textToInsert;
                    }

                    textarea.focus();
                });
            });
        });

        document.getElementById("formSubmit").addEventListener("submit", function() {
            document.getElementById("submit-button").disabled = true;
        });

        document.querySelectorAll('.set-default-text').forEach(button => {
            button.addEventListener('click', function() {
                const targetName = this.dataset.target;
                const defaultText = this.dataset.default;

                const textarea = document.querySelector(`[name="${targetName}"]`);
                console.log('a', targetName);
                console.log('b', defaultText);
                console.log('c', textarea);

                if (!textarea) return;

                textarea.value = defaultText;
                textarea.focus();
            });
        });
    </script>
@endpush

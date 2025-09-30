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
                        <h3>{{ $noticationAction->nameProduct }}</h3>
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
            <form action="{{ route('dashboard.notification.update') }}" method="POST" enctype="multipart/form-data"
                id="formSubmit">
                @csrf
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="flex flex-col md:flex-row gap-4 mt-3 pb-8 border-b border-gray-300">
                        <div class="w-full md:w-1/2">
                            <label for="nameAction">Nome da Ação</label>
                            <input class="border p-2 w-full rounded" name="nameAction" type="text" id="actionName"
                                value="{{ old('actionName', $noticationAction->actionName) }}" />
                        </div>

                        <input type="hidden" name="actionId" value="{{ $noticationAction->id }}" />

                        <div class="w-full md:w-1/2">
                            <label for="descAction">Descrição da Ação</label>
                            <input placeholder="Digite uma descrição pra ação" class="border p-2 w-full rounded"
                                name="descAction" type="text" id="descAction"
                                value="{{ old('descAction', $noticationAction->descAction) }}" />
                        </div>
                    </div>
                @endcomponent
                <div class="mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8 mt-5 pt-10 mb-10" id="toggle-component-abandonedcart">

                        <label class="mb-0 flex cursor-pointer items-center justify-between" for="abandonedcart">

                            <input class="peer hidden" id="abandonedcart" type="checkbox" name="Notifications[0][status]"
                                {{ old('Notifications[0][status]', $noticationAction->notifications[0]->status) ? 'checked' : '' }} />
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
                                    <label for="notification[0]id">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="notification[0]id" name="Notifications[0][id]"
                                        value="{{ $noticationAction->notifications[0]->id }}">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." name="Notifications[0][text]">{{ old('Notifications[0][text]', $noticationAction->notifications[0]->text_whatsapp) }}</textarea>
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

                                <div class="col-span-12 pb-6">
                                    @include('components.dropzone', [
                                        'id' => 'Notifications[0][image]',
                                        'name' => 'Notifications[0][image]',
                                        'accept' => 'image/*,application/pdf',
                                        'required' => false,
                                    ])
                                    <input type="hidden" name="Notifications[0][oldImage]"
                                        value="{{ $noticationAction->notifications[0]->url_embed }}">
                                    @if (isset($noticationAction->notifications[0]->url_embed))
                                        <div class="col-span-12 mt-8" id="media-0">
                                            <div
                                                class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                                <div class="overflow-x-scroll md:overflow-visible">
                                                    <table class="table-lg table w-full">
                                                        <thead>
                                                            <tr>
                                                                <th>Foto</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <a title="Ver Foto"
                                                                        href="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[0]->url_embed }}"
                                                                        target="_blank">
                                                                        <img class="h-20 w-20 rounded-lg object-cover"
                                                                            src="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[0]->url_embed }}"
                                                                            alt="{{ $noticationAction->notifications[0]->url_embed }}"
                                                                            loading="lazy" />
                                                                    </a>
                                                                </td>
                                                                <td class="text-end">
                                                                    @component('components.dropdown-button', [
                                                                        'id' => 'dropdownMoreTablefeaturedImage' . $noticationAction->notifications[0]->id,
                                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                                        'customContainer' => 'ml-auto w-fit',
                                                                        'custom' => 'text-xl',
                                                                    ])
                                                                        <ul>
                                                                            <li>
                                                                                <a class="removeMedia flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                    data-id="0" href="javascript:void(0)">
                                                                                    Remover
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    @endcomponent
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-span-12">
                                    <label for="abandonedcartSelect">Enviar a mensagem:</label>
                                    <select id="abandonedcartSelect" name="Notifications[0][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="5"
                                            {{ old('Notifications[0][dispatchTime]', $noticationAction->notifications[0]->dispatch_time) == 5 ? 'selected' : '' }}>
                                            5 minutos após a ação
                                        </option>
                                        <option value="10"
                                            {{ old('Notifications[0][dispatchTime]', $noticationAction->notifications[0]->dispatch_time) == 10 ? 'selected' : '' }}>
                                            10 minutos após a ação
                                        </option>
                                        <option value="15"
                                            {{ old('Notifications[0][dispatchTime]', $noticationAction->notifications[0]->dispatch_time) == 15 ? 'selected' : '' }}>
                                            15 minutos após a ação
                                        </option>
                                        <option value="20"
                                            {{ old('Notifications[0][dispatchTime]', $noticationAction->notifications[0]->dispatch_time) == 20 ? 'selected' : '' }}>
                                            20 minutos após a ação
                                        </option>
                                        <option value="25"
                                            {{ old('Notifications[0][dispatchTime]', $noticationAction->notifications[0]->dispatch_time) == 25 ? 'selected' : '' }}>
                                            25 minutos após a ação
                                        </option>
                                        <option value="30"
                                            {{ old('Notifications[0][dispatchTime]', $noticationAction->notifications[0]->dispatch_time) == 30 ? 'selected' : '' }}>
                                            30 minutos após a ação
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                @endcomponent
                <div class="mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8" id="toggle-component-IssuanceOfBoletoAndPix">

                        <label class="mb-0 flex cursor-pointer items-center justify-between" for="IssuanceOfBoletoAndPix">

                            <input class="peer hidden" id="IssuanceOfBoletoAndPix" type="checkbox"
                                name="Notifications[1][status]"
                                {{ old('Notifications[1][status]', $noticationAction->notifications[1]->status) ? 'checked' : '' }} />

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

                                    <label for="notification[1]id">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="notification[1]id" name="Notifications[1][id]"
                                        value="{{ $noticationAction->notifications[1]->id }}">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." name="Notifications[1][text]">{{ old('Notifications[1][text]', $noticationAction->notifications[1]->text_whatsapp) }}</textarea>
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
                                    <input type="hidden" name="Notifications[1][oldImage]"
                                        value="{{ $noticationAction->notifications[1]->url_embed }}">
                                    @if (isset($noticationAction->notifications[1]->url_embed))
                                        <div class="col-span-12 mt-8" id="media-1">
                                            <div
                                                class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                                <div class="overflow-x-scroll md:overflow-visible">
                                                    <table class="table-lg table w-full">
                                                        <thead>
                                                            <tr>
                                                                <th>Foto</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <a title="Ver Foto"
                                                                        href="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[1]->url_embed }}"
                                                                        target="_blank">
                                                                        <img class="h-20 w-20 rounded-lg object-cover"
                                                                            src="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[1]->url_embed }}"
                                                                            alt="{{ $noticationAction->notifications[1]->url_embed }}"
                                                                            loading="lazy" />
                                                                    </a>
                                                                </td>
                                                                <td class="text-end">
                                                                    @component('components.dropdown-button', [
                                                                        'id' => 'dropdownMoreTablefeaturedImage' . $noticationAction->notifications[1]->id,
                                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                                        'customContainer' => 'ml-auto w-fit',
                                                                        'custom' => 'text-xl',
                                                                    ])
                                                                        <ul>
                                                                            <li>
                                                                                <a class="removeMedia flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                    data-id="1" href="javascript:void(0)">
                                                                                    Remover
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    @endcomponent
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-span-12">
                                    <label for="IssuanceOfBoletoAndPixSelect">Enviar a mensagem:</label>
                                    <select id="IssuanceOfBoletoAndPixSelect" name="Notifications[1][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="0"
                                            {{ old('Notifications[1][dispatchTime]', $noticationAction->notifications[1]->dispatch_time) == 0 ? 'selected' : '' }}>
                                            Imediatamente após a ação
                                        </option>
                                        <option value="5"
                                            {{ old('Notifications[1][dispatchTime]', $noticationAction->notifications[1]->dispatch_time) == 5 ? 'selected' : '' }}>
                                            5 minutos após a ação
                                        </option>
                                        <option value="10"
                                            {{ old('Notifications[1][dispatchTime]', $noticationAction->notifications[1]->dispatch_time) == 10 ? 'selected' : '' }}>
                                            10 minutos após a ação
                                        </option>
                                        <option value="15"
                                            {{ old('Notifications[1][dispatchTime]', $noticationAction->notifications[1]->dispatch_time) == 15 ? 'selected' : '' }}>
                                            15 minutos após a ação
                                        </option>
                                        <option value="20"
                                            {{ old('Notifications[1][dispatchTime]', $noticationAction->notifications[1]->dispatch_time) == 20 ? 'selected' : '' }}>
                                            20 minutos após a ação
                                        </option>
                                        <option value="25"
                                            {{ old('Notifications[1][dispatchTime]', $noticationAction->notifications[1]->dispatch_time) == 25 ? 'selected' : '' }}>
                                            25 minutos após a ação
                                        </option>
                                        <option value="30"
                                            {{ old('Notifications[1][dispatchTime]', $noticationAction->notifications[1]->dispatch_time) == 30 ? 'selected' : '' }}>
                                            30 minutos após a ação
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                @endcomponent
                <div class="mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8" id="toggle-component-PaymentConfirmationBoletoAndPix">

                        <label class="mb-0 flex cursor-pointer items-center justify-between"
                            for="PaymentConfirmationBoletoAndPix">
                            <input class="peer hidden" id="PaymentConfirmationBoletoAndPix" type="checkbox"
                                name="Notifications[2][status]"
                                {{ old('Notifications[2][status]', $noticationAction->notifications[2]->status) ? 'checked' : '' }} />

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
                                    <label for="notification[2]id">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="notification[2]id" name="Notifications[2][id]"
                                        value="{{ $noticationAction->notifications[2]->id }}">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." name="Notifications[2][text]">{{ old('Notifications[2][text]', $noticationAction->notifications[2]->text_whatsapp) }}</textarea>
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
                                    <input type="hidden" name="Notifications[2][oldImage]"
                                        value="{{ $noticationAction->notifications[2]->url_embed }}">
                                    @if (isset($noticationAction->notifications[2]->url_embed))
                                        <div class="col-span-12 mt-8" id="media-2">
                                            <div
                                                class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                                <div class="overflow-x-scroll md:overflow-visible">
                                                    <table class="table-lg table w-full">
                                                        <thead>
                                                            <tr>
                                                                <th>Foto</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <a title="Ver Foto"
                                                                        href="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[2]->url_embed }}"
                                                                        target="_blank">
                                                                        <img class="h-20 w-20 rounded-lg object-cover"
                                                                            src="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[2]->url_embed }}"
                                                                            alt="{{ $noticationAction->notifications[2]->url_embed }}"
                                                                            loading="lazy" />
                                                                    </a>
                                                                </td>
                                                                <td class="text-end">
                                                                    @component('components.dropdown-button', [
                                                                        'id' => 'dropdownMoreTablefeaturedImage' . $noticationAction->notifications[2]->id,
                                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                                        'customContainer' => 'ml-auto w-fit',
                                                                        'custom' => 'text-xl',
                                                                    ])
                                                                        <ul>
                                                                            <li>
                                                                                <a class="removeMedia flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                    data-id="2" href="javascript:void(0)">
                                                                                    Remover
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    @endcomponent
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-span-12">
                                    <label for="PaymentConfirmationBoletoAndPixSelect">Enviar a mensagem:</label>
                                    <select id="PaymentConfirmationBoletoAndPixSelect" name="Notifications[2][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="0"
                                            {{ old('Notifications[2][dispatchTime]', $noticationAction->notifications[2]->dispatch_time) == 0 ? 'selected' : '' }}>
                                            Imediatamente após a ação
                                        </option>
                                        <option value="5"
                                            {{ old('Notifications[2][dispatchTime]', $noticationAction->notifications[2]->dispatch_time) == 5 ? 'selected' : '' }}>
                                            5 minutos após a ação
                                        </option>
                                        <option value="10"
                                            {{ old('Notifications[2][dispatchTime]', $noticationAction->notifications[2]->dispatch_time) == 10 ? 'selected' : '' }}>
                                            10 minutos após a ação
                                        </option>
                                        <option value="15"
                                            {{ old('Notifications[2][dispatchTime]', $noticationAction->notifications[2]->dispatch_time) == 15 ? 'selected' : '' }}>
                                            15 minutos após a ação
                                        </option>
                                        <option value="20"
                                            {{ old('Notifications[2][dispatchTime]', $noticationAction->notifications[2]->dispatch_time) == 20 ? 'selected' : '' }}>
                                            20 minutos após a ação
                                        </option>
                                        <option value="25"
                                            {{ old('Notifications[2][dispatchTime]', $noticationAction->notifications[2]->dispatch_time) == 25 ? 'selected' : '' }}>
                                            25 minutos após a ação
                                        </option>
                                        <option value="30"
                                            {{ old('Notifications[2][dispatchTime]', $noticationAction->notifications[2]->dispatch_time) == 30 ? 'selected' : '' }}>
                                            30 minutos após a ação
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                @endcomponent
                <div class="mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8" id="toggle-component-PaymentConfirmationCreditCard">

                        <label class="mb-0 flex cursor-pointer items-center justify-between"
                            for="PaymentConfirmationCreditCard">

                            <input class="peer hidden" id="PaymentConfirmationCreditCard" type="checkbox"
                                name="Notifications[3][status]"
                                {{ old('Notifications[3][status]', $noticationAction->notifications[3]->status) ? 'checked' : '' }} />
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

                                    <label for="notification[3]id">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="notification[3]id" name="Notifications[3][id]"
                                        value="{{ $noticationAction->notifications[3]->id }}">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." name="Notifications[3][text]">{{ old('Notifications[3][text]', $noticationAction->notifications[3]->text_whatsapp) }}</textarea>
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
                                    <input type="hidden" name="Notifications[3][oldImage]"
                                        value="{{ $noticationAction->notifications[3]->url_embed }}">
                                    @if (isset($noticationAction->notifications[3]->url_embed))
                                        <div class="col-span-12 mt-8" id="media-3">
                                            <div
                                                class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                                <div class="overflow-x-scroll md:overflow-visible">
                                                    <table class="table-lg table w-full">
                                                        <thead>
                                                            <tr>
                                                                <th>Foto</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <a title="Ver Foto"
                                                                        href="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[3]->url_embed }}"
                                                                        target="_blank">
                                                                        <img class="h-20 w-20 rounded-lg object-cover"
                                                                            src="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[3]->url_embed }}"
                                                                            alt="{{ $noticationAction->notifications[3]->url_embed }}"
                                                                            loading="lazy" />
                                                                    </a>
                                                                </td>
                                                                <td class="text-end">
                                                                    @component('components.dropdown-button', [
                                                                        'id' => 'dropdownMoreTablefeaturedImage' . $noticationAction->notifications[3]->id,
                                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                                        'customContainer' => 'ml-auto w-fit',
                                                                        'custom' => 'text-xl',
                                                                    ])
                                                                        <ul>
                                                                            <li>
                                                                                <a class="removeMedia flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                    data-id="3" href="javascript:void(0)">
                                                                                    Remover
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    @endcomponent
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-span-12">
                                    <label for="PaymentConfirmationCreditCardSelect">Enviar a mensagem:</label>
                                    <select id="PaymentConfirmationCreditCardSelect" name="Notifications[3][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="0"
                                            {{ old('Notifications[3][dispatchTime]', $noticationAction->notifications[3]->dispatch_time) == 0 ? 'selected' : '' }}>
                                            Imediatamente após a ação
                                        </option>
                                        <option value="5"
                                            {{ old('Notifications[3][dispatchTime]', $noticationAction->notifications[3]->dispatch_time) == 5 ? 'selected' : '' }}>
                                            5 minutos após a ação
                                        </option>
                                        <option value="10"
                                            {{ old('Notifications[3][dispatchTime]', $noticationAction->notifications[3]->dispatch_time) == 10 ? 'selected' : '' }}>
                                            10 minutos após a ação
                                        </option>
                                        <option value="15"
                                            {{ old('Notifications[3][dispatchTime]', $noticationAction->notifications[3]->dispatch_time) == 15 ? 'selected' : '' }}>
                                            15 minutos após a ação
                                        </option>
                                        <option value="20"
                                            {{ old('Notifications[3][dispatchTime]', $noticationAction->notifications[3]->dispatch_time) == 20 ? 'selected' : '' }}>
                                            20 minutos após a ação
                                        </option>
                                        <option value="25"
                                            {{ old('Notifications[3][dispatchTime]', $noticationAction->notifications[3]->dispatch_time) == 25 ? 'selected' : '' }}>
                                            25 minutos após a ação
                                        </option>
                                        <option value="30"
                                            {{ old('Notifications[3][dispatchTime]', $noticationAction->notifications[3]->dispatch_time) == 30 ? 'selected' : '' }}>
                                            30 minutos após a ação
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcomponent
                <div class="mb-5"></div>
                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-8" id="toggle-component-PaymentErrorCreditCard">

                        <label class="mb-0 flex cursor-pointer items-center justify-between" for="PaymentErrorCreditCard">

                            <input class="peer hidden" id="PaymentErrorCreditCard" type="checkbox"
                                name="Notifications[4][status]"
                                {{ old('Notifications[4][status]', $noticationAction->notifications[4]->status) ? 'checked' : '' }} />

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

                                    <label for="notification[4]id">Conteúdo da mensagem:</label>
                                    <input type="hidden" id="notification[4]id" name="Notifications[4][id]"
                                        value="{{ $noticationAction->notifications[4]->id }}">
                                    <textarea class="mensagem" rows="5" placeholder="Texto da Mensagem..." name="Notifications[4][text]">{{ old('Notifications[4][text]', $noticationAction->notifications[4]->text_whatsapp) }}</textarea>
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
                                    <input type="hidden" name="Notifications[4][oldImage]"
                                        value="{{ $noticationAction->notifications[4]->url_embed }}">
                                    @if (isset($noticationAction->notifications[4]->url_embed))
                                        <div class="col-span-12 mt-8" id="media-4">
                                            <div
                                                class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                                <div class="overflow-x-scroll md:overflow-visible">
                                                    <table class="table-lg table w-full">
                                                        <thead>
                                                            <tr>
                                                                <th>Foto</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <a title="Ver Foto"
                                                                        href="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[4]->url_embed }}"
                                                                        target="_blank">
                                                                        <img class="h-20 w-20 rounded-lg object-cover"
                                                                            src="{{ \App\Helpers\StorageUrl::getStorageUrlS3() . $noticationAction->notifications[4]->url_embed }}"
                                                                            alt="{{ $noticationAction->notifications[4]->url_embed }}"
                                                                            loading="lazy" />
                                                                    </a>
                                                                </td>
                                                                <td class="text-end">
                                                                    @component('components.dropdown-button', [
                                                                        'id' => 'dropdownMoreTablefeaturedImage' . $noticationAction->notifications[4]->id,
                                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                                        'customContainer' => 'ml-auto w-fit',
                                                                        'custom' => 'text-xl',
                                                                    ])
                                                                        <ul>
                                                                            <li>
                                                                                <a class="removeMedia flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                    data-id="4" href="javascript:void(0)">
                                                                                    Remover
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    @endcomponent
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-span-12">
                                    <label for="PaymentErrorCreditCardSelect">Enviar a mensagem:</label>
                                    <select id="PaymentErrorCreditCardSelect" name="Notifications[4][dispatchTime]"
                                        class="!ring-neutral-200">
                                        <option value="0"
                                            {{ old('Notifications[4][dispatchTime]', $noticationAction->notifications[4]->dispatch_time) == 0 ? 'selected' : '' }}>
                                            Imediatamente após a ação
                                        </option>
                                        <option value="5"
                                            {{ old('Notifications[4][dispatchTime]', $noticationAction->notifications[4]->dispatch_time) == 5 ? 'selected' : '' }}>
                                            5 minutos após a ação
                                        </option>
                                        <option value="10"
                                            {{ old('Notifications[4][dispatchTime]', $noticationAction->notifications[4]->dispatch_time) == 10 ? 'selected' : '' }}>
                                            10 minutos após a ação
                                        </option>
                                        <option value="15"
                                            {{ old('Notifications[4][dispatchTime]', $noticationAction->notifications[4]->dispatch_time) == 15 ? 'selected' : '' }}>
                                            15 minutos após a ação
                                        </option>
                                        <option value="20"
                                            {{ old('Notifications[4][dispatchTime]', $noticationAction->notifications[4]->dispatch_time) == 20 ? 'selected' : '' }}>
                                            20 minutos após a ação
                                        </option>
                                        <option value="25"
                                            {{ old('Notifications[4][dispatchTime]', $noticationAction->notifications[4]->dispatch_time) == 25 ? 'selected' : '' }}>
                                            25 minutos após a ação
                                        </option>
                                        <option value="30"
                                            {{ old('Notifications[4][dispatchTime]', $noticationAction->notifications[4]->dispatch_time) == 30 ? 'selected' : '' }}>
                                            30 minutos após a ação
                                        </option>
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

        document.querySelectorAll('.removeMedia').forEach(function(element) {
            element.addEventListener('click', function(event) {
                event.preventDefault();
                const mediaId = element.getAttribute('data-id');

                const mediaDiv = document.querySelector(`#media-${mediaId}`);

                if (mediaDiv) {
                    mediaDiv.remove();
                }

                const inputField = document.querySelector(
                    `input[name="Notifications[${mediaId}][oldImage]"]`);
                if (inputField) {
                    inputField.value = '';
                }
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

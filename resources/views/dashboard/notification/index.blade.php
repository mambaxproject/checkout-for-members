@extends('layouts.dashboard')

@section('content')
<div class="relative space-y-6 md:space-y-8 lg:space-y-10">
    <div class="flex items-center gap-3">
        <h1 class="mr-auto">
            <span class="text-neutral-400">Mensageria ></span>
            Whatsapp
        </h1>
        @if ($whatsappConnection['connection'])
        <div class="flex h-12 items-center justify-center gap-1 rounded-full bg-white pl-5 pr-5">
            <p class="text-primary">{{ $whatsappConnection['phoneConnected'] }} </p>
        </div>
        <div class="flex h-12 items-center justify-center gap-2 rounded-full bg-white pl-4 pr-5">
            <div class="relative h-2 w-2">
                <span class="absolute h-2 w-2 animate-pulse rounded-full bg-primary"></span>
                <span class="absolute h-2 w-2 animate-ping rounded-full bg-primary"></span>
            </div>
            Conectado
        </div>
        <div class="flex h-12 items-center justify-center gap-2 rounded-full bg-white pl-4 pr-5">
            <button
                type="button" id="buttonDisconnect" onclick="disconnectInstance()">
                <div class="w-5 h-5 bg-red-600"></div>
                <p class="absolute bg-black text-white text-sm px-2 py-1 rounded opacity-0 transition-opacity duration-300 hover:opacity-100">
                    Desconectar
                </p>
            </button>
        </div>

        @else
        <button
            class="button button-outline-primary h-12 rounded-full"
            data-modal-target="connectWhatsAppModal"
            data-modal-toggle="connectWhatsAppModal"
            type="button">
            Conectar com WhatsApp
        </button>
        @endif
        <button
            class="button button-primary h-12 rounded-full"
            data-modal-target="addNewActionModal"
            data-modal-toggle="addNewActionModal"
            type="button">
            Adicionar nova ação
        </button>

    </div>

    <div class="flex w-full items-center gap-6">

        <form
            action="{{ route('dashboard.notification.index' , ['services' => 'whatsapp']) }}"
            method=""
            class="flex-1">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <div class="append">
                        <input
                            type="text"
                            id="nameAction"
                            name="nameAction"
                            value=""
                            placeholder="Pesquisar por nome da ação">

                        <button
                            class="append-item-right w-12"
                            type="submit">
                            @include('components.icon', [
                            'icon' => 'search',
                            'custom' => 'text-neutral-500 text-xl',
                            ])
                        </button>

                    </div>
                </div>
            </div>
        </form>

        <button
            class="button button-outline-primary h-12 gap-2 rounded-full"
            data-drawer-target="drawerFilterMessaging"
            data-drawer-show="drawerFilterMessaging"
            data-drawer-placement="right"
            type="button">
            @include('components.icon', [
            'type' => 'fill',
            'icon' => 'filter_alt',
            'custom' => 'text-xl',
            ])
            Filtros de pesquisa
        </button>

    </div>
    @if(session('message'))
    <div class="alert alert-success d-flex p-2 mt-2" style=" justify-content:center; font-size: 16px; font-weight: bold; border-radius: 5px;">
        {{ session('message') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-success d-flex p-2 mt-2" style=" justify-content:center; font-size: 16px; font-weight: bold; border-radius: 5px;">
        {{ session('error') }}
    </div>
    @endif
    @if($errors->has('nameAction'))
    <div class="alert alert-danger d-flex p-2 mt-2" style=" justify-content:center; font-size: 16px; font-weight: bold; border: 2px solid red; border-radius: 5px;">
        <span>
            {{ $errors->first('nameAction') }}
        </span>
    </div>
    @endif
    <div class="overflow-hidden rounded-xl bg-white">

        <div class="overflow-x-scroll md:overflow-visible">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Ação</th>
                        <th>Produto</th>
                        <th>Última ação</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($actions as $action)
                    <tr>
                        <td>{{ $action->actionName }}</td>
                        <td>{{ $action->nameProduct }}</td>
                        <td> {{ date('d/m/Y H:i', strtotime($action->updated_at)) }}</td>
                        <td>
                            @if ($action->productRemoved)
                            <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'circle',
                                'custom' => 'text-xs text-danger-700',
                                ])
                                Produto Removido
                            </div>
                            @elseif ($action->actionStatus)
                            <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'circle',
                                'custom' => 'text-xs text-success-400',
                                ])
                                Ativo
                            </div>
                            @else
                            <div class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                @include('components.icon', [
                                'type' => 'fill',
                                'icon' => 'circle',
                                'custom' => 'text-xs text-danger-700',
                                ])
                                Desativado
                            </div>
                            @endif
                        </td>
                        <td class="text-right">
                            @component('components.dropdown-button', [
                            'id' => 'itemActions' . $action->id,
                            'icon' => 'more_vert',
                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                            'custom' => 'text-xl',
                            ])
                            <ul>

                                <li>
                                    <a
                                        class="flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                        href="{{ route('dashboard.notification.edit' , ['actionId' => $action->id]) }}">
                                        Editar
                                    </a>
                                </li>
                                <li>
                                    <button
                                        class="flex w-full items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                        data-modal-target="duplicateAction"
                                        data-modal-toggle="duplicateAction"
                                        data-action-id="{{ $action->id }}"
                                        type="button">
                                        Duplicar
                                    </button>
                                </li>

                                @if (!$action->productRemoved)
                                <hr class="my-1 border-neutral-100">
                                <li>
                                    <form
                                        action="{{ route('dashboard.notification.changeStatus', ['actionId' => $action->id]) }}"
                                        method="POST"
                                        class="w-full">
                                        @method('PUT')
                                        @csrf
                                        <button
                                            type="submit"
                                            class="flex w-full items-center rounded-lg px-3 py-2 text-sm text-danger-500 hover:bg-danger-50 border-none bg-transparent cursor-pointer">
                                            @if($action->actionStatus)
                                            @include('components.icon', [
                                            'type' => 'fill',
                                            'icon' => 'circle',
                                            'custom' => 'text-xs text-danger-700',
                                            ])
                                            <span class="ml-1 text-danger-700">
                                                Desativar
                                            </span>
                                            @else
                                            @include('components.icon', [
                                            'type' => 'fill',
                                            'icon' => 'circle',
                                            'custom' => 'text-xs text-success-400',
                                            ])
                                            <span class="ml-1 text-success-400">
                                                Ativar
                                            </span>
                                            @endif
                                        </button>
                                    </form>
                                </li>
                                @endif
                            </ul>
                            @endcomponent
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

    @component('components.pagination', [
    'currentPage' => $actions->currentPage(),
    'totalPages' => $actions->lastPage(),
    'totalItems' => $actions->total(),
    ])
    @endcomponent
</div>

@endsection

@push('floating')
@component('components.modal', [
'id' => 'connectWhatsAppModal',
'title' => '',
])

<div class="pb-8">
    <div class="mx-auto mb-8 h-[280px] w-[280px]" id="image-container">
    </div>
    <div class="flex items-center justify-center  mb-10" id="spinQrCode"><img class="h-10 w-10 animate-spin" src="/images/dashboard/spin-gray.svg"> Aguarde...</div>`,
    <h4 class="mb-4 text-center">Conecte com o WhatsApp</h4>
    <ol class="mx-auto max-w-sm space-y-2 text-center text-sm text-neutral-500">
        <li>Aponte seu celular para a tela do dispositivo que você deseja conectar para escanear o QR code.</li>
    </ol>
</div>

@endcomponent

@component('components.modal', [
'id' => 'addNewActionModal',
'title' => 'Adicionar nova ação',
])
<form
    action="{{ route('dashboard.notification.store') }}"
    method="GET"
    class="space-y-6"
    id="addNewActionForm">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <label for="productSelect">Selecionar produto</label>
            <select
                name="productId"
                id="productSelect"
                onchange="setProductName('productSelect')"
                required>
                <option value="">Selecione um produto</option>
            </select>
            <div class=" feedback-message">Selecione pelo menos um produto
            </div>
        </div>
        <div class="col-span-12">
            <label for="nameAction">Nome da ação</label>
            <input
                placeholder="Nome da ação"
                required
                id="nameAction"
                name="nameAction"
                type="text">
            <div class="feedback-message">Selecione pelo menos um produto</div>
        </div>
        <input type="hidden" name="nameProduct" id="nameProduct">
        <div class="col-span-12">
            <label for="descAction">Descrição da Ação</label>
            <input
                placeholder="Descrição da ação"
                type="text"
                id="descAction"
                value=""
                name="descAction">
        </div>
    </div>
    <div class="flex justify-end">
        <button type="submit" class="button button-primary h-12 rounded-full">
            Salvar
        </button>
    </div>
</form>
@endcomponent

@component('components.modal', [
'id' => 'duplicateAction',
'title' => 'duplicar ação',
])
<form
    action="{{ route('dashboard.notification.duplicate') }}"
    method="POST"
    class="space-y-6"
    id="duplicateActionForm">
    @csrf
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <label for="productSelect">Selecionar produto</label>
            <select
                name="productId"
                id="productSelectDuplicate"
                onchange="setProductName('productSelectDuplicate')"
                required>
                <option value="">Selecione um produto</option>
            </select>
            <div class=" feedback-message">Selecione pelo menos um produto
            </div>
        </div>
        <input
            id="actionId"
            name="actionId"
            type="hidden">
        <div class="col-span-12">
            <label for="nameAction">Nome da ação</label>
            <input
                placeholder="Nome da ação"
                required
                id="nameAction"
                name="nameAction"
                type="text">
            <div class="feedback-message">Selecione pelo menos um produto</div>
        </div>
        <div class="col-span-12">
            <label for="descAction">Descrição da Ação</label>
            <input
                placeholder="Descrição da ação"
                type="text"
                id="descAction"
                value=""
                name="descAction">
        </div>
    </div>
    <div class="flex justify-end">
        <button type="submit" class="button button-primary h-12 rounded-full">
            Salvar
        </button>
    </div>
</form>
@endcomponent



@component('components.drawer', [
'id' => 'drawerFilterMessaging',
'title' => 'Filtros de pesquisa',
'custom' => 'max-w-xl'
])
<form
    action="{{ route('dashboard.notification.index' , ['services' => 'whatsapp']) }}"
    method="GET">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <label for="nameAction">Nome da ação</label>
            <input
                placeholder="Pesquise digitando nome da acão"
                type="text"
                id="nameAction"
                name="nameAction" />
        </div>
        <div class="col-span-12">
            <label for="nameProduct">Nome do produto</label>
            <input
                placeholder="Pesquise digitando nome do produto"
                type="text"
                id="nameProduct"
                name="nameProduct" />
        </div>

        <div class="col-span-12">
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="">Selecione um status</option>
                <option value="active">Ativo</option>
                <option value="desactive">Inativo</option>
                <option value="removed">Removido</option>
            </select>
        </div>

    </div>

    <button
        class="button button-primary mt-8 h-12 w-full rounded-full"
        type="submit">
        Pesquisar
    </button>
</form>
@endcomponent
@endpush

@push('custom-script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const element = document.getElementById("addNewActionModal");
        const closeModalBtn = document.querySelector('[data-modal-hide="addNewActionModal"]');
        closeModalBtn.addEventListener("click", () => {
            document.getElementById('addNewActionForm').reset();
        });

        const observer = new MutationObserver((mutationsList) => {
            for (let mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'aria-modal') {
                    const ariaModalValue = element.getAttribute('aria-modal');
                    if (ariaModalValue === 'true') {
                        element.addEventListener("click", (event) => {
                            if (event.target === element) {
                                document.getElementById('addNewActionForm').reset();
                            }
                        });
                    }
                    const isModalOpen = element.getAttribute('aria-modal') === 'true';

                    if (isModalOpen) {
                        getProductsAvailable('productSelect')
                    }
                }
            }
        });

        const elementDuplicationAction = document.getElementById("duplicateAction");
        const closeModalBtnDuplicationAction = document.querySelector('[data-modal-hide="duplicateAction"]');

        closeModalBtnDuplicationAction.addEventListener("click", () => {
            document.getElementById('duplicateActionForm').reset();
        });

        const observerDuplicationAction = new MutationObserver((mutationsList) => {
            for (let mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'aria-modal') {
                    const ariaModalValue = element.getAttribute('aria-modal');
                    if (ariaModalValue === 'true') {
                        elementDuplicationAction.addEventListener("click", (event) => {
                            if (event.target === element) {
                                document.getElementById('duplicateActionForm').reset();
                            }
                        });
                    }
                    const isModalOpen = elementDuplicationAction.getAttribute('aria-modal') === 'true';

                    if (isModalOpen) {
                        getProductsAvailable('productSelectDuplicate');
                    }
                }
            }
        });

        observerDuplicationAction.observe(elementDuplicationAction, {
            attributes: true,
        });


        const buttons = document.querySelectorAll('[data-modal-toggle="duplicateAction"]');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const actionId = button.getAttribute('data-action-id');

                document.getElementById('actionId').value = actionId;
            });
        });

        async function getProductsAvailable(select) {
            const data = await fetchData("{{ route('dashboard.notification.productsAvailable') }}");
            populateProducts(data, select);
        }

        function populateProducts(data, select) {
            const selectElement = document.getElementById(select);

            while (selectElement.firstChild) {
                selectElement.removeChild(selectElement.firstChild);
            }

            let defaultOption = document.createElement('option');
            defaultOption.value = "";
            defaultOption.textContent = "Selecione um produto";
            selectElement.appendChild(defaultOption);

            data.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.dataset.name = product.name;
                option.textContent = product.name;
                selectElement.appendChild(option);
            });
        }

        observer.observe(element, {
            attributes: true,
        });

        const elementModalQrCode = document.getElementById("connectWhatsAppModal");
        const handleModalChange = (mutationsList) => {
            mutationsList.forEach(mutation => {
                if (mutation.attributeName === 'aria-modal') {
                    const isModalQrCodeOpen = elementModalQrCode.getAttribute('aria-modal') === 'true';
                    if (isModalQrCodeOpen) {
                        getQrCode();
                        setInterval(() => getQrCode(), 6000);
                    }
                }
            });
        };

        const observerQrCode = new MutationObserver(handleModalChange);
        observerQrCode.observe(elementModalQrCode, {
            attributes: true,
            attributeFilter: ['aria-modal'],
        });

        async function getQrCode() {
            const response = await fetchData("{{ route('dashboard.notification.connection') }}");
            if (response.connection) {
                location.reload();
                setPhoneNumberConnected(response.phoneConnected)
            } else {
                let qrCodeSpin = document.getElementById('spinQrCode');
                qrCodeSpin.remove()
                const container = document.getElementById("image-container");
                const existingImg = document.getElementById("qrCodeImage")

                if (existingImg) {
                    existingImg.src = response.qrCode;
                    return;
                }
                const img = document.createElement("img");
                img.src = response.qrCode;
                img.alt = "qrCode";
                img.id = "qrCodeImage";
                container.appendChild(img);
            }
        }
    });

    function setProductName(select) {
        var select = document.getElementById(select);
        var selectedOption = select.options[select.selectedIndex];
        let productNameInput = document.getElementById('nameProduct');
        productNameInput.value = selectedOption.getAttribute('data-name');
    }

    async function disconnectInstance() {
        let btn = document.getElementById('buttonDisconnect');
        btn.disabled = true;
        const response = await fetch("{{ route('dashboard.notification.disconnectWhatsapp') }}", {
            method: "DELETE",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        location.reload();
    }

    async function fetchData(url) {
        const response = await fetch(url, {
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        return response.json();
    }
</script>
@endpush
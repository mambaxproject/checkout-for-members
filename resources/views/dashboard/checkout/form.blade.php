@extends('layouts.pagebuilder')

@section('content')
    <div class="min-h-full">

        <div class="fixed top-0 z-20 w-full bg-neutral-800 p-4">

            <div class="flex items-center gap-4">

                <img
                    class="h-8"
                    src="{{ asset('images/dashboard/brand-suitpay.svg') }}"
                    alt="{{ config('app.name') }}"
                    loading="lazy"
                >

                <a
                    class="flex h-10 items-center gap-1 rounded-full pl-3 pr-4 text-white hover:bg-neutral-700"
                    title="Voltar"
                    href="{{ route('dashboard.products.edit', ['productUuid' => $checkout->product->client_product_uuid] ) . '#tab=tab-checkout' }}"
                >
                    @include('components.icon', [
                        'icon' => 'arrow_back',
                        'custom' => 'text-xl',
                    ])
                    Voltar
                </a>

                <div class="flex flex-1 justify-end gap-4">

                    <div class="flex items-center gap-1 rounded-xl bg-neutral-900 p-1">

                        <button
                            class="animate flex h-8 w-8 items-center justify-center rounded-lg hover:bg-neutral-700 hover:text-white aria-selected:bg-neutral-700 aria-selected:text-white"
                            id="buttonSelectDesktop"
                            aria-selected="true"
                            onclick="handleButtonSelectDesktop()"
                            type="button"
                        >
                            @include('components.icon', [
                                'icon' => 'desktop_windows',
                                'custom' => 'text-xl',
                            ])
                        </button>

                        <button
                            class="animate flex h-8 w-8 items-center justify-center rounded-lg hover:bg-neutral-700 hover:text-white aria-selected:bg-neutral-700 aria-selected:text-white"
                            id="buttonSelectMobile"
                            aria-selected="false"
                            onclick="handleButtonSelectMobile()"
                            type="button"
                        >
                            @include('components.icon', [
                                'icon' => 'phone_iphone',
                                'custom' => 'text-xl',
                            ])
                        </button>

                    </div>

                    <button
                        class="animate flex h-10 items-center justify-center gap-2 rounded-full bg-primary px-6 text-sm text-white"
                        id="submitButton"
                        type="button"
                        onclick="submitForm()"
                    >
                        Salvar
                    </button>

                </div>

            </div>

        </div>

        <div class="h-[calc(100%-72px)] w-full">

            <div class="fixed top-[72px] z-10 h-[calc(100vh-72px)] w-full max-w-[400px] overflow-y-auto rounded-r-3xl bg-white">

                <form
                    class="flex min-h-full flex-col px-6 py-6"
                    id="formCheckout"
                    action="{{ isset($checkout) ? route('dashboard.checkouts.update', [$checkout->id]) : route('dashboard.checkouts.store') }}"
                    enctype="multipart/form-data"
                    method="POST"
                >

                    <input
                        hidden
                        type="text"
                        name="settings[origin]"
                        value="{{ $checkout->settings['origin'] ?? '' }}"
                    />

                    <input
                        hidden
                        type="text"
                        name="settings[testimonials]"
                    >
                    <div
                        class="flex-1"
                        id="accordion-collapse"
                        data-accordion="collapse"
                    >

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="list-inside list-disc text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @method(isset($checkout) ? 'PUT' : 'POST')
                        @csrf

                        <div class="divide-y divide-neutral-100">

                            <div class="space-y-6 py-6">

                                <button
                                    class="animate group flex w-full items-center justify-between hover:text-primary aria-expanded:bg-white aria-expanded:text-primary"
                                    data-accordion-target="#accordion-collapse-body-1"
                                    aria-expanded="false"
                                    type="button"
                                >

                                    <h4>Informações</h4>
                                    @include('components.icon', [
                                        'icon' => 'arrow_drop_down',
                                        'custom' => 'animate group-aria-expanded:rotate-180',
                                    ])

                                </button>

                                <div id="accordion-collapse-body-1">

                                    <p class="mb-4 text-sm">Personalize sua página de checkout para garantir a melhor experiência para sua clientela</p>

                                    <div class="grid grid-cols-12 gap-6">

                                        <div class="col-span-12">
                                            <label for="name">Nome do Checkout</label>
                                            <input
                                                type="text"
                                                id="name"
                                                name="name"
                                                value="{{ old('name', $checkout->name ?? '') }}"
                                                class="{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                required
                                            />
                                            @error('name')
                                                <p class="mt-1 text-xs italic text-danger-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-span-12">
                                            <label
                                                class="checkbox"
                                                for="inputCheckboxSetDefault"
                                            >
                                                <input
                                                    type="checkbox"
                                                    id="inputCheckboxSetDefault"
                                                    name="default"
                                                    class="{{ $errors->has('default') ? ' is-invalid' : '' }}"
                                                    @checked(old('default', $checkout->default ?? false))
                                                />
                                                Definir como checkout padrão
                                            </label>
                                            @error('default')
                                                <p class="mt-1 text-xs italic text-danger-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="space-y-6 py-6">

                                <button
                                    class="animate group flex w-full items-center justify-between hover:text-primary aria-expanded:bg-white aria-expanded:text-primary"
                                    data-accordion-target="#accordion-collapse-body-2"
                                    aria-expanded="true"
                                    type="button"
                                >

                                    <h4>Opções</h4>
                                    @include('components.icon', [
                                        'icon' => 'arrow_drop_down',
                                        'custom' => 'animate group-aria-expanded:rotate-180',
                                    ])

                                </button>

                                <div id="accordion-collapse-body-2">

                                    <div class="grid grid-cols-12 gap-6">

                                        <div class="col-span-12">
                                            @component('components.toggle', [
                                                'id' => 'inputCheckboxAllowCoupons',
                                                'name' => 'settings[allowCouponsDiscounts]',
                                                'value' => true,
                                                'label' => 'Permitir cupons de desconto',
                                                'action' => 'onchange=handleSetCupom()',
                                                'isChecked' => $checkout->settings['allowCouponsDiscounts'] ?? false,
                                                'contentEmpty' => true,
                                            ])
                                            @endcomponent
                                        </div>

                                        <div class="col-span-12">
                                            @component('components.toggle', [
                                                'id' => 'addCustomFieldInput',
                                                'name' => 'settings[allowCustomField]',
                                                'value' => true,
                                                'label' => 'Campo personalizado',
                                                'action' => 'onchange=handleDeleteCustomField()',
                                                'isChecked' => $checkout->settings['allowCustomField'] ?? false,
                                            ])
                                                <div class="grid grid-cols-12 gap-4">

                                                    <div class="col-span-12">

                                                        <label for="newNameCustomField">Nome do campo</label>
                                                        <input
                                                            type="text"
                                                            id="newNameCustomField"
                                                            name="settings[nameCustomField]"
                                                            value="{{ old('settings.nameCustomField', $checkout->settings['nameCustomField'] ?? '') }}"
                                                        />

                                                        <p
                                                            class="mt-1 text-xs italic text-danger-500"
                                                            id="inputNameMsgError"
                                                        ></p>

                                                    </div>

                                                    <div class="col-span-12">

                                                        <label for="newTypeCustomField">Tipo de campo</label>
                                                        <select
                                                            id="newTypeCustomField"
                                                            name="settings[typeCustomField]"
                                                        >
                                                            <option value="">Selecionar tipo</option>
                                                            <option
                                                                value="text"
                                                                @selected(old('settings.typeCustomField', $checkout->settings['typeCustomField'] ?? false) === 'text')
                                                            >
                                                                Texto
                                                            </option>
                                                            <option
                                                                value="number"
                                                                @selected(old('settings.typeCustomField', $checkout->settings['typeCustomField'] ?? false) === 'number')
                                                            >
                                                                Número
                                                            </option>
                                                        </select>

                                                        <p
                                                            class="mt-1 text-xs italic text-danger-500"
                                                            id="inputTypeMsgError"
                                                        ></p>

                                                    </div>

                                                    <div
                                                        class="col-span-12 hidden"
                                                        id="maskCustomFieldContainer"
                                                    >

                                                        <label for="newMaskCustomField">Máscara do campo</label>
                                                        <input
                                                            id="newMaskCustomField"
                                                            name="settings[maskCustomField]"
                                                            value="{{ old('settings.maskCustomField', $checkout->settings['maskCustomField'] ?? '') }}"
                                                            type="text"
                                                        />

                                                        <div class="mt-2 rounded-xl bg-warning-50 p-4 text-warning-800">
                                                            <p class="text-xs">"9" para um dígito numérico.</p>
                                                            <p class="text-xs">"A" para uma letra maiúscula.</p>
                                                            <p class="text-xs">"a" para uma letra minúscula.</p>
                                                            <p class="mt-1 text-xs">Outros caracteres no padrão (como traços ou parênteses) serão automaticamente inseridos no lugar certo. Ex: (99) 99999-9999</p>
                                                        </div>

                                                    </div>

                                                    <div class="col-span-12">
                                                        <label
                                                            class="checkbox"
                                                            for="newRequiredCustomField"
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                id="newRequiredCustomField"
                                                                name="settings[requiredCustomField]"
                                                                value="true"
                                                                @checked(old('settings.requiredCustomField', $checkout->settings['requiredCustomField'] ?? false))
                                                            />
                                                            Campo Obrigatório
                                                        </label>
                                                    </div>

                                                    <div class="col-span-12">

                                                        <div class="space-y-2">

                                                            <button
                                                                class="button button-light h-10 w-full rounded-full text-sm"
                                                                id="addCustomField"
                                                                onclick="handleAddCustomField()"
                                                                type="button"
                                                            >
                                                                Adicionar campo
                                                            </button>

                                                            <button
                                                                class="button button-light hidden h-10 w-full rounded-full text-sm"
                                                                id="updateCustomField"
                                                                onclick="handleUpdateCustomField()"
                                                                type="button"
                                                            >
                                                                Atualizar campo
                                                            </button>

                                                            <button
                                                                class="button hidden h-10 w-full rounded-full bg-danger-500 text-sm text-white"
                                                                id="deleteCustomField"
                                                                onclick="handleDeleteCustomField()"
                                                                type="button"
                                                            >
                                                                Deletar campo
                                                            </button>

                                                        </div>

                                                    </div>

                                                </div>
                                            @endcomponent
                                        </div>

                                        <div class="col-span-12">
                                            @component('components.toggle', [
                                                'id' => 'addTimer',
                                                'name' => 'settings[allowTimer]',
                                                'value' => true,
                                                'label' => 'Adicionar cronometro',
                                                'action' => 'onchange=handleSetTimer()',
                                                'isChecked' => $checkout->settings['allowTimer'] ?? false,
                                            ])
                                                <div class="grid grid-cols-12 gap-4">

                                                    <div class="col-span-12">
                                                        <label for="">Mensagem</label>
                                                        <input
                                                            name="settings[timer_title]"
                                                            class="timerTitle"
                                                            value="{{ $checkout->settings['timer_title'] ?? 'Compras feitas através de Cartão de crédito são despachadas mais rápido.' }}"
                                                            type="text"
                                                        />
                                                    </div>

                                                    <div class="col-span-12">
                                                        <label for="inputSelectSizeTitleTimer">Fonte da mensagem</label>
                                                        <select
                                                            name="settings[size_title_timer]"
                                                            id="inputSelectSizeTitleTimer"
                                                        >
                                                            <option
                                                                {{ ($checkout->settings['size_title_timer'] ?? '') == '16px' ? 'selected' : '' }}
                                                                value="16px"
                                                            >
                                                                Padrão
                                                            </option>
                                                            <option
                                                                {{ ($checkout->settings['size_title_timer'] ?? '') == '22px' ? 'selected' : '' }}
                                                                value="22px"
                                                            >
                                                                Médio
                                                            </option>
                                                            <option
                                                                {{ ($checkout->settings['size_title_timer'] ?? '') == '28px' ? 'selected' : '' }}
                                                                value="28px"
                                                            >
                                                                Grande
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-span-12">
                                                        <label for="">Tempo</label>
                                                        <input
                                                            class="noScrollInput timerTimer"
                                                            name="settings[timer_timer]"
                                                            value="{{ $checkout->settings['timer_timer'] ?? 20 }}"
                                                            type="number"
                                                        />
                                                        <p class="mt-1 text-xs text-neutral-500">Adicione um tempo em minutos</p>
                                                    </div>

                                                    <div class="col-span-12">
                                                        <label
                                                            class="color"
                                                            for=""
                                                        >
                                                            <input
                                                                id="inputSelectTitleTimerColor"
                                                                name="settings[title_timer_color]"
                                                                value="{{ $checkout->settings['title_timer_color'] ?? '#ffffff' }}"
                                                                type="color"
                                                            >
                                                            Cor do título
                                                        </label>
                                                    </div>

                                                    <div class="col-span-12">
                                                        <label
                                                            class="color"
                                                            for=""
                                                        >
                                                            <input
                                                                id="inputSelectTextTimerColor"
                                                                name="settings[text_timer_color]"
                                                                value="{{ $checkout->settings['text_timer_color'] ?? '#fff700' }}"
                                                                type="color"
                                                            >
                                                            Cor do tempo
                                                        </label>
                                                    </div>

                                                    <div class="col-span-12">
                                                        <label
                                                            class="color"
                                                            for=""
                                                        >
                                                            <input
                                                                id="inputSelectBackgroundTimerColor"
                                                                name="settings[background_timer_color]"
                                                                value="{{ $checkout->settings['background_timer_color'] ?? '#ff4d4d' }}"
                                                                type="color"
                                                            >
                                                            Cor de background
                                                        </label>
                                                    </div>

                                                </div>
                                            @endcomponent
                                        </div>

                                        <div class="col-span-12">
                                            @component('components.toggle', [
                                                'id' => 'addTestimonials',
                                                'name' => 'settings[testimonial]',
                                                'value' => true,
                                                'label' => 'Mostrar depoimentos',
                                                'action' => 'onchange=handleSetTestimonials()',
                                                'contentEmpty' => true,
                                                'isChecked' => $checkout->settings['testimonial'] ?? false,
                                            ])
                                            @endcomponent
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="space-y-6 py-6">

                                <button
                                    class="animate group flex w-full items-center justify-between hover:text-primary aria-expanded:bg-white aria-expanded:text-primary"
                                    data-accordion-target="#accordion-collapse-body-3"
                                    aria-expanded="false"
                                    type="button"
                                >

                                    <h4>Personalização</h4>
                                    @include('components.icon', [
                                        'icon' => 'arrow_drop_down',
                                        'custom' => 'animate group-aria-expanded:rotate-180',
                                    ])

                                </button>

                                <div id="accordion-collapse-body-3">

                                    <div class="grid grid-cols-12 gap-6">

                                        <div class="col-span-12">
                                            <label
                                                class="color"
                                                for="inputSelectPrimaryColor"
                                            >
                                                <input
                                                    type="color"
                                                    id="inputSelectPrimaryColor"
                                                    name="settings[primaryColor]"
                                                    value="{{ old('settings.primaryColor', $checkout->settings['primaryColor'] ?? '#33cc33') }}"
                                                />
                                                Cor primária
                                            </label>
                                        </div>

                                        <div class="col-span-12">
                                            <label
                                                class="color"
                                                for="inputSelectBackgroundColor"
                                            >
                                                <input
                                                    type="color"
                                                    id="inputSelectBackgroundColor"
                                                    name="settings[backgroundColor]"
                                                    value="{{ old('settings.backgroundColor', $checkout->settings['backgroundColor'] ?? '#f5f5f5') }}"
                                                />
                                                Cor de background
                                            </label>
                                        </div>

                                        <div class="col-span-12">
                                            <label
                                                class="color"
                                                for="inputSelectBackgroundButtonColor"
                                            >
                                                <input
                                                    type="color"
                                                    id="inputSelectBackgroundButtonColor"
                                                    name="settings[backgroundButtonColor]"
                                                    value="{{ old('settings.backgroundButtonColor', $checkout->settings['backgroundButtonColor'] ?? '#33cc33') }}"
                                                />
                                                Cor do botão
                                            </label>
                                        </div>

                                        <div class="col-span-12">
                                            <label
                                                class="color"
                                                for="inputSelectTextButtonColor"
                                            >
                                                <input
                                                    type="color"
                                                    id="inputSelectTextButtonColor"
                                                    name="settings[textButtonColor]"
                                                    value="{{ old('settings.textButtonColor', $checkout->settings['textButtonColor'] ?? '#ffffff') }}"
                                                />
                                                Cor do texto do botão
                                            </label>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="space-y-6 py-6">

                                <button
                                    class="animate group flex w-full items-center justify-between hover:text-primary aria-expanded:bg-white aria-expanded:text-primary"
                                    data-accordion-target="#accordion-collapse-body-4"
                                    aria-expanded="false"
                                    type="button"
                                >

                                    <h4>Banners</h4>
                                    @include('components.icon', [
                                        'icon' => 'arrow_drop_down',
                                        'custom' => 'animate group-aria-expanded:rotate-180',
                                    ])

                                </button>

                                <div id="accordion-collapse-body-4">

                                    <div class="grid grid-cols-12 gap-6">

                                        <div class="col-span-12">
                                            @component('components.toggle', [
                                                'id' => 'uploadHorizontalBanner',
                                                'name' => 'setHorizontalBanner',
                                                'label' => 'Banner horizontal abaixo da logo',
                                                'action' => 'onchange=handleSetHorizontalBanner()',
                                                'isChecked' => isset($checkout) && $checkout->getMedia('horizontalBanner')->isNotEmpty(),
                                            ])
                                                <div class="grid grid-cols-12 gap-4">

                                                    <div class="col-span-12">

                                                        @component('components.dropzone', [
                                                            'id' => 'media[horizontalBanner]',
                                                            'name' => 'media[horizontalBanner]',
                                                            'accept' => 'image/*',
                                                            'multiple' => false,
                                                        ])
                                                        @endcomponent
                                                        <p class="mt-1 text-sm text-neutral-400">Formato: 1100x320px com no máximo 2MB.</p>

                                                    </div>

                                                    @if (isset($checkout) && $checkout->getMedia('horizontalBanner')->isNotEmpty())
                                                        <div class="col-span-12">
                                                            <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                                                <div class="overflow-x-scroll md:overflow-visible">
                                                                    <table class="table-lg table w-full">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Foto</th>
                                                                                <th>Nome</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($checkout->getMedia('horizontalBanner') as $media)
                                                                                <tr>
                                                                                    <input
                                                                                        type="hidden"
                                                                                        name="media[]"
                                                                                        value="{{ $media->id }}"
                                                                                    />
                                                                                    <td>
                                                                                        <a
                                                                                            title="Ver imagem"
                                                                                            href="{{ $media->getUrl() }}"
                                                                                            target="_blank"
                                                                                        >
                                                                                            <img
                                                                                                class="imgHorizontalBanner h-12 w-12 rounded-lg object-cover"
                                                                                                src="{{ $media->getUrl() }}"
                                                                                                alt="{{ $media->name }}"
                                                                                                loading="lazy"
                                                                                            />
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="whitespace-normal">{{ $media->name }}</td>
                                                                                    <td>
                                                                                        <a
                                                                                            class="flex h-6 w-6 items-center justify-center rounded-md bg-gray-100 hover:bg-red-300 hover:text-red-800"
                                                                                            onclick="this.closest('tr').remove()"
                                                                                            href="javascript:void(0)"
                                                                                        >
                                                                                            @include('components.icon', ['icon' => 'close', 'custom' => 'text-xl'])
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endcomponent
                                        </div>

                                        <div class="col-span-12">
                                            @component('components.toggle', [
                                                'id' => 'uploadVerticalBanner',
                                                'name' => 'setVerticalBanner',
                                                'label' => 'Banner vertical acima dos selos',
                                                'action' => 'onchange=handleSetVerticalBanner()',
                                                'isChecked' => isset($checkout) && $checkout->getMedia('verticalBanner')->isNotEmpty(),
                                            ])
                                                <div class="grid grid-cols-12 gap-4">

                                                    <div class="col-span-12">

                                                        @component('components.dropzone', [
                                                            'id' => 'media[verticalBanner]',
                                                            'name' => 'media[verticalBanner]',
                                                            'accept' => 'image/*',
                                                            'multiple' => false,
                                                        ])
                                                        @endcomponent
                                                        <p class="mt-1 text-sm text-neutral-400">Formato: 320x590px com no máximo 2mb.</p>

                                                    </div>

                                                    @if (isset($checkout) && $checkout->getMedia('verticalBanner')->isNotEmpty())
                                                        <div class="col-span-12">
                                                            <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                                                <div class="overflow-x-scroll md:overflow-visible">
                                                                    <table class="table-lg table w-full">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Foto</th>
                                                                                <th>Nome</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($checkout->getMedia('verticalBanner') as $media)
                                                                                <tr>
                                                                                    <input
                                                                                        type="hidden"
                                                                                        name="media[]"
                                                                                        value="{{ $media->id }}"
                                                                                    />

                                                                                    <td>
                                                                                        <a
                                                                                            title="Ver imagem"
                                                                                            href="{{ $media->getUrl() }}"
                                                                                            target="_blank"
                                                                                        >
                                                                                            <img
                                                                                                class="imgVerticalBanner h-16 w-16 rounded-lg object-cover"
                                                                                                src="{{ $media->getUrl() }}"
                                                                                                alt="{{ $media->name }}"
                                                                                                loading="lazy"
                                                                                            />
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>{{ $media->name }}</td>
                                                                                    <td>
                                                                                        @component('components.dropdown-button', [
                                                                                            'id' => 'dropdownMoreTablefeaturedImage' . $loop->iteration,
                                                                                            'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                                                            'customContainer' => 'ml-auto w-fit',
                                                                                            'custom' => 'text-xl',
                                                                                        ])
                                                                                            <ul>
                                                                                                <li>
                                                                                                    <a
                                                                                                        class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                                                                        onclick="this.closest('tr').remove()"
                                                                                                        href="javascript:void(0)"
                                                                                                    >
                                                                                                        Remover
                                                                                                    </a>
                                                                                                </li>
                                                                                            </ul>
                                                                                        @endcomponent
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endcomponent
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="space-y-6 py-6">

                                <button
                                    class="animate group flex w-full items-center justify-between hover:text-primary aria-expanded:bg-white aria-expanded:text-primary"
                                    data-accordion-target="#accordion-collapse-body-5"
                                    aria-expanded="false"
                                    type="button"
                                >

                                    <h4>Selos</h4>
                                    @include('components.icon', [
                                        'icon' => 'arrow_drop_down',
                                        'custom' => 'animate group-aria-expanded:rotate-180',
                                    ])

                                </button>

                                <div id="accordion-collapse-body-5">

                                    <div class="grid grid-cols-12 gap-6">

                                        <div class="col-span-12">

                                            @component('components.toggle', [
                                                'id' => 'setSecurePurchaseSeal',
                                                'label' => 'Compra 100% segura',
                                                'contentEmpty' => true,
                                                'name' => 'settings[hasSecurePurchaseSeal]',
                                                'value' => true,
                                                'isChecked' => $checkout->settings['hasSecurePurchaseSeal'] ?? true,
                                                'action' => 'onchange=handleSetSecurePurchaseSeal()',
                                            ])
                                            @endcomponent

                                        </div>

                                        <div class="col-span-12">

                                            @component('components.toggle', [
                                                'id' => 'setPrivacySeal',
                                                'label' => 'Privacidade protegida',
                                                'contentEmpty' => true,
                                                'name' => 'settings[hasPrivacySeal]',
                                                'value' => true,
                                                'isChecked' => $checkout->settings['hasPrivacySeal'] ?? true,
                                                'action' => 'onchange=handleSetPrivacySeal()',
                                            ])
                                            @endcomponent

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <button
                        class="button button-primary mt-auto h-10 w-full rounded-full"
                        type="button"
                        onclick="submitForm()"
                    >
                        Salvar
                    </button>

                </form>

            </div>

            <div class="ml-auto w-[calc(100%-400px)] pt-[72px]">

                <div
                    id="pageContent"
                    class="relative"
                >

                    <div
                        class="mx-auto"
                        id="pageSize"
                    >

                        <div
                            class="-mb-px flex h-[67px] items-center justify-between rounded-t-[60px] bg-primary px-8"
                            id="mockupMobileHeader"
                        >

                            <span class="text-sm font-semibold text-white">{{ date('H:m') }}</span>

                            <div class="flex items-center gap-3">
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 33 21"
                                    fill="#fff"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="power"
                                >
                                    <rect
                                        y="12"
                                        width="6"
                                        height="9"
                                        rx="2"
                                    ></rect>
                                    <rect
                                        x="8.7002"
                                        y="9"
                                        width="6"
                                        height="12"
                                        rx="2"
                                    ></rect>
                                    <rect
                                        x="17.4004"
                                        y="5"
                                        width="6"
                                        height="16"
                                        rx="2"
                                    ></rect>
                                    <rect
                                        x="26.1006"
                                        width="6"
                                        height="21"
                                        rx="2"
                                    ></rect>
                                </svg>
                                <spana class="text-sm font-semibold text-white">5G</spana>
                                <svg
                                    class="h-6 w-6"
                                    viewBox="0 0 86 39"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <rect
                                        fill="transparent"
                                        x="1.75"
                                        y="1.75"
                                        width="75.5"
                                        height="35.5"
                                        rx="8.25"
                                        stroke="white"
                                        stroke-opacity="0.4"
                                        stroke-width="3.5"
                                    ></rect>
                                    <rect
                                        x="6.5"
                                        y="6.5"
                                        width="66"
                                        height="26"
                                        rx="5"
                                        fill="white"
                                    ></rect>
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M81.5 26.5C83.9363 24.9844 85.5 22.6361 85.5 20C85.5 17.3639 83.9363 15.0156 81.5 13.5V26.5Z"
                                        fill="white"
                                        fill-opacity="0.6"
                                    ></path>
                                </svg>
                            </div>

                        </div>

                        <iframe
                            class="m-0 w-full"
                            id="pageCheckout"
                            frameborder="0"
                            onload="resizeIframeToContentSize(this)"
                            src="{{ route('checkout.checkout.index', $checkout?->product) }}"
                        ></iframe>

                    </div>

                    <img
                        class="pointer-events-none absolute left-[calc(50%-(468px+52px)/2)] top-0 mx-auto h-[calc(967px+40px)] w-[calc(468px+52px)]"
                        id="mockupMobile"
                        src="{{ asset('images/dashboard/iphone-16-pro-max-2024.png') }}"
                    >

                </div>

            </div>

        </div>

    </div>

    @component('components.modal', [
        'id' => 'editModal',
        'title' => 'Editar depoimento',
    ])
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <label for="media[avatar]">Avatar</label>

                <div
                    class="relative mx-auto mb-3 h-20 w-20 overflow-hidden rounded-full"
                    id="avatarPreview"
                >
                    <img
                        class="absolute h-full w-full object-cover"
                        src=""
                        alt="Avatar"
                    >
                </div>

                @component('components.dropzone', [
                    'id' => 'media[avatar]',
                    'name' => 'media[avatar]',
                    'accept' => 'image/*',
                    'multiple' => false,
                ])
                @endcomponent
                <p class="mt-1 text-sm text-neutral-400">Formato: 400x400px com no máximo 2MB.</p>
            </div>
            <div class="col-span-12">
                <label for="editName">Nome</label>
                <input
                    class="block w-full rounded-lg border px-3 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    id="editName"
                    type="text"
                />
            </div>
            <div class="col-span-12">
                <label for="editDescription">Descrição</label>
                <textarea
                    class="block w-full rounded-lg border px-3 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    id="editDescription"
                    rows="3"
                ></textarea>
            </div>
            <div class="col-span-12">
                <label for="editStars">Estrelas (0-5)</label>
                <input
                    class="noScrollInput block w-full rounded-lg border px-3 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    id="editStars"
                    min="0"
                    max="5"
                    type="number"
                />
            </div>
        </div>
        <div class="mt-6 flex items-center gap-2">
            <button
                id="saveEdit"
                type="button"
                class="button button-primary h-10 rounded-full"
            >
                Salvar
            </button>
        </div>
    @endcomponent
@endsection

@section('script')
    <script src="{{ asset('js/dashboard/dropzone-config.js') }}"></script>

    <script>
        document.getElementById("submitButton").addEventListener("click", function() {
            document.getElementById("formCheckout").submit();
        });
    </script>

    <script>
        function resizeIframeToContentSize(iframe) {
            if (iframe.contentDocument) {
                iframe.style.height = iframe.contentDocument.body.scrollHeight + 20 + "px";
            }
        }
    </script>

    <script>
        const pageContent = document.getElementById('pageContent');
        const pageSize = document.getElementById('pageSize');
        const iframe = document.getElementById('pageCheckout');
        const buttonSelectMobile = document.getElementById('buttonSelectMobile');
        const buttonSelectDesktop = document.getElementById('buttonSelectDesktop');
        const mockupMobile = document.getElementById("mockupMobile");
        const mockupMobileHeader = document.getElementById("mockupMobileHeader");

        function handleButtonSelectMobile() {
            pageSize.style.width = '468px';
            pageSize.style.paddingTop = '22px';
            pageContent.style.transform = 'scale(0.90)';
            mockupMobile.style.display = 'block';
            mockupMobileHeader.style.display = 'flex';
            iframe.style.height = '900px';
            iframe.style.borderRadius = '0 0 60px 60px';

            buttonSelectMobile.setAttribute('aria-selected', 'true');
            buttonSelectDesktop.setAttribute('aria-selected', 'false');
        }

        function handleButtonSelectDesktop() {
            pageSize.style.width = 'auto';
            pageSize.style.paddingTop = '0px';
            pageContent.style.transform = 'scale(1)';
            mockupMobile.style.display = 'none';
            mockupMobileHeader.style.display = 'none';
            iframe.style.borderRadius = '0';

            buttonSelectMobile.setAttribute('aria-selected', 'false');
            buttonSelectDesktop.setAttribute('aria-selected', 'true');

            resizeIframeToContentSize(iframe);
        }

        mockupMobile.style.display = 'none';
        mockupMobileHeader.style.display = 'none';

        window.addEventListener('load', function() {
            resizeIframeToContentSize(iframe)

            @if ($checkout->settings['testimonial'] ?? false)
                handleSetTestimonials()
            @endif
        });
    </script>

    <script>
        // Show Elements
        function handleElementDisplay(inputId, className, iframeId) {
            const input = document.getElementById(inputId);
            const iframe = document.getElementById(iframeId);
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const elements = iframeDoc.getElementsByClassName(className);

            let shouldResize = false;

            for (let i = 0; i < elements.length; i++) {
                if (input.checked) {
                    elements[i].style.display = 'block';
                    shouldResize = true;
                } else {
                    elements[i].style.display = 'none';
                    shouldResize = true;
                }
            }

            if (shouldResize) {
                resizeIframeToContentSize(iframe);
            }
        }

        // Add Custom Filed
        function handleAddCustomField() {
            let activeCustomField = document.getElementById('addCustomFieldInput');

            let inputName = document.getElementById('newNameCustomField');
            let inputType = document.getElementById('newTypeCustomField');
            let inputMask = document.getElementById('newMaskCustomField');
            let inputRequired = document.getElementById('newRequiredCustomField');

            // Limpa input mask ao alterar o type
            inputType.addEventListener('change', function() {
                inputMask.value = '';
            });

            // Reseta campos e msg de error
            activeCustomField.addEventListener('change', function() {
                if (!activeCustomField.checked) {
                    // Limpa os campos
                    inputName.value = '';
                    inputType.value = '';
                    inputMask.value = '';
                    inputRequired.checked = false;

                    // Limpa input Mask
                    toggleMaskVisibility();

                    // Limpa a msg de error
                    document.getElementById('inputNameMsgError').innerHTML = '';

                    // Mostra os botões
                    $('#addCustomField').css('display', 'block');
                    $('#updateCustomField').css('display', 'none');
                    $('#deleteCustomField').css('display', 'none');
                }
            });

            // Adiciona máscara ao input text
            function toggleMaskVisibility() {
                const maskCustomFieldContainer = document.getElementById('maskCustomFieldContainer');
                maskCustomFieldContainer.style.display = inputType.value === 'text' ? 'block' : 'none';
            }

            toggleMaskVisibility();
            inputType.addEventListener('change', toggleMaskVisibility);

            // Motando Input
            const inputId = 'newCustomField';

            const iframe = document.getElementById('pageCheckout');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            if (activeCustomField.checked) {

                const containerDiv = iframeDoc.getElementById('setCustomField');

                if (containerDiv) {

                    const checkInputId = iframeDoc.getElementById(inputId);

                    if (!checkInputId) {

                        if (inputName.value.trim() === '') {
                            $('#inputNameMsgError').text('Campo obrigatório');
                        } else {
                            $("#inputNameMsgError").text('');
                        }

                        if (inputType.value.trim() === '') {
                            $('#inputTypeMsgError').text('Campo obrigatório');
                        } else {
                            $("#inputTypeMsgError").text('');
                        }

                        if (inputName.value.trim() !== '' && inputType.value.trim() !== '') {

                            // create label
                            const createLabel = iframeDoc.createElement('label');
                            createLabel.setAttribute('for', inputId);
                            createLabel.textContent = inputName.value;

                            // create input
                            const createInput = iframeDoc.createElement('input');
                            createInput.setAttribute('id', inputId);
                            createInput.setAttribute('name', inputName.value);
                            createInput.setAttribute('type', inputType.value);
                            createInput.setAttribute('placeholder', inputMask.value);

                            console.log(inputMask.value);


                            if (inputRequired) {
                                createInput.setAttribute('required', true);
                            }

                            if (inputMask) {
                                createInput.setAttribute('oninput', "setInputMask(this, '" + inputMask.value + "')");
                            }

                            // add
                            containerDiv.appendChild(createLabel);
                            containerDiv.appendChild(createInput);

                            // 
                            resizeIframeToContentSize(iframe);

                            // 
                            $('#addCustomField').css('display', 'none');
                            $('#updateCustomField').css('display', 'block');
                            $('#deleteCustomField').css('display', 'block');

                        }

                    }

                }

            }
        }

        // Update Custom Filed
        function handleUpdateCustomField() {

            const activeCustomField = document.getElementById('addCustomFieldInput');

            const iframe = document.getElementById('pageCheckout');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            const checkInputId = iframeDoc.getElementById('newCustomField');

            if (activeCustomField.checked) {

                if (checkInputId) {

                    checkInputId.previousSibling.remove();
                    checkInputId.remove();

                    handleAddCustomField();

                }

            }

        }

        // Delete Custom Filed
        function handleDeleteCustomField() {

            const iframe = document.getElementById('pageCheckout');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            const checkInputId = iframeDoc.getElementById('newCustomField');

            if (checkInputId) {

                checkInputId.previousSibling.remove();
                checkInputId.remove();

                document.getElementById('newNameCustomField').value = '';
                document.getElementById('newTypeCustomField').value = '';
                document.getElementById('newMaskCustomField').value = '';
                document.getElementById('newRequiredCustomField').checked = false;

                resizeIframeToContentSize(iframe);

                // 
                $('#addCustomField').css('display', 'block');
                $('#updateCustomField').css('display', 'none');
                $('#deleteCustomField').css('display', 'none');

            }

        }

        // Add Banner
        function handleSetHorizontalBanner() {
            handleElementDisplay('uploadHorizontalBanner', 'setHorizontalBanner', 'pageCheckout');

            const iframe = document.getElementById('pageCheckout');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const element = iframeDoc.querySelector('.setHorizontalBannerURL');

            let imgHorizontalBanner = document.querySelector('.imgHorizontalBanner').src;

            element.src = imgHorizontalBanner ?? element.src;
        }

        // Add Banner
        function handleSetVerticalBanner() {
            handleElementDisplay('uploadVerticalBanner', 'setVerticalBanner', 'pageCheckout');

            const iframe = document.getElementById('pageCheckout');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const element = iframeDoc.querySelector('.setVerticalBannerURL');

            let imgHorizontalBanner = document.querySelector('.imgVerticalBanner').src;

            element.src = imgHorizontalBanner ?? element.src;
        }

        // Add Coupom
        function handleSetCupom() {
            handleElementDisplay('inputCheckboxAllowCoupons', 'setCupom', 'pageCheckout');
        }

        // Add Seals
        function handleSetSecurePurchaseSeal() {
            handleElementDisplay('setSecurePurchaseSeal', 'setSecurePurchaseSeal', 'pageCheckout');
        }

        // Add Seals
        function handleSetPrivacySeal() {
            handleElementDisplay('setPrivacySeal', 'setPrivacySeal', 'pageCheckout');
        }

        // Add Seals
        function toggleSealsVisibility() {
            const privacySeal = document.getElementById('setPrivacySeal');
            const securePurchaseSeal = document.getElementById('setSecurePurchaseSeal');
            const iframe = document.getElementById('pageCheckout');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const seals = iframeDoc.getElementsByClassName('notSeal');

            let shouldResize = false;

            if (!privacySeal.checked && !securePurchaseSeal.checked) {
                for (let i = 0; i < seals.length; i++) {
                    seals[i].style.display = 'none';
                    shouldResize = true;
                }
            } else {
                for (let i = 0; i < seals.length; i++) {
                    seals[i].style.display = 'block';
                    shouldResize = true;
                }
            }

            if (shouldResize) {
                resizeIframeToContentSize(iframe);
            }
        }

        // Add Timer
        function handleSetTimer() {
            handleElementDisplay('addTimer', 'setTimer', 'pageCheckout');
        }

        // Add Testimonials
        function handleSetTestimonials() {
            handleElementDisplay('addTestimonials', 'setTestimonials', 'pageCheckout');

            const iframe = document.getElementById('pageCheckout');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            if (iframeDoc) {
                const testimonialsElement = iframeDoc.getElementById('testimonials');

                if (testimonialsElement) {
                    $("#addTestimonials").on('change', function() {
                        if (this.checked) {
                            testimonialsElement.scrollIntoView({
                                behavior: 'smooth',
                            });
                        } else {
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth',
                            });
                        }
                    });
                }
            }
        }

        // Style
        function updateStyle({
            primaryColor,
            backgroundColor,
            buttonColor,
            textButtonColor,
            titleTimerColor,
            sizeTitleTimer,
            textTimerColor,
            backgroundTimerColor
        }) {
            const iframe = document.getElementById('pageCheckout');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const style = iframeDoc.getElementById('customCheckoutStyle');

            mockupMobileHeader.style.backgroundColor = backgroundColor;

            if (iframeDoc) {
                if (style) {
                    style.innerHTML = `
                        #backgroundColor {
                            background-color: ${backgroundColor};
                        }

                        .textPrimaryColor,
                        .linkPrimaryColor a {
                            color: ${primaryColor};
                        }

                        .setButtonColor {
                            background-color: ${buttonColor};
                        }

                        .setButtonTextColor {
                            color: ${textButtonColor};
                        }

                        .selectPaymentMethod input:checked~.content {
                            border-color: ${primaryColor};
                        }

                        .selectPaymentMethod input:checked~.content .radio {
                            background-color: ${primaryColor};
                            border-color: ${primaryColor};
                        }

                        .timer {
                            background-color: ${backgroundTimerColor};
                        }

                        .timerTitle {
                            color: ${titleTimerColor};
                            font-size: ${sizeTitleTimer};
                        }

                        .timerText {
                            color: ${textTimerColor};
                        }
                    `;
                }
            }
        }

        function getColorValues() {
            return {
                primaryColor: document.getElementById('inputSelectPrimaryColor').value,
                backgroundColor: document.getElementById('inputSelectBackgroundColor').value,
                buttonColor: document.getElementById('inputSelectBackgroundButtonColor').value,
                textButtonColor: document.getElementById('inputSelectTextButtonColor').value,
                titleTimerColor: document.getElementById('inputSelectTitleTimerColor').value,
                sizeTitleTimer: document.getElementById('inputSelectSizeTitleTimer').value,
                textTimerColor: document.getElementById('inputSelectTextTimerColor').value,
                backgroundTimerColor: document.getElementById('inputSelectBackgroundTimerColor').value
            };
        }

        function applyInitialColors() {
            const colorValues = getColorValues();
            updateStyle(colorValues);
        }

        function addColorInputListeners() {
            const inputs = document.querySelectorAll('[id^="inputSelect"]');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    const colorValues = getColorValues();
                    updateStyle(colorValues);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            applyInitialColors();
            addColorInputListeners();
        });

        document.addEventListener('DOMContentLoaded', () => {
            const titleInput = document.querySelector('.timerTitle');
            const sizeTitleSelect = document.querySelector('.timerSizeTitle');
            const timerInput = document.querySelector('.timerTimer');
            const iframe = document.getElementById('pageCheckout');

            const formatTimeFromMinutes = (minutes) => {
                const hrs = Math.floor(minutes / 60);
                const mins = Math.floor(minutes % 60);
                const secs = Math.floor((minutes % 1) * 60);

                return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            };

            const updateTitleIframe = () => {
                if (iframe) {
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    if (iframeDoc) {
                        let titleElem = iframeDoc.querySelector('.timerTitle');
                        if (titleElem) {
                            titleElem.textContent = titleInput.value;
                        }
                    }
                }
            }
            const updateTimerIframe = () => {
                if (iframe) {
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    if (iframeDoc) {
                        let timerElem = iframeDoc.querySelector('.showtime');
                        if (timerElem) {
                            const minutes = parseFloat(timerInput.value);
                            if (!isNaN(minutes)) {
                                timerElem.textContent = formatTimeFromMinutes(minutes);
                            } else {
                                timerElem.textContent = '00:00:00';
                            }
                        }
                    }
                }
            }

            iframe.addEventListener('load', () => {
                if (titleInput.value.trim()) updateTitleIframe();
                if (timerInput.value.trim()) updateTimerIframe();
            });

            titleInput.addEventListener('input', updateTitleIframe);
            timerInput.addEventListener('input', updateTimerIframe);
        });

        window.addEventListener('load', applyInitialColors);
        window.addEventListener('load', handleAddCustomField);
        window.addEventListener('load', handleSetCupom);
        window.addEventListener('load', handleSetHorizontalBanner);
        window.addEventListener('load', handleSetVerticalBanner);
        window.addEventListener('load', handleSetTimer);
        window.addEventListener('load', toggleSealsVisibility);
    </script>

    <script>
        const iframeElement = document.getElementById('pageCheckout');

        iframeElement.addEventListener('load', function() {
            const iframeDoc = iframeElement.contentDocument || iframeElement.contentWindow.document;
            const paymentMethodInputs = iframeDoc.querySelectorAll('input[name="selectPaymentMethod"]');

            if (paymentMethodInputs.length > 0) {
                paymentMethodInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        resizeIframeToContentSize(iframeElement);
                    });
                });
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const iframe = document.getElementById('pageCheckout');

            if (!iframe) {
                console.error("O iframe com ID 'pageCheckout' não foi encontrado.");
                return;
            }

            iframe.addEventListener("load", function() {
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

                if (!iframeDoc) {
                    console.error("O documento do iframe não pôde ser acessado.");
                    return;
                }

                const listContainer = iframeDoc.getElementById("list");

                if (!listContainer) {
                    console.error("O elemento com ID 'list' não foi encontrado no iframe.");
                    return;
                }

                const addButton = iframeDoc.getElementById("add-item");

                if (!addButton) {
                    console.error("O botão com ID 'add-item' não foi encontrado.");
                    return;
                }

                let itemIndex = 0;

                addButton.addEventListener("click", function() {
                    const newItem = {
                        avatarSeed: "https://api.dicebear.com/9.x/bottts-neutral/svg?seed=" + Math.random().toString(36).substring(7),
                        description: "Nova descrição aqui.",
                        name: "Novo Nome",
                        stars: 5,
                    };
                    addListItem(newItem, itemIndex);
                    resizeIframeToContentSize(iframe);
                    itemIndex++;
                });

                const testimonials = JSON.parse(@json($checkout->settings['testimonials'] ?? [])).map(function(item) {
                    return {
                        avatarSeed: item.img,
                        description: item.description,
                        name: item.name,
                        stars: item.starsCount,
                    }
                });

                testimonials.forEach(function(item, index) {
                    addListItem(item, index);
                });

                function addListItem(item, index) {
                    const li = document.createElement("li");

                    li.id = `item-${index}`;
                    li.classList.add("py-2", "md:py-8", "first:pt-0", "last:pb-0", "group", "flex", "items-center");
                    li.innerHTML = `
                        <div class="flex gap-4 items-start">
                            <figure class="relative h-16 w-16 overflow-hidden rounded-full bg-neutral-200">
                                <img
                                    class="absolute h-full w-full object-cover"
                                    src="${item.avatarSeed}"
                                    alt="Avatar"
                                >
                            </figure>
                            <div class="flex-1">
                                <div class="mb-2">
                                    <p>${item.description}</p>
                                </div>

                                <h4 class="font-semibold">${item.name}</h4>
                                <ul class="flex items-center gap-px">
                                    ${generateStars(item.stars)}
                                </ul>
                            </div>
                        </div>
                        <div class="hidden ml-auto gap-2 group-hover:flex">
                            <button class="edit-item bg-blue-500 text-white px-2 py-1 rounded" data-id="item-${index}">Editar</button>
                            <button class="remove-item bg-red-500 text-white px-2 py-1 rounded" data-id="item-${index}">Remover</button>
                        </div>
                    `;

                    listContainer.appendChild(li);

                    li.querySelector(".edit-item").addEventListener("click", function() {
                        editListItem(li, item);
                    });

                    li.querySelector(".remove-item").addEventListener("click", function() {
                        removeListItem(li);
                    });
                }

                function generateStars(starsCount) {
                    const totalStars = 5;
                    let starsHTML = "";

                    for (let i = 0; i < starsCount; i++) {
                        starsHTML += `<li class="text-warning-400 star-full">★</li>`;
                    }

                    for (let i = starsCount; i < totalStars; i++) {
                        starsHTML += `<li class="text-neutral-400">☆</li>`;
                    }

                    return starsHTML;
                }

                function editListItem(listItem, item) {
                    const options = {
                        placement: 'center',
                        backdrop: 'dynamic',
                        closable: true,
                    };

                    const $modal = document.getElementById("editModal");
                    const nameInput = document.getElementById("editName");
                    const descriptionInput = document.getElementById("editDescription");
                    const starsInput = document.getElementById("editStars");
                    const avatarInput = document.getElementById("media[avatar]");
                    const saveButton = document.getElementById("saveEdit");
                    const fileList = document.querySelector("#editModal .fileListContent .fileList");
                    const avatarPreview = document.getElementById("avatarPreview");

                    nameInput.value = item.name;
                    descriptionInput.value = item.description;
                    starsInput.value = item.stars;

                    const imgElement = avatarPreview.querySelector("img");
                    imgElement.src = item.avatarSeed;

                    const modal = new Modal($modal, options);
                    modal.show();

                    fileList.replaceChildren();
                    $(fileList).hide();

                    const saveHandler = () => {
                        item.name = nameInput.value.trim();
                        item.description = descriptionInput.value.trim();
                        item.stars = Math.max(0, Math.min(5, parseInt(starsInput.value.trim(), 10) || 0));

                        // Processa o upload de imagem
                        if (avatarInput.files.length > 0) {
                            const file = avatarInput.files[0];
                            const reader = new FileReader();

                            reader.onload = (event) => {
                                const avatarUrl = event.target.result;
                                item.avatarSeed = null;

                                // Atualiza o avatar no DOM
                                const avatarImg = listItem.querySelector("figure img");

                                $.ajax({
                                    headers: {
                                        'x-csrf-token': '{{ csrf_token() }}'
                                    },
                                    method: 'POST',
                                    url: '{{ route('api.public.upload-imagem') }}',
                                    data: {
                                        base64: avatarUrl
                                    },
                                    success: function(data, textStatus, xhr) {
                                        avatarImg.src = data.url;
                                    },
                                    error: function(data, textStatus, xhr) {
                                        console.error(data);
                                    }
                                })
                            };

                            reader.readAsDataURL(file);
                        }

                        listItem.querySelector("h4").textContent = item.name;
                        listItem.querySelector("p").textContent = item.description;
                        listItem.querySelector("ul").innerHTML = generateStars(item.stars);

                        modal.hide();
                        fileList.replaceChildren();
                        $(fileList).hide();
                        saveButton.removeEventListener("click", saveHandler);
                    };

                    saveButton.addEventListener("click", saveHandler);

                    $modal.querySelector('[data-modal-hide="editModal"]').addEventListener("click", () => {
                        modal.hide();
                        fileList.replaceChildren();
                        $(fileList).hide();

                        saveButton.removeEventListener("click", saveHandler);
                    });
                }

                function removeListItem(listItem) {
                    if (confirm("Tem certeza que deseja remover este item?")) {
                        listItem.remove();
                        resizeIframeToContentSize(iframe);
                    }
                }
            });
        });

        function submitForm() {

            // 
            const iframe = document.getElementById('pageCheckout');
            if (!iframe) {
                notyf.error("O iframe com ID 'pageCheckout' não foi encontrado.");
                return;
            }

            // 
            const activeCustomField = document.getElementById('addCustomFieldInput');
            if (activeCustomField.checked) {
                const inputName = document.getElementById('newNameCustomField');
                const inputType = document.getElementById('newTypeCustomField');

                if (inputName.value.trim() === '') {
                    $('#inputNameMsgError').text('Campo obrigatório');
                }

                if (inputType.value.trim() === '') {
                    $('#inputTypeMsgError').text('Campo obrigatório');
                }

                if (inputName.value.trim() === '' && inputType.value.trim() === '') {
                    notyf.error('Preecha corretamente o campo personalizado');
                    return;
                }
            }

            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const listContainer = iframeDoc.getElementById("list");

            let depoimentoLista = [];

            listContainer.querySelectorAll(':scope > li').forEach(function(item) {
                depoimentoLista.push({
                    name: item.querySelector('h4').innerText,
                    description: item.querySelector('p').innerText,
                    img: item.querySelector('img').src,
                    starsCount: item.querySelectorAll('.star-full').length
                })
            })

            document.querySelector('input[name="settings[testimonials]"]').value = JSON.stringify(depoimentoLista);
            document.getElementById('formCheckout').submit();
        }
    </script>
@endsection

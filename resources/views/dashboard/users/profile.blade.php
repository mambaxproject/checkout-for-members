@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>Meus dados</h1>

        <form
            class="space-y-6 md:space-y-8"
            action="{{ route('dashboard.users.update') }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            @component('components.card', ['custom' => 'p-6 md:p-8'])
                <div class="space-y-6">
                    <h3>Dados do usuários</h3>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <label for="user[name]">Nome</label>
                            <input
                                type="text"
                                class="{{ $errors->has('user.name') ? ' is-invalid' : '' }} cursor-not-allowed"
                                id="user[name]"
                                name="user[name]"
                                value="{{ old('user.name', user()->name ?? "") }}"
                                placeholder="Digite seu nome"
                                disabled
                            />
                            @error('user.name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-12">
                            <label for="user[name]">E-mail</label>
                            <input
                                type="email"
                                class="{{ $errors->has('user.email') ? ' is-invalid' : '' }} cursor-not-allowed"
                                id="user[email]"
                                name="user[email]"
                                value="{{ old('user.email', user()->email ?? "") }}"
                                placeholder="email@dominio.com"
                                disabled
                            />
                            @error('user.email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-12 lg:col-span-6">
                            <label for="user[phone_number]">WhatsApp</label>
                            <input
                                type="text"
                                class="{{ $errors->has('user.phone_number') ? ' is-invalid' : '' }} cursor-not-allowed"
                                id="user[phone_number]"
                                name="user[phone_number]"
                                value="{{ old('user.phone_number', user()->phone_number ?? "") }}"
                                placeholder="(00) 0 0000-0000"
                                oninput="setInputMask(this, '(99) 99999-9999')"
                                disabled
                            />
                            @error('user.phone_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-12 lg:col-span-6">
                            <label for="user[document_number]">CPF/CNPJ</label>
                            <input
                                type="text"
                                class="{{ $errors->has('user.document_number') ? ' is-invalid' : '' }} cursor-not-allowed"
                                id="user[document_number]"
                                name="user[document_number]"
                                value="{{ old('user.document_number', user()->document_number ?? "") }}"
                                placeholder="000.000.000-00"
                                oninput="setInputMask(this, '999.999.999-99')"
                                onblur="validateCPF(this)"
                                disabled
                            />
                            @error('user.document_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            @endcomponent

            @component('components.card', ['custom' => 'p-6 md:p-8'])
                <div class="space-y-6">
                    <h3>Dados da loja</h3>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <label for="shop[name]">Nome da loja</label>
                            <input
                                type="text"
                                class="{{ $errors->has('shop.name') ? ' is-invalid' : '' }}"
                                name="shop[name]"
                                id="shop[name]"
                                value="{{ old('shop.name', user()->shop()->name ?? "") }}"
                                placeholder="Digite nome da loja"
                                required
                            />
                            @error('shop.name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-12">
                            <label for="shop[link]">URL da loja</label>
                            <input
                                type="url"
                                class="{{ $errors->has('shop.link') ? ' is-invalid' : '' }}"
                                name="shop[link]"
                                id="shop[link]"
                                value="{{ old('shop.link', user()->shop()->link ?? "") }}"
                                placeholder="Ex: nomedaloja.com.br"
                            />
                            @error('shop.link')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            @endcomponent

            <button
                class="button button-primary mx-auto mt-6 h-12 rounded-full px-12"
                type="submit"
            >
                Atualizar dados
            </button>
        </form>

    </div>
@endsection

@section('script')
    <script src="{{ asset('js/dashboard/validation/pattern.js') }}"></script>
    <script src="{{ asset('js/dashboard/autoComplete/autoCompleteInputsFromZipcode.js') }}"></script>
    <script src="{{ asset('js/dashboard/validation/validateDocumentNumber.js') }}"></script>
@endsection
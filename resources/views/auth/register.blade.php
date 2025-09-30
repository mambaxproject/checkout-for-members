@extends('layouts.checkout')

@section('content')
    <div class="mx-auto max-w-4xl">
        <div class="rounded-3xl bg-neutral-100 p-4 md:p-8">

            <div class="space-y-4 md:space-y-6">

                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-4 md:gap-6">

                        <div class="flex-1">

                            <h3 class="text-center">
                                Faça seu cadastro
                            </h3>

                        </div>

                    </div>

                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    @component('components.card', ['custom' => 'p-6 md:p-8 flex-1'])
                        <div class="space-y-6">

                            <h3>Dados pessoais</h3>

                            <div class="grid grid-cols-12 gap-6">

                                <div class="col-span-12">
                                    <label for="name">Nome completo</label>
                                    <input
                                            type="text"
                                            id="name"
                                            name="name"
                                            value="{{ old('name') }}"
                                            class="{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            placeholder="Digite seu nome completo"
                                            required
                                    />
                                    @error('name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-span-12">
                                    <label for="email">Seu e-mail</label>
                                    <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            class="{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            placeholder="seuemail@dominio.com.br"
                                            required
                                    />
                                    @error('email')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label for="document_number">CPF</label>
                                    <input
                                            type="text"
                                            id="document_number"
                                            name="document_number"
                                            value="{{ old('document_number') }}"
                                            class="{{ $errors->has('document_number') ? ' is-invalid' : '' }}"
                                            inputmode="numeric"
                                            placeholder="000.000.000-00"
                                            oninput="setCpfCnpjMask(this)"
                                            required
                                    />
                                    @error('document_number')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label for="phone_number">Telefone</label>
                                    <input
                                            type="text"
                                            id="phone_number"
                                            name="phone_number"
                                            value="{{ old('phone_number') }}"
                                            class="{{ $errors->has('phone_number') ? ' is-invalid' : '' }}"
                                            inputmode="numeric"
                                            placeholder="(99) 99999-9999"
                                            oninput="setInputMask(this, '(99) 99999-9999')"
                                            required
                                    />
                                    @error('phone_number')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label for="password">Senha</label>
                                    <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            value="{{ old('password') }}"
                                            class="{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            placeholder="Digite sua senha"
                                            required
                                    />
                                    @error('password')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label for="password_confirmation">Confirme sua senha</label>
                                    <input
                                            type="password"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            value="{{ old('password_confirmation') }}"
                                            class="{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                            placeholder="Confirme sua senha"
                                            required
                                    />
                                    @error('password_confirmation')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <button
                                    type="submit"
                                    class="button h-10 w-full rounded-full md:h-12 focus:outline-none text-white bg-green-700 hover:bg-green-800"
                            >
                                Salvar
                            </button>
                        </div>
                    @endcomponent
                </form>

                <div class="flex flex-col gap-6">
                    <div class="flex items center gap-4 md:gap-6">
                        <div class="flex-1">
                            <h4 class="text-center">
                                <a href="{{ route('login') }}" class="hover:underline">
                                    Já possui cadastro? Faça seu login
                                </a>
                            </h4>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection

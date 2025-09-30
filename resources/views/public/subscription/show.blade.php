<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Assinatura #{{ $order->client_orders_uuid }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Dados da assinatura -->
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold text-gray-800 mb-4">
                        Dados da assinatura - #{{ $order->client_orders_uuid }}
                    </h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="border rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1">Valor total</div>
                            <div class="text-2xl font-bold text-black-50">
                                {{ $order->brazilianPrice }}
                            </div>
                        </div>
                        <div class="border rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1">Data da assinatura</div>
                            <div class="text-2xl font-bold text-black-50">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-2">Descrição</div>
                        <div class="text-gray-800">
                            Pagamento da assinatura do produto {{ $order->item->product->parentProduct->name }} na loja <b>{{ $order->shop->name }}</b>.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados do comprador -->
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Dados do comprador</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Nome</div>
                            <div class="text-gray-800 font-medium">
                                {{ $order->user->name }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Email</div>
                            <div class="text-gray-800 font-medium">
                                {{ $order->user->email }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forma de pagamento -->
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <form method="POST"
                      action="{{ route('public.subscription.updateCard', $order->id) }}"
                      onsubmit="return confirm('Tem certeza?')"
                >
                    @csrf

                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">
                            Selecione a forma de pagamento
                        </h2>

                        <button class="bg-[#34c833] text-white px-3 py-2 rounded-lg font-medium mb-6 flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                            </svg>
                            Cartão de Crédito
                        </button>

                        <div class="mb-6">
                            <h3 class="text-gray-800 font-medium mb-4">
                                Informe os dados do cartão para efetuar o pagamento
                            </h3>

                            <div class="space-y-4">
                                <!-- Número do cartão -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="card[number]">
                                        Número do cartão
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                               id="card[number]"
                                               name="card[number]"
                                               value="{{ old('card.number') }}"
                                               placeholder="Informe o número do cartão"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               maxlength="19"
                                               onkeyup="this.value = this.value.replace(/[^0-9 ]/g, '').replace(/(\..*)\./g, '$1');"
                                               inputmode="numeric"
                                               required
                                        />
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>

                                        @error('card.number')
                                            <div class="text-red-500 text-sm mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nome do titular -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="card[cardHolderName]">
                                        Nome do titular do cartão
                                    </label>
                                    <input type="text"
                                           id="card[cardHolderName]"
                                           name="card[cardHolderName]"
                                           value="{{ old('card.cardHolderName') }}"
                                           placeholder="Informe o nome do titular"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           maxlength="255"
                                           required
                                    />
                                    @error('card.cardHolderName')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Validade -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Válido até:</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1" for="card[expirationMonth]">
                                                Mês
                                            </label>
                                            <select id="card[expirationMonth]"
                                                    name="card[expirationMonth]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    required
                                            >
                                                <option value="" disabled selected>Selecione o mês</option>
                                                @foreach(range(1, 12) as $month)
                                                    <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                                                            @selected(old('card.expirationMonth') == str_pad($month, 2, '0', STR_PAD_LEFT))
                                                    >
                                                        {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('card.expirationMonth')
                                                <div class="text-red-500 text-sm mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1" for="card[expirationYear]">
                                                Ano
                                            </label>
                                            <select id="card[expirationYear]"
                                                    name="card[expirationYear]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    required
                                            >
                                                <option value="" disabled selected>Selecione o ano</option>
                                                @foreach(range(date('Y'), date('Y') + 10) as $year)
                                                    <option value="{{ $year }}"
                                                            @selected(old('card.expirationYear') == $year)
                                                    >
                                                        {{ $year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('card.expirationYear')
                                                <div class="text-red-500 text-sm mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Código de segurança -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="card[cvv]">
                                        Código de segurança (CVV)
                                    </label>
                                    <input  type="text"
                                            id="card[cvv]"
                                            name="card[cvv]"
                                            value="{{ old('card.cvv') }}"
                                            placeholder="Informe o código de segurança"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            maxlength="4"
                                            inputmode="numeric"
                                            pattern="[0-9]*"
                                            required
                                    />
                                    @error('card.cvv')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                class="bg-[#34c833] text-white px-6 py-3 text-xl rounded-lg font-medium transition-colors w-full"
                        >
                            Confirmar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Rodapé -->
            <div class="text-center text-sm text-gray-600 italic">
                Esta cobrança é de responsabilidade única e exclusiva de <b>{{ $order->shop->name }}</b>.
                A SuitPay não se responsabiliza pelo produto ou serviço prestado.
                Em caso de dúvida, entre em contato com seu fornecedor.
            </div>
        </div>

        <script>
            document.getElementById('card[cardHolderName]').addEventListener('input', function() {
                const value = this.value.trim();
                const words = value.split(/\s+/);
                if (words.length < 2) {
                    this.setCustomValidity('Digite o nome completo do titular do cartão.');
                } else {
                    this.setCustomValidity('');
                }
            });

            document.getElementById('card[number]').addEventListener('input', function() {
                const cardNumber = this.value.replace(/\s/g, '');

                if (cardNumber.length < 13 || cardNumber.length > 19) {
                    this.setCustomValidity('O número do cartão deve ter entre 13 e 19 dígitos.');
                } else {
                    this.setCustomValidity('');
                }
            });

            function validateExpirationDate() {
                const month = document.getElementById('card[expirationMonth]').value;
                const year  = document.getElementById('card[expirationYear]').value;

                if (month && year) {
                    const currentDate    = new Date();
                    const expirationDate = new Date(year, month - 1); // month is 0-indexed

                    if (expirationDate < currentDate) {
                        document.getElementById('card[expirationMonth]').setCustomValidity('A data de validade não pode ser no passado.');
                        document.getElementById('card[expirationYear]').setCustomValidity('A data de validade não pode ser no passado.');
                    } else {
                        document.getElementById('card[expirationMonth]').setCustomValidity('');
                        document.getElementById('card[expirationYear]').setCustomValidity('');
                    }
                }
            }

            document.getElementById('card[expirationMonth]').addEventListener('change', validateExpirationDate);
            document.getElementById('card[expirationYear]').addEventListener('change', validateExpirationDate);
        </script>
    </body>
</html>
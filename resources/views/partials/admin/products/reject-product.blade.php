@component('components.admin.ui.drawer', [
    'id' => 'drawerRejectProduct',
    'drawerTitle' => 'Reprovar produto',
    'btnCustom' => 'ti-btn-danger',
    'btnTitle' => 'Reprovar produto',
    'btnIcon' => 'x',
])
    <form
        action="{{route('admin.products.updateSituation', $product)}}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <input type="hidden" name="situation" value="{{ \App\Enums\SituationProductEnum::REPROVED->name }}" />

        <div class="relative space-y-4 p-4 md:p-6">

            <div class="">
                <label
                    class="form-label"
                    for=""
                >
                    Porque foi reprovado?
                </label>
                <select class="form-control" name="product[attributes][rejectReasons]" required>
                    <option value="">Selecionar uma opção</option>
                    @foreach(config('products.rejectReasons') as $key => $reason)
                        <option value="{{ $reason['value'] }}">
                            {{ $reason['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="relative">

                <label
                    class="form-label"
                    for=""
                >
                    Sugestões de porque foi reprovada?
                </label>
                <textarea
                    class="form-control"
                    id="meuTextarea"
                    rows="10"
                    name="product[attributes][rejectDescription]"
                    placeholder="Digite '/' para ver opções predefinidas"
                ></textarea>

                <div
                    class="autocomplete-list bg-slate-50 [&>div:hover]:bg-slate-200 absolute bottom-0 w-full divide-y border shadow-md [&>div]:cursor-pointer [&>div]:p-3 [&>div]:transition-all [&>div]:duration-300 [&>div]:ease-in-out"
                    id="autocomplete"
                >
                </div>

            </div>

            <div class="flex justify-end">
                <button
                    class="ti-btn ti-btn-danger"
                    type="submit"
                    onclick="return confirm('Tem certeza?')"
                >
                    Reprovar produto
                </button>
            </div>


        </div>
    </form>
@endcomponent

@push('scripts')
    <script>
        const textosPredefinidos = ['Texto 1: Descrição rápida', 'Texto 2: Exemplo de uso', 'Texto 3: Informação adicional'];
        const textarea = document.getElementById('meuTextarea');
        const autocomplete = document.getElementById('autocomplete');

        // Função para mostrar a lista de opções
        function showAutocomplete(options) {
            autocomplete.innerHTML = ''; // Limpa as opções anteriores
            options.forEach(option => {
                const div = document.createElement('div');
                div.className = 'autocomplete-item';
                div.textContent = option;
                div.addEventListener('click', () => {
                    insertTextAtCaret(textarea, option); // Insere o texto ao clicar
                    autocomplete.style.display = 'none'; // Esconde a lista de opções
                });
                autocomplete.appendChild(div);
            });
            autocomplete.style.display = 'block';
        }

        // Função para inserir o texto no local do cursor, removendo o '/' anterior
        function insertTextAtCaret(textarea, text) {
            const start = textarea.selectionStart - 1; // Move uma posição para trás para remover o '/'
            const end = textarea.selectionEnd;

            if (textarea.value[start] === '/') {
                textarea.value = textarea.value.substring(0, start) + text + textarea.value.substring(end);
                textarea.selectionStart = textarea.selectionEnd = start + text.length; // Move o cursor para o final do texto inserido
            } else {
                textarea.value = textarea.value.substring(0, start + 1) + text + textarea.value.substring(end);
                textarea.selectionStart = textarea.selectionEnd = start + 1 + text.length;
            }
        }

        // Escuta a digitação no textarea
        textarea.addEventListener('keyup', (e) => {
            const cursorPosition = textarea.selectionStart;
            const text = textarea.value.substring(0, cursorPosition);

            if (text.endsWith('/')) {
                showAutocomplete(textosPredefinidos); // Mostra as opções ao digitar '/'
            } else {
                autocomplete.style.display = 'none'; // Esconde a lista se a barra não estiver presente
            }
        });

        // Fecha o autocomplete se clicar fora
        document.addEventListener('click', (e) => {
            if (!autocomplete.contains(e.target) && e.target !== textarea) {
                autocomplete.style.display = 'none';
            }
        });
    </script>
@endpush

<div class="tab-content hidden" id="tab-links" data-tab="tab-links">
    @component('components.card', ['custom' => 'p-6 md:p-8'])
        <div class="space-y-8">
            <div class="space-y-4 md:space-y-6">
                <h3>Link do curso</h3>
                <div class="flex items-center gap-2">
                    <span class="copyClipboard group relative flex w-fit cursor-pointer items-center gap-2"
                        data-clipboard-text="{{ $linkCourse }}">
                        @include('components.icon', [
                            'icon' => 'content_copy',
                            'custom' => 'text-xl text-gray-400',
                        ])
                        {{ $linkCourse }}
                        <span
                            class="absolute -right-16 hidden rounded-md bg-gray-200 px-2 py-1 text-xs font-semibold group-hover:block">Copiar</span>
                    </span>
                </div>
            </div>
        @endcomponent
        <script>
            document.addEventListener("click", function(event) {
                const button = event.target.closest(".copyClipboard");
                if (!button) return;

                const clipboardText = button.getAttribute("data-clipboard-text");

                if (!navigator.clipboard) {
                    notyf.error("Seu navegador não suporta copiar para a área de transferência!");
                    return;
                }

                if (!clipboardText) {
                    notyf.error("Nenhum texto encontrado para copiar!");
                    return;
                }

                navigator.clipboard.writeText(clipboardText)
                    .then(() => notyf.success("Copiado com sucesso!"))
                    .catch(() => notyf.error("Erro ao tentar copiar!"));
            });
        </script>
    </div>
</div>
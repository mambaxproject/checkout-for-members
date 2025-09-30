document.addEventListener("click", function (event) {
    const trigger = event.target.closest(".copyClipboard");

    if (trigger) {
        event.preventDefault();

        const clipboardText = trigger.getAttribute("data-clipboard-text");

        if (!navigator.clipboard) {
            notyf.error("Seu navegador não suporta a função de copiar para a área de transferência!");
            return;
        }

        if (!clipboardText) {
            notyf.error("Nenhum texto encontrado para copiar!");
            return;
        }

        navigator.clipboard
            .writeText(clipboardText)
            .then(() => {
                notyf.success("Copiado com sucesso!");
            })
            .catch(() => {
                notyf.error("Erro ao tentar copiar!");
            });
    }
});

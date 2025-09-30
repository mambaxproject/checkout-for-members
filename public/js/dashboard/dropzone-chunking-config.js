Dropzone.autoDiscover = false;

document.addEventListener("DOMContentLoaded", () => {
    const dropzone = new Dropzone("#chunkUploadDropzone", {
        url: "/dashboard/file-upload-chunking",
        method: "POST",
        maxFiles: 1,
        maxFilesize: 200, // 200MB
        chunking: true,
        forceChunking: true,
        chunkSize: 2 * 1024 * 1024, // 2MB
        parallelChunkUploads: false,
        retryChunks: true,
        retryChunksLimit: 3,
        addRemoveLinks: false,
        acceptedFiles: "image/*, application/pdf",
        dictDefaultMessage: `
            <div class="w-full cursor-pointer text-center">
                <i class="material-symbols-rounded text-2xl text-primary" translate="no">add</i>
                <p class="px-8 text-sm">Arraste aqui um arquivo de imagem ou <span class="text-primary">Procure por um arquivo</span></p>
            </div>
        `,
        previewTemplate: `
            <div class="listItem w-full">
                <div class="relative flex items-center gap-4 overflow-hidden rounded-lg bg-neutral-700 px-6 py-4">
                    <span class="flex-1 text-sm text-white" data-dz-name></span>
                    <span class="rounded-md bg-neutral-600 px-3 py-2 text-xs font-semibold uppercase text-white md:mr-[20%]" data-dz-size></span>
                    <div class="flex items-center gap-1">
                        <button class="flex h-8 w-8 items-center justify-center rounded-md hover:bg-neutral-600" data-action="view" type="button">
                            <span class="material-symbols-rounded text-xl text-neutral-400 pointer-events-none">visibility</span>
                        </button>
                        <button class="flex h-8 w-8 items-center justify-center rounded-md hover:bg-neutral-600" data-action="remove" data-chunking-remove type="button">
                            <span class="material-symbols-rounded text-xl text-neutral-400 pointer-events-none">close</span>
                        </button>
                    </div>
                    <div class="absolute bottom-0 left-0 bg-primary" style="width:0%; height: 3px;" data-dz-uploadprogress></div>
                </div>
            </div>
        `,
        init: function () {
            this.on("addedfile", function (file) {
                const previewElement = file.previewElement;

                if (!previewElement) return;

                // valida tamanho do arquivo adicionado
                const maxBytes = this.options.maxFilesize * 1024 * 1024;
                if (file.size > maxBytes) {
                    notyf.error(`O arquivo é maior que o permitido (máx ${this.options.maxFilesize}MB)!`);
                    dropzone.removeFile(file);
                }

                // Botão de visualizar
                const viewButton = previewElement.querySelector('[data-action="view"]');
                if (viewButton) {
                    viewButton.addEventListener("click", function () {
                        // Se for imagem ou PDF, abrir em nova aba
                        if (file.type.startsWith("image/") || file.type === "application/pdf") {
                            const fileUrl = URL.createObjectURL(file);
                            window.open(fileUrl, "_blank");
                        } else {
                            alert("Visualização não suportada para este tipo de arquivo.");
                        }
                    });
                }

                // Botão de remover
                const removeButton = previewElement.querySelector('[data-action="remove"]');
                if (removeButton) {
                    removeButton.addEventListener("click", function () {
                        dropzone.removeFile(file);
                    });
                }
            });

            this.on("uploadprogress", function (file, progress) {
                const buttonSubmit = document.querySelector(".form-button-submit");

                // Desabilita o botão enquanto está enviando
                buttonSubmit.setAttribute("disabled", "disabled");

                // Quando chegar a 100%, reativa
                if (progress >= 100) {
                    buttonSubmit.removeAttribute("disabled");
                }
            });

            this.on("success", function (file, response) {
                console.log("Arquivo enviado com sucesso:", response);

                const inputAttachment = document.querySelector("input[name='media[attachmentFromChuncking]']");

                const existingValue = inputAttachment.value;
                inputAttachment.value = existingValue ? `${existingValue},${response.path}` : response.path;
            });

            this.on("removedfile", function (file) {
                console.log("Removendo arquivo:", file.upload.uuid);

                const inputAttachment = document.querySelector("input[name='media[attachmentFromChuncking]']");
                inputAttachment.value = "";
            });
        },
        sending: function (file, xhr, formData) {
            formData.append("_token", document.querySelector("input[name='_token']").value);
            formData.append("dzUuid", document.querySelector("input[name='dzUuid']").value);
        },
    });
});

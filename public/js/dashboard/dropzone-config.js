document.addEventListener("DOMContentLoaded", () => {
    const dropzones = document.querySelectorAll(".upload-full-zone");
    const maxTotalSize = 10 * 1024 * 1024; // 10 MB

    // Esconde todas as listas de arquivos inicialmente
    $(".fileList").hide();

    // Inicializa os IDs únicos para .fileListContent
    dropzones.forEach((dropzone, index) => {
        const fileListContent = dropzone.querySelector(".fileListContent");
        if (fileListContent) {
            fileListContent.id = `fileListContent-${index}`;
        }
    });

    dropzones.forEach((dropzone, index) => {
        const fileInput = dropzone.querySelector(".fileInput");
        const fileList = dropzone.querySelector(".fileList");

        if (!fileInput || !fileList) {
            console.error(`Elementos fileInput ou fileList não encontrados no dropzone ${index}.`);
            return;
        }

        dropzone.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropzone.classList.add("dragging");
        });

        dropzone.addEventListener("dragleave", () => {
            dropzone.classList.remove("dragging");
        });

        dropzone.addEventListener("drop", (e) => {
            e.preventDefault();
            dropzone.classList.remove("dragging");
            const files = e.dataTransfer.files;
            if (checkFileSize(files)) {
                displayFiles(files, fileList);
            }
        });

        fileInput.addEventListener("change", () => {
            const files = fileInput.files;
            if (checkFileSize(files)) {
                displayFiles(files, fileList);
            }
        });

        dropzone.addEventListener("click", (e) => {
            const action = e.target.closest("button")?.dataset.action;
            const filename = e.target.closest("button")?.dataset.filename;
            const listItem = e.target.closest("li");

            if (action === "view") {
                viewFile(filename, fileInput);
            } else if (action === "remove" && listItem) {
                removeFile(filename, fileInput, fileList, listItem);
            }
        });
    });

    function displayFiles(files, fileList) {
        // Mostra a lista correspondente
        $(fileList).show();

        fileList.innerHTML = "";
        Array.from(files).forEach((file) => {
            const fileType = file.type.split("/")[1].toUpperCase();
            const listItem = document.createElement("li");
            listItem.classList.add("listItem");
            listItem.innerHTML = `
                <div class="flex items-center gap-4 rounded-lg bg-neutral-700 px-6 py-4">

                    <span class="flex-1 text-sm text-white">${file.name}</span>
                    <span class="rounded-md bg-neutral-600 px-3 py-2 text-xs font-semibold uppercase text-white md:mr-[20%]">${fileType}</span>

                    <div class="flex items-center gap-1">
                        <button
                            class="flex h-8 w-8 items-center justify-center rounded-md hover:bg-neutral-600"
                            data-action="view"
                            data-filename="${file.name}"
                            type="button"
                        >
                            <span
                                class="material-symbols-rounded text-xl text-neutral-400 pointer-events-none"
                                translate="no"
                            >
                                visibility
                            </span>
                        </button>
                        <button
                            class="flex h-8 w-8 items-center justify-center rounded-md hover:bg-neutral-600"
                            data-action="remove"
                            data-filename="${file.name}"
                            type="button"
                        >
                            <span
                                class="material-symbols-rounded text-xl text-neutral-400 pointer-events-none"
                                translate="no"
                            >
                                close
                            </span>
                        </button>
                    </div>

                </div>
            `;
            fileList.appendChild(listItem);
        });
    }

    function checkFileSize(files) {
        const totalSize = Array.from(files).reduce((acc, file) => acc + file.size, 0);
        if (totalSize > maxTotalSize) {
            alert("O tamanho total dos arquivos excede o limite de 10 MB.");
            return false;
        }
        return true;
    }

    function viewFile(filename, fileInput) {
        const file = Array.from(fileInput.files).find((file) => file.name === filename);
        if (file) {
            const url = URL.createObjectURL(file);
            window.open(url);
            URL.revokeObjectURL(url);
        } else {
            console.error("Arquivo não encontrado:", filename);
        }
    }

    function removeFile(filename, fileInput, fileList, listItem) {
        const filteredFiles = Array.from(fileInput.files).filter((file) => file.name !== filename);
        const dataTransfer = new DataTransfer();

        filteredFiles.forEach((file) => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
        listItem.remove();

        if (filteredFiles.length === 0) {
            $(fileList).hide();
        }
    }
});

function limitCharger(input) {
    const value = parseInt(input.value, 10);

    if (value > 9) {
        input.value = 9;
    } else if (value < 0) {
        input.value = 0;
    }
}

function formatDateForInput(dateString) {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

document.body.style.overflow = "hidden";

window.addEventListener("load", function () {
    const loadingDiv = document.getElementById("loading");
    if (loadingDiv) {
        loadingDiv.style.display = "none";
        document.body.style.overflow = "visible";
    }
});

$(document).ready(function () {
    // Função para mostrar ou esconder o conteúdo
    function toggleContent($component) {
        var $checkbox = $component.find('> label > input[type="checkbox"]');
        var $toggleContent = $component.find("> .toggleContent");

        if ($checkbox.is(":checked")) {
            $toggleContent.show();
        } else {
            $toggleContent.hide();
        }
    }

    // Iterar sobre cada componente de toggle
    $('[id^="toggle-component-"]').each(function () {
        var $component = $(this);

        // Verificar o estado do checkbox ao carregar a página
        toggleContent($component);

        // Adicionar evento de mudança ao checkbox
        $component.find('> label > input[type="checkbox"]').change(function () {
            toggleContent($component);
        });
    });
});

$(document).ready(function () {
    function toggleBodyOverflow() {
        if ($("div[drawer-backdrop]").is(":visible")) {
            document.documentElement.style.overflow = "hidden";
        } else {
            document.documentElement.style.overflow = "";
        }
    }

    toggleBodyOverflow();

    // Observa mudanças na DOM para detectar o aparecimento do drawer-backdrop
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            toggleBodyOverflow();
        });
    });

    // Configura o observador para o body
    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });

    // Também monitora alterações de estilo, caso o backdrop seja ativado/desativado via CSS
    $("div[drawer-backdrop]").on("transitionend", function () {
        toggleBodyOverflow();
    });
});

document.addEventListener("wheel", function (event) {
    if (document.activeElement.type === "number" && document.activeElement.classList.contains("noScrollInput")) {
        document.activeElement.blur();
    }
});

// Realiza limpeza dos inputs em Drawer
document.addEventListener("DOMContentLoaded", () => {
    const drawers = document.querySelectorAll(".drawer");
    const backdrop = document.querySelector("[drawer-backdrop]"); // Seleciona o backdrop globalmente

    const clearFormInputs = (drawer) => {
        if (drawer.classList.contains("persist-inputs")) return; // Ignora drawers com a classe .filter

        const formInputs = drawer.querySelectorAll("input, select, textarea");
        formInputs.forEach((input) => {
            if (input.type === "hidden") return; // Ignora campos do tipo hidden

            switch (input.type) {
                case "number":
                    input.value = input.min || 0; // Define para o valor mínimo ou vazio se não houver
                    break;
                case "checkbox":
                    break;
                case "radio":
                    input.checked = false;
                    break;
                case "select-one":
                case "select-multiple":
                    const firstOption = Array.from(input.options).find((option) => !option.disabled);
                    if (firstOption) {
                        input.value = firstOption.value;
                    }
                    break;
                default:
                    input.value = ""; // Limpa o valor de outros tipos de input
                    break;
            }
        });
    };

    // Adiciona evento para o botão de fechar
    drawers.forEach((drawer) => {
        const closeButton = drawer.querySelector(".closeButton");
        if (closeButton) {
            closeButton.addEventListener("click", () => {
                clearFormInputs(drawer);
            });
        }
    });

    // Observa mudanças no atributo 'aria-hidden'
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === "attributes" && mutation.attributeName === "aria-hidden") {
                const drawer = mutation.target;
                const isHidden = drawer.getAttribute("aria-hidden") === "true";

                if (isHidden) {
                    clearFormInputs(drawer); // Limpa os inputs quando o drawer é escondido
                }
            }
        });
    });

    drawers.forEach((drawer) => {
        observer.observe(drawer, {
            attributes: true,
        });
    });

    // Adiciona evento ao backdrop
    if (backdrop) {
        backdrop.addEventListener("click", () => {
            drawers.forEach((drawer) => {
                const isHidden = drawer.getAttribute("aria-hidden") === "false";
                if (isHidden) {
                    clearFormInputs(drawer);
                }
            });
        });
    }
});

// Adiciona campo obrigatorio
document.addEventListener("DOMContentLoaded", () => {
    // Seleciona todos os inputs, textareas e selects com o atributo 'required', exceto inputs do tipo 'file'
    const requiredElements = document.querySelectorAll("input[required]:not([type='file']), textarea[required], select[required]");

    requiredElements.forEach((element) => {
        // Encontra o ancestral mais próximo que tenha uma classe começando com "col-span-"
        const container = element.closest('div[class^="col-span-"]');

        // Se o container for encontrado, procura pela label dentro dele
        if (container) {
            let label = container.querySelector("label");

            // Adiciona " (Obrigatório *)" se a label existir e ainda não tiver o texto
            if (label && !label.textContent.includes("Obrigatório *")) {
                label.textContent += " (Obrigatório *)";
            }
        }
    });
});

// Tooltip
$(document).ready(function () {
    $("[data-tooltip-text]").on("mouseenter", function () {
        const $el = $(this);
        const text = $el.data("tooltip-text");
        const position = $el.data("tooltip-position") || "auto";

        const $tooltip = $(
            `<div class="absolute bg-neutral-800 text-white text-xs px-2 py-1 rounded shadow-md opacity-0 transition-opacity duration-200">
                ${text}
            </div>`,
        ).appendTo("body");

        const offset = $el.offset();
        const width = $el.outerWidth();
        const height = $el.outerHeight();
        const tooltipWidth = $tooltip.outerWidth();
        const tooltipHeight = $tooltip.outerHeight();

        let top, left;
        switch (position) {
            case "top":
                top = offset.top - tooltipHeight - 8;
                left = offset.left + width / 2 - tooltipWidth / 2;
                break;
            case "bottom":
                top = offset.top + height + 8;
                left = offset.left + width / 2 - tooltipWidth / 2;
                break;
            case "left":
                top = offset.top + height / 2 - tooltipHeight / 2;
                left = offset.left - tooltipWidth - 8;
                break;
            case "right":
                top = offset.top + height / 2 - tooltipHeight / 2;
                left = offset.left + width + 8;
                break;
            default:
                top = offset.top - tooltipHeight - 8;
                left = offset.left + width / 2 - tooltipWidth / 2;
        }

        $tooltip.css({ top, left }).removeClass("opacity-0");

        $el.on("mouseleave", function () {
            $tooltip.remove();
        });
    });
});

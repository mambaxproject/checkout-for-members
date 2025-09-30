function setCurrencyMask(input) {
    input.addEventListener("input", function () {
        maskBrlCurrency(input);
    });

    // Formatação inicial para quando o input estiver vazio
    input.addEventListener("focus", function () {
        if (input.value === "") {
            input.value = "0,00";
        }
    });

    // Remove o valor se o input estiver vazio ao perder o foco
    input.addEventListener("blur", function () {
        if (input.value === "0,00") {
            input.value = "";
        }
    });
}

function maskBrlCurrency(input) {
    let value = input.value.replace(/\D/g, "");

    if (value) {
        value = (parseFloat(value) / 100).toFixed(2);
        value = value.replace(".", ",");
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    } else {
        input.value = "";
    }
}

function formatCurrencyBR(value) {
    return parseFloat(value)
        .toFixed(2)
        .replace(".", ",")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

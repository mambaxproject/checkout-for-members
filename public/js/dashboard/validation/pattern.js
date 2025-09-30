function setInputMask(input, pattern) {
    input.addEventListener("input", function () {
        let value = input.value.replace(/[^a-zA-Z0-9]/g, ""); // Remove todos os caracteres não alfanuméricos
        let maskedValue = "";
        let patternIndex = 0;

        for (let i = 0; i < value.length && patternIndex < pattern.length; i++) {
            if (pattern[patternIndex] === "9") {
                // Garante que o valor seja numérico
                if (/\d/.test(value[i])) {
                    maskedValue += value[i];
                    patternIndex++;
                }
            } else if (pattern[patternIndex] === "A") {
                // Garante que o valor seja uma letra maiúscula
                if (/[A-Z]/i.test(value[i])) {
                    maskedValue += value[i].toUpperCase();
                    patternIndex++;
                }
            } else if (pattern[patternIndex] === "a") {
                // Garante que o valor seja uma letra minúscula
                if (/[a-z]/i.test(value[i])) {
                    maskedValue += value[i].toLowerCase();
                    patternIndex++;
                }
            } else {
                // Adiciona os caracteres fixos do padrão, como traços ou espaços
                maskedValue += pattern[patternIndex++];
                i--;
            }
        }

        input.value = maskedValue;
    });
}

function setPercentageMask(input) {
    let value = input.value.replace(/[^0-9.]/g, ""); // Remove caracteres inválidos
    let [integerPart, decimalPart] = value.split(".");

    // Limita a parte inteira para valores até 100
    integerPart = integerPart ? Math.min(100, parseInt(integerPart, 10)).toString() : "";

    if (decimalPart !== undefined) {
        decimalPart = decimalPart.substring(0, 2); // Limita a duas casas decimais
        value = `${integerPart}.${decimalPart}`;
    } else {
        value = integerPart;
    }

    // Não adiciona ".00" se o valor for exatamente 100
    if (value === "100" || value === "100.") value = "100";

    input.value = value;
}

function setCpfCnpjMask(input) {
    input.addEventListener("input", function () {
        let value = input.value.replace(/\D/g, ""); // Remove todos os caracteres não numéricos
        let maskedValue = "";
        let pattern;

        // Define o padrão baseado na quantidade de dígitos
        if (value.length <= 11) {
            // Padrão para CPF: 000.000.000-00
            pattern = "999.999.999-99";
        } else {
            // Padrão para CNPJ: 00.000.000/0000-00
            pattern = "99.999.999/9999-99";
        }

        let patternIndex = 0;
        for (let i = 0; i < value.length && patternIndex < pattern.length; i++) {
            if (pattern[patternIndex] === "9") {
                maskedValue += value[i];
                patternIndex++;
            } else {
                maskedValue += pattern[patternIndex++];
                i--;
            }
        }

        input.value = maskedValue;
    });
}

function validateCPFAndCNPJ(val) {
    if (val.length <= 14) {
        $('#cnpj-validation-info').addClass('hidden')

        var cpf = val.trim();

        cpf = cpf.replace(/\./g, '');
        cpf = cpf.replace('-', '');
        cpf = cpf.split('');

        var v1 = 0;
        var v2 = 0;
        var aux = false;

        for (var i = 1; cpf.length > i; i++) {
            if (cpf[i - 1] != cpf[i]) {
                aux = true;
            }
        }

        if (aux == false) {
            $('#cpf-validation-info').removeClass('hidden')
            return false;
        }

        for (var i = 0, p = 10; (cpf.length - 2) > i; i++, p--) {
            v1 += cpf[i] * p;
        }

        v1 = ((v1 * 10) % 11);

        if (v1 == 10) {
            v1 = 0;
        }

        if (v1 != cpf[9]) {
            $('#cpf-validation-info').removeClass('hidden')
            return false;
        }

        for (var i = 0, p = 11; (cpf.length - 1) > i; i++, p--) {
            v2 += cpf[i] * p;
        }

        v2 = ((v2 * 10) % 11);

        if (v2 == 10) {
            v2 = 0;
        }

        if (v2 != cpf[10]) {
            $('#cpf-validation-info').removeClass('hidden')
            return false;
        } else {
            $('#cpf-validation-info').addClass('hidden')
            return true;
        }
    } else if (val.length > 14) {
        $('#cpf-validation-info').addClass('hidden')

        var cnpj = val.trim();

        cnpj = cnpj.replace(/\./g, '');
        cnpj = cnpj.replace('-', '');
        cnpj = cnpj.replace('/', '');
        cnpj = cnpj.split('');

        var v1 = 0;
        var v2 = 0;
        var aux = false;

        for (var i = 1; cnpj.length > i; i++) {
            if (cnpj[i - 1] != cnpj[i]) {
                aux = true;
            }
        }

        if (aux == false) {
            $('#cnpj-validation-info').removeClass('hidden')
            return false;
        }

        for (var i = 0, p1 = 5, p2 = 13; (cnpj.length - 2) > i; i++, p1--, p2--) {
            if (p1 >= 2) {
                v1 += cnpj[i] * p1;
            } else {
                v1 += cnpj[i] * p2;
            }
        }

        v1 = (v1 % 11);

        if (v1 < 2) {
            v1 = 0;
        } else {
            v1 = (11 - v1);
        }

        if (v1 != cnpj[12]) {
            $('#cnpj-validation-info').removeClass('hidden')
            return false;
        }

        for (var i = 0, p1 = 6, p2 = 14; (cnpj.length - 1) > i; i++, p1--, p2--) {
            if (p1 >= 2) {
                v2 += cnpj[i] * p1;
            } else {
                v2 += cnpj[i] * p2;
            }
        }

        v2 = (v2 % 11);

        if (v2 < 2) {
            v2 = 0;
        } else {
            v2 = (11 - v2);
        }

        if (v2 != cnpj[13]) {
            $('#cnpj-validation-info').removeClass('hidden')
            return false;
        } else {
            $('#cnpj-validation-info').addClass('hidden')
            return true;
        }
    } else {
        return false;
    }
}

function setCpfMask(input) {
    input.addEventListener("input", function () {
        let value = input.value.replace(/\D/g, ""); // Remove caracteres não numéricos
        let maskedValue = "";
        let pattern = "999.999.999-99";

        let patternIndex = 0;
        for (let i = 0; i < value.length && patternIndex < pattern.length; i++) {
            if (pattern[patternIndex] === "9") {
                maskedValue += value[i];
                patternIndex++;
            } else {
                maskedValue += pattern[patternIndex++];
                i--;
            }
        }

        input.value = maskedValue;
    });
}

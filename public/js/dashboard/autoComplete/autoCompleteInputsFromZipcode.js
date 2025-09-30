document.getElementById('user[address][zipcode]').addEventListener('input', function (e) {
    let target = e.target, position = target.selectionEnd, length = target.value.length;

    target.value = target.value.replace(/\D/g, '');
    target.value = target.value.replace(/^(\d{5})(\d)/, '$1-$2');

    if (length > 9) {
        target.selectionEnd = position + 1;
    }

    if (length === 9) {
        fetch('https://viacep.com.br/ws/' + target.value + '/json/')
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                updateField('user[address][street_address]', 'logradouro', data);
                updateField('user[address][neighborhood]', 'bairro', data);
                updateField('user[address][city]', 'localidade', data);
                updateField('user[address][state]', 'uf', data);

                document.getElementById('user[address][number]').focus();

                if (data.erro) {
                    clearAllFields();
                }
            });
    }
});

function updateField(elementId, dataKey, data) {
    const element = document.getElementById(elementId);
    const value = data[dataKey] || '';

    element.value = value;

    if (value) {
        element.readOnly = true;
        element.classList.add('cursor-not-allowed');
    }
}

function clearField(elementId) {
    const element = document.getElementById(elementId);

    element.value = '';
    element.readOnly = false;
    element.classList.remove('cursor-not-allowed');
}

function clearAllFields() {
    clearField('street_address');
    clearField('neighborhood');
    clearField('city');
    clearField('state');
}

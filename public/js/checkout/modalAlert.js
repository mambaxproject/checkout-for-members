function showModalError(title, messages) {
    const targetEl = document.getElementById('showToken');
    const modal = new Modal(targetEl);
    modal.toggle();

    $('.modal-text-error').html(title);
    let modal_error = $('.modal-error');

    modal_error.html('');
    messages.forEach(function(message) {
        modal_error.append(`<p>` + message + `</p>`)
    })

    $('[data-modal-hide="showToken"]').on('click', function() {
        modal.hide()
    });
};
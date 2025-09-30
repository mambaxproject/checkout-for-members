function createAbandonedCart() {
    let form = new FormData(document.querySelector('.form'))
    let affiliateCode = (new URLSearchParams(window.location.search)).get('afflt');

    if (form.get('user[name]') && form.get('user[email]')) {
        let payload = {
            name: form.get('user[name]'),
            amount: checkout.total,
            payment_method: form.get('payment[paymentMethod]'),
            product_id: checkout.items[0].id,
            link_checkout: window.location.href,
            infosProduct: [checkout.items[0]],
        }

        if (affiliateCode) {
            payload.affiliate_code = affiliateCode
        }

        if (form.get('user[phone_number]')) {
            payload.phone_number = form.get('user[phone_number]')
        }

        if (form.get('user[email]')) {
            payload.email = form.get('user[email]')
        }

        $.ajax({
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/api/v1/public/abandoned-carts',
            data: payload,
            success: function(data) {
                console.log(data)
            },
            error: function(data) {
                console.error(data)
            }
        })
    }
}
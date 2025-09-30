function showCouponMessage(message, error = false) {
    let coupon_field_feedback = $('.coupon_field_feedback');

    if (!error) {
        coupon_field_feedback.removeClass('textDangerColor')
        coupon_field_feedback.addClass('textPrimaryColor')
    } else {
        coupon_field_feedback.removeClass('textPrimaryColor')
        coupon_field_feedback.addClass('textDangerColor')
    }

    coupon_field_feedback.html(message)
}

function resetDiscount() {
    checkout.currentCoupon = null;
    checkout.discount = 0;
    $('.coupon_field_feedback').html('')
    checkout.calc()
    purchaseDetails.updateView(checkout)
}

function setCoupon(coupon, is_auto = false)
{
    resetDiscount();

    if (!coupon.payment_methods.includes(checkout.currentPaymentMethod)) {
        resetDiscount()
        if (!is_auto) showCouponMessage("Cupom invalido para esse metodo de pagamento.", true);
        return;
    }

    checkout.currentCoupon = coupon;

    if (coupon.type === 'VALUE') {
        checkout.discount = parseFloat(coupon.amount);
    } else {
        checkout.discount = parseFloat(coupon.amount) * (checkout.totalWithoutOrderBump / 100)
    }

    $('.coupon_field').val(coupon.code);
    showCouponMessage('Cupom aplicado.')

    checkout.calc()
    purchaseDetails.updateView(checkout)
}

function checkCurrentCouponPaymentMethod() {
    if (checkout.currentCoupon == null) return false

    if (checkout.currentCoupon.payment_methods.includes(checkout.currentPaymentMethod)) return true

    return false
}

function checkAutoCoupon() {
    let hasAffiliateCode = Boolean((new URLSearchParams(window.location.search)).get('afflt'));

    $.ajax({
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: 'api/v1/public/discounts/automaticCoupon',
        data: {
            amount: checkout.total,
            product_id: checkout.principalProductId,
            offer_id: checkout.items[0].id,
            customer_email: $('input[name="user[email]"]').val(),
            is_affiliate_link: Number(hasAffiliateCode)
        },
        success: function(data) {
            setCoupon(data.data.coupon);
        },
        error: function(data) {
            console.error(data)
        }
    })
}

function applyCoupon() {
    let hasAffiliateCode = Boolean((new URLSearchParams(window.location.search)).get('afflt'));
    let code = $('.coupon_field').val()

    if (!code) {
        resetDiscount()
        showCouponMessage('', false);
        return;
    }

    $.ajax({
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: 'api/v1/public/discounts/validateCoupon',
        data: {
            code: code,
            amount: checkout.total,
            product_id: checkout.principalProductId,
            offer_id: checkout.items[0].id,
            customer_email: $('input[name="user[email]"]').val(),
            is_affiliate_link: Number(hasAffiliateCode)
        },
        success: function(data) {
            setCoupon(data.data.coupon);
        },
        error: function(data) {
            resetDiscount();
            showCouponMessage(Object.values(data.responseJSON.data)[0]?? "cupom invalido", true);
        }
    })
}
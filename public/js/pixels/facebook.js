// Função para carregar o script do Facebook Pixel
function loadFacebookPixelScript() {
    !(function (f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function () {
            n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = "2.0";
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s);
    })(window, document, "script", "https://connect.facebook.net/pt_BR/fbevents.js");
}

// Função para inicializar o Pixel do Facebook
function initFacebookPixel(id_pixel) {
    if (!id_pixel) {
        console.error("ID do Pixel não fornecido.");
        return false;
    }
    fbq("init", id_pixel);
    return true;
}

// Função para preparar os dados dos produtos
function prepareProductData(products) {
    return products.map((product) => ({
        id: product.id.toString(),
        name: product.name,
        quantity: product.quantity,
        value: parseFloat(product.value),
    }));
}

// Função para rastrear a page view no Facebook Pixel
function trackFacebookPageView(id_pixel, products, num_items, value) {
    if (!initFacebookPixel(id_pixel)) return;

    const contents = prepareProductData(products);

    fbq("track", "PageView", {
        content_ids: products.map((product) => product.id.toString()),
        content_type: "product",
        contents: contents,
        currency: "BRL",
        num_items: num_items,
        value: parseFloat(value),
    });
}

function trackFacebookInitiateCheckout(id_pixel, products, num_items, value) {
    if (!initFacebookPixel(id_pixel)) return;

    const contents = prepareProductData(products);

    fbq("track", "InitiateCheckout", {
        content_ids: products.map((product) => product.id.toString()), // Array de IDs
        content_type: "product",
        contents: contents,
        currency: "BRL",
        num_items: num_items,
        payment_method: checkout.currentPaymentMethod,
        value: parseFloat(value),
    });
}

function trackFacebookContact(name, phone, email)
{
    let facebookPixels = getFacebookPixels();

    if (!facebookPixels.length) return;

    let products = getProducts();
    const contents = prepareProductData(products);

    facebookPixels.forEach(function (pixel) {
        fbq("track", "Contact", {
            content_ids: products.map((product) => product.id.toString()), // Array de IDs
            contact: {
                name: name,
                phone_number: phone,
                email: email
            },
            content_type: "product",
            contents: contents,
            currency: "BRL",
            num_items: checkout.items.length,
            payment_method: checkout.currentPaymentMethod,
            value: parseFloat(checkout.total),
        });
    })
}

// Função para rastrear a compra no Facebook Pixel
function trackFacebookPurchase(id_pixel, products, num_items, value, payment_method) {
    if (!initFacebookPixel(id_pixel)) return;

    const contents = prepareProductData(products);

    fbq("track", "Purchase", {
        content_ids: products.map((product) => product.id.toString()), // Array de IDs
        content_type: "product",
        contents: contents,
        currency: "BRL",
        num_items: num_items,
        payment_method: payment_method,
        value: parseFloat(value),
    });
}

function getFacebookPixels() {
    if (!Array.isArray(window.pixels)) {
        console.warn("window.pixels não é um array ou não foi definido.");
        return [];
    }
    
    return  window.pixels.filter(obj => {
        return obj.pixel_service.name === 'Facebook'
    })
}

function getProducts() {
    return checkout.items.map(function(item) {
        return {
            id: item.id.toString(),
            name: item.name,
            quantity: item.quantity,
            value: item.has_first_payment ? parseFloat(item.priceFirstPayment) : parseFloat(item.price ?? product.promotional_price),
        }
    });
}

// Como usar
window.addEventListener("load", function () {
    let facebookPixels = getFacebookPixels();

    if (!facebookPixels.length) return;

    
    loadFacebookPixelScript();

    facebookPixels.forEach(function (pixel) {
        if (typeof checkout !== 'undefined') {
            let products = getProducts()

            trackFacebookPageView(
                pixel.pixel_id,
                products,
                checkout.items.length,
                checkout.total,
            );

            trackFacebookInitiateCheckout(
                pixel.pixel_id,
                products,
                checkout.items.length,
                checkout.total,
            )

        } else {
            let products = window.order.products.map(function(item) {
                return {
                    id: item.product.id.toString(),
                    name: item.product.name,
                    quantity: item.quantity,
                    value: item.amount,
                }
            });

            if (window.order.payment_method !== 'PIX') {
                if (pixel.attributes.backend_purchase) return;

                trackFacebookPurchase(
                    pixel.pixel_id,
                    products,
                    products.length,
                    window.order.total,
                    window.order.payment_method,
                );
            }
        }
    })
});

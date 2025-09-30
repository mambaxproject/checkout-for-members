// Função para carregar o script do Google Ads
function loadGoogleAdsScript(id_tag) {
    !(function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != "dataLayer" ? "&l=" + l : "";
        j.async = true;
        j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, "script", "dataLayer", id_tag); // Substitua pelo ID do seu Google Tag Manager
}

// Função para inicializar a tag de conversão do Google Ads
function initGoogleAdsConversion(id_conversion) {
    if (!id_conversion) {
        console.error("ID de conversão não fornecido.");
        return false;
    }
    window.gtag =
        window.gtag ||
        function () {
            window.dataLayer.push(arguments);
        };
    gtag("js", new Date());
    gtag("config", id_conversion); // Substitua pelo ID de conversão do Google Ads
    return true;
}

// Função para preparar os dados dos produtos
function prepareProductDataGoogle(products) {
    return products.map((product) => ({
        id: product.id.toString(),
        name: product.name,
        quantity: product.quantity,
        price: parseFloat(product.value).toFixed(2),
    }));
}

// Função para rastrear a visualização da página no Google Ads
function trackGoogleAdsPageView(id_conversion, products, num_items, value) {
    if (!initGoogleAdsConversion(id_conversion)) return;

    const contents = prepareProductDataGoogle(products);

    gtag("event", "page_view", {
        items: contents,
        currency: "BRL",
        num_items: num_items,
        value: parseFloat(value).toFixed(2),
    });
}

// Função para rastrear a compra no Google Ads
function trackGoogleAdsPurchase(id_conversion, products, num_items, value, transaction_id, payment_method) {
    if (!initGoogleAdsConversion(id_conversion)) return;

    const contents = prepareProductDataGoogle(products);

    gtag("event", "purchase", {
        transaction_id: transaction_id,
        items: contents,
        currency: "BRL",
        num_items: num_items,
        value: parseFloat(value).toFixed(2),
        payment_method: payment_method,
    });
}

function trackGoogleBeginCheckout(id_conversion, products, num_items, value, payment_method) {
    if (!initGoogleAdsConversion(id_conversion)) return;

    const contents = prepareProductDataGoogle(products);

    gtag("event", "begin_checkout", {
        items: contents,
        currency: "BRL",
        num_items: num_items,
        value: parseFloat(value).toFixed(2),
        payment_method: payment_method,
    });
}

// Como usar
window.addEventListener("load", function () {
    if (!Array.isArray(window.pixels)) {
        console.warn("window.pixels não é um array ou não foi definido.");
        return;
    }

    let googlePixels = window.pixels.filter(obj => {
        return obj.pixel_service.name === 'Google ADS'
    })

    if (!googlePixels.length) return;

    googlePixels.forEach(function(pixel) {
        loadGoogleAdsScript(pixel.pixel_id);

        if (typeof checkout !== 'undefined') {
            let products = checkout.items.map(function(item) {
                return {
                    id: item.id.toString(),
                    name: item.name,
                    quantity: item.quantity,
                    value: item.value,
                }
            });

            // Rastrear visualização de página
            trackGoogleAdsPageView(
                pixel.attributes.conversionLabel, // Substitua pelo ID de conversão do Google Ads
                products,
                checkout.items.length,
                checkout.total,
            );
        } else {

            let products = window.order.products.map(function(item) {
                return {
                    id: item.product.id.toString(),
                    name: item.product.name,
                    quantity: item.quantity,
                    value: item.amount,
                }
            });

            // Rastrear compra
            trackGoogleAdsPurchase(
                pixel.attributes.conversionLabel, // Substitua pelo ID de conversão do Google Ads
                products,
                products.length,
                window.order.total,
                window.order.id_transaction, // ID da transação
                window.order.payment_method, // Método de pagamento
            );
        }
    })
});
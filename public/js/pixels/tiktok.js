// Função para carregar o script do TikTok Pixel
function loadTikTokPixelScript(pixel_id) {
    !(function (w, d, t) {
        w.TiktokAnalyticsObject = t;
        var ttq = (w[t] = w[t] || []);
        (ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie"]),
            (ttq.setAndDefer = function (t, e) {
                t[e] = function () {
                    t.push([e].concat(Array.prototype.slice.call(arguments, 0)));
                };
            });
        for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
        (ttq.instance = function (t) {
            for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]);
            return e;
        }),
            (ttq.load = function (e, n) {
                var i = "https://analytics.tiktok.com/i18n/pixel/events.js";
                (ttq._i = ttq._i || {}), (ttq._i[e] = []), (ttq._i[e]._u = i), (ttq._t = ttq._t || {}), (ttq._t[e] = +new Date()), (ttq._o = ttq._o || {}), (ttq._o[e] = n || {});
                var o = document.createElement("script");
                (o.type = "text/javascript"), (o.async = !0), (o.src = i + "?sdkid=" + e + "&lib=" + t);
                var a = document.getElementsByTagName("script")[0];
                a.parentNode.insertBefore(o, a);
            });

        ttq.load(pixel_id);
        ttq.page();
    })(window, document, "ttq");
}

// Função para inicializar o Pixel do TikTok
function initTikTokPixel(id_pixel) {
    if (!id_pixel) {
        console.error("ID do Pixel não fornecido.");
        return false;
    }
    ttq.load(id_pixel);
    return true;
}

// Como usar
window.addEventListener("load", function () {
    if (!Array.isArray(window.pixels)) {
        console.warn("window.pixels não é um array ou não foi definido.");
        return;
    }
    
    let tiktokPixels = window.pixels.filter(obj => {
        return obj.pixel_service.name === 'TikTok'
    })

    if (!tiktokPixels.length) return;

    tiktokPixels.forEach(function (pixel) {
        loadTikTokPixelScript(pixel.pixel_id);
        initTikTokPixel(pixel.pixel_id);
    })
});

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $appsShop['google-analytics']['data']['tracking_id'] ?? "" }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', "{{ $appsShop['google-analytics']['data']['tracking_id'] ?? "" }}");
</script>

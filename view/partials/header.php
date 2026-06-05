<?php if(!defined('HEADER_LOADED')) { define('HEADER_LOADED', true); ?>

<!-- Fonts e iconos -->
    <script src="/proyectoGeo/web/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Public Sans:300,400,500,600,700"]},
            custom: {"families":["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
                    urls: ['/proyectoGeo/web/assets/css/fonts.min.css']},
            active: function() { sessionStorage.fonts = true; }
        });
    </script>

 
    <link rel="stylesheet" href="/proyectoGeo/web/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/proyectoGeo/web/assets/css/plugins.min.css">
    <link rel="stylesheet" href="/proyectoGeo/web/assets/css/kaiadmin.min.css">

<?php } ?>
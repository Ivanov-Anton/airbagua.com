jQuery('document').ready(function ($) {
    jQuery('.speedcache_tooltip').qtip({
        content: {
            attr: 'alt'
        },
        position: {
            my: 'bottom left',
            at: 'top top'
        },
        style: {
            tip: {
                corner: true
            },
            classes: 'speedoflight-qtip qtip-rounded speedoflight-qtip-dashboard'
        },
        show: 'hover',
        hide: {
            fixed: true,
            delay: 10
        }

    });
});
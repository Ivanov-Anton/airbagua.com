jQuery(document).ready(function($) {
    // Marker
    $('body').on('click', '.su-marker-nav span', function (e) {
        var $tab = $(this),
            data = $tab.data(),
            index = $tab.index(),
            is_disabled = $tab.hasClass('su-marker-disabled'),
            $marker = $tab.parent('.su-marker-nav').children('span'),
            $panes = $tab.parents('.su-marker').find('.su-marker-pane'),
            $gmaps = $panes.eq(index).find('.su-gmap:not(.su-gmap-reloaded)');
        // Check tab is not disabled
        if (is_disabled) return false;
        // Hide all panes, show selected pane
        $panes.hide().eq(index).show();
        // Disable all marker, enable selected tab
        $marker.removeClass('su-marker-current').eq(index).addClass('su-marker-current');
        // Reload gmaps
        if ($gmaps.length > 0) $gmaps.each(function() {
            var $iframe = $(this).find('iframe:first');
            $(this).addClass('su-gmap-reloaded');
            $iframe.attr('src', $iframe.attr('src'));
        });
        // Set height for vertical marker
        marker_height();

        // Open specified url
        if (data.url !== '') {
            if (data.target === 'self') window.location = data.url;
            else if (data.target === 'blank') window.open(data.url);
        }        
        e.preventDefault();
    });

    var myVar;
    var cuberCheck = jQuery('.su-marker .su-photo-gallery-slide').attr("style");
    if(jQuery('.su-marker').has(".su-photo-gallery-slide") && (typeof cuberCheck === "undefined")){
            myVar = setInterval(activeTab, 300);
    }else{
        activeTab();

    }

 

function activeTab() {
 // Activate marker
    $('.su-marker').each(function() {
        var active = parseInt($(this).data('active')) - 1;
        $(this).children('.su-marker-nav').children('span').eq(active).trigger('click');
        marker_height();
    });
     if(jQuery('.su-marker').has(".su-photo-gallery-slide") && jQuery('.su-marker .su-photo-gallery-slide').attr("style") != undefined ){
        if(myVar)
            clearInterval(myVar);
    }else{
        if(myVar)
        clearInterval(myVar);
    } 
}
   

    // Activate anchor nav for marker and spoilers
    tab_anchor();

    function marker_height() {
        $('.su-marker-vertical').each(function() {
            var $marker = $(this),
                $nav = $marker.children('.su-marker-nav'),
                $panes = $marker.find('.su-marker-pane'),
                height = 0;
            $panes.css('min-height', $nav.outerHeight(true));
        });
    }

    function tab_anchor() {
        // Check hash
        if (document.location.hash === '') return;
        // Go through marker
        $('.su-marker-nav span[data-anchor]').each(function() {
            if ('#' + $(this).data('anchor') === document.location.hash) {
                var $marker = $(this).parents('.su-marker');
                // Activate tab
                $(this).trigger('click');
                // Scroll-in marker container
                window.setTimeout(function() {
                    $(window).scrollTop($marker.offset().top - 10);
                }, 100);
            }
        });
    }

    if ('onhashchange' in window) $(window).on('hashchange', tab_anchor);
});
jQuery('document').ready(function ($) {
    var lastTab;
    if (typeof(joomla_sc) == 'undefined') {
        joomla_sc = {};
    }
    /*
     function to check all
     */
    joomla_sc.checkAll = function (checkbox, stub) {
        if (!checkbox.form) return false;

        stub = stub ? stub : 'cb';

        var c = 0,
            i, e, n;

        for (i = 0, n = checkbox.form.elements.length; i < n; i++) {
            e = checkbox.form.elements[i];

            if (e.type == checkbox.type && e.id.indexOf(stub) === 0) {
                e.checked = checkbox.checked;
                c += e.checked ? 1 : 0;
            }
        }

        if (checkbox.form.boxchecked) {
            checkbox.form.boxchecked.value = c;
        }

        return true;
    };

    //Click on add from menu url
    $('#toolbar-speedcacheimportmenubtn').click(function () {
        lastTab = $('ul#speedTabTabs .active').find('a').attr('href');
        lastTab = lastTab.substring(1);
        w = window.getWidth() - 80;
        h = window.getHeight() - 80;
        SqueezeBox.open("index.php?option=com_speedcache&view=menus&tmpl=component&lasttab=" + lastTab, {
            handler: "iframe",
            size: {x: w, y: h}
        })
    });

    //Add icons to custom buttons
    $('#toolbar-addincludeRules,#toolbar-addexclude,#toolbar-addexcludeRules').find('span').removeClass().addClass('icon-new');
    $('#toolbar-guest').find('span').removeClass().addClass('icon-publish');
    $('#toolbar-unguest').find('span').removeClass().addClass('icon-unpublish');
    $('#toolbar-preloadguest,#toolbar-logged,#toolbar-preloadlogged,#toolbar-preloaduser,#toolbar-excludeguest,#toolbar-excludelogged').hide().find('span').removeClass().addClass('icon-publish');
    $('#toolbar-unpreloadguest,#toolbar-unlogged,#toolbar-unpreloadlogged,#toolbar-unpreloaduser,#toolbar-unexcludeguest,#toolbar-unexcludelogged').hide().find('span').removeClass().addClass('icon-unpublish');

    $('#toolbar-batch select').change(function () {
        val = $(this).val();
        if (val === 'guest') {
            $('#toolbar-preloadguest,#toolbar-unpreloadguest,#toolbar-logged,#toolbar-unlogged,#toolbar-preloadlogged,#toolbar-unpreloadlogged,#toolbar-preloaduser,#toolbar-unpreloaduser,#toolbar-excludeguest,#toolbar-unexcludeguest,#toolbar-excludelogged,#toolbar-unexcludelogged').hide();
            $('#toolbar-guest, #toolbar-unguest').show();
        } else if (val === 'preloadguest') {
            $('#toolbar-guest, #toolbar-unguest,#toolbar-logged,#toolbar-unlogged,#toolbar-preloadlogged,#toolbar-unpreloadlogged,#toolbar-preloaduser,#toolbar-unpreloaduser,#toolbar-excludeguest,#toolbar-unexcludeguest,#toolbar-excludelogged,#toolbar-unexcludelogged').hide();
            $('#toolbar-preloadguest,#toolbar-unpreloadguest').show();
        } else if(val === 'logged'){
            $('#toolbar-guest, #toolbar-unguest,#toolbar-preloadguest,#toolbar-unpreloadguest,#toolbar-preloadlogged,#toolbar-unpreloadlogged,#toolbar-preloaduser,#toolbar-unpreloaduser,#toolbar-excludeguest,#toolbar-unexcludeguest,#toolbar-excludelogged,#toolbar-unexcludelogged').hide();
            $('#toolbar-logged,#toolbar-unlogged').show();
        }else if(val === 'preloadlogged'){
            $('#toolbar-guest, #toolbar-unguest,#toolbar-preloadguest,#toolbar-unpreloadguest,#toolbar-logged,#toolbar-unlogged,#toolbar-preloaduser,#toolbar-unpreloaduser,#toolbar-excludeguest,#toolbar-unexcludeguest,#toolbar-excludelogged,#toolbar-unexcludelogged').hide();
            $('#toolbar-preloadlogged,#toolbar-unpreloadlogged').show();
        }else if(val ==='preloaduser'){
            $('#toolbar-guest, #toolbar-unguest,#toolbar-preloadguest,#toolbar-unpreloadguest,#toolbar-logged,#toolbar-unlogged,#toolbar-preloadlogged,#toolbar-unpreloadlogged,#toolbar-excludeguest,#toolbar-unexcludeguest,#toolbar-excludelogged,#toolbar-unexcludelogged').hide();
            $('#toolbar-preloaduser,#toolbar-unpreloaduser').show();
        }else if(val === 'excludeguest'){
            $('#toolbar-guest, #toolbar-unguest,#toolbar-preloadguest,#toolbar-unpreloadguest,#toolbar-logged,#toolbar-unlogged,#toolbar-preloadlogged,#toolbar-unpreloadlogged,#toolbar-preloaduser,#toolbar-unpreloaduser,#toolbar-excludelogged,#toolbar-unexcludelogged').hide();
            $('#toolbar-excludeguest,#toolbar-unexcludeguest').show();
        }else{
            $('#toolbar-guest, #toolbar-unguest,#toolbar-preloadguest,#toolbar-unpreloadguest,#toolbar-logged,#toolbar-unlogged,#toolbar-preloadlogged,#toolbar-unpreloadlogged,#toolbar-preloaduser,#toolbar-unpreloaduser,#toolbar-excludeguest,#toolbar-unexcludeguest').hide();
            $('#toolbar-excludelogged,#toolbar-unexcludelogged').show();
        }
    });

    //load page again
    $(window).load(function () {
        //keep html when reload page
        var $current_tabs = $('ul#speedTabTabs .active').find('a').attr('href');
        if(typeof $current_tabs != 'undefined'){
            if ($current_tabs.indexOf('url_exclude') > -1 ) {
                $('#toolbar-speedcacheimportmenubtn, #toolbar-new').hide();
                $('#toolbar-addexclude,#toolbar-speedcacheselectmenubtn').css('display', 'inline-block');
                $('#toolbar-addincludeRules,#toolbar-addexcludeRules').css('display', 'none');
            }else if($current_tabs.indexOf('include_rules') > -1){
                $('#toolbar-speedcacheimportmenubtn, #toolbar-new').hide();
                $('#toolbar-addincludeRules').css('display', 'inline-block');
                $('#toolbar-addexcludeRules,#toolbar-speedcacheselectmenubtn,#toolbar-addexclude').css('display', 'none');
            }else if($current_tabs.indexOf('exclude_rules') > -1){
                $('#toolbar-speedcacheimportmenubtn, #toolbar-new').hide();
                $('#toolbar-addexcludeRules').css('display', 'inline-block');
                $('#toolbar-addincludeRules,#toolbar-speedcacheselectmenubtn,#toolbar-addexclude').css('display', 'none');
            }else {
                $('#toolbar-speedcacheimportmenubtn, #toolbar-new').show();
                $('#toolbar-speedcacheselectmenubtn,#toolbar-addincludeRules,#toolbar-addexclude,#toolbar-addexcludeRules').css('display', 'none');
            }
        }

    });


    //click select from menu button
    $('#toolbar-speedcacheselectmenubtn').click(function () {
        w = window.getWidth() - 80;
        h = window.getHeight() - 80;
        SqueezeBox.open("index.php?option=com_speedcache&view=menus&tmpl=component&lasttab=" + lastTab, {
            handler: "iframe",
            size: {x: w, y: h}
        })
    });

    $(function () {
        // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            // save the latest tab; use cookies if you like 'em better:
            localStorage.setItem('lastTab', $(this).attr('href'));

            $tabs = $(this).attr('href');
            if ($tabs.indexOf('url_exclude') > -1 ) {
                $('#toolbar-speedcacheimportmenubtn, #toolbar-new').hide();
                $('#toolbar-addexclude,#toolbar-speedcacheselectmenubtn').css('display', 'inline-block');
                $('#toolbar-addincludeRules,#toolbar-addexcludeRules').css('display', 'none');
            }else if($tabs.indexOf('include_rules') > -1){
                $('#toolbar-speedcacheimportmenubtn, #toolbar-new').hide();
                $('#toolbar-addincludeRules').css('display', 'inline-block');
                $('#toolbar-addexcludeRules,#toolbar-speedcacheselectmenubtn,#toolbar-addexclude').css('display', 'none');
            }else if($tabs.indexOf('exclude_rules') > -1){
                $('#toolbar-speedcacheimportmenubtn, #toolbar-new').hide();
                $('#toolbar-addexcludeRules').css('display', 'inline-block');
                $('#toolbar-addincludeRules,#toolbar-speedcacheselectmenubtn,#toolbar-addexclude').css('display', 'none');
            }else {
                $('#toolbar-speedcacheimportmenubtn, #toolbar-new').show();
                $('#toolbar-speedcacheselectmenubtn,#toolbar-addincludeRules,#toolbar-addexclude,#toolbar-addexcludeRules').css('display', 'none');
            }
        });

        // go to the latest tab, if it exists:
        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('[href="' + lastTab + '"]').tab('show');
        }
    });

});
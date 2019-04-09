jQuery('document').ready(function ($) {
    $('.sc_clearcache_link ').click(function () {
        speedcache_show();
        $('#sc_speedcache_msg').hide();
        $('.clear-cache-icon').hide();
        $('.sc-image-loading').show();
        $.ajax({
            url: 'index.php?option=com_speedcache&task=dashboard.clearcache&' + sc_token_key + '=1',
            type: 'POST',
            success: function (res) {
                setTimeout(function () {
                    $('.sc-image-loading').hide();
                    $('.clear-cache-icon').show();
                    $('#sc_speedcache_msg').show();

                    res = $.parseJSON(res);
                    if (res.status == 'error') {
                        $('#sc_speedcache_msg').addClass('btn-danger');
                        $('#sc_speedcache_msg').html(sc_speedcache_msg_error);
                    } else {
                        data = res.size + 'Kb of cache cleaned !';
                        $('#sc_speedcache_msg').addClass('btn-successs');
                        $('#sc_speedcache_msg').html(data);
                        $('#sc_speedcache_msg').append('<span class="icon-delete icon-remove icon-cancel-2 close-notification"></span>');

                        $('.close-notification').click(function () {
                            speedcache_end();
                        });
                        speedcache_end(5);
                    }
                }, 2000);

            }
        });

    });

    $('<span/>', {
        id: 'sc_speedcache_msg',
        css: {'opacity': 0},
        click: function () {
        }
    }).appendTo('body');


    var speedcache_show = function () {
        // $('#sc_speedcache_msg')
        //     .html('<img src="' + sc_speedcache_root + 'administrator/modules/mod_speedcache/assets/images/loading.gif" /> ' + sc_speedcache_msg)
        //     .removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').addClass('visible');
        $('#sc_speedcache_msg').fadeTo('fast', 1);
    };


    var speedcache_end = function (delay) {
        if (delay) {
            setTimeout(function () {
                $('#sc_speedcache_msg').fadeOut('fast', function () {
                    $('#sc_speedcache_msg').hide();
                });
            }, delay * 1000);
        } else {
            $('#sc_speedcache_msg').hide();
        }
    };
});
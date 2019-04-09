/**
 * Speedcache
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@joomunited.com *
 * @package Speedcache
 * @copyright Copyright (C) 2016 JoomUnited (https://www.joomunited.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

jQuery('document').ready(function ($) {
    $.ajax({
        url: 'index.php?option=com_speedcache&task=dashboard.check',
        method: 'POST',
        success: function (data) {
            data = JSON.parse(data);
            //Check for gzip
            if (data.gzip == true) {
                $('.block.gzip .enabled').removeClass('hidden');
                $('.block.gzip .checking').addClass('hidden');
                $('.block.gzip').addClass('ok');
            } else if (data.gzip == false) {
                $('.block.gzip .disabled').removeClass('hidden');
                $('.block.gzip .checking').addClass('hidden');
                $('.block.gzip').addClass('error');
            }

            //Check for expire headers
            if (data.expires_headers.expires_headers == true) {
                $('.block.expires .enabled').removeClass('hidden');
                $('.block.expires .checking').addClass('hidden');
                $('.block.expires').addClass('ok');

                //Check expires header time
                content = '';
                for (var types in data.expires_headers.filetypes) {
                    if (data.expires_headers.filetypes.hasOwnProperty(types)) {
                        if (data.expires_headers.filetypes[types]['expires'] !== false) {
                            diff = (Date.parse(data.expires_headers.filetypes[types]['expires']) - Date.parse(data.expires_headers.filetypes[types]['date'])) / (1000 * 60 * 60 * 24);
                            if (diff < 7) {
                                if (content) {
                                    content += ',';
                                }
                                content += types;
                            }
                        }
                    }
                }
                if (content != "") {
                    $('#expires_headers_time_error_list').replaceWith(content);
                    $('.block.expires_time .disabled').removeClass('hidden');
                    $('.block.expires_time .checking').addClass('hidden');
                    $('.block.expires_time').addClass('error');
                } else {
                    $('.block.expires_time .enabled').removeClass('hidden');
                    $('.block.expires_time .checking').addClass('hidden');
                    $('.block.expires_time').addClass('ok');
                }

            } else if (data.expires_headers.expires_headers == false) {
                content = '';
                for (var types in data.expires_headers.filetypes) {
                    if (data.expires_headers.filetypes.hasOwnProperty(types)) {
                        if (data.expires_headers.filetypes[types]['expires'] == false) {
                            if (content) {
                                content += ',';
                            }
                            content += types;
                        }
                    }
                }
                $('#expires_headers_error_list').replaceWith(content);
                $('.block.expires .disabled').removeClass('hidden');
                $('.block.expires .checking').addClass('hidden');
                $('.block.expires').addClass('error');

                $('.block.expires_time .missing').removeClass('hidden');
                $('.block.expires_time .checking').addClass('hidden');
                $('.block.expires_time').addClass('error');
            }

            // Check for expires module
            if (data.expires_module) {
                $('.block.expired_module .checking').addClass('hidden');
                $('.block.expired_module').addClass('ok');
                $('.block.expired_module .enabled').removeClass('hidden');
            } else {
                $('.block.expired_module .checking').addClass('hidden');
                $('.block.expired_module').addClass('error');
                $('.block.expired_module .disabled').removeClass('hidden');
            }
        }
    });

    //Relation table between class and task
    fix = {
        'caching': 'fixCaching', //Fix Joomla cache not set
        'gzip': 'fixGzip',
        'cache_time': 'fixCachetime',
        'browser_cache': 'fixBrowserCache',
        'expires': 'fixExpiresHeaders',
        'autoclear': 'fixAutoClear'
    };

    $.each(fix, function (index, value) {
        $('.block.' + index + ' .patch-it').click(function (e) {
            e.preventDefault();
            $.ajax({
                url: 'index.php?option=com_speedcache&task=dashboard.' + value + '&' + window.token + '=1',
                type: 'POST',
                success: function () {
                    window.location.reload(true);
                }
            });
        });
    });


});
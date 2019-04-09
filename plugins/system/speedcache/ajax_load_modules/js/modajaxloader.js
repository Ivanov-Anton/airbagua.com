//jQuery.noConflict();
var jQuery = jQuery;
(function($) {
    jQuery(window).load(function() {
        //get elements to use ajax load
        var elements = jQuery('.sc-display-module');

        //loop the elements
        for (var i = 0; i < elements.length; i++) {
            //get info to fetch modules
            modulepos = elements[i].getAttribute('rel');
            timereload = elements[i].getAttribute('alt');

            //set default time for periodical reload in miliseconds
            if (!timereload || timereload == 0) {
                timereload = 30;
            }

            timereload = timereload * 60 * 1000;

            if (modulepos != undefined) {
                url = speedcache_base_url + '?tmpl=speedcacheajaxloadmodule&modpos=' + modulepos;
                //define loader function and pass current iteration element and url to it
                //defining the function in such a way is a MUST, because of iteration nature in JS
                //otherwise the ajax loading will be applied not to each loop element, but to the last one
                var loader = function(element, url) {
                    return function() {
                        jQuery.ajax({
                            url: url + '&tto=' + (new Date().getTime()),
                            context: document.body,
                            beforeSend: function() {
                                element.innerHTML = '<img src="' +loader_link+'" >'
                            },
                            success: function(data) {
                                element.innerHTML = data;
                            },
                        });

                    }
                }(elements[i], url);
                var loaderR = function(element, url, timereload) {
                    return function() {
                        setInterval(function() {
                            jQuery.ajax({
                                url: url + '&tto=' + (new Date().getTime()),
                                context: document.body,
                                beforeSend: function() {
                                    element.innerHTML = '<img src="' +loader_link+'" >'
                                },
                                success: function(data) {
                                    element.innerHTML = data;
                                },
                            });
                        }, timereload);
                    }
                }(elements[i], url, timereload);

                //use periodical reload or no
                var hasClass = $(elements[i]).hasClass('recurrent');

                if (hasClass) {
                    loader();
                    setTimeout(loaderR, 500);
                    //setInterval(loader,timereload);
                } else {
                    loader();
                }
            }
        }
    });
})(jQuery); //Passing the jQuery object as a first argument

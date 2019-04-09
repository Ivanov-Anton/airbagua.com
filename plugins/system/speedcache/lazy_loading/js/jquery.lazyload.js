"use strict";

var SC_LAZYLOADING = {
    _ticking: false,

    check: function () {

        if ( SC_LAZYLOADING._ticking ) {
            return;
        }

        SC_LAZYLOADING._ticking = true;

        var winH = document.documentElement.clientHeight || body.clientHeight;

        var updated = false;

        var els = document.getElementsByClassName('speedcache-lazy-hidden');

        [].forEach.call( els, function( el, index, array ) {

            var elemRect = el.getBoundingClientRect();

            if ( winH - elemRect.top > 0 ) {
                SC_LAZYLOADING.show( el );
                updated = true;
            }

        } );

        SC_LAZYLOADING._ticking = false;
        if ( updated ) {
            SC_LAZYLOADING.check();
        }
    },

    show: function( el ) {
        el.className = el.className.replace( /(?:^|\s)speedcache-lazy-hidden(?!\S)/g , '' );
        el.addEventListener( 'load', function() {
            el.className += " speedcache-lazy-loaded";
            SC_LAZYLOADING.customEvent( el, 'lazyloaded' );
        }, false );

        if ( null != el.getAttribute('data-speedcachelazy-srcset') ) {
            el.setAttribute( 'srcset', el.getAttribute('data-speedcachelazy-srcset') );
        }

        el.setAttribute( 'src', el.getAttribute('data-speedcachelazy-src') );

    },
    customEvent: function( el, eventName ) {
        var event;

        if ( document.createEvent ) {
            event = document.createEvent( "HTMLEvents" );
            event.initEvent( eventName, true, true );
        } else {
            event = document.createEventObject();
            event.eventType = eventName;
        }

        event.eventName = eventName;

        if ( document.createEvent ) {
            el.dispatchEvent( event );
        } else {
            el.fireEvent( "on" + event.eventType, event );
        }
    }
};

window.addEventListener( 'load', SC_LAZYLOADING.check, false );
window.addEventListener( 'scroll', SC_LAZYLOADING.check, false );
window.addEventListener( 'resize', SC_LAZYLOADING.check, false );
window.addEventListener( 'post-load', SC_LAZYLOADING.check, false );

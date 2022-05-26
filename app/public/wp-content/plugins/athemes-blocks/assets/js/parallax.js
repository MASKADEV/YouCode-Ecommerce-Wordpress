/*
 * aThemes Blocks
 * Parallax
 */

'use strict';

athemesBlocksDomReady(function () {
    var elements = document.querySelectorAll( '.athemes-blocks-block-container-bg-cover.athemes-blocks-block-container-bg-effect-parallax .athemes-blocks-background-image' );

    if( elements.length > 0 ) {
        window.addEventListener( 'scroll', function() {
            for( var i = 0; i < elements.length; i++ ) {
                var el = elements[i];
                if( isElementInViewport( el ) ) {
                    var top = el.getBoundingClientRect().top / 10;

                    el.style = 'transform: translate3d(0, '+ ( top ) +'px, 0);';
                }
            }
        } );
    }
});

function isElementInViewport(el) {
    var rect = el.getBoundingClientRect(),
        elemTop = rect.top,
        elemBottom = rect.bottom,
        isVisible = elemTop < window.innerHeight && elemBottom >= 0;

    return isVisible;
}

/**
 * Is the DOM ready?
 *
 * This implementation is coming from https://gomakethings.com/a-native-javascript-equivalent-of-jquerys-ready-method/
 *
 * @param {Function} fn Callback function to run.
 */

function athemesBlocksDomReady(fn) {
    if (typeof fn !== 'function') {
      return;
    }
  
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      return fn();
    }
  
    document.addEventListener('DOMContentLoaded', fn, false);
}
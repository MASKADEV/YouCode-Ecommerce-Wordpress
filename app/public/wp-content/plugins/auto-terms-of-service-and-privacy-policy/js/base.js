(function () {
    var handlers = [];
    var ran = false;

    function runHandlers() {
        if (ran) {
            return;
        }
        ran = true;
        for (var k in handlers) {
            handlers[k](jQuery);
        }
    }

    wp.domReady(runHandlers);

    function wpAutoTermsDomReady(fn) {
        if (ran) {
            fn(jQuery);
        } else {
            handlers.push(fn);
        }
    }

    window.wpAutoTermsDomReady = wpAutoTermsDomReady;
    var oldErrorHandler = window.onerror;
    window.onerror = function () {
        runHandlers();
        if (oldErrorHandler) {
            oldErrorHandler.apply(null, arguments);
        }
    }
    jQuery.readyException = runHandlers;
})();

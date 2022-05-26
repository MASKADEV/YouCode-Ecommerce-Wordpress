wpAutoTermsDomReady(function ($) {
    var COOKIE_EXPIRE_DAYS = 100 * 365;

    function maxZIndex() {
        return Math.max.apply(null,
            $.map($('body *'), function (e, n) {
                if ($(e).css('position') !== 'static')
                    return parseInt($(e).css('z-index')) || 1;
            }));
    }

    function getShadowProperty(e, modifier, prop, def) {
        var v = window.getComputedStyle($(e)[0], modifier)
            .getPropertyValue(prop);
        return typeof v === "undefined" ? def : v;
    }

    var oldBottomValue = parseInt(getShadowProperty("body", ":after", "bottom", 0));
    var topContainer = $("#wpautoterms-top-fixed-container");
    var bottomContainer = $("#wpautoterms-bottom-fixed-container");
    var z = maxZIndex();
    topContainer.css('z-index', z - (-1));
    bottomContainer.css('z-index', z - (-2));
    var topStaticContainer = $("#wpautoterms-top-static-container");
    $("body").prepend(topStaticContainer);
    topStaticContainer.css("margin-top", parseInt(getShadowProperty("body", ":before", "height", 0)) + "px");
    $("#wpautoterms-bottom-static-container").css("margin-bottom", parseInt(getShadowProperty("body", ":after", "height", 0)) + "px");

    function recalcContainers() {
        $("#wpautoterms-top-fixed-style,#wpautoterms-bottom-fixed-style").remove();
        var h = $("head");
        var topContainer = $("#wpautoterms-top-fixed-container");
        var bottomContainer = $("#wpautoterms-bottom-fixed-container");
        if (topContainer.length) {
            h.append('<style id="wpautoterms-top-fixed-style">body:before{top:' +
                parseInt(topContainer.height()) + 'px !important;}</style>');
        }
        if (bottomContainer.length) {
            h.append('<style id="wpautoterms-bottom-fixed-style">body:after{bottom:' +
                (oldBottomValue + parseInt(bottomContainer.height())) + 'px !important;}</style>');
        }
    }

    function setCookie(name, value, expire) {
        var d = new Date();
        var names = String(name).split(',');
        var values = String(value).split(',');
        d.setTime(d.getTime() + (expire * 24 * 60 * 60 * 1000));
        for (var idx in names) {
            name = names[idx];
            value = values[idx];
            document.cookie = name + "=" + encodeURIComponent(value) + "; expires=" + d.toUTCString() + "; path=/";
        }
    }

    function getCookie(name) {
        name = name.toLowerCase();
        var cookies = document.cookie.split(';');
        for (var k in cookies) {
            var el = cookies[k].split('=');
            if (el[0].trim().toLowerCase() === name) {
                return el[1];
            }
        }
        return null;
    }

    function populateUpdateBox(data) {
        var TEMPLATE = wp.template("wpautoterms-update-notice");
        var container = $("#wpautoterms-update-notice-placeholder");
        container.show();
        for (var k in data) {
            $(TEMPLATE(data[k])).appendTo(container);
        }
        $(document.body).trigger("post-load");
    }

    function handleUpdatesBox() {
        if (typeof wpautoterms_js_update_notice === "undefined") {
            return;
        }
        if (wpautoterms_js_update_notice.disable) {
            return;
        }
        var dc = getCookie(wpautoterms_js_update_notice.cache_detector_cookie);
        var isCached = dc === null || dc == wpautoterms_js_update_notice.cache_detected;
        setCookie(wpautoterms_js_update_notice.cache_detector_cookie,
            wpautoterms_js_update_notice.cache_detected,
            COOKIE_EXPIRE_DAYS);
        if (isCached) {
            $.post(wpautoterms_js_update_notice.ajaxurl, {
                action: wpautoterms_js_update_notice.action
            }).done(function (response) {
                if (typeof response !== "object") {
                    console.error("[WPAutoTerms][UpdateNotice] Response is not an object", response);
                } else {
                    populateUpdateBox(response.data);
                    bindClose();
                }
            }).fail(function (error) {
                console.error("[WPAutoTerms][UpdateNotice] Query error", error);
            });
        } else {
            var data = wpautoterms_js_update_notice.data;
            if (data == null || typeof data === "undefined") {
                return;
            }
            populateUpdateBox(data);
        }
    }

    recalcContainers();
    handleUpdatesBox();

    function handleClose() {
        var t = $(this);
        setCookie(t.data("cookie"), t.data("value"), COOKIE_EXPIRE_DAYS);
        var p1 = $(this).parent();
        var p2 = p1.parent();
        p1.remove();
        if (p2.html().length < 1) {
            p2.remove();
        }
        recalcContainers();
    }

    function bindClose() {
        $(".wpautoterms-notice-close").off("click", handleClose).on("click", handleClose);
    }

    function closeCookieNotice() {
        if (typeof wpautoterms_js_cookies_notice === "undefined") {
            return;
        }
        if (!wpautoterms_js_cookies_notice.disable && getCookie(wpautoterms_js_cookies_notice.cookie_name) != 1) {
            var entries = document.querySelectorAll("." + wpautoterms_js_cookies_notice.class);
            for (var k = 0; k < entries.length; ++k) {
                var el = entries[k];
                el.style.display = null;
            }
        }
    }

    bindClose();
    closeCookieNotice();
});

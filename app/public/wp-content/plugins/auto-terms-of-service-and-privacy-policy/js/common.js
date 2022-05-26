wpAutoTermsDomReady(function ($) {
// Copy of wp-admin/js/common.js makeNoticesDismissible()
    $("[data-wpautoterms-dismissible]").each(function () {
        var $el = $(this);
        var btnText = $el.data('wpautoterms-dismissible') || commonL10n.dismiss || '';
        var $button = $('<button type="button" class="wpautoterms-dismiss-button">' +
            '<span class="wpautoterms-dismiss-icon">' + btnText + '</span>' +
            '</button>');
        var action = $el.data('wpautoterms-action-id') || false;
        var data = $el.data('wpautoterms-action-data') || false;
        $button.on('click.wp-dismiss-notice', function (event) {
            event.preventDefault();
            $el.fadeTo(100, 0, function () {
                $el.slideUp(100, function () {
                    $el.remove();
                });
            });
            if (action !== false) {
                var args = {
                    action: action,
                    nonce: wpautotermsCommon.nonce[action],
                };
                if (data !== false) {
                    if (typeof data === "object") {
                        Object.assign(args, data);
                    }
                }
                $.post(ajaxurl, args);
            }
        });

        $el.append($button);
    });
});

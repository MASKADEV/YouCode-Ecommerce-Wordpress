wpAutoTermsDomReady(function () {
    var KEY_INPUT = "[data-control=wpautoterms_transfer_key]";
    var SITE_INPUT = "[data-control=wpautoterms_transfer_site]";
    var EMAIL_INPUT = "[data-control=wpautoterms_transfer_email]";
    var INPUTS = [EMAIL_INPUT, SITE_INPUT, KEY_INPUT];
    var XFER_BUTTON = "[data-control=wpautoterms_transfer_button]";
    var LICENSE_KEY_OPTION = "#wpautoterms_license";
    var _cb;
    var button;
    var inputs;
    var node;
    var query = false;

    function val(selector) {
        return node.find(selector).val() || "";
    }

    function onClickTransfer() {
        button.attr("disabled", "disabled");
        query = true;
        jQuery.post(ajaxurl, {
            action: wpautotermsLicenseTransfer.action,
            nonce: wpautotermsLicenseTransfer.nonce,
            key: val(KEY_INPUT),
            email: val(EMAIL_INPUT),
            site: val(SITE_INPUT),
        }).done(function (response) {
            _cb(true, response);
        }).fail(function (error) {
            _cb(false, error);
        }).always(function () {
            query = false;
            updateTransferButton();
        });
    }

    function areInputsGood() {
        var result = true;
        inputs.each(function () {
            if (this.value.length < 1) {
                result = false;
            }
        });
        return result;
    }

    function updateTransferButton() {
        if (button == null) {
            return;
        }
        if (!query && areInputsGood()) {
            button.removeAttr("disabled");
        } else {
            button.attr("disabled", "disabled");
        }
    }

    function cleanup() {
        button.off("click", onClickTransfer);
        inputs.off("input", updateTransferButton);
        button = null;
        inputs = null;
        node = null;
    }

    wpautotermsLicenseTransfer.transferShow = function (selector, cb) {
        if (button == null) {
            node = jQuery(selector).show();
            _cb = cb;
            node.find(KEY_INPUT).val(jQuery(LICENSE_KEY_OPTION).val());
            button = node.find(XFER_BUTTON);
            button.on("click", onClickTransfer);
            inputs = node.find(INPUTS.join(",")).on("input", updateTransferButton);
            return true;
        }
        return false;
    };
    wpautotermsLicenseTransfer.transferHide = function () {
        if (button != null) {
            node.hide();
            cleanup();
            return true;
        }
        return false;
    };
});

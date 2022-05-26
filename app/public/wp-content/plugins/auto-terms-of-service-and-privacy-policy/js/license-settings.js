wpAutoTermsDomReady(function () {
    var RECHECK_BUTTON = jQuery("#wpautoterms_recheck");
    var TRANSFER_ERROR = jQuery("[data-control=wpautoterms_transfer_error]");

    function onClickRecheck() {
        RECHECK_BUTTON.attr("disabled", "disabled");
        jQuery.post(ajaxurl, {
            action: wpautotermsLicenseSettings.action,
            nonce: wpautotermsLicenseSettings.nonce,
            apiKey: jQuery("#" + wpautotermsLicenseSettings.keyId).val()
        }).done(function (response) {
            if (typeof response !== "object") {
                alert(response);
            } else {
                wpautotermsLicenseSettings.status = response.status;
                jQuery("#wpautoterms_license_upgrade").toggle(response.maxSites !== 0);
                jQuery("#wpautoterms_websites_limit_row").toggle(response.shouldShowWebsites);
                jQuery("#wpautoterms_license_status").text(response.licenseType);
                jQuery("#wpautoterms_websites_limit").text(response.websites);
                jQuery("#wpautoterms_license_summary").text(response.summary);
                updateTransferOptions();
            }
        }).fail(function (error) {
            console.error(error);
            alert(error.statusText);
        }).always(function () {
            RECHECK_BUTTON.removeAttr("disabled");
        });
    }

    function updateTransferOptions() {
        jQuery("#wpautoterms_transfer").toggle(wpautotermsLicenseSettings.status === "max_sites");
        jQuery("#wpautoterms_transfer_address").toggle(wpautotermsLicenseSettings.status === "ip_mismatch");
    }

    function transferResult(querySuccess, response) {
        if (!querySuccess || typeof response !== "object") {
            console.error(result);
            alert(error.statusText);
            return;
        }
        if (response.result === "success") {
            wpautotermsLicenseTransfer.transferHide();
            onClickRecheck();
        } else {
            TRANSFER_ERROR.show();
        }
    }

    function onClickTransferBlock(selector, e) {
        e.preventDefault();
        e.stopPropagation();
        TRANSFER_ERROR.hide();
        if (!wpautotermsLicenseTransfer.transferShow(selector, transferResult)) {
            wpautotermsLicenseTransfer.transferHide();
        }
    }

    RECHECK_BUTTON.click(onClickRecheck);
    jQuery("#wpautoterms_transfer_open").click(onClickTransferBlock.bind(null, "#wpautoterms_request_transfer"));
    jQuery("#wpautoterms_transfer_address_open").click(onClickTransferBlock.bind(null, "#wpautoterms_request_transfer_address"));
    updateTransferOptions();
});

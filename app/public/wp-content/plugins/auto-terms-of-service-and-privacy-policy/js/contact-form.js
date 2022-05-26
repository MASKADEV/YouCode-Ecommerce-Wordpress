wpAutoTermsDomReady(function () {
    var SITE_INFO_NONE = 0;
    var PREVIEW_SIZE = 80;
    var valid = false;

    var FORM = jQuery("#wpautoterms_contact");
    var EMAIL_NOTICE = FORM.find("[data-name='email'][data-type='notice']");
    var FORM_NOTICE = FORM.find("[data-name='form'][data-type='notice']");
    var URL_NOTICE = FORM.find("[data-name='site_url'][data-type='notice']");
    var SITE_INFO_SELECTOR = FORM.find("[name='site_info_preview']");
    var SITE_INFO_TEXT = FORM.find("[name='site_info']");
    var SITE_INFO = FORM.find("[data-name='site_info_preview'][data-type='info']");
    var SITE_INFO_TEMPLATE = wp.template("wpautoterms-site-info");
    var SUBMIT = FORM.find("input[type='submit']");
    var SENDING = jQuery("#wpautoterms_sending");

    function collectData() {
        var si = SITE_INFO_SELECTOR.val();
        return {
            site_url: FORM.find("[name='site_url']").val(),
            site_name: FORM.find("[name='site_name']").val(),
            email: FORM.find("[name='email']").val(),
            text: FORM.find("[name='text']").val(),
            site_info: si,
            site_info_text: wpautotermsContact.siteInfo[si]
        };
    }

    function validate() {
        var data = collectData();
        SITE_INFO_TEXT.val(data.site_info_text);
        var formError = data.site_url.length < 1 || data.site_name.length < 1 || data.email.length < 1 || data.text.length < 1;
        var emailError = data.email.split('@').length !== 2;
        var urlError = data.site_url.split("..").length > 1 || data.site_url.split(".").length < 2;
        FORM_NOTICE.toggle(formError);
        EMAIL_NOTICE.toggle(emailError);
        URL_NOTICE.toggle(urlError);
        if (formError || emailError || urlError) {
            SUBMIT.prop('disabled', 'disabled');
        } else {
            SUBMIT.prop('disabled', false);
        }
    }

    function submit(e) {
        if (valid) {
            return true;
        }
        var data = collectData();
        SUBMIT.prop('disabled', 'disabled');
        SENDING.show();
        data = Object.assign(data, {
            action: wpautotermsContact.id,
            nonce: wpautotermsContact.nonce
        });
        jQuery.post(ajaxurl, data).done(function (response) {
            if (typeof response !== "object") {
                console.error("[WPAutoTerms][ContactForm] Response is not an object", response);
            } else {
                if (response.valid) {
                    valid = true;
                    FORM.submit();
                } else {
                    wpautoterms.setNotice(response.message, "updated error");
                }
            }
        }).fail(function (error) {
            console.error("[WPAutoTerms][ContactForm] Query error", error);
        }).always(function () {
            SUBMIT.prop('disabled', false);
            SENDING.hide();
            validate();
        });
        return false;
    }

    function createExpanders(node) {
        node.find("[data-type='expander']").click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            var node = e.target;
            while (typeof node !== "undefined" && node !== null) {
                if (node.dataset.target) {
                    jQuery(node.dataset.target).toggle();
                    break;
                }
                node = node.parentNode;
            }
        });
    }

    function updateCounter(c, text) {
        var max = parseInt(c.data('max'));
        var cur = text.val();
        var l = cur.length;
        if (l > max) {
            l = max;
            text.val(cur.substr(0, l));
        }
        c.text(l + ' / ' + max);
    }

    function createCounters(node) {
        node.find("[data-type='char-counter']").each(function (idx, el) {
            el = jQuery(el);
            el.show();
            var t = jQuery(el.data('target'));
            t.on("input", updateCounter.bind(null, el, t)).trigger("input");
        });
    }

    FORM.submit(submit);
    FORM.find("input,textarea").on("input", validate).keyup(validate);
    SITE_INFO_SELECTOR.change(function () {
        var value = SITE_INFO_SELECTOR.val();
        if (value == SITE_INFO_NONE) {
            SITE_INFO.hide();
            return;
        }
        var si = SITE_INFO.get(0);
        while (si.firstChild) {
            si.removeChild(si.firstChild);
        }
        var full = wpautotermsContact.siteInfo[value];
        var preview = full.substr(0, PREVIEW_SIZE);
        if (full.length !== preview.length) {
            preview += "...";
        } else {
            full = null;
        }
        SITE_INFO.show();
        SITE_INFO.html(SITE_INFO_TEMPLATE({preview: preview, full: full}));
        createExpanders(SITE_INFO);
    }).trigger("change");
    createCounters(FORM);

    validate();
    jQuery("[data-type=accordion]").accordion();
    var CONTACT_BUTTON = jQuery("#wpautoterms_contact_button");
    var HIDE_BUTTON = jQuery("#wpautoterms_form_container_hide");
    var CONTACT_FORM = jQuery("#wpautoterms_form_container");
    CONTACT_BUTTON.click(function () {
        CONTACT_BUTTON.hide();
        CONTACT_FORM.show();
    });
    HIDE_BUTTON.click(function () {
        CONTACT_BUTTON.show();
        CONTACT_FORM.hide();
    });
});

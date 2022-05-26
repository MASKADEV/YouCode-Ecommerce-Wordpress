var wpautoterms = wpautoterms || {};
wpautoterms.NOTICE_TEMPLATE = '<div$CLASSES><p><strong>$NOTICE</strong></p></div>';
wpautoterms.NOTICE_AREA = jQuery("#wpautoterms_notice");

wpautoterms.setNotice = function (notice, classes) {
    classes = classes || "";
    if (classes.length) {
        classes = ' class="' + classes + '"';
    }
    wpautoterms.NOTICE_AREA.html(wpautoterms.NOTICE_TEMPLATE.replace("$NOTICE", notice).replace("$CLASSES", classes));
};

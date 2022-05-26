wpAutoTermsDomReady(function () {
    var ON_OFF_SELECT = jQuery("select[name='" + wpautotermsLegacy.onOffName + "']");
    var ON_OFF_NOTICE = jQuery("[data-type='notice'][data-name='" + wpautotermsLegacy.onOffName + "']");
    var onOffValue;
    var requiredEmpty = {};

    function updateOnOff() {
        var shouldDisable = Object.values(requiredEmpty).some(function (x) {
            return x;
        });
        ON_OFF_NOTICE.toggle(shouldDisable);
        if (shouldDisable) {
            ON_OFF_SELECT.prop('disabled', "disabled").val(wpautotermsLegacy.onOffValueOff);
        } else {
            ON_OFF_SELECT.prop('disabled', false).val(onOffValue);
        }
    }

    function onChangeOnOff(e) {
        onOffValue = ON_OFF_SELECT.val();
    }

    function onChange(e) {
        var value = e.target.value === "";
        jQuery("[data-type='notice'][data-name='" + e.target.name + "']").toggle(value);
        requiredEmpty[e.target.name] = value;
        updateOnOff();
    }

    ON_OFF_SELECT.change(onChangeOnOff).trigger('change');
    jQuery("[data-required='1']").each(function (idx, el) {
        jQuery("[data-type='notice'][data-name='" + el.name + "']").addClass('wpautoterms-option-required')
            .text(wpautotermsLegacy.required);
    }).keyup(onChange).change(onChange).trigger('change');
    ON_OFF_NOTICE.addClass('wpautoterms-option-required').text(wpautotermsLegacy.onOffNotice);
});

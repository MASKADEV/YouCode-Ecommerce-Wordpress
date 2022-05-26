wpAutoTermsDomReady(function () {
    wpautotermsCountry.initCountrySelector = function () {
        var COUNTRIES = jQuery("[data-type='country-selector']");
        var LOCALES = [wpautotermsCountry.locale.split("_")[0]];
        var savedOptions = {};
        var selectedCountry = wpautotermsCountry.country;

        function sortSelect(selector, languages) {
            var el = jQuery(selector);
            var selected = el.val();
            var options = el.find("option");
            var arr = options.map(function (idx, x) {
                return [[x.innerText, x.value]];
            }).get();
            arr.sort(function (x, y) {
                return x[0].localeCompare(y[0], languages);
            });

            options.each(function (idx, x) {
                x.value = arr[idx][1];
                x.innerText = arr[idx][0];
            });
            el.val(selected);
        }

        var STATE_INPUT = jQuery("[data-type='state-selector']");
        var stateRow = jQuery("[data-type='state-row']");
        if (stateRow.length < 1) {
            stateRow = STATE_INPUT.parent().parent();
        }
        if (selectedCountry) {
            savedOptions[selectedCountry] = wpautotermsCountry.state;
        }

        function updateStates() {
            var states = wpautotermsStates.states[COUNTRIES.val()] || [];
            var state = STATE_INPUT.val();
            if (state !== null) {
                savedOptions[selectedCountry] = STATE_INPUT.val();
            }
            if (states.length < 1) {
                stateRow.hide();
                STATE_INPUT.html("");
                STATE_INPUT.val("");
                // STATE_INPUT.removeAttr("required");
                return;
            }
            stateRow.show();
            // STATE_INPUT.attr("required", "required");
            var options = states.map(function (x) {
                return '<option value="' + x + '">' + wpautotermsStates.translations[x] + '</option>';
            });
            selectedCountry = COUNTRIES.val();
            STATE_INPUT.html(options.join("\n"));
            sortSelect(STATE_INPUT, LOCALES);
            if (Object.keys(savedOptions).indexOf(selectedCountry) >= 0) {
                STATE_INPUT.val(savedOptions[selectedCountry]);
            } else {
                STATE_INPUT.val(STATE_INPUT.find("option:first").val());
            }
        }

        var options = Object.keys(wpautotermsStates.states).map(function (x) {
            return '<option value="' + x + '">' + wpautotermsStates.translations[x] + '</option>';
        });
        COUNTRIES.html(options.join("\n"));
        COUNTRIES.val(selectedCountry);

        sortSelect(COUNTRIES, LOCALES);
        COUNTRIES.show();
        STATE_INPUT.show();
        COUNTRIES.change(updateStates).trigger("change");
    };
    if (jQuery("[data-type='country-selector']").length > 0) {
        wpautotermsCountry.initCountrySelector();
    }
});

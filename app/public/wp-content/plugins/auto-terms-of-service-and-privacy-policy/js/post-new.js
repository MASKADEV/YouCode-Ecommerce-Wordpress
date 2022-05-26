wpAutoTermsDomReady(function ($) {
    var CREATE_SELECTOR = $("#legal-page-create-selector").show();
    
    $('#post').attr('novalidate', true);
    console.log('#post novalidate');

    $("[data-type='dismiss-button']").click(function (e) {
        var el = $(e.target);
        var parent;
        while (true) {
            parent = el.parent();
            if (parent === null || (typeof parent === "undefined")) {
                console.error("wpautoterms-is-dismissible parent not found.");
                return;
            }
            if (parent.hasClass("wpautoterms-is-dismissible")) {
                break;
            }
        }
        parent.hide();
        if ($("[data-type='permanent-dismiss'][data-name='" + el.data('name') + "']").prop('checked')) {
            $.post(ajaxurl, {
                action: 'settings_warning_disable',
                nonce: wpautotermsCommon.nonce['settings_warning_disable'],
                state: 1
            })
        }
    });

    var PAGE_ID = wpautotermsPostNew.page_id;
    if (PAGE_ID.length > 0) {
        var legalPageDep = wpautotermsPostNew.dependencies[PAGE_ID];
        var CONTAINER = $('#legal-page-container');

        function updateDependencies() {
            for (var section in legalPageDep) {
                var s = jQuery("#" + section);
                var args = legalPageDep[section];
                var state;
                var c = jQuery("#" + args[0] + ":visible");
                if (c.length) {
                    if (typeof (args[1]) === "boolean") {
                        state = c.prop("checked");
                    } else {
                        state = c.val();
                    }
                }
                console.log(state, args[1]);
                var show = args[2] === "show";
                if (state !== args[1]) {
                    show = !show;
                }
                if (show) {
                    s.show();
                } else {
                    s.hide();
                }
                updateSectionRequirements(section, show);
            }
        }

        function updateSectionRequirements(sectionId, visible) {
            // jQuery("#" + sectionId + " input[type=radio]").prop("required", visible);
            console.log('updateSectionRequirements: ' + sectionId + ' ' + visible);
        }

        var hidden = wpautotermsPostNew.hidden[PAGE_ID];
        $(hidden.map(function (x) {
            return "#" + x;
        }).join(",")).hide();
        CONTAINER.find("input[type=radio],input[type=checkbox]").click(updateDependencies);
        
        // CONTAINER.find("input[type=radio]:visible").prop("required", true);
        console.log('CONTAINER input[type=radio]:visible');
        
        CONTAINER.find("input[type='submit'],button[type='submit']").click(function (e) {
            CONTAINER.find("input[type=radio]").each(function () {
                var t = $(this);
                var v = t.val();
                if (v === "yes") {
                    t.val("legal-page-radio-yes");
                } else if (v === "no") {
                    t.val("legal-page-radio-no");
                }
            });
            return true;
        });
        wpautotermsCountry.initCountrySelector();
        updateDependencies();
        $("input[name=post_title]:not([data-wpautoterms])").remove();
    }
});

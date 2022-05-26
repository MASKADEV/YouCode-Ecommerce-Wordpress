wpAutoTermsDomReady(function ($) {
    $(".wpautoterms-color-selector").wpColorPicker();
    $(".wpautoterms-options-select-combo select").each(function () {
        var t = jQuery(this);
        var id = t.attr("id");
        var custom = t.find(".wpautoterms-custom-value-" + id);
        var tf = jQuery("input[name='custom_" + id + "']");
        t.change(function () {
            if (custom.prop("selected")) {
                tf.show();
            } else {
                tf.hide();
            }
        });
        tf.change(function () {
            custom.val(tf.val());
        });
        t.trigger("change");
        tf.trigger("change");
    });
    $(".wpautoterms-options-select-tag").each(function () {
        var t = jQuery(this);
        var id = t.attr("id");
        var n = jQuery(".wpautoterms-options-new-tag[id='new-tag-" + id + "']");
        t.change(function () {
            if (parseInt(t.val()) === 0) {
                n.show();
            } else {
                n.hide();
            }
        });
    });
    $(".wpautoterms-option-dependent").each(function () {
        var t = jQuery(this);
        var p = t.parent().parent();
        var depType = t.data("type");
        var depVal = t.data("value");
        var s = jQuery("#" + t.data("source"));
        if (depVal === "show") {
            p.hide();
            t.show();
        }
        s.change(function () {
            var show = depType === "show";
            if (s.val() !== depVal) {
                show = !show;
            }
            if (show) {
                p.show();
                t.show();
            } else {
                p.hide();
            }
        });
        s.trigger("change");
    });

    function send_to_editor(editorId, html, replace) {
        var editor;
        if (typeof tinymce !== 'undefined') {
            editor = tinymce.get(editorId);
        }
        if (editor && !editor.isHidden()) {
            editor.execCommand(replace ? "mceSetContent" : "mceInsertContent", false, html);
        } else {
            if (replace) {
                document.getElementById(editorId).value = html;
            } else {
                document.getElementById(editorId).value += html;
            }
        }
    }

    function inject_action(el, replace) {
        var t = jQuery(el);
        var id = t.data("editor");
        var data = t.data("data");
        t.click(function () {
            send_to_editor(id, data, replace);
        });
    }

    $(".wpautoterms-shortcodes-source a").each(function () {
        inject_action(this, false);
    });
    $(".wpautoterms-replace-source a").each(function () {
        inject_action(this, true);
    });
});

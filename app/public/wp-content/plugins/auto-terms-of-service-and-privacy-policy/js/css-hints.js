wpAutoTermsDomReady(function () {
    var targets = {};

    function onClick(e) {
        e.preventDefault();
        e.stopPropagation();
        var ds = e.target.dataset;
        var value = ds.value + " {\n}";
        if (typeof targets[ds.target] === "undefined") {
            var target = jQuery("#" + ds.target);
            var text = target.val();
            if (text.length > 0) {
                text += "\n";
            }
            target.val(text + value);
            target.scrollTop(target[0].scrollHeight);
        } else {
            var cm = targets[ds.target];
            if (cm.getValue().length > 0) {
                value = "\n" + value;
            }
            cm.replaceRange(value, CodeMirror.Pos(cm.lastLine()));
        }
    }

    function isValidKey(code) {
        return (code > 47 && code < 58) // 0-9
            || (code > 64 && code < 91) // a-z
            || (code > 95 && code < 112) // numpad
            || (code > 185 && code < 193) // ;=,-./`
        // || (code > 218 && code < 223);   // [\]'
    }

    function autocomplete(cm, e) {
        if (!cm.state.completionActive && isValidKey(e.keyCode)) {
            CodeMirror.commands.autocomplete(cm, null, {completeSingle: false});
        }
    }

    jQuery("[data-control=css-hint]").click(onClick);
    jQuery("[data-codemirror]").each(function (_, x) {
        var target = x.id;
        var cm = CodeMirror.fromTextArea(x, {
            lineNumbers: true,
            mode: "css",
            hintOptions: {hint: CodeMirror.hint.css},
            styleActiveLine: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            highlightSelectionMatches: {showToken: true, annotateScrollbar: true}
        });
        cm.on("keyup", autocomplete);
        targets[target] = cm;
    });
});

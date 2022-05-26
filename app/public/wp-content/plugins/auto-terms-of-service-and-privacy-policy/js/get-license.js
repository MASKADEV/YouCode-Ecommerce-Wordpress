wpAutoTermsDomReady(function () {
    var form = document.createElement("form");
    form.action = wpautotermsGetLicense.url;
    form.method = "POST";
    var INPUTS = {
        referrer: wpautotermsGetLicense.key
    };
    for (var idx in INPUTS) {
        var el = document.createElement("input");
        el.type = "hidden";
        el.value = INPUTS[idx];
        el.name = idx;
        form.appendChild(el);
    }

    function redirect() {
        form.submit();
    }

    document.body.appendChild(form);

    setTimeout(redirect, 1);
});

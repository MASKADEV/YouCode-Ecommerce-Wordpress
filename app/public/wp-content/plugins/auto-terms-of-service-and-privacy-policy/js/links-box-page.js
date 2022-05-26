wpAutoTermsDomReady(function ($) {
    var LINKS = $("#links_order");
    var OPTION = $("[name$=links_order]");

    function onUpdate(event, ui) {
        var sorted = LINKS.sortable("toArray", {attribute: "data-id"});
        OPTION.val(sorted.join(","));
    }

    LINKS.sortable({}).on("sortupdate", onUpdate).disableSelection();
});

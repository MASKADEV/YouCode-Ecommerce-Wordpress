wpAutoTermsDomReady( function( $ ){
    $( '.inline-row-action-summary' ).each( function(){
        var pSpan = $(this).parent();
        var pTarget = pSpan.parent();
        $(this).detach();
        pSpan.remove();
        pTarget.before($(this));
    });
});

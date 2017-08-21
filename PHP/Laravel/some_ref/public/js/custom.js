(function($) {
    $(window).on("load", function() {

        $(".task-content").mCustomScrollbar({
            setHeight: 480,
            theme: "dark-3"
        });
    });
})(jQuery);


$(document).ready(function() {
    var trigger = $('.hamburger'),
        overlay = $('.overlay'),
        isClosed = false;
    trigger.click(function() {
        hamburger_cross();
    });

    function hamburger_cross() {
        if (isClosed == true) {
            overlay.hide();
            trigger.removeClass('is-open');
            trigger.addClass('is-closed');
            isClosed = false;
        } else {
            overlay.show();
            trigger.removeClass('is-closed');
            trigger.addClass('is-open');
            isClosed = true;
        }
    }

    $('[data-toggle="offcanvas"]').click(function() {
        $('#bodywrapper').toggleClass('toggled');
    });
});


function hideSideMenu() {
    $('#app').addClass('toggled');
}
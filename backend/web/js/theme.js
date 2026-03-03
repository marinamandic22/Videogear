

let theme = {};

$ = jQuery.noConflict();

$(function () {
    theme.init();
});

theme = (function ($) {
    return {
        init: function () {
            let body = $(document).find('body');
            let isLargeScreen = screen.width > 768;
            let date = new Date();
            date.setTime(date.getTime() + (7*24*60*60*1000));

            if(!isLargeScreen) {
                document.cookie = "sidebarCollapsed=true; expires=" + date.toUTCString() + "; path=/";
            }

            $(document).on('click', '.btn-toggle-sidebar', function (e) {
                body.toggleClass('sidebar-collapsed');

                if(!isLargeScreen) {
                    return;
                }

                let isCollapsed = body.hasClass('sidebar-collapsed');

                document.cookie = "sidebarCollapsed=" + isCollapsed + "; expires=" + date.toUTCString() + "; path=/";
            });
        }
    };
})
(jQuery);

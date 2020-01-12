// call with: $('#button-id').collapsible('#container-id');
(function($) {
    'use strict';

    $.fn.collapsible = function(target) {
        $(target).css('display', 'none');
        $(this).click(function() {
            var status = $(target).css('display');
            if (status == 'none') {
                $(target).css('display', 'block');
            } else {
                $(target).css('display', 'none');
            }
        });
    };
})(jQuery);

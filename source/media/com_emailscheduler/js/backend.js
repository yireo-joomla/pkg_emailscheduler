/**
 * Joomla! component EmailScheduler
 *
 * @author Yireo (info@yireo.com)
 * @package EmailScheduler
 * @copyright Copyright 2017
 * @link https://www.yireo.com
 */

jQuery(document).ready(function () {
    (function ($) {
        $('.emailbox').parent().find('.chosen-container input[type=text]').live('keydown', function (evt) {
            var stroke = event.which;
            if (stroke === 188 || stroke === 9) {
                var currentValue = this.value;
                if (validateEmail(currentValue)) {
                    var $selectBox = $(this).parent().parent().parent().parent().find('.emailbox');
                    $selectBox.append($('<option></option>')
                        .val(currentValue)
                        .attr('selected', 'selected')
                        .html(currentValue)
                    ).trigger('chosen:updated');

                    event.preventDefault();
                }
            }
        });
    })(jQuery);
});

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}





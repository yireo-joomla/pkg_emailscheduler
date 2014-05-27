/*
 * Joomla! component EmailScheduler
 *
 * @author Yireo (info@yireo.com)
 * @package EmailScheduler
 * @copyright Copyright 2014
 * @link http://www.yireo.com
 */

jQuery(function($) {

    function hideshowTypes()
    {
        $('[name="item[type_usergroup]"]').attr('disabled', 'disabled');
        $('[name="item[type_specific]"]').attr('disabled', 'disabled');
        var type = $('[name="item[type_' + $('#item_type').val() + ']"]');
        if(type) {
            type.removeAttr('disabled');
        }
    }

    hideshowTypes();
    $('#item_type').change(function() {
        hideshowTypes();
    });
});


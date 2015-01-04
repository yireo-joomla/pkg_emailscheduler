/*
 * Joomla! component EmailScheduler
 *
 * @author Yireo (info@yireo.com)
 * @package EmailScheduler
 * @copyright Copyright 2015
 * @link http://www.yireo.com
 */

jQuery(function($) {

    function hideshowTypes()
    {
        $('[name="actions[type_usergroup]"]').attr('disabled', 'disabled');
        $('[name="actions[type_specific]"]').attr('disabled', 'disabled');
        var type = $('[name="actions[type_' + $('#actions_type').val() + ']"]');
        if(type) {
            type.removeAttr('disabled');
        }
    }

    hideshowTypes();
    $('#actions_type').change(function() {
        hideshowTypes();
    });
});


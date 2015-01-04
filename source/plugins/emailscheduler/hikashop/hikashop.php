<?php
/**
 * EmailScheduler plugin - HikaShop
 *
 * @author Yireo (info@yireo.com)
 * @package EmailScheduler
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// Import the parent class
require_once JPATH_SITE.'/administrator/components/com_emailscheduler/plugins/trigger.php';

/**
 * EmailScheduler plugin - HikaShop
 */
class PlgEmailschedulerHikashop extends EmailschedulerPluginTrigger
{
    /**
     * Constructor
     *
     * @access protected
     * @param object $subject The object to observe
     * @param array $config  An array that holds the plugin configuration
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onEmailschedulerTriggerPrepareForm(&$form, &$data)
    {
		JForm::addFormPath(__DIR__ . '/form');
		$form->loadFile('trigger');

        if(is_array($data['condition']) && isset($data['condition']['hikashop.product'])) {
            $data['hikashop.product'] = $data['condition']['hikashop.product'];
        }
    }

    public function onEmailschedulerTriggerSaveBefore(&$data)
    {
        if(isset($data['jform']['params']['product_id'])) {
            $product_id = $data['jform']['params']['product_id'];
            $data['condition']['hikashop.product'] = $product_id;
        }

        return true;
    }
}

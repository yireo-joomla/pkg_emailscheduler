<?php
/**
 * EmailScheduler plugin - VirtueMart3
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
 * EmailScheduler plugin - VirtueMart3
 */
class PlgEmailschedulerVirtuemart3 extends EmailschedulerPluginTrigger
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
    }

    public function onEmailschedulerTriggerSaveBefore(&$data)
    {
        return true;
    }
}

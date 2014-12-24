<?php
/**
 * Emailscheduler System Plugin
 *
 * @author Yireo (info@yireo.com)
 * @package Emailscheduler
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * System Plugin to schedule a mail whenever a new user registers
 *
 * @package Emailscheduler
 */
class PlgSystemEmailscheduler extends JPlugin
{
	/**
	 * Event method onAfterRender
	 *
	 * @return  void
	 */
	public function onAfterRender()
    {
        $body = JResponse::getBody();
        $script = $this->getScript();

        $body = str_replace('</body>', $script . '</body>', $body);
        JResponse::setBody($body);
    }

    public function getScript()
    {
        JHtml::_('jquery.framework');
        $url = JURI::root().JRoute::_('index.php?option=com_emailscheduler&tmpl=component&format=raw&task=ajax');

        $script = '<script>jQuery(function(){jQuery.get("'.$url.'");});</script>';
        return $script;
    }
}

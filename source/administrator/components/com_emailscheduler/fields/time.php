<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author    Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com
 */

defined('JPATH_BASE') or die;

/**
 * Form field that adds a time selector
 */
class JFormFieldTime extends JFormFieldText
{
	/**
	 * @var string
	 */
	protected $type = 'Time';

	/**
	 * Method to get the field input markup.
	 *
	 * @return string $html   The field input markup.
	 */
	protected function getInput()
	{
		$html = parent::getInput();
		$html = str_replace('class="inputbox', 'class="ui-timepicker-input ', $html);

		$script   = "\njQuery(function() { jQuery('#" . $this->id . "').timepicker({timeFormat:'H:i:s'}); });";
		$document = JFactory::getDocument();

		$document->addStyleSheet(JURI::root() . 'media/com_emailscheduler/css/jquery.timepicker.css');
		$document->addScript(JURI::root().'media/com_emailscheduler/js/jquery.timepicker.min.js');
		$document->addScriptDeclaration($script);

		return $html;
	}
}

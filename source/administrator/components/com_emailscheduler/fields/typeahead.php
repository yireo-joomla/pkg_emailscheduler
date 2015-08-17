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

use Joomla\Registry\Registry;

/**
 * Form field that adds a modal article picker plus a reset-button
 */
class EmailschedulerFormFieldTypeahead extends JFormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'Typeahead';

	/**
	 * Method to get the field input markup.
	 *
	 * @return    string    The field input markup.
	 */
	protected function getInput()
	{
		// Setup variables for display
		$plugin = (string) $this->element['plugin'];

		$ajaxLink = JUri::root() . '/administrator/index.php?option=com_emailscheduler&task=autocomplete&plugin=' . $plugin;
		$ajaxLink .= '&' . JSession::getFormToken() . '=1';

		// Load other values
		$id = $this->id;
		$minTermLength = 1;
		$selector = '#' . $id;

		// Tags field ajax
		$chosenAjaxSettings = new Registry(
			array(
				'selector'      => $selector,
				'type'          => 'GET',
				'url'           => $ajaxLink,
				'dataType'      => 'json',
				'jsonTermKey'   => 'like',
				'minTermLength' => $minTermLength
			)
		);
		JHtml::_('formbehavior.ajaxchosen', $chosenAjaxSettings);

		$attr = '';
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';

		$options = array();
		$options[] = array('value' => 'all', 'text' => JText::_('JALL'));
		$options[] = array('value' => 'none', 'text' => JText::_('JNONE'));

		if (!empty($this->value) && is_array($this->value))
		{
			JPluginHelper::importPlugin('emailscheduler');
			$event = 'onEmailscheduler' . ucfirst($plugin) . 'Search';
			$dispatcher = JEventDispatcher::getInstance();
			$ids = array();

			foreach ($this->value as $value)
			{
				$ids[] = (int) $value;
			}

			$matches = $dispatcher->trigger($event, array(null, $ids));

			foreach ($matches[0] as $matchId => $matchLabel)
			{
				$options[] = array('value' => $matchId, 'text' => $matchLabel);
			}
		}

		$html = array();
		$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);

		return implode($html);
	}
}

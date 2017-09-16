<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author    Yireo (info@yireo.com)
 * @copyright Copyright 2017
 * @license   GNU Public License
 * @link      https://www.yireo.com
 */

defined('JPATH_BASE') or die;

/**
 * Form field that adds a modal article picker plus a reset-button
 */
class YireoFormFieldEmail extends JFormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'Email';

	/**
	 * Method to get the field input markup.
	 *
	 * @return    string    The field input markup.
	 */
	protected function getInput()
	{
		JHtml::_('jquery.framework');

		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::root() . 'media/com_emailscheduler/css/chosen.min.css');
		$document->addScript(JUri::root() . 'media/com_emailscheduler/js/chosen.jquery.min.js');
		$document->addScriptDeclaration('jQuery(function($) { $(".emailbox").chosen({});});');

		$html = [];

		$currentOptions = $this->getCurrentOptions($this->value);
		$allOptions     = $this->getAllOptions();

		$html[] = JHTML::_('select.genericlist', $allOptions, $this->name . '[]', 'class="inputbox emailbox"  multiple="multiple"', 'value', 'text', $currentOptions);

		return implode('', $html);
	}

	/**
	 *
	 * @return array
	 */
	protected function getAllOptions()
	{
		$result = [];

		$usergroups = $this->getUserGroups();
		foreach ($usergroups as $usergroup)
		{
			$id       = 'group:' . $usergroup->id;
			$result[$id] = JHTML::_('select.option', $id, $usergroup->title);
		}

		$result = array_merge($result, $this->getCurrentOptions($this->value));

		return $result;
	}

	/**
	 * @param $value
	 *
	 * @return array
	 */
	protected function getCurrentOptions($value)
	{
		$result = [];
		$values = explode(',', $value);

		foreach ($values as $value)
		{
			$value = trim($value);
			if (!empty($value))
			{
				$title = $value;
				if (preg_match('/^group:([0-9]+)/', $value, $match))
				{
					$title = $this->getUserGroupTitleById($match[1]);
				}

				$result[$value] = JHTML::_('select.option', $value, $title);
			}
		}

		return $result;
	}

	/**
	 * @param $groupId
	 *
	 * @return string
	 *
	 * @since version
	 */
	protected function getUserGroupTitleById($groupId)
	{
		$usergroups = $this->getUserGroups();

		foreach ($usergroups as $usergroup)
		{
			if ($usergroup->id == $groupId)
			{
				return $usergroup->title;
			}
		}

		return 'group:' . $groupId;
	}

	/**
	 *
	 * @return array
	 */
	protected function getUserGroups()
	{
		static $rows;

		if (empty($rows))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName(['id', 'title']))
				->from($db->quoteName('#__usergroups'));

			$db->setQuery($query);
			$rows = $db->loadObjectList();
		}

		return $rows;
	}
}

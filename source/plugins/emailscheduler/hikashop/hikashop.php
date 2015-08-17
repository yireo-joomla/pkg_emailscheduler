<?php
/**
 * EmailScheduler plugin - HikaShop
 *
 * @author    Yireo (info@yireo.com)
 * @package   EmailScheduler
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Import the parent class
require_once JPATH_SITE . '/administrator/components/com_emailscheduler/plugins/trigger.php';

/**
 * EmailScheduler plugin - HikaShop
 */
class PlgEmailschedulerHikashop extends EmailschedulerPluginTrigger
{
	/**
	 * Constructor
	 *
	 * @param object $subject The object to observe
	 * @param array  $config  An array that holds the plugin configuration
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Event triggered when setting up the form
	 *
	 * @param JForm $form
	 * @param array $data
	 */
	public function onEmailschedulerTriggerPrepareForm(&$form, &$data)
	{
		JForm::addFormPath(__DIR__ . '/form');
		$form->loadFile('trigger');

		if (is_array($data['condition']) && isset($data['condition']['hikashop.product']))
		{
			$data['hikashop.product'] = $data['condition']['hikashop.product'];
		}
	}

	/**
	 * Event triggered when saving the form-data of a trigger
	 *
	 * @param array $data
	 *
	 * @return bool
	 */
	public function onEmailschedulerTriggerSaveBefore(&$data)
	{
		return true;
	}

	/**
	 * Event triggered when autocompleting the possible product IDs
	 *
	 * @param string $query
	 * @param array $ids
	 *
	 * @return array
	 */
	public function onEmailschedulerHikashopSearch($search = null, $ids = array(), $limit = 0)
	{
		$db = JFactory::getDbo();

		/** @var JDatabaseQuery $query */
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('product_id', 'product_name')));
		$query->from($db->quoteName('#__hikashop_product'));

		if (!empty($search))
		{
			$searchQuery = $db->quote('%' . $db->escape($search) . '%', false);
			$query->where($db->quoteName('product_name') . ' LIKE ' . $searchQuery);
		}

		if (!empty($ids))
		{
			$query->where($db->quoteName('product_id') . ' IN (' . implode(',', $ids) . ')');
		}

		if ($this->params->get('limit_ajax', 20) > 0)
		{
			$query->limit($limit);
		}

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$matches = array();

		foreach	($rows as $row)
		{
			$matches[$row->product_id] = $row->product_name;
		}

		return $matches;
	}
}

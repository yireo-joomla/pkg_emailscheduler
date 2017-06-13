<?php
/**
 * EmailScheduler plugin - VirtueMart3
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
 * EmailScheduler plugin - VirtueMart3
 */
class PlgEmailschedulerVirtuemart3 extends EmailschedulerPluginTrigger
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
	 * @param array  $ids
	 *
	 * @return array
	 */
	public function onEmailschedulerVirtuemart3Search($search = null, $ids = array(), $limit = 0)
	{
		$db = JFactory::getDbo();
		$languageTag = $this->getLanguageTag();

		/** @var JDatabaseQuery $query */
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('p.virtuemart_product_id', 'l.product_name')));
		$query->from($db->quoteName('#__virtuemart_products', 'p'));
		$query->leftJoin($db->quoteName('#__virtuemart_products_'.$languageTag, 'l') . ' ON p.virtuemart_product_id = l.virtuemart_product_id');

		if (!empty($search))
		{
			$searchQuery = $db->quote('%' . $db->escape($search) . '%', false);
			$query->where($db->quoteName('l.product_name') . ' LIKE ' . $searchQuery);
		}

		if (!empty($ids))
		{
			$query->where($db->quoteName('p.virtuemart_product_id') . ' IN (' . implode(',', $ids) . ')');
		}

		if ($this->params->get('limit_ajax', 20) > 0)
		{
			$query->limit($limit);
		}

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$matches = array();

		foreach ($rows as $row)
		{
			$matches[$row->virtuemart_product_id] = $row->product_name;
		}

		return $matches;
	}

	/**
	 * @return bool|mixed|string
	 */
	protected function getLanguageTag()
	{
		$this->loadVirtueMart();
		$languageTag = VmConfig::$vmlang;

		if (!empty($languageTag)) {
			return $languageTag;
		}

		$languageTag = JFactory::getLanguage()->getTag();
		$languageTag = str_replace('-', '_', $languageTag);
		$languageTag = strtolower($languageTag);

		return $languageTag;
	}

    /**
     * Load VirtueMart
     */
    public function loadVirtueMart()
    {
        if (!class_exists('VmConfig'))
        {
            $vmConfigFile = JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/config.php';

            if (file_exists($vmConfigFile))
            {
                defined('DS') or define('DS', DIRECTORY_SEPARATOR);

                include_once $vmConfigFile;

                VmConfig::loadConfig();
            }
        }

        if (class_exists('VmConfig'))
        {
            VmConfig::setdbLanguageTag();
        }
    }
}

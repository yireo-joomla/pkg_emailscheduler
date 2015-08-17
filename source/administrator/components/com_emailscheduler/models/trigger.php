<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright Yireo.com 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/*
 * Emailscheduler Trigger model
 */

class EmailschedulerModelTrigger extends YireoModel
{
	/**
	 * Constructor method
	 *
	 * @access public
	 *
	 * @param null
	 *
	 * @return null
	 */
	public function __construct()
	{
		parent::__construct('trigger');
	}

	public function onDataLoad($data)
	{
		if (is_string($data->actions))
		{
			$data->actions = json_decode($data->actions, true);
		}

		if (is_string($data->condition))
		{
			$data->condition = json_decode($data->condition, true);
		}

		return $data;
	}

	/**
	 * Method to store the model
	 *
	 * @access     public
	 * @subpackage Yireo
	 *
	 * @param mixed $data
	 *
	 * @return bool
	 */
	public function store($data)
	{
		if (!isset($data['condition']) || !is_array($data['condition']))
		{
			$data['condition'] = array();
		}

		JPluginHelper::importPlugin('emailscheduler');
		$dispatcher = JEventDispatcher::getInstance();

		$dispatcher->trigger('onEmailschedulerTriggerSaveBefore', array(&$data));

		$data['condition'] = json_encode($data['condition']);

		if (!isset($data['actions']) || !is_array($data['actions']))
		{
			$data['actions'] = array();
		}

		$data['actions'] = json_encode($data['actions']);

		return parent::store($data);
	}
}

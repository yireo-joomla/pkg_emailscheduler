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

/**
 * Class EmailschedulerModelEmails
 */
class EmailschedulerModelEmails extends YireoModel
{
	/**
	 * Constructor method
	 */
	public function __construct()
	{
		parent::__construct('email');

		$send_state = $this->getFilter('send_state');
		
		if (!empty($send_state))
		{
			$db = JFactory::getDbo();
			$this->addWhere($db->quoteName('send_state') . '=' . $db->quote($send_state));
		}

		$this->_search          = array('subject', 'to', 'cc', 'bcc');
		$this->_orderby_default = 'send_date';
	}

	/**
	 * Method to modify the data once it is loaded
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	protected function onDataLoad($data)
	{
		return $data;
	}
}

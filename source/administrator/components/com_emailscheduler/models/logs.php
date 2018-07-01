<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright Yireo.com 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla
defined('_JEXEC') or die();

/**
 * Class EmailschedulerModelLogs
 */
class EmailschedulerModelLogs extends YireoModel
{
	/**
	 * Constructor method
	 */
	public function __construct()
	{
		parent::__construct('log');

		$this->_search          = array('email.subject', 'email.from', 'email.to', 'email.cc', 'email.bcc');
		$this->_orderby_default = 'email_id';
         $this->setConfig('limit_query', true);
	}

	/**
	 * Method to build the database query
	 *
	 * @param null
	 *
	 * @return mixed
	 */
	protected function buildQuery($query = '')
	{
		$query = "SELECT log.*, email.subject, email.to FROM #__emailscheduler_logs AS log \n";
		$query .= " LEFT JOIN #__emailscheduler_emails AS email ON email.id = log.email_id \n";

		return parent::buildQuery($query);
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

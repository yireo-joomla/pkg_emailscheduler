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
 * Emailscheduler Logs model
 */

class EmailschedulerModelLogs extends YireoModel
{
	/**
	 * Constructor method
	 */
	public function __construct()
	{
		parent::__construct('log');

		$this->_search = array('email.subject', 'email.from', 'email.to', 'email.cc', 'email.bcc');
		$this->_orderby_default = 'email_id';
	}

	/**
	 * Method to build the database query
	 *
	 * @param string $query
	 *
	 * @return mixed
	 */
	protected function buildQuery($query = '')
	{
		$query = "SELECT log.*, email.subject, email.to FROM #__emailscheduler_logs AS log \n";
		$query .= " LEFT JOIN #__emailscheduler_emails AS email ON email.id = log.email_id \n";

		return parent::buildQuery($query);
	}
}

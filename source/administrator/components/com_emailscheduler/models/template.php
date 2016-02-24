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
 * Emailscheduler Template model
 */

class EmailschedulerModelTemplate extends YireoModel
{
	/**
	 * Constructor method
	 */
	public function __construct()
	{
		parent::__construct('template');
	}
}

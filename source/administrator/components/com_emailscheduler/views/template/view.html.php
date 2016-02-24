<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class
 */
class EmailschedulerViewTemplate extends YireoViewForm
{
	/*
	 * Display method
	 *
	 * @param string $tpl
	 */
	public function display($tpl = null)
	{
		$this->fetchItem();
		parent::display($tpl);
	}
}

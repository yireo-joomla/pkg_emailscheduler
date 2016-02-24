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
class EmailschedulerViewLogs extends YireoViewList
{
	/*
	 * Display method
	 *
	 * @param string $tpl
	 */
	public function display($tpl = null)
	{
		// Add clean-button to toolbar
		JToolBarHelper::custom('deleteSent', 'delete.png', 'delete.png', 'Clean sent', false);

		parent::display($tpl);
	}
}


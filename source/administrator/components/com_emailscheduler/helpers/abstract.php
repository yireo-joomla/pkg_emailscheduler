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
 * Emailscheduler Structure
 */
class HelperAbstract
{
	/**
	 * Structural data of this component
	 */
	static public function getStructure()
	{
		return array(
			'title' => 'Emailscheduler',
			'menu' => array(
				'home' => 'Home',
				'emails' => 'Emails',
				'logs' => 'Logs',
				'triggers' => 'Triggers',
				'templates' => 'Templates',),
			'views' => array(
				'home' => 'Home',
				'emails' => 'Emails',
				'email' => 'Email',
				'log' => 'Log',
				'logs' => 'Logs',
				'template' => 'Template',
				'templates' => 'Templates',
				'trigger' => 'Trigger',
				'triggers' => 'Triggers',),
			'obsolete_folders' => array(
				JPATH_ADMINISTRATOR . '/components/com_emailscheduler/views/schedule',
				),
			);
	}
}

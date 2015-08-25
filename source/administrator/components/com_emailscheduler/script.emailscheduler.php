<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author    Yireo
 * @package   Emailscheduler
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

class com_emailschedulerInstallerScript
{
	public function postflight($action, $installer)
	{
		switch ($action)
		{
			case 'install':
			case 'update':

				// Perform extra queries
				$db = JFactory::getDBO();
				$queries = array();

				if (!empty($queries))
				{
					foreach ($queries as $query)
					{
						$db->setQuery($query);
						$db->query();
					}
				}

				// Remove obsolete files
				$files = array();
				foreach ($files as $file)
				{
					if (file_exists($file))
					{
						@unlink($file);
					}
				}

				break;
		}

		// Collection of queries were going to try
		$update_queries = array();

		// Perform all queries - we don't care if it fails
		$db = JFactory::getDBO();

		foreach ($update_queries as $query)
		{
			$db->debug(0);
			$db->setQuery($query);
			$db->query();
		}
	}
}

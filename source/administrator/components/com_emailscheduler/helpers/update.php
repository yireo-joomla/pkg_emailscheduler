<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2017
 * @license GNU Public License
 * @link https://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Emailscheduler update helper
 */
class EmailschedulerUpdate
{
	/**
	 * Run update queries
	 *
	 * @return void
	 */
	static public function runUpdateQueries()
	{
		$sqlfiles = array(
			JPATH_COMPONENT . '/sql/install.sql',
			JPATH_COMPONENT . '/sql/update.sql',
		);

		foreach ($sqlfiles as $sqlfile)
		{
			if (file_exists($sqlfile) && is_readable($sqlfile))
			{
				self::runUpdateQueriesFromFile($sqlfile);
			}
		}
	}

	/**
	 * Run update queries from file
	 *
	 * @return void
	 */
	static public function runUpdateQueriesFromFile($sqlfile)
	{
		$db      = JFactory::getDBO();
		$buffer  = file_get_contents($sqlfile);
		$queries = JDatabaseDriver::splitSql($buffer);

		foreach ($queries as $query)
		{
			$query = trim($query);

			if ($query === '' || $query{0} === '#')
			{
				continue;
			}

			try
			{
				$db->setQuery($query);
				$db->execute();
			}
			catch (Exception $e)
			{
				continue;
			}
		}
	}
}

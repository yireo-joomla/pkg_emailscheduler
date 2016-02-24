<?php
/**
 * EmailScheduler component
 *
 * @author    Yireo (info@yireo.com)
 * @package   EmailScheduler
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * EmailScheduler Abstract Plugin parent class
 */
class EmailschedulerPluginAbstract extends JPlugin
{
	/**
	 * Constructor.
	 *
	 * @param   object &$subject The object to observe.
	 * @param   array  $config   An optional associative array of configuration settings.
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		jimport('joomla.log.log');
		JLog::addLogger(array('text_file' => 'emailscheduler.log.php'), JLog::ALL, array('emailscheduler'));
	}

	/**
	 * Method to log or debug something
	 *
	 * @param   string $message  Message to log
	 * @param   string $variable Variable to dump
	 */
	protected function log($message, $variable = null)
	{
		$log = $message;

		if (!empty($variable))
		{
			$log .= ': ' . var_export($variable, true);
		}

		JLog::add($log, JLog::NOTICE, 'emailscheduler');
	}
}

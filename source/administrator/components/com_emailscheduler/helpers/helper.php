<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author    Yireo
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Class EmailschedulerHelper
 */
class EmailschedulerHelper
{
	/**
	 * Fetch a list of accounts
	 *
	 * @param bool $include_null
	 *
	 * @return array
	 */
	static public function getSendStateOptions($include_null = false)
	{
		$rows = array(
			array('title' => JText::_('COM_EMAILSCHEDULER_STATE_SENT'), 'value' => 'sent'),
			array('title' => JText::_('COM_EMAILSCHEDULER_STATE_PENDING'), 'value' => 'pending'),
			array('title' => JText::_('COM_EMAILSCHEDULER_STATE_PROCESSING'), 'value' => 'processing'),
			array('title' => JText::_('COM_EMAILSCHEDULER_STATE_FAILED'), 'value' => 'failed'),
			array('title' => JText::_('COM_EMAILSCHEDULER_STATE_PAUSED'), 'value' => 'paused'),
		);

		if ($include_null)
		{
			$option = array('title' => '-- Select --', 'value' => null);
			array_unshift($rows, $option);
		}

		return $rows;
	}

	/**
	 * Method to return the extra seconds for a specific string
	 *
	 * @param mixed $current_time
	 * @param mixed $reschedule_time
	 *
	 * @return string
	 */
	static public function getRescheduleTime($current_time, $reschedule_time)
	{
		if (preg_match('/^([0-9]+)([a-z]+)/', $reschedule_time, $match))
		{
			$new_time = strtotime('+' . $match[1] . ' ' . $match[2], strtotime($current_time));

			return date('Y-m-d H:i:s', $new_time);
		}

		return $current_time;
	}

	/**
	 * Method to format the time
	 *
	 * @param mixed $time
	 *
	 * @return string
	 */
	static public function formatTime($time)
	{
		$timestamp = strtotime($time);
		$seconds   = $timestamp - time();

		$time_string = null;

		if ($seconds == 0)
		{
			return 'now';
		}

		if ($seconds > 0)
		{
			$minutes = round($seconds / 60);
			$hours   = round($seconds / 60 / 60);
			$days    = round($seconds / 60 / 60 / 24);

			if ($minutes < 2)
			{
				$time_string = $minutes . ' minute';
			}
			elseif ($minutes < 60)
			{
				$time_string = $minutes . ' minutes';
			}
			elseif ($hours == 1)
			{
				$time_string = $hours . ' hour';
			}
			elseif ($hours < 24)
			{
				$time_string = $hours . ' hours';
			}
			elseif ($days == 1)
			{
				$time_string = $days . ' day';
			}
			else
			{
				$time_string = $days . ' days';
			}

			return $time_string;
		}

		$minutes = round((0 - $seconds) / 60);
		$hours   = round((0 - $seconds) / 60 / 60);
		$days    = round((0 - $seconds) / 60 / 60 / 24);

		if ($minutes < 2)
		{
			$time_string = $minutes . ' minute ago';
		}
		elseif ($minutes < 60)
		{
			$time_string = $minutes . ' minutes ago';
		}
		elseif ($hours == 1)
		{
			$time_string = $hours . ' hour ago';
		}
		elseif ($hours < 24)
		{
			$time_string = $hours . ' hours ago';
		}
		elseif ($days == 1)
		{
			$time_string = $days . ' day ago';
		}
		else
		{
			$time_string = $days . ' days ago';
		}
		
		return $time_string;
	}

	/**
	 * @param $email
	 *
	 * @return bool|JUser
	 */
	static public function loadByEmail($email)
	{
		// Abort if the email is not set
		$email = trim($email);

		if (empty($email))
		{
			return false;
		}

		// Fetch the user-record for this email-address
		$db    = JFactory::getDbo();
		$query = "SELECT id FROM #__users WHERE `email` = " . $db->quote($email);
		$db->setQuery($query);
		$row = $db->loadObject();

		// If there is no such a row, this user does not exist
		if (empty($row) || !isset($row->id) || !$row->id > 0)
		{
			return false;
		}

		// Load the user by its user-ID
		$user_id = $row->id;
		$user    = JFactory::getUser($user_id);
		if ($user == false || empty($user->id))
		{
			return false;
		}

		return $user;
	}

	/**
	 * Method to get a frontend link
	 *
	 * @param string $route
	 */
	static public function getFrontendUrl($route)
	{
		$app    = JApplication::getInstance('site');
		$router = $app->getRouter();

		if (!$router)
		{
			return null;
		}

		$uri = $router->build($route);
		$url = $uri->toString(array('path', 'query', 'fragment'));
		$url = htmlspecialchars($url);

		// Replace '/administrator'
		$url = str_replace('/administrator', '', $url);

		// Spoof the frontend
		$root = substr(JUri::root(), 0, -1);
		$root = str_replace('/administrator', '', $root);
		$url  = $root . $url;

		return $url;
	}
}

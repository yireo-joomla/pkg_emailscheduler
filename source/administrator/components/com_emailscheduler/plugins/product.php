<?php
/**
 * EmailScheduler plugin - HikaShop
 *
 * @author    Yireo (info@yireo.com)
 * @package   EmailScheduler
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE . '/administrator/components/com_emailscheduler/plugins/abstract.php';

/**
 * EmailScheduler Product Plugin parent class
 */
class EmailschedulerPluginProduct extends EmailschedulerPluginAbstract
{
	public function getTriggers($like = array())
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$selectFields = $db->quoteName(array('condition', 'actions', 'access', 'params'));
		$tableName = $db->quoteName('#__emailscheduler_triggers');
		$query->select($selectFields)->from($tableName)->where($db->quoteName('published') . '=1')->order($db->quoteName('ordering') . ' ASC');

		if (!empty($like))
		{
			foreach ($like as $likeName => $likeValue)
			{
				$query->where($db->quoteName($likeName) . ' LIKE ' . $db->quote($likeValue));
			}
		}

		$db->setQuery($query);
		$triggers = $db->loadObjectList();

		foreach ($triggers as $triggerIndex => $trigger)
		{
			$trigger->condition = json_decode($trigger->condition, true);
			$trigger->actions = json_decode($trigger->actions, true);
			$trigger->params = json_decode($trigger->params, true);
		}

		return $triggers;
	}

	public function doActions($actions, $params)
	{
		// Exit if there's no article
		if (empty($actions['article_id']))
		{
			return false;
		}

		// Exit if there's no action-type
		if (empty($actions['type']))
		{
			return false;
		}

		// Include the API file
		$apiFile = JPATH_ADMINISTRATOR . '/components/com_emailscheduler/api.php';
		require_once $apiFile;

		// Construct the API
		$email = new Emailscheduler;
		$email->setArticle($actions['article_id']);

		// Set the template
		if ($params['template_id'] > 0)
		{
			$email->setTemplate($params['template_id']);
		}

		// Set the delay
		if ($params['delay'] > 0)
		{
			$delay = (int) $params['delay'];
		}
		else
		{
			$delay = 0;
		}

		// Set the recipients
		if ($actions['type'] == 'user')
		{
			$recipients = array('to' => $actions['user_email']);
		}
		elseif ($actions['type'] == 'specific')
		{
			$recipients = array('to' => $actions['type_specific']);
		}
		else
		{
			$recipients = array('to' => $actions['user_email']);
		}

		if (!empty($params['bcc']))
		{
			$recipients['bcc'] = $params['bcc'];
		}

		$email->setRecipients($recipients);

		// Set additional variables
		if (!empty($params['variables']))
		{
			$email->setAdditionalVariables($params['variables']);
		}

		// Save this mail with a bit of delay
		$email->setSendDate(time() + $delay);
		$email->save();

		return true;
	}
}

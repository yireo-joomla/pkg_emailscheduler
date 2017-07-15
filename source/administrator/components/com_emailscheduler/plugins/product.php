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
	/**
	 * Method to get a listing of the stored triggers
	 *
	 * @param array $like
	 *
	 * @return mixed
	 */
	public function getTriggers($like = array())
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$selectFields = $db->quoteName(array('condition', 'actions', 'access', 'params'));
		$tableName = $db->quoteName('#__emailscheduler_triggers');
		$query->select($selectFields)
			->from($tableName)
			->where($db->quoteName('published') . '=1')
			->order($db->quoteName('ordering') . ' ASC');

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

	/**
	 * Method to perform the actions contained in a trigger and save the email accordingly
	 *
	 * @param $actions
	 * @param $params
	 *
	 * @return bool
	 */
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
		$email->setMessageId($this->getUniqueMessageId($actions, $params));

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

        // Extend variables through a simple PHP file
        $params['variables'] = $this->extendVariables($params['variables']);

		// Set additional variables
		if (!empty($params['variables']))
		{
			$email->setVariables($params['variables']);
		}

		// Save this mail with a bit of delay
		$email->setSendDate(time() + $delay);
		$email->save();

		return true;
	}

    private function extendVariables($variables)
    {
        $variableFile = $this->getVariableFile();

        if (empty($variableFile))
        {
            return $variables;
        }

        if (!is_array($variables))
        {
            $variables = [];
        }

		include $templateFile;

        if (!is_array($variables))
        {
            throw new Exception('Variables are no longer in array format');
        }

        return $variables;
    }

    private function getVariableFile()
    {
        $type = get_class($type);

		if (file_exists(JPATH_SITE . '/media/com_emailscheduler/email/variables/' . $type . '.php'))
		{
			return JPATH_SITE . '/media/com_emailscheduler/email/variables/' . $type . '.php';
		}
    }

	/**
	 * Create an unique string that identifies this email
	 *
	 * @param $actions
	 * @param $params
	 *
	 * @return string
	 */
	public function getUniqueMessageId($actions, $params)
	{
		if (isset($params['variables']))
		{
			unset($params['variables']);
		}

		return md5(var_export($actions, true) . var_export($params, true));
	}


    protected function objectToArray($object)
    {
        $array = [];
        $variables = get_object_vars($object);

        foreach ($variables as $name => $value) {
            if (!is_array($value) && !is_object($value)) {
                $array[$name] = $value;
            }
        }

        return $array;
    }
}

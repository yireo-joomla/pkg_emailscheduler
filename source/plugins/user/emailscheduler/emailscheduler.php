<?php
/**
 * Emailscheduler User Plugin
 *
 * @author Yireo (info@yireo.com)
 * @package Emailscheduler
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * User Plugin to schedule a mail whenever a new user registers
 *
 * @package Emailscheduler
 */
class PlgUserEmailscheduler extends JPlugin
{
	/**
	 * Event method onUserAfterSave
	 *
	 * @param   array    $user     Holds the new user data.
	 * @param   boolean  $isnew    True if a new user is stored.
	 * @param   boolean  $success  True if user was succesfully stored in the database.
	 * @param   string   $msg      Message.
	 *
	 * @return  void
	 */
	public function onUserAfterSave($user, $isnew, $success, $msg)
    {
		// If the user wasn't stored we don't do anything
		if (!$success)
		{
			return false;
		}

		// If the user isn't new we don't do anything
		if (!$isnew)
		{
			return false;
        }

		// Ensure the user id is really an int
		$user_id = (int) $user['id'];

		// If the user id appears invalid then bail out just in case
		if (empty($user_id))
		{
			return false;
		}

        // Double check the user
        $userObject = new JUser();
        $userObject->load($user_id);
        if($userObject->id == 0 || $userObject->block == 1)
        {
            return false;
        }

        // Get the article ID
        $article_id = (int)$this->params->get('article_id');
        
        // Skip this if there is no valid article
        if (empty($article_id))
        {
            return false;
        }

        // Check for the API
        $apiFile = JPATH_ADMINISTRATOR.'/components/com_emailscheduler/api.php';
        if (file_exists($apiFile) == false)
        {
            return false;
        }

        // Include the API file
        require_once $apiFile;
        $email = new Emailscheduler();
        $email->setArticle($article_id);

        // Load other parameters
        $template_id = (int)$this->params->get('template_id');
        $bcc = $this->params->get('bcc');
        $delay = (int)$this->params->get('delay');

        // Set the template
        if($template_id > 0)
        {
            $email->setTemplate($template_id);
        }

        // Set the recipients
        $recipients = array('to' => $user['email']);
        if(!empty($bcc)) $recipients['bcc'] = $bcc;
        $email->setRecipients($recipients);

        // Save this mail with a bit of delay
        $email->setSendDate(time() + $delay);
        $email->save();

        return true;
    }
}

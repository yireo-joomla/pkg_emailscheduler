<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright Yireo.com 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/*
 * Emailscheduler Email model
 */

class EmailschedulerModelEmail extends YireoModel
{
	/*
	 * Definition of send-states
	 */
	const SEND_STATE_PENDING = 'pending';
	const SEND_STATE_SENT = 'sent';
	const SEND_STATE_PAUSED = 'paused';
	const SEND_STATE_FAILED = 'failed';

	protected $template_body = null;

	protected $template_subject = null;

	/**
	 * Constructor method
	 *
	 * @param null
	 */
	public function __construct()
	{
		parent::__construct('email');
	}

	/**
	 * Method to store the model
	 *
	 * @access     public
	 * @subpackage Yireo
	 *
	 * @param mixed $data
	 *
	 * @return bool
	 */
	public function store($data)
	{
		$send_date = (isset($data['item']['send_date'])) ? $data['item']['send_date'] : null;
		$send_time = (isset($data['item']['send_time'])) ? $data['item']['send_time'] : null;

		$send_date = strtotime($send_date);

		if (!empty($send_time) && preg_match('/([0-9]{2}):([0-9]{2})/', $send_time))
		{
			$send_date = date('Y-m-d', $send_date) . ' ' . $send_time;
			$send_date = strtotime($send_date);
		}

		if (empty($send_date))
		{
			$send_date = time() + 5 * 60;
		}

		$data['item']['send_date'] = date('Y-m-d H:i:s', $send_date);

		return parent::store($data);
	}

	/**
	 * Method to send the email
	 *
	 * @return bool
	 */
	public function send()
	{
		// Get the data
		$data = (object) $this->getData(true);
		$mailData = clone $data;

		// Recheck the status
		if ($mailData->send_state != 'pending')
		{
			return false;
		}

		// Change status to processing
		$mailData->send_state = 'processing';
		$this->store((array) $mailData);

		// Load the associated template
		$this->loadTemplate($mailData->template_id);

		// Variables
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();

		if (YireoHelper::isJoomla25())
		{
			$dispatcher = JDispatcher::getInstance();
		}
		else
		{
			$dispatcher = JEventDispatcher::getInstance();
		}

		// Set sender
		if (empty($mailData->from))
		{
			$mailData->from = array($config->get('mailfrom'), $config->get('fromname'));
		}

		$mailer->setSender($mailData->from);

		// Set recipients
		$recipients = explode(',', $mailData->to);

		foreach ($recipients as $recipient)
		{
			$recipient = trim($recipient);
			if (!empty($recipient))
			{
				$mailer->addRecipient($recipient);
			}
		}

		// Set CC
		if (!empty($mailData->cc))
		{
			$recipients = explode(',', $mailData->cc);

			foreach ($recipients as $recipient)
			{
				$recipient = trim($recipient);

				if (!empty($recipient))
				{
					$mailer->addCC($recipient);
				}
			}
		}

		// Set BCC
		if (!empty($mailData->bcc))
		{
			$recipients = explode(',', $mailData->bcc);

			foreach ($recipients as $recipient)
			{
				$recipient = trim($recipient);

				if (!empty($recipient))
				{
					$mailer->addBCC($recipient);
				}
			}
		}

		// Parse the text
		$mailData = $this->parseImages($mailData);
		$mailData = $this->parseLinks($mailData);
		$mailData = $this->parseText($mailData);

		// Allow plugins to modify the data
		$dispatcher->trigger('onEmailschedulerMailAfterSend', array(&$mailData));

		// Set subject
		$mailer->setSubject($mailData->subject);

		// Set body
		if (!empty($mailData->body_html))
		{
			$mailer->setBody($mailData->body_html);
		}
		else
		{
			$mailer->setBody($mailData->body_text);
		}

		//echo $mailData->body_html;exit;

		// Optional attachments
		if (!empty($mailData->attachments))
		{
			$attachments = explode(',', $mailData->attachments);

			foreach ($attachments as $attachment)
			{
				$attachment = trim($attachment);

				if (!file_exists($attachment))
				{
					$attachment = JPATH_SITE . '/' . $attachment;
				}

				if (file_exists($attachment))
				{
					$mailer->addAttachment($attachment);
				}
			}
		}

		// Parse the parameters
		$params = YireoHelper::toRegistry($mailData->params);
		$mailer->isHTML(true);
		//$mailer->isHTML((bool)$body_html); // @todo: How to make this work properly?
		//$mailer->Encoding = $params->get('encoding', 'base64'); // @todo: When is this needed?

		// Send the message
		$rt = $mailer->Send();

		// Allow plugins to modify the data
		$dispatcher->trigger('onEmailschedulerMailAfterSend', array(&$mailData));

		// Prepare log-data
		$logData = array('email_id' => $data->id, 'send_date' => date('Y-m-d H:i:s'),);

		// Handle send response
		if ($rt == true)
		{
			$logData['message'] = (!empty($mailer->message)) ? $mailer->message : null;
			$logData['send_state'] = self::SEND_STATE_SENT;
			$data->send_state = $logData['send_state'];
			$data->send_date = $logData['send_state'];
		}
		else
		{
			$logData['message'] = (!empty($mailer->message)) ? $mailer->message : null;
			$logData['send_state'] = self::SEND_STATE_FAILED;
			$data->send_state = $logData['send_state'];
		}

		// Save logdata
		require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/models/log.php';

		$logModel = new EmailschedulerModelLog;
		$logModel->store($logData);

		// Save status
		$this->store((array) $data);

		// Return
		if ($rt == true)
		{
			return true;
		}

		return false;
	}

	/**
	 * Method to load the related template
	 *
	 * @access protected
	 *
	 * @param int $template_id
	 *
	 * @return null
	 */
	protected function loadTemplate($template_id)
	{
		if ($template_id > 0)
		{
			$this->_db->setQuery('SELECT * FROM #__emailscheduler_templates WHERE id=' . (int) $template_id);
			$template = $this->_db->loadObject();

			if (!empty($template->body))
			{
				$this->template_body = $template->body;
			}

			if (!empty($template->subject))
			{
				$this->template_subject = $template->subject;
			}
		}
	}

	/**
	 * Method to parse the text-parts for variables and template
	 *
	 * @access protected
	 *
	 * @param object $data
	 *
	 * @return null
	 */
	protected function parseText($data)
	{
		// Apply the template to the HTML-body
		if (!empty($this->template_body))
		{
			$data->body_html = str_ireplace('{body}', $data->body_html, $this->template_body);
		}

		// Apply the template to the subject
		if (!empty($this->template_subject))
		{
			$data->subject = str_ireplace('{subject}', $data->subject, $this->template_subject);
		}

		// Construct variables
		$variables = array();
		$variables['email'] = $data->to;
		$variables['subject'] = $data->subject;

		// Replace user-variables
		$user = EmailschedulerHelper::loadByEmail($data->to);

		if (is_object($user))
		{
			$variables['username'] = $user->username;
			$variables['name'] = $user->name;
		}
		else
		{
			$variables['username'] = null;
			$variables['name'] = null;
		}

		// Add additional variables
		if (isset($data->additional_variables) && is_array($data->additional_variables))
		{
			foreach ($data->additional_variables as $name => $value)
			{
				$variables[$name] = $value;
			}
		}

		// Replace variables
		foreach ($variables as $variableName => $variableValue)
		{
			$data->body_html = str_ireplace('{' . $variableName . '}', $variableValue, $data->body_html);
			$data->body_text = str_ireplace('{' . $variableName . '}', $variableValue, $data->body_text);
			$data->subject = str_ireplace('{' . $variableName . '}', $variableValue, $data->subject);
		}

		return $data;
	}

	/**
	 * Method to scan text for links and convert them
	 *
	 * @access protected
	 *
	 * @param string $text
	 *
	 * @return null
	 */
	protected function parseLinks($data)
	{
		// Scan the body for links
		$body_html = $data->body_html;

		if (preg_match_all('/("|\')index.php\?option=com_([^\"\']+)/', $body_html, $matches))
		{
			foreach ($matches[0] as $matchIndex => $match)
			{
				$link = 'index.php?option=com_' . $matches[2][$matchIndex];
				$sefLink = EmailschedulerHelper::getFrontendUrl($link);
				$body_html = str_replace($link, $sefLink, $body_html);
			}
		}

		$data->body_html = $body_html;

		return $data;
	}

	/**
	 * Method to scan text for images and add them as embedded
	 *
	 * @access protected
	 *
	 * @param string $text
	 *
	 * @return null
	 */
	protected function parseImages($data)
	{
		$root = substr(JURI::root(), 0, -1);
		$root = str_replace('/administrator', '', $root);

		// Scan the body for links
		$body_html = $data->body_html;

		if (preg_match_all('/src=("|\')([^\"\']+)/', $body_html, $matches))
		{
			foreach ($matches[2] as $match)
			{
				$image = $match;

				if (preg_match('/^(http|https)\:\/\//', $image))
				{
					continue;
				}

				if (file_exists(JPATH_ROOT . '/' . $image))
				{
					$image = $root . '/' . $image;
				}

				$body_html = str_replace($match, $image, $body_html);
			}
		}

		$data->body_html = $body_html;

		// @todo: $mailer->AddEmbeddedImage($image, md5($image), basename($image), 'base64', $mimetype);
		return $data;
	}

	/**
	 * Method to modify the data once it is loaded
	 *
	 * @access protected
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	protected function onDataLoad($data)
	{
		$send_date = strtotime($data->send_date);

		if (empty($send_date))
		{
			$send_date = time() + 5 * 60;
			$data->send_date = date('Y-m-d H:i:s', $send_date);
		}

		$data->send_time = date('H:i:s', $send_date);

		return $data;
	}
}

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
	/**
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
	 * Method to load the model
	 *
	 * @param int $id
	 *
	 * @return array
	 */
	public function load($id)
	{
		$this->setId($id);
		$data = (object) $this->getData(true);

		return $data;
	}

	/**
	 * Method to load the model by message ID
	 *
	 * @param string $messageId
	 *
	 * @return false|array
	 */
	public function loadByMessageId($messageId)
	{
		if (empty($messageId) || !is_string($messageId))
		{
			return false;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__emailscheduler_emails'));
		$query->where($db->quoteName('message_id') . '=' . $db->quote($messageId));

		$query->setLimit(1);
		$db->setQuery($query);
		$id = $db->loadResult();

		if (empty($id))
		{
			return false;
		}

		$this->setId($id);
		$data = (object) $this->getData(true);

		return $data;
	}

	/**
	 * Method to load the model
	 *
	 * @param array $search
	 *
	 * @return false|array
	 */
	public function loadBySearch($search = array())
	{
		if (empty($search) || !is_array($search))
		{
			return false;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__emailscheduler_emails'));

		foreach ($search as $searchName => $searchValue)
		{
			$query->where($db->quoteName($searchName) . '=' . $db->quote($searchValue));
		}

		$query->setLimit(1);
		$db->setQuery($query);
		$id = $db->loadResult();

		if (empty($id))
		{
			return false;
		}

		$this->setId($id);
		$data = (object) $this->getData(true);

		return $data;
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
		if (isset($data['item']['send_date']))
		{
			$send_date = $data['item']['send_date'];
		}
		elseif (isset($data['send_date']))
		{
			$send_date = $data['send_date'];
		}
		else
		{
			$send_date = null;
		}

		if (isset($data['item']['send_time']))
		{
			$send_time = $data['item']['send_time'];
		}
		elseif (isset($data['send_time']))
		{
			$send_time = $data['send_time'];
		}
		else
		{
			$send_time = null;
		}

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
	 * Method to prepare this email for sending
	 *
	 * @param object $mailData
	 */
	public function prepare(&$mailData)
	{
		// Load the associated template
		$this->loadTemplate($mailData->template_id);

		// Parse the text
		$this->parseText($mailData);
		$this->parseImages($mailData);
		$this->parseLinks($mailData);
	}

	/**
	 * Method to prepare this email for sending
	 *
	 * @param object $mailData
	 * @param object $mailer
	 */
	public function prepareAddresses(&$mailData, &$mailer)
	{
		$config = JFactory::getConfig();

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
	}

	/**
	 * Method to prepare the attachments
	 *
	 * @param object $mailData
	 * @param object $mailer
	 */
	public function prepareAttachments(&$mailData, &$mailer)
	{
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

		// Variables
		$mailer = JFactory::getMailer();

		// Prepare this mail for sending
		$this->prepare($mailData);
		$this->prepareAddresses($mailData, $mailer);

		if (YireoHelper::isJoomla25())
		{
			$dispatcher = JDispatcher::getInstance();
		}
		else
		{
			$dispatcher = JEventDispatcher::getInstance();
		}

		// Allow plugins to modify the data
		$dispatcher->trigger('onEmailschedulerMailBeforeSend', array(&$mailData));

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

		// Optional attachments
		$this->prepareAttachments($mailData, $mailer);

		// Parse the parameters
		$params = YireoHelper::toRegistry($mailData->params);
		$mailer->isHTML(true);
		//$mailer->isHTML((bool)$body_html); // @todo: How to make this work properly?
		//$mailer->Encoding = $params->get('encoding', 'base64'); // @todo: When is this needed?

		// Send the message
		$rt = $mailer->Send();

		// Allow plugins to modify the data
		$dispatcher->trigger('onEmailschedulerMailAfterSend', array(&$mailData));

		$this->logSend($data, $rt, $mailer);

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
	 * @param int $template_id
	 *
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
	 * @param object $mailData
	 */
	protected function parseText(&$mailData)
	{
		// Apply the template to the HTML-body
		if (!empty($this->template_body))
		{
			$mailData->body_html = str_ireplace('{body}', $mailData->body_html, $this->template_body);
		}

		// Apply the template to the subject
		if (!empty($this->template_subject))
		{
			$mailData->subject = str_ireplace('{subject}', $mailData->subject, $this->template_subject);
		}

		// Construct variables
		$templateVariables = array();
		$templateVariables['email'] = $mailData->to;
		$templateVariables['subject'] = $mailData->subject;

		// Replace user-variables
		$user = EmailschedulerHelper::loadByEmail($mailData->to);

		if (is_object($user))
		{
			$templateVariables['username'] = $user->username;
			$templateVariables['name'] = $user->name;
		}
		else
		{
			$templateVariables['username'] = null;
			$templateVariables['name'] = null;
		}

		// Add variables
		if (isset($mailData->variables) && is_array($mailData->variables))
		{
			foreach ($mailData->variables as $name => $value)
			{
				$templateVariables[$name] = $value;
			}
		}

		// Add additional variables
		if (isset($mailData->additional_variables) && is_array($mailData->additional_variables))
		{
			foreach ($mailData->additional_variables as $name => $value)
			{
				$templateVariables[$name] = $value;
			}
		}

		$this->parseViaTwig($mailData->body_html, $templateVariables);

		// Replace variables
		foreach ($templateVariables as $variableName => $variableValue)
		{
			if (is_string($variableValue) == false && is_numeric($variableValue) == false)
			{
				continue;
			}

			$mailData->body_html = str_ireplace('{' . $variableName . '}', $variableValue, $mailData->body_html);
			$mailData->body_text = str_ireplace('{' . $variableName . '}', $variableValue, $mailData->body_text);
			$mailData->subject = str_ireplace('{' . $variableName . '}', $variableValue, $mailData->subject);
		}
	}

	/**
	 * Method to use Twig to parse a text
	 *
	 * @param string $text
	 * @param array $variables
	 */
	public function parseViaTwig(&$text, $variables)
	{
		$this->initTwig();

		$loader = new Twig_Loader_Array(array(
			'body' => $text,
		));

		$twig = new Twig_Environment($loader);

		$text = $twig->render('body', $variables);
	}

	/**
	 * Method to initialize Twig
	 */
	public function initTwig()
	{
		static $initTwig = false;

		if ($initTwig == false)
		{
			$initTwig = true;

			require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/vendor/Twig/Autoloader.php';
			Twig_Autoloader::register();
		}
	}

	/**
	 * Method to scan text for links and convert them
	 *
	 * @param object $mailData
	 */
	protected function parseLinks(&$mailData)
	{
		// Scan the body for links
		$body_html = $mailData->body_html;

		if (preg_match_all('/("|\')index.php\?option=com_([^\"\']+)/', $body_html, $matches))
		{
			foreach ($matches[0] as $matchIndex => $match)
			{
				$link = 'index.php?option=com_' . $matches[2][$matchIndex];
				$sefLink = EmailschedulerHelper::getFrontendUrl($link);
				$body_html = str_replace($link, $sefLink, $body_html);
			}
		}

		$mailData->body_html = $body_html;
	}

	/**
	 * Method to scan text for images and add them as embedded
	 *
	 * @param object $mailData
	 */
	protected function parseImages(&$mailData)
	{
		$root = substr(JURI::root(), 0, -1);
		$root = str_replace('/administrator', '', $root);

		// Scan the body for links
		$body_html = $mailData->body_html;

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

		$mailData->body_html = $body_html;

		// @todo: $mailer->AddEmbeddedImage($image, md5($image), basename($image), 'base64', $mimetype);
	}

	/**
	 * Method to log the send action
	 *
	 * @param $data
	 * @param $rt
	 * @param $mailer
	 */
	public function logSend($data, $rt, $mailer)
	{
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

		if (!empty($data->variables))
		{
			$data->variables = unserialize($data->variables);
		}

		return $data;
	}
}

<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author    Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the loader
require_once dirname(__FILE__) . '/lib/loader.php';

/**
 * Emailscheduler API
 */
class Emailscheduler
{
	/*
	 * Data
	 */
	protected $data = array();

	/**
	 * Constructor
	 *
	 * @param string $subject
	 * @param string $body
	 * @param mixed  $recipients
	 * @param mixed  $from
	 * @param mixed  $attachments
	 * @param int    $send_date
	 * @param array	 $variables
	 *
	 * @return $this
	 */
	public function __construct($subject = '', $body = '', $recipients = null, $from = null, $attachments = null, $send_date = 0, $variables = null)
	{
		// Construct data
		$this->setBody($body);
		$this->setSubject($subject);
		$this->setRecipients($recipients);
		$this->setFrom($from);
		$this->setAttachments($attachments);
		$this->setSendDate($send_date);
		$this->setVariables($variables);

		return $this;
	}

	/**
	 * Method to set the subject
	 *
	 * @param string $subject
	 *
	 * @return $this
	 */
	public function setSubject($subject)
	{
		$this->data['subject'] = $subject;

		return $this;
	}

	/**
	 * Method to set the body
	 *
	 * @param string $body
	 *
	 * @return $this
	 */
	public function setBody($body)
	{
		if (is_string($body))
		{
			$this->data['body_html'] = $body;
			$this->data['body_text'] = null;
		}
		elseif (is_array($body))
		{
			$this->data['body_html'] = $body['html'];
			$this->data['body_text'] = $body['text'];
		}

		return $this;
	}

	/**
	 * Method to set the from address
	 *
	 * @param string $from
	 *
	 * @return $this
	 */
	public function setFrom($from)
	{
		if (empty($from))
		{
			$config = JFactory::getConfig();
			$from = $config->get('mailfrom');
		}

		$this->data['from'] = $from;

		return $this;
	}

	/**
	 * Method to set the template
	 *
	 * @param int $template_id
	 *
	 * @return $this
	 */
	public function setTemplate($template_id)
	{
		$this->data['template_id'] = (int) $template_id;

		return $this;
	}

	/**
	 * Method to set the attachments
	 *
	 * @param array $attachments
	 *
	 * @return $this
	 */
	public function setAttachments($attachments)
	{
		$this->data['attachments'] = $attachments;

		return $this;
	}

	/**
	 * Method to set the variables
	 *
	 * @param array $variables
	 *
	 * @return $this
	 */
	public function setVariables($variables)
	{
		$this->data['variables'] = $variables;

		return $this;
	}

	/**
	 * Method to set the message ID
	 *
	 * @param string $message_id
	 *
	 * @return $this
	 */
	public function setMessageId($message_id)
	{
		if (is_string($message_id))
		{
			$message_id = serialize($message_id);
		}

		if (strlen($message_id) != 32)
		{
			$message_id = md5($message_id);
		}

		$this->data['message_id'] = $message_id;

		return $this;
	}

	/**
	 * Method to set the send_date
	 *
	 * @param mixed $send_date
	 *
	 * @return $this
	 */
	public function setSendDate($send_date)
	{
		if (!is_numeric($send_date))
		{
			$send_date = strtotime($send_date);
		}

		if (empty($send_date) || $send_date < (time() - 60))
		{
			$send_date = time();
		}

		$this->data['send_date'] = date('Y-m-d H:i:s', $send_date);

		return $this;
	}

	/**
	 * Method to set the send_state
	 *
	 * @param string $send_state
	 *
	 * @return $this
	 */
	public function setSendState($send_state)
	{
		$this->data['send_state'] = $send_state;

		return $this;
	}

	/**
	 * Method to use an article for subject and body
	 *
	 * @param int  $article_id
	 * @param bool $use_subject
	 *
	 * @return $this
	 */
	public function setArticle($article_id, $use_subject = true)
	{
		$db = JFactory::getDBO();
		$db->setQuery('SELECT `title`, `introtext`, `fulltext` FROM `#__content` WHERE `id` = ' . (int) $article_id);
		$article = $db->loadObject();

		if (empty($article))
		{
			return $this;
		}

		if (!empty($article->fulltext))
		{
			$this->data['body_html'] = $article->fulltext;
		}
		else
		{
			$this->data['body_html'] = $article->introtext;
		}

		if ($use_subject)
		{
			$this->data['subject'] = $article->title;
		}

		return $this;
	}

	/**
	 * Method to set the recipients
	 *
	 * @param mixed $recipients
	 *
	 * @return $this
	 */
	public function setRecipients($recipients)
	{
		// Add a single recipient
		if (is_string($recipients))
		{
			$this->data['to'] = $recipients;

			// Multiple recipients
		}
		elseif (is_array($recipients))
		{
			$to = array();
			$cc = array();
			$bcc = array();

			foreach ($recipients as $recipientIndex => $recipient)
			{
				// Sanitize
				$recipient = trim($recipient);

				if (empty($recipient))
				{
					continue;
				}

				// Simple recipient-string
				if (empty($recipientIndex))
				{
					$to[] = $recipient;
				}
				elseif ($recipientIndex == 'to')
				{
					$to[] = $recipient;
				}
				elseif ($recipientIndex == 'cc')
				{
					$cc[] = $recipient;
				}
				elseif ($recipientIndex == 'bcc')
				{
					$bcc[] = $recipient;
				}
			}

			$this->data['to'] = implode(',', $to);
			$this->data['cc'] = implode(',', $cc);
			$this->data['bcc'] = implode(',', $bcc);
		}

		return $this;
	}

	/**
	 * Method to set additional variables
	 *
	 * @param $variables
	 *
	 * @return $this
	 */
	public function setAdditionalVariables($variables)
	{
		$this->data['additional_variables'] = $variables;

		return $this;
	}

	/**
	 * Method to get the current data
	 *
	 * @param null
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Method to add an email batch to the queue
	 *
	 * @return bool
	 */
	public function save()
	{
		// Load the model and save the data
		require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/tables/email.php';
		require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/models/email.php';

		$model = new EmailschedulerModelEmail;
		$rt = $model->store($this->data);

		return $rt;
	}

	/**
	 * Method to delete an email that already is in the queue
	 *
	 * @param mixed $search ID, message ID or associated array uniquely identifying this email
	 *
	 * @return bool
	 */
	static public function delete($search = '')
	{
		// Load the model and save the data
		require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/tables/email.php';
		require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/models/email.php';

		$model = new EmailschedulerModelEmail;

		if (is_numeric($search))
		{
			$model->load($search);
		}
		elseif (is_string($search) && strlen($search) == 32)
		{
			$model->loadByMessageId($search);
		}
		elseif (is_array($search))
		{
			$model->loadBySearch($search);
		}

		$rt = false;

		if ($model->getId() > 0)
		{
			$rt = $model->delete(array($model->getId()));
		}

		return $rt;
	}

	/**
	 * Static method to save all pending emails
	 *
	 * @return bool
	 */
	static public function send()
	{
		ini_set('display_errors', 1);

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__emailscheduler_emails'));
		$query->where($db->quoteName('send_date') . ' < NOW()');
		$query->where($db->quoteName('send_state') . '=' . $db->quote('pending'));

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if (empty($rows))
		{
			return false;
		}

		// Load the model
		require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/helpers/helper.php';
		require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/tables/email.php';
		require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/models/email.php';

		$model = new EmailschedulerModelEmail;

		foreach ($rows as $row)
		{
			$model->setId($row->id);
			$model->send();
		}

		return true;
	}
}

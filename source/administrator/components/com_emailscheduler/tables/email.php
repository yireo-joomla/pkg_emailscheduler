<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Email Table class
 */
class TableEmail extends YireoTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabase $db
	 */
	public function __construct(& $db)
	{
		// Set the required fields
		$this->_required = array(
			'subject',
			'to',);

		// Call the constructor
		parent::__construct('#__emailscheduler_emails', 'id', $db);
	}

	/**
	 * Overloaded bind method
	 *
	 * @param array  $data
	 * @param string $ignore
	 *
	 * @return mixed
	 * @see    JTable:bind
	 */
	public function bind($data, $ignore = '')
	{
		// Autocorrect sending time
		$send_date = strtotime($data['send_date']);

		if (empty($send_date) || $send_date < time())
		{
			$data['send_date'] = date('Y-m-d H:i:s', time());
		}

		// Convert arrays into strings
		$arrayNames = array('to', 'cc', 'bcc', 'attachments', 'headers');

		foreach ($arrayNames as $arrayName)
		{
			if (!empty($data[$arrayName]) && is_array($data[$arrayName]))
			{
				$data[$arrayName] = implode(',', $data[$arrayName]);
			}
		}

		// Serializes variables
		if (!empty($data['variables']))
		{
			$data['variables'] = serialize($data['variables']);
		}

		// Generate a message_id if it does not exist
		if (empty($data['message_id']))
		{
            $hash = $data['subject'];
            $hash .= $data['to'];

            if(!empty($data['cc']))
            {
                $hash .= $data['cc'];
            }

            if(!empty($data['bcc']))
            {
                $hash .= $data['bcc'];
            }

			$data['message_id'] = md5($hash);
		}

		// Lookup the ID based upon the message_id
		if (empty($data['id']))
		{
			$this->_db->setQuery('SELECT `' . $this->_tbl_key . '` FROM `' . $this->_tbl . '` WHERE `message_id`="' . $data['message_id'] . '"');
			$data['id'] = $this->_db->loadResult();
		}

		// Set the send_state
		if (empty($data['send_state']))
		{
			$data['send_state'] = 'pending';
		}

		return parent::bind($data, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @return bool
	 */
	public function check()
	{
		// Perform the parent-checks
		$result = parent::check();

		if ($result == false)
		{
			return false;
		}

		// Email-addresses
		$fields = array('from', 'to', 'cc', 'bcc');

		foreach ($fields as $field)
		{
			$rt = $this->validateEmail($this->$field);

			if ($rt == false)
			{
				$this->_error = JText::sprintf('COM_EMAILSCHEDULER_TABLE_INVALID_EMAIL', $this->$field);

				return false;
			}
		}

		// Check whether the message does not exceed the maximum
		$too_many_chars = 255 - strlen($this->subject);

		if ($too_many_chars < 0)
		{
			$this->_error = JText::sprintf('COM_EMAILSCHEDULER_TABLE_SUBJECT_TOO_LARGE', 0 - $too_many_chars);

			return false;
		}

		return true;
	}

	/**
	 * Validate email-address(es)
	 *
	 * @param string
	 *
	 * @return bool
	 */
	protected function validateEmail($email)
	{
		// If the address is empty, skip it
		$email = trim($email);

		if (empty($email))
		{
			return true;
		}

		// Allow for comma-seperated lists
		$emails = explode(',', $email);

		foreach ($emails as $email)
		{
			$email = trim($email);
			$rt = JMailHelper::isEmailAddress($email);

			if ($rt == false)
			{
				return false;
			}
		}

		return true;
	}
}

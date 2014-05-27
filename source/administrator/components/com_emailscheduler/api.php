<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the loader
require_once dirname(__FILE__).'/lib/loader.php';

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
     * @access public
     * @param string $subject
     * @param string $body
     * @param mixed $recipients
     * @param mixed $from
     * @param mixed $attachments
     * @param int $send_date
     * @return bool
     */
    public function __construct($subject = null, $body = null, $recipients = null, $from = null, $attachments = null, $send_date = null)
    {
        // Construct data
        $this->setBody($body);
        $this->setSubject($subject);
        $this->setRecipients($recipients);
        $this->setFrom($from);
        $this->setAttachments($attachments);
        $this->setSendDate($send_date);
        return $this;
    }

    /**
     * Method to set the subject
     *
     * @access public
     * @param string $subject
     * @return bool
     */
    public function setSubject($subject)
    {
        $this->data['subject'] = $subject;
    }

    /**
     * Method to set the body
     *
     * @access public
     * @param string $body
     * @return bool
     */
    public function setBody($body)
    {
        if(is_string($body)) {
            $this->data['body_html'] = $body;
            $this->data['body_text'] = null;
        } elseif(is_array($body)) {
            $this->data['body_html'] = $body['html'];
            $this->data['body_text'] = $body['text'];
        }
        return $this;
    }

    /**
     * Method to set the from address
     *
     * @access public
     * @param string $fom
     * @return bool
     */
    public function setFrom($from)
    {
        $this->data['from'] = $from;
    }

    /**
     * Method to set the template
     *
     * @access public
     * @param int $template_id
     * @return null
     */
    public function setTemplate($template_id)
    {
        $this->data['template_id'] = (int)$template_id;
    }

    /**
     * Method to set the attachments
     *
     * @access public
     * @param string $subject
     * @return bool
     */
    public function setAttachments($attachments)
    {
        $this->data['attachments'] = $attachments;
    }

    /**
     * Method to set the message ID
     *
     * @access public
     * @param string $message_id
     * @return bool
     */
    public function setMessageId($message_id)
    {
        if(is_string($message_id)) $message_id = serialize($message_id);
        if(strlen($message_id) != 32) $message_id = md5($message_id);
        $this->data['message_id'] = $message_id;
    }

    /**
     * Method to set the send_date
     *
     * @access public
     * @param mixed $send_date
     * @return bool
     */
    public function setSendDate($send_date)
    {
        if(!is_numeric($send_date)) $send_date = strtotime($send_date);
        if(empty($send_date) || $send_date < time()) $send_date = time();
        $this->data['send_date'] = date('Y-m-d H:i:s', $send_date);

        return $this;
    }

    /**
     * Method to set the send_state
     *
     * @access public
     * @param string $send_state
     * @return null
     */
    public function setSendState($send_state)
    {
        $this->data['send_state'] = $send_state;
    }

    /**
     * Method to use an article for subject and body
     *
     * @access public
     * @param int $article_id
     * @param bool $use_subject
     * @return bool
     */
    public function setArticle($article_id, $use_subject = true)
    {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT `title`, `introtext`, `fulltext` FROM `#__content` WHERE `id` = '.(int)$article_id);
        $article = $db->loadObject();
        if (empty($article)) {
            return $this;
        }

        if (!empty($article->fulltext)) {
            $this->data['body_html'] = $article->fulltext;
        } else {
            $this->data['body_html'] = $article->introtext;
        }

        if($use_subject) {
            $this->data['subject'] = $article->title;
        }

        return $this;
    }

    /**
     * Method to set the recipients
     *
     * @access public
     * @param mixed $recipients
     * @return bool
     */
    public function setRecipients($recipients)
    {
        // Add a single recipient
        if(is_string($recipients)) {
            $this->data['to'] = $recipients;

        // Multiple recipients
        } elseif(is_array($recipients)) {
            $to = array();
            $cc = array();
            $bcc = array();
            foreach($recipients as $recipientIndex => $recipient) {

                // Sanitize
                $recipient = trim($recipient);
                if(empty($recipient)) continue;

                // Simple recipient-string
                if(empty($recipientIndex)) {
                    $to[] = $recipient;

                // Recipient identified by keyword
                } elseif($recipientIndex == 'to') {
                    $to[] = $recipient;
                } elseif($recipientIndex == 'cc') {
                    $cc[] = $recipient;
                } elseif($recipientIndex == 'bcc') {
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
     * Method to get the current data
     *
     * @access public
     * @param null
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Method to add an email batch to the queue
     *
     * @access public
     * @param null
     * @return bool
     */
    public function save()
    {
        // Load the model and save the data
        require_once JPATH_ADMINISTRATOR.'/components/com_emailscheduler/tables/email.php';
        require_once JPATH_ADMINISTRATOR.'/components/com_emailscheduler/models/email.php';
        $model = new EmailschedulerModelEmail();
        $rt = $model->store($this->data);
        return $rt;
    }

    /**
     * Static method to save all pending emails
     *
     * @access public
     * @param null
     * @return null
     */
    static public function send()
    {
        ini_set('display_errors', 1);
        $query = 'SELECT id FROM #__emailscheduler_emails WHERE send_date < NOW() AND send_state = "pending"';
        $db = JFactory::getDBO();
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if(empty($rows)) {
            return false;
        }

        // Load the model
        require_once JPATH_ADMINISTRATOR.'/components/com_emailscheduler/helpers/helper.php';
        require_once JPATH_ADMINISTRATOR.'/components/com_emailscheduler/tables/email.php';
        require_once JPATH_ADMINISTRATOR.'/components/com_emailscheduler/models/email.php';
        $model = new EmailschedulerModelEmail();
        foreach($rows as $row) {
            $model->setId($row->id);
            $model->send();
        }
    }
}

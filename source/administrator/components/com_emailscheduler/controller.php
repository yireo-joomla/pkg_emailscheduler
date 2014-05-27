<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Emailscheduler Controller
 */
class EmailschedulerController extends YireoController
{
    /**
     * Constructor
     * @package Emailscheduler
     */
    public function __construct()
    {
        $this->_default_view = 'home';
        parent::__construct();
    }

    /**
     * Method to send a specific mail
     *
     * @access public 
     * @param null
     * @return null
     */
    public function send()
    {
        // Get the ID-list
        $cid = $this->getIds();
        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_('LIB_YIREO_CONTROLLER_ITEM_SELECT'));
        }

        // Use the model to publish this entry
        $model = $this->_loadModel();
        foreach($cid as $id) {
            $model->setId($id);
            $model->send();
        }

        // Redirect
        $link = 'index.php?option=com_emailscheduler&view=emails';
        $msg = JText::_('COM_EMAILSCHEDULER_CONTROLLER_PENDING_SENT');
        $this->setRedirect($link, $msg);
    }

    /**
     * Method to run SQL-update queries
     *
     * @access public 
     * @param null
     * @return null
     */
    public function updateQueries()
    {
        // Run the update-queries
        require_once JPATH_COMPONENT.'/helpers/update.php';
        EmailschedulerUpdate::runUpdateQueries();

        // Redirect
        $link = 'index.php?option=com_emailscheduler&view=home';
        $msg = JText::_('LIB_YIREO_CONTROLLER_DB_UPGRADED');
        $this->setRedirect($link, $msg);
    }

    /**
     * Method to delete all sent emails
     *
     * @access public 
     * @param null
     * @return null
     */
    public function deleteSent()
    {
        $db = JFactory::getDBO();

        // Delete all logs
        $db->setQuery('DELETE FROM #__emailscheduler_logs WHERE `email_id` IN (SELECT id FROM #__emailscheduler_emails WHERE `send_state`="sent")');
        $db->query();

        // Delete all emails
        $db->setQuery('DELETE FROM #__emailscheduler_emails WHERE `send_state`="sent"');
        $db->query();

        // Redirect
        $link = 'index.php?option=com_emailscheduler&view=emails';
        $msg = JText::_('COM_EMAILSCHEDULER_CONTROLLER_DELETE_SENT');
        $this->setRedirect($link, $msg);
    }
}

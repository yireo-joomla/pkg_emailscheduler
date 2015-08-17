<?php
/**
 * Joomla! script to add a new email message to the EmailScheduler queue
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Detect the Joomla! directory
define('DOCUMENT_ROOT', dirname(dirname(__FILE__)).'/');

/*
 * Joomla! class
 */
class YireoJoomla 
{
    /*
     * ID of Super Users group
     */
    protected $gid = 25;

    /*
     * Main method to run this installer
     */
    public function __construct()
    {
        // Fake some environment-stuff
        $_SERVER['HTTP_HOST'] = null;

        // PHP settings
        ini_set('display_errors', 0);

        // Neccessary definitions
        define('_JEXEC', 1);
        define('JPATH_BASE', DOCUMENT_ROOT);
        define('DS', DIRECTORY_SEPARATOR );

        // Initialize Joomla!
        $this->initJoomla();
    }

    /*
     * Initialize the Joomla! application
     */
    public function initJoomla()
    {
        // Change the path to the JPATH_BASE
        if(!is_file(JPATH_BASE.DS.'includes'.DS.'framework.php')) {
            die('Incorrect Joomla! base-path');
        }
        chdir(JPATH_BASE);

        // Include the framework
        require_once(JPATH_BASE.DS.'includes'.DS.'defines.php');
        require_once(JPATH_BASE.DS.'includes'.DS.'framework.php');
        jimport('joomla.environment.request');
        jimport('joomla.database.database');

        // Start the application
        $mainframe = JFactory::getApplication('site');
        $mainframe->initialise();
        if(method_exists('JFactory', 'getDbo')) {
            $db = JFactory::getDbo();
        } else {
            $db = JFactory::getDBO();
        }

        // Add Joomla! variables to this object
        $this->db = $db;
        $this->app = $mainframe;

        // Fetch the Super Users group
        $db->setQuery('SELECT id FROM #__usergroups WHERE title = "Super Users" LIMIT 1');
        $this->gid = (int)$db->loadResult();
        if(!$this->gid > 0) {
            die("FATAL ERROR: Unable to find Super Users group\n");
        }

        // Spoof the first admin-user
        $db->setQuery('SELECT user_id FROM #__user_usergroup_map WHERE group_id = '.$this->gid.' LIMIT 1');
        $id = $db->loadResult();
        $my = JFactory::getUser();
        $my->load($id);
    }
}

// Initialize Joomla!
$yireo = new YireoJoomla();

$order = (object)null;
$order->order_id = 4;

JPluginHelper::importPlugin('hikashop');
$dispatcher = JEventDispatcher::getInstance();
$send_email = true;
$results = $dispatcher->trigger('onAfterOrderUpdate', array(&$order, &$send_email));

<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Load the API
require_once JPATH_ADMINISTRATOR.'/components/com_emailscheduler/api.php';
Emailscheduler::send();
JFactory::getApplication()->close();

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
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/loader.php';

// Load the libraries
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/helper.php';

// Make sure the user is authorised to view this page
$application = JFactory::getApplication();
$user        = JFactory::getUser();

// Require the current controller
$view            = $application->input->getCmd('view');
$controller_file = JPATH_COMPONENT . '/controllers/' . $view . '.php';

if (is_file($controller_file))
{
	require_once $controller_file;
	$controller_name = 'EmailschedulerController' . ucfirst($view);
	$controller      = new $controller_name;
}
else
{
	require_once 'controller.php';
	$controller = new EmailschedulerController;
}

// Perform the requested task
$controller->execute($application->input->getCmd('task'));
$controller->redirect();


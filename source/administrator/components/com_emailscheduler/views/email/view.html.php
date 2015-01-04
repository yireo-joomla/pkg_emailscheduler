<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class
 */
class EmailschedulerViewEmail extends YireoViewForm
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
	public function display($tpl = null)
	{
        YireoHelper::jquery();
        $this->document->addScript( JURI::root().'media/com_emailscheduler/js/backend.js' ) ;
        $this->fetchItem();

        // @todo: Allow for selecting attachments

		parent::display($tpl);
	}
}

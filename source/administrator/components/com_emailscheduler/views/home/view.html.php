<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @package Emailscheduler
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

/**
 * HTML View class 
 *
 * @static
 * @package Emailscheduler
 */
class EmailschedulerViewHome extends YireoViewHome
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        $icons = array();
        $icons[] = $this->icon( 'email&task=add', JText::sprintf('LIB_YIREO_VIEW_NEW_X', JText::_('COM_EMAILSCHEDULER_VIEW_EMAIL')), 'new.png', null );
        $icons[] = $this->icon( 'emails', 'COM_EMAILSCHEDULER_VIEW_EMAILS', 'email.png', null );
        $icons[] = $this->icon( 'logs', 'COM_EMAILSCHEDULER_VIEW_LOGS', 'log.png', null );
        $icons[] = $this->icon( 'trigger', 'COM_EMAILSCHEDULER_VIEW_TRIGGER', 'schedule.png', null );
        $this->assignRef( 'icons', $icons );

        $urls = array();
        $urls['twitter'] ='https://twitter.com/yireo';
        $urls['facebook'] ='https://www.facebook.com/yireo';
        $urls['tutorials'] = 'https://www.yireo.com/tutorials/emailscheduler';
        $urls['jed'] = 'https://extensions.joomla.org/extensions/extension/contacts-and-feedback/email/yireo-emailscheduler';
        $this->assignRef( 'urls', $urls );

        JToolBarHelper::custom('updateQueries', 'archive', '', 'DB Upgrade', false);

        parent::display($tpl);
    }
}

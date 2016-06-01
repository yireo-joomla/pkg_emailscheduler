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
class EmailschedulerViewTrigger extends YireoViewForm
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
	public function display($tpl = null)
	{
        YireoHelper::bootstrap();

        $this->fetchItem();

        $data = (array)$this->getModel()->getData();
        $form = $this->getModel()->getForm();
        
        JPluginHelper::importPlugin('emailscheduler');
        if(YireoHelper::isJoomla25()) {
            $dispatcher = JDispatcher::getInstance();
        } else {
            $dispatcher = JEventDispatcher::getInstance();
        }
        $results = $dispatcher->trigger('onEmailschedulerTriggerPrepareForm', array(&$form, &$data));

        $form->bind(array(
            'item' => $data, 
            'actions' => $data['actions'],
            'condition' => $data['condition'],
        ));
        $this->assignRef('form', $form);

		parent::display($tpl);
	}
}

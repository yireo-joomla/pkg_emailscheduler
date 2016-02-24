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
class EmailschedulerViewEmails extends YireoViewList
{
	/*
	 * Display method
	 *
	 * @param string $tpl
	 * @return null
	 */
	public function display($tpl = null)
	{
		// Add clean-button to toolbar
		JToolBarHelper::custom('deleteSent', 'delete.png', 'delete.png', 'Clean sent', false);

		// Create select-filters
		$javascript = 'onchange="document.adminForm.submit();"';
		$this->lists['send_state_filter'] = JHTML::_('select.genericlist', EmailschedulerHelper::getSendStateOptions(true), 'filter_send_state', $javascript, 'value', 'title', $this->getFilter('send_state'));

		parent::display($tpl);
	}
}


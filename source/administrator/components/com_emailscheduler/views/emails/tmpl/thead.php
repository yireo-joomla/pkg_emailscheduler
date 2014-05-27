<?php
/**
 * Joomla! Yireo Library
 *
 * @author Yireo
 * @package YireoLib
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 * @version 0.4.3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<th class="title">
    <?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_SUBJECT', 'email.subject', $this->lists['order_Dir'], $this->lists['order'] ); ?>
</th>
<th class="title">
    <?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_TO', 'email.to', $this->lists['order_Dir'], $this->lists['order'] ); ?>
</th>
<th width="210" class="title">
    <?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_SEND_DATE', 'email.send_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
</th>
<th width="90" class="title">
    <?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_SEND_STATE', 'email.send_state', $this->lists['order_Dir'], $this->lists['order'] ); ?>
</th>
<th width="100" class="title">
    <?php echo JText::_('LIB_YIREO_ACTIONS'); ?>
</th>

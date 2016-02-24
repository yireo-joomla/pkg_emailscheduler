<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author    Yireo (info@yireo.com)
 * @copyright Copyright Yireo.com 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<th class="title">
	<?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_SUBJECT', 'email.subject', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>
<th class="title">
	<?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_TO', 'email.to', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>
<th width="210" class="title">
	<?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_SEND_DATE', 'log.send_date', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>
<th width="90" class="title">
	<?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_SEND_STATE', 'log.send_state', $this->lists['order_Dir'], $this->lists['order']); ?>
</th>

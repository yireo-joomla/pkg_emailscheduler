<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author Yireo
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Do not automatically generate all columns
$auto_columns = false;
?>
<td>
    <?php echo $item->subject; ?>
</td>
<td>
    <?php echo $item->to; ?>
</td>
<td>
    <?php echo $item->send_date; ?>
    (<?php echo EmailschedulerHelper::formatTime($item->send_date); ?>)
</td>
<td>
    <?php echo JText::_('COM_EMAILSCHEDULER_STATE_'.$item->send_state); ?>
</td>

<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author Yireo
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Do not automatically generate all columns
$auto_columns = false;

// Define the actions            
$actions = array();
if($item->send_state == 'pending') {
    $actions['index.php?option=com_emailscheduler&view=email&task=send&id='.$item->id] = JText::_('COM_EMAILSCHEDULER_SEND_EMAIL');
} else {
    $actions['index.php?option=com_emailscheduler&view=email&task=send&id='.$item->id] = JText::_('COM_EMAILSCHEDULER_RESEND_EMAIL');
}
?>
<td>
    <a href="<?php echo JRoute::_('index.php?option=com_emailscheduler&view=email&id='.$item->id); ?>"><?php echo $item->subject; ?></a>
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
<td>
    <?php foreach($actions as $url => $action) { ?>
    <a href="<?php echo $url; ?>" title="<?php echo $action; ?>"><?php echo $action; ?></a>
    <?php } ?>
</td>

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
    <a href="<?php echo JRoute::_('index.php?option=com_emailscheduler&view=template&id='.$item->id); ?>"><?php echo $item->label; ?></a>
</td>
<td>
    <?php echo $item->subject; ?>
</td>

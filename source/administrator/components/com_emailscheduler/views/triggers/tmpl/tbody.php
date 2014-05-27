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
?>
<td>
    <a href="<?php echo JRoute::_('index.php?option=com_emailscheduler&view=trigger&id='.$item->id); ?>"><?php echo $item->label; ?></a>
</td>

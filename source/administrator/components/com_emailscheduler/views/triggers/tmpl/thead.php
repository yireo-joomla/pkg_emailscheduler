<?php
/**
 * Joomla! Yireo Library
 *
 * @author Yireo
 * @package YireoLib
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<th class="title">
    <?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_LABEL', 'trigger.label', $this->lists['order_Dir'], $this->lists['order'] ); ?>
</th>

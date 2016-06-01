<?php
/**
 * Joomla! Yireo Library
 *
 * @author Yireo
 * @package YireoLib
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
 * @version 0.4.3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<th class="title">
    <?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_LABEL', 'template.label', $this->lists['order_Dir'], $this->lists['order'] ); ?>
</th>
<th class="title">
    <?php echo JHTML::_('grid.sort', 'COM_EMAILSCHEDULER_FIELDNAME_SUBJECT', 'template.subject', $this->lists['order_Dir'], $this->lists['order'] ); ?>
</th>

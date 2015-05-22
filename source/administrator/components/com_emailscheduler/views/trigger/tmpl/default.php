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
defined('_JEXEC') or die('Restricted access');

// Set the right image directory for JavaScipt
jimport('joomla.utilities.utility');
?>
<?php echo $this->loadTemplate('script'); ?>

<form method="post" name="adminForm" id="adminForm" role="form">
<div class="row-fluid">
    <div class="span6">
        <?php echo $this->loadTemplate('fieldset', array('fieldset' => 'basic')); ?>
		<?php $conditionMatch = false; ?>
        <?php foreach($this->form->getFieldsets() as $fieldsetName => $fieldset): ?>
            <?php if(preg_match('/^condition/', $fieldsetName)) : ?>
                <?php echo $this->loadTemplate('fieldset', array('fieldset' => $fieldsetName)); ?>
				<?php $conditionMatch = true; ?>
            <?php endif; ?>
        <?php endforeach; ?>
		<?php if ($conditionMatch == false) : ?>
			<fieldset>
				<legend><?php echo JText::_('LIB_YIREO_VIEW_FORM_FIELDSET_TRIGGER'); ?></legend>
				<p><?php echo JText::_('COM_EMAILSCHEDULER_NO_TRIGGERS_FOUND'); ?></p>
			</fieldset>
		<?php endif; ?>
    </div>
    <div class="span6">
        <?php echo $this->loadTemplate('fieldset', array('fieldset' => 'actions')); ?>
        <?php echo $this->loadTemplate('fieldset', array('fieldset' => 'recipients')); ?>
        <?php echo $this->loadTemplate('fieldset', array('fieldset' => 'params')); ?>
    </div>
</div>
<?php echo $this->loadTemplate('formend'); ?>
</form>

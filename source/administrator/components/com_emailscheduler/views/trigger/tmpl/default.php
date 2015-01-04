<?php 
/**
 * Joomla! Yireo Lib
 *
 * @author Yireo
 * @package YireoLib
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com/
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
        <?php foreach($this->form->getFieldsets() as $fieldsetName => $fieldset): ?>
            <?php if(preg_match('/^condition/', $fieldsetName)) : ?>
                <?php echo $this->loadTemplate('fieldset', array('fieldset' => $fieldsetName)); ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="span6">
        <?php echo $this->loadTemplate('fieldset', array('fieldset' => 'actions')); ?>
        <?php echo $this->loadTemplate('fieldset', array('fieldset' => 'recipients')); ?>
        <?php echo $this->loadTemplate('fieldset', array('fieldset' => 'params')); ?>
    </div>
</div>
<?php echo $this->loadTemplate('formend'); ?>
</form>

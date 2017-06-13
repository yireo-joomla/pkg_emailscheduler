<?php
/**
 * Joomla! Yireo Lib
 *
 * @author    Yireo
 * @package   YireoLib
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com/
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Set the right image directory for JavaScipt
jimport('joomla.utilities.utility');
?>
<?php echo $this->loadTemplate('script'); ?>

<form method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="row-fluid">
        <div class="span6">
			<?php echo $this->loadTemplate('fieldset', array('fieldset' => 'basic')); ?>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
			<?php echo $this->loadTemplate('fieldset', array('fieldset' => 'editor')); ?>
        </div>
    </div>
	<?php echo $this->loadTemplate('formend'); ?>
</form>

<div class="hints">
    You can use the following variables:
    <ul>
        <li><code>{body}</code>: Body of the email (most commonly a Joomla article</li>
        <li><code>{subject}</code>: Subject of the email</li>
        <li><code>{email}</code>: Email of recipient</li>
        <li><code>{name}</code>: Joomla name of recipient</li>
        <li><code>{username}</code>: Joomla username of recipient</li>
    </ul>
</div>
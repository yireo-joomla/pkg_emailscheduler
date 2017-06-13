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

$variables = false;
if (isset($this->item->variables)) {
	$variables = $this->item->variables;
}
?>
<h3>Custom variables</h3>
<?php if (empty($variables)) : ?>
    No variables found
<?php else: ?>
<pre>
<?php print_r($variables); ?>
</pre>
<?php endif; ?>


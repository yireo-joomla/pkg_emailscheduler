<?php
/**
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

defined('JPATH_BASE') or die;

/**
 * Form field that adds a time selector
 */
class JFormFieldTime extends JFormField
{
    protected $type = 'Time';

    /**
     * Method to get the field input markup.
     *
     * @return    string    The field input markup.
     */
    protected function getInput()
    {
    }
}

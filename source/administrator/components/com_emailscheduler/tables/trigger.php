<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Trigger Table class
*/
class TableTrigger extends YireoTable
{
    /**
     * Constructor
     *
     * @access public
     * @param JDatabase $db
     * @return null
     */
    public function __construct(& $db)
    {
        // Set the required fields
        $this->_required = array(
            'label',
        );

        // Call the constructor
        parent::__construct('#__emailscheduler_triggers', 'id', $db);
    }
}

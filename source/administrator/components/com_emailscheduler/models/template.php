<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright Yireo.com 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/*
 * Emailscheduler Template model
 */
class EmailschedulerModelTemplate extends YireoModel
{
    /**
     * Constructor method
     *
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        parent::__construct('template');
    }

    /**
     * Method to store the model
     *
     * @access public
     * @subpackage Yireo
     * @param mixed $data
     * @return bool
     */
    public function store($data)
    {
        return parent::store($data);
    }
}

<?php
/*
 * Joomla! component Emailscheduler
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright Yireo.com 2013
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

/*
 * Emailscheduler Logs model
 */
class EmailschedulerModelLogs extends YireoModel
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
        parent::__construct('log');

        $this->_search = array('message');
        $this->_orderby_default = 'email_id';
    }

    /**
     * Method to build the database query
     *
     * @access protected
     * @param null
     * @return mixed
     */
    protected function buildQuery($query = '')
    {
        $query = "SELECT log.*, email.subject, email.to FROM #__emailscheduler_logs AS log \n";
        $query .= " LEFT JOIN #__emailscheduler_emails AS email ON email.id = log.email_id \n";
        return parent::buildQuery($query);
    }

    /**
     * Method to modify the data once it is loaded
     *
     * @access protected
     * @param array $data
     * @return array
     */
    protected function onDataLoad($data)
    {
        return $data;
    }
}

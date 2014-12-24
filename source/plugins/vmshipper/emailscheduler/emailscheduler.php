<?php
/**
 * EmailScheduler plugin - VirtueMart
 *
 * @author Yireo (info@yireo.com)
 * @package EmailScheduler
 * @copyright Copyright 2014
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// Import the parent class
require_once JPATH_SITE.'/administrator/components/com_emailscheduler/plugins/product.php';

/**
 * EmailScheduler plugin - VirtueMart
 */
//class PlgVmshipperEmailscheduler extends EmailschedulerPluginProduct
class PlgVmshipperEmailscheduler extends vmShipperPlugin
{
    public function plgOnConfirmShipper()
    {
        $this->log('plgOnConfirmShipper');
    }

    public function plgVmOnUpdateOrderLine()
    {
        $this->log('plgVmOnUpdateOrderLine');
    }

    public function plgVmOnSaveOrderShipperBE()
    {
        $this->log('plgVmOnSaveOrderShipperBE');
    }

    /**
     * Event "onAfterOrderUpdate"
     * 
     * @access public
     * @param object $order HikaShop order containing all details
     * @param bool $send_email Allow or disable sending email to customer afterwards
     *
     * @return bool
     */
    /*public function onAfterOrderUpdate(&$order,&$send_email)
    {
        $this->log($order);
        return;
        $allowedStatus = array('created', 'confirmed', 'shipped', 'cancelled', 'refunded');
        $productIds = array();

        // Exit if there is no order ID
		if (empty($order->order_id))
        {
            return true;
        }

        // Load the full order
		$orderClass = hikashop_get('class.order');
		$order = $orderClass->loadFullOrder($order->order_id,false ,false);

        // Skip if this status is not allowed
        if (!in_array($order->order_status, $allowedStatus))
        {
            return true;
        }

        // Gather the products
        foreach ($order->products as $product)
        {
            $productIds[] = $product->product_id;
        }
            
        $triggers = $this->getTriggers(array('condition' => '%hikashop.product%'));

        if (empty($triggers))
        {
            return true;
        }

        // Loop through the triggers to find a match
        foreach ($triggers as $trigger)
        {
            $product = (int)$trigger->condition['hikashop.product'];

            if (in_array($product, $productIds))
            {
                $trigger->actions['user_email'] = $order->customer->user_email;
                $this->doActions($trigger->actions, $trigger->params);
            }
        }

        return true;
    }
    */
}

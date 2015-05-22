<?php
/**
 * EmailScheduler plugin - HikaShop
 *
 * @author    Yireo (info@yireo.com)
 * @package   EmailScheduler
 * @copyright Copyright 2015
 * @license   GNU Public License
 * @link      http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Import the parent class
require_once JPATH_SITE . '/administrator/components/com_emailscheduler/plugins/product.php';

/**
 * EmailScheduler plugin - HikaShop
 */
class PlgHikashopEmailscheduler extends EmailschedulerPluginProduct
{
	/**
	 * Event "onAfterOrderCreate"
	 *
	 * @access public
	 *
	 * @param object $order      HikaShop order containing all details
	 * @param bool   $send_email Allow or disable sending email to customer afterwards
	 *
	 * @return bool
	 */
	public function onAfterOrderCreate(&$order, &$send_email)
	{
		return $this->onAfterOrderUpdate($order, $send_email);
	}

	/**
	 * Event "onAfterOrderUpdate"
	 *
	 * @access public
	 *
	 * @param object $order      HikaShop order containing all details
	 * @param bool   $send_email Allow or disable sending email to customer afterwards
	 *
	 * @return bool
	 */
	public function onAfterOrderUpdate(&$order, &$send_email)
	{
		$allowedStatus = array('created', 'confirmed', 'shipped', 'cancelled', 'refunded');
		$productIds = array();

		// Exit if there is no order ID
		if (empty($order->order_id))
		{
			return true;
		}

		// Load the full order
		$orderClass = hikashop_get('class.order');
		$order = $orderClass->loadFullOrder($order->order_id, false, false);

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
			$product = (int) $trigger->condition['hikashop.product'];

			if (in_array($product, $productIds))
			{
				// @todo: Add additional variables via $trigger->params['variables'] array


				$trigger->actions['user_email'] = $order->customer->user_email;
				$this->doActions($trigger->actions, $trigger->params);
			}
		}

		return true;
	}
}

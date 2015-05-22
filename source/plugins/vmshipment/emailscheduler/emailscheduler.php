<?php
/**
 * EmailScheduler plugin - VirtueMart
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
 * EmailScheduler plugin - VirtueMart
 */
class PlgVmShipmentEmailscheduler extends EmailschedulerPluginProduct
{
	/**
	 * Event "plgVmConfirmedOrder"
	 *
	 * @param VirtueMartCart $cart  VirtueMart shopping cart object
	 * @param array          $order Array containing all order information
	 *
	 * @return bool
	 */
	public function plgVmConfirmedOrder(VirtueMartCart $cart, $order)
	{
		// Exit if there is no order ID
		if (empty($order['details']['BT']))
		{
			return true;
		}

		// Handle the order
		$this->handleOrder($order);
	}

	/**
	 * Event "plgVmOnUpdateOrderShipment"
	 *
	 * @param object $data             Object of order data being changed
	 * @param mixed  $old_order_status Old order status
	 *
	 * @return bool
	 */
	public function plgVmOnUpdateOrderShipment($data, $old_order_status)
	{
		// Exit if there is no order ID
		$order_id = $data->virtuemart_order_id;

		if (empty($order_id))
		{
			return true;
		}

		// Load the full order
		$orderModel = VmModel::getModel();
		$order = $orderModel->getOrder($order_id);

		// Exit if there is no order ID
		if (empty($order['details']['BT']))
		{
			return true;
		}

		// Handle the order
		$this->handleOrder($order);
	}

	/**
	 * Helper method to handle email triggers for this order
	 *
	 * @param object $order Order object
	 *
	 * @return bool
	 */
	public function handleOrder($order)
	{
		// Gather the product IDs and SKUs
		$productIds = array();
		$productSkus = array();

		foreach ($order['items'] as $product)
		{
			$productIds[] = $product->virtuemart_product_id;
			$productSkus[] = $product->order_item_sku;
		}

		$triggers = $this->getTriggers(array('condition' => '%virtuemart3.product%'));

		if (empty($triggers))
		{
			return true;
		}

		// Loop through the triggers to find a match
		foreach ($triggers as $trigger)
		{
			$product = trim($trigger->condition['virtuemart3.product']);
			$statuses = $trigger->condition['virtuemart3.order_status'];

			if (!in_array($product, $productIds))
			{
				continue;
			}

			if (!empty($statuses) && !in_array($order['details']['BT']->order_status, $statuses))
			{
				$this->log('no');
				continue;
			}

			// @todo: Add additional variables via $trigger->params['variables'] array

			$trigger->actions['user_email'] = $order['details']['BT']->email;
			$this->doActions($trigger->actions, $trigger->params);
		}

		return true;
	}
}

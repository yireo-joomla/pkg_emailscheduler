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
		$helperFile = JPATH_ADMINISTRATOR . '/components/com_hikashop/helpers/helper.php';

		if (file_exists($helperFile) == false)
		{
			return true;
		}

		include_once $helperFile;

		// Exit if there is no order ID
		if (empty($order->order_id))
		{
			return true;
		}

		// Load the full order
		$orderClass = hikashop_get('class.order');
		$order = $orderClass->loadFullOrder($order->order_id, false, false);

		if (empty($order->order_id))
		{
			return true;
		}

		// Skip if this status is not allowed
		$allowedStatus = array('created', 'confirmed', 'shipped', 'cancelled', 'refunded');

		if (!in_array($order->order_status, $allowedStatus))
		{
			return true;
		}

		// Gather the products
		$productIds = array();

		foreach ($order->products as $product)
		{
			$productIds[] = $product->product_id;
		}

		// Gather custom variables
		$customVariables = array();
		$customVariables['products'] = $this->getProductsExtract($productIds);
		$customVariables['order'] = $this->getOrderExtract($order);
		$customVariables['customer'] = $this->getCustomerExtract($order);

		// Gather the triggers
		$triggers = $this->getTriggers(array('condition' => '%hikashop.product%'));

		if (empty($triggers))
		{
			return true;
		}

		// Loop through the triggers to find a match
		foreach ($triggers as $trigger)
		{
			$products = $trigger->condition['hikashop.product'];

			if (empty($products))
			{
				continue;
			}

			$trigger->params['variables'] = $customVariables;

			$productFound = false;

			foreach ($products as $product)
			{
				if (!in_array($product, $productIds) && !in_array(strtolower($product), array('*', 'all')))
				{
					continue;
				}

				$productFound = true;
			}

			if ($productFound)
			{
				$trigger->actions['user_email'] = $order->customer->user_email;
				$this->doActions($trigger->actions, $trigger->params);
			}
		}

		return true;
	}

	/**
	 * Gather a simple extract of the HikaShop order
	 *
	 * @param $order
	 *
	 * @return array
	 */
	public function getOrderExtract($order)
	{
		return array(
			'id' => $order->order_id,
			'status' => $order->order_status,
			'created' => $order->order_created,
			'invoice_number' => $order->order_invoice_number,
		);
	}

	/**
	 * Gather a simple extract of the HikaShop customer
	 *
	 * @param $order
	 *
	 * @return array
	 */
	public function getCustomerExtract($order)
	{
		if (isset($order->billing_address))
		{
			$address = $order->billing_address;
		}
		else
		{
			$address = $order->shipping_address;
		}

		return array(
			'firstname' => $address->address_firstname,
			'lastname' => $address->address_lastname,
		);
	}

	/**
	 * Gather a simple array of the purchased HikaShop products
	 *
	 * @param array $ids
	 *
	 * @return array
	 */
	public function getProductsExtract($ids = array())
	{
		$db = JFactory::getDbo();

		/** @var JDatabaseQuery $query */
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('p.product_id', 'p.product_name', 'p.product_code')));
		$query->from($db->quoteName('#__hikashop_product', 'p'));
		$query->where($db->quoteName('product_id') . ' IN (' . implode(',', $ids) . ')');

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$matches = array();

		foreach ($rows as $row)
		{
			$matches[] = array(
				'id' => $row->product_id,
				'sku' => $row->product_code,
				'name' => $row->product_name,
			);
		}

		return $matches;
	}
}

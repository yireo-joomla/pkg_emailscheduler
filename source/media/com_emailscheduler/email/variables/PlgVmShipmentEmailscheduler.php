<?php
defined('_JEXEC') or die;

/*
// Log all current variables to a logfile
$logFile = JPATH_SITE . '/logs/com_emailscheduler.log';
file_put_contents($logFile, var_export($variables, true)."\n", FILE_APPEND);
*/

/*
// Convert all canonical URLs to real Joomla URLs
foreach ($variables['products'] as $productId => $product)
{
    $product['canonical'] = JRoute::_($product['canonical']);
    $variables['products'][$productId] = $product;
}
*/

/*
// Properly format pricing
setlocale(LC_MONETARY, 'en_US');
$variables['bt']['order_total'] = money_format('%i', $variables['bt']['order_total']);
*/


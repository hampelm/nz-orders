<?php

include 'Shopify.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $shopify = new Shopify;
   $shopify->createOrder($_POST);
   $shopify->shipOrder();
}

// Create some test items
// $order = new Order;
// $item1 = new Item;
// $item2 = new Item;
// $items = Array($item1, $item2);
// $order->items = $items;
// $order->writeXML();

?>

<?php

include 'Shopify.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'));
  $shopify = new Shopify;
  $shopify->createOrder($data);
  $shopify->shipOrder('http://requestb.in/1dsa59w1');
}

?>

<?php

include 'Order.php';


/**
 * Tools for turning a Shopify order notification into
 * Order and Item objects.
 *
 * http://docs.shopify.com/api/tutorials/using-webhooks#webhook-events
 * http://docs.shopify.com/api/webhook
 */
class Shopify {

  public $data; // the decoded JSON
  public $order; // the structured order
  public $result; // the resonse from fulfillment


  /**
   * Creates an order from POSTed JSON
   * @param Array $json
   */
  public function createOrder($json) {
    $data = json_decode($json, true);
    $order = new Order;

    print_r($data);
    print_r($order);
  }


  /**
   * Ships an order off to the fulfillment system
   */
  public function shipOrder() {
    $xml = $order->writeXML();

    // $ch = curl_init('http://requestb.in/1dsa59w1');
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    // curl_setopt($ch, CURLOPT_HEADER, 0);
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_builder);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    // curl_setopt($ch, CURLOPT_REFERER, 'http://neutral-zone.org');
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $ch_result = curl_exec($ch);
    // curl_close($ch);
    // // Print CURL result.
    // echo $ch_result;
  }
}

?>

<?php

inclue 'Order.php';

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
  }

  /**
   * Ships an order off to the fulfillment system
   */
  public function shipOrder() {
    $ch = curl_init('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_builder);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.bharath..co.uk');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $ch_result = curl_exec($ch);
    curl_close($ch);
    // Print CURL result.
    echo $ch_result;

  }


}

?>

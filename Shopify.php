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
  public function createOrder($data) {

    // Parse the shipping data
    $empty = '';
    $address = $data->shipping_address;
    print_r($address);

    $order = new Order;
    $order->orderNumber = $data->id;
    $order->name = ($address->name ? $address->name : $empty);
    $order->addr1 = ($address->address1 ? $address->address1 : $empty);
    $order->addr2 = ($address->address2 ? $address->address2 : $empty);
    $order->city = ($address->city ? $address->city : $empty);
    $order->state = ($address->province_code ? $address->province_code : $empty);
    $order->zip = ($address->zip ? $address->zip : $empty);
    $order->phone = ($address->phone ? $address->phone : $empty);
    $this->order = $order;

    // Parse the items
    $items = $data->line_items;
    $i = 1;
    foreach ($items as &$item) {
      $lineNumber = $i;
      $itemId = $item->sku;
      $quantity = $item->quantity;
      $this->order->addItem($lineNumber, $itemId, $quantity);
      $i +=1;
    }

  }


  /**
   * Ships an order off to the fulfillment system
   */
  public function shipOrder($endpoint) {
    // print_r($this->order);
    $this->order->ship($endpoint);
  }
}

?>

<?php

include 'Item.php';

/**
 * A generic structure for Orders.
 * Also, tools for exporting Orders and their Items as XML
 * that the fulfilment service can handle.
 *
 */
class Order {
   private $debug = TRUE;

   public $addr1;
   public $addr2;
   public $city;
   public $items = array();
   public $state;
   public $zip;
   public $phone;

   public function addItem($lineNumber, $itemId, $quantity) {
      $item = new Item;
      $item->lineNumber= $lineNumber;
      $item->itemId = $itemId;
      $item->quantity = $quantity;
      array_push($this->items, $item);
   }

   public function generateXML() {
      $writer = new XMLWriter();
      $writer->openMemory();
      $writer->startDocument('1.0','UTF-8');
      $writer->setIndent(4);
      $writer->startElement('OrderBatch');
         $writer->writeAttribute('Version', '1.0');
         $writer->startElement('FulfillmentOrder');
            $writer->writeElement('Line1', $this->addr1);
            $writer->writeElement('Line2', $this->addr2);
            $writer->writeElement('City', $this->city);
            $writer->writeElement('StateProvidenceCode', $this->state);
            $writer->writeElement('PostalZipCode', $this->zip);


            foreach ($this->items as &$item) {
               $writer->startElement('FulfillmentOrderItemDetail');
                  $writer->writeElement('LineItem', $item->lineNumber);
                  $writer->writeElement('ItemID', $item->itemId);
                  $writer->writeElement('OrderQuantity', $item->quantity);
               $writer->endElement();
            }

         $writer->endElement();

         if ($this->debug) {
            $writer->writeElement('TestOrderFlag', '1');
         }

      $writer->endElement();
      $writer->endDocument();
      $xml = $writer->outputMemory();
      return $xml;
   }

   public function ship($url) {
      $xml = $this->generateXML();

      $post_data = array('xml' => $xml);
      $stream_options = array(
          'http' => array(
              'method'  => 'POST',
              'header'  => 'Content-type: application/x-www-form-urlencoded' . "\r\n",
              'content' =>  http_build_query($post_data)));

      $context  = stream_context_create($stream_options);
      $response = file_get_contents($url, null, $context);
      print_r($response);


      // $ch = curl_init();
      // curl_setopt($ch, CURLOPT_HEADER, 0);
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      // curl_setopt($ch, CURLOPT_URL, $endpoint);
      // curl_setopt($ch, CURLOPT_POST, 1);
      // curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
      // $content=curl_exec($ch);
   }

}

?>

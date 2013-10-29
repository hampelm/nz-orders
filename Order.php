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
   private $CUSTNO = 8820;

   public $orderNumber; // we use the shopify order id here.
   public $name;
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
         $writer->writeElement('CustNo', $this->CUSTNO);

         $writer->startElement('FulfillmentOrder');
            $writer->writeAttribute('OrderNumber', $this->orderNumber); 
            $writer->writeElement('FreightCode', 'U11');

            $writer->startElement('ShipToAddress');
               $writer->writeElement('Attention', $this->name);
               $writer->writeElement('Line1', $this->addr1);
               $writer->writeElement('Line2', $this->addr2);
               $writer->writeElement('City', $this->city);
               $writer->writeElement('StateProvinceCode', $this->state);
               $writer->writeElement('PostalZipCode', $this->zip);
               $writer->writeElement('Domestic');
            $writer->endElement(); // end ShipToAddress

            foreach ($this->items as &$item) {
               $writer->startElement('FulfillmentOrderItemDetail');
                  $writer->writeElement('LineNumber', $item->lineNumber);
                  $writer->writeElement('ItemID', $item->itemId);
                  $writer->writeElement('OrderQuantity', $item->quantity);
               $writer->endElement();
            }
         $writer->endElement(); // End FulfillmentOrder

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

      // We send XML via CURL using POST with a http header of text/xml.
      $ch = curl_init();

      // set URL and other appropriate options
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($ch, CURLOPT_REFERER, 'http://requestb.in/1dsa59w1');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return the result on
                                                   // success, FALSE on failure
      $ch_result = curl_exec($ch);

      // Print CURL result.
      // echo $ch_result;

      curl_close($ch);
   }

}

?>

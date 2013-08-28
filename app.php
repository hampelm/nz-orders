<?php
class Order {
   private $debug = TRUE;

   public $addr1;
   public $addr2;
   public $city;
   public $email;
   public $items = array();
   public $number;
   public $state;
   public $name;
   public $zip;
   public $phone;

   public function writeXML() {
      $writer = new XMLWriter();
      $writer->openURI('php://output');
      $writer->startDocument('1.0','UTF-8');
      $writer->setIndent(4);
      $writer->startElement('OrderBatch');
         $writer->writeAttribute('Version', '1.0');
         $writer->startElement('FulfillmentOrder');
            $writer->writeElement('CompanyName', $name);
            $writer->writeElement('Line1', $addr1);
            $writer->writeElement('Line2', $addr2);
            $writer->writeElement('City', $city);
            $writer->writeElement('StateProvidenceCode', $state);
            $writer->writeElement('PostalZipCode', $zip);


            foreach ($this->items as &$item) {
               $writer->startElement('FulfillmentOrderItemDetail');
                  $writer->writeElement('LineItem', $item->lineNumber);
                  $writer->writeElement('ItemID', $item->itemId);
                  $writer->writeElement('OrderQuantity', $item->quantity);
               $writer->endElement();
            }

         $writer->endElement();

         if ($debug) {
            $writer->writeElement('TestOrderFlag', '1');
         }

      $writer->endElement();
      $writer->endDocument();
      $writer->flush();
   }

}

class Item {
   public $lineNumber;
   public $itemId;
   public $quantity;
}

// Create some test items
$order = new Order;
$item1 = new Item;
$item2 = new Item;
$items = Array($item1, $item2);
$order->items = $items;
$order->writeXML();


?>

<?php
/**
 * Delete Order, Invoices, Shipments and Creditmemos programatically 
 * with standalone php file using magento bootstrap.
 * 
 * Tested on Magento 2.2.6 and 2.3.1
 * 
 * Using $_debugMode for test before.
 * 
 */
ini_set('error_reporting', E_ALL);
ini_set("display_errors", "1");
ini_set("memory_limit","1024M");

use Magento\Framework\App\Bootstrap;
require 'app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$registry = $objectManager->get('Magento\Framework\Registry');
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$_debugMode = true;

$ids = array(1,2,3,4); // your order_id 

echo "<pre>Begin... <br />";

if($_debugMode){
    echo "Debug mode is ENABLED. Orders don't be deleted. <br />";
}

foreach ($ids as $id) {
    echo "--------------------------------------------------------- <br/>";
    $order = $objectManager->create('Magento\Sales\Model\Order')->load($id);



   if(!empty($order->getId())){
        $registry->register('isSecureArea','true');

        // Delete all invoices from Order.
        $_invoices = $order->getInvoiceCollection();

        if($_invoices){
            foreach($_invoices as $invoice){
               (!$_debugMode) ? $invoice->delete() : '';
               echo "InvoiceID:".$invoice->getId()."<br />";
            }
        }

        // Delete all Shipments from Order.
        $_shipments = $order->getShipmentsCollection();

        if($_shipments){
            foreach($_shipments as $shipment){
                (!$_debugMode) ? $shipment->delete() : '';
                echo "ShipmentID:".$shipment->getId()."<br />";
            }
        }

        // Delete all CreditMemos from Order.
        $_creditmemos = $order->getCreditmemosCollection();

        if($_creditmemos){
            foreach($_creditmemos as $creditmemo){
                (!$_debugMode) ? $creditmemo->delete() : '';
               echo "CreditMemoID:".$creditmemo->getId()."<br />";
            }
        }

        (!$_debugMode) ? $order->delete() : '';



        $registry->unregister('isSecureArea');
        echo "Order ".$id." deleted <br />";
   }else{
       echo "Order $id not found. <br/>";
   }
}

echo "<br />Finished! <br /></pre>";
<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            vPGjkQHqxXo20xCC7zQ8CGcLxhRkBY+cGe1+8TjDIvI=
 * Last Modified: 2016-05-31T21:06:16+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Order/General.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Order_General extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    private $_origWriteArray;

    public function getConfiguration()
    {
        return array(
            'name' => 'General order information',
            'category' => 'Order',
            'description' => 'Export extended order information from the sales_flat_order table.',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO, Xtento_OrderExport_Model_Export::ENTITY_AWRMA, Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA),
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        // Fetch fields to export
        $order = $collectionItem->getOrder();
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_ORDER) {
            $this->_writeArray = & $returnArray; // Write directly on order level
        } else {
            $this->_writeArray = & $returnArray['order']; // Write on a subnode so the order details can be accessed for invoices/shipments/credit memos
            // Timestamps of creation/update
            if ($this->fieldLoadingRequired('created_at_timestamp')) $this->writeValue('created_at_timestamp', Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($order->getCreatedAt()));
            if ($this->fieldLoadingRequired('updated_at_timestamp')) $this->writeValue('updated_at_timestamp', Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($order->getUpdatedAt()));
            $this->writeValue('entity_id', $order->getEntityId());
        }
        $this->_origWriteArray = & $this->_writeArray;

        // Nicer store name
        $this->writeValue('store_name_orig', $order->getStoreName());
        $this->writeValue('store_name', preg_replace('/[^A-Za-z0-9- ]/', ' - ', $order->getStoreName()));

        // General order data
        foreach ($order->getData() as $key => $value) {
            if ($key == 'entity_id' || $key == 'store_name') {
                continue;
            }
            $this->writeValue($key, $value);
        }

        // Sample code to export data from a 3rd party table
        /*if ($order->getId()) {
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            //$tableName = $resource->getTableName('catalog/product');
            $query = 'SELECT admin_name FROM salesrep WHERE order_id = ' . (int)$order->getId() . ' LIMIT 1';
            $adminUser = $readConnection->fetchOne($query);
            $this->writeValue('admin_name', $adminUser);
        }*/

        // Last invoice, shipment, credit memo ID
        if ($order->getInvoiceCollection() && $order->hasInvoices() && ($this->fieldLoadingRequired('invoice_increment_id') || $this->fieldLoadingRequired('invoice_created_at_timestamp') || $this->fieldLoadingRequired('invoice_updated_at_timestamp') || $this->fieldLoadingRequired('invoice_count'))) {
            $invoiceCollection = $order->getInvoiceCollection();
            $this->writeValue('invoice_count', $invoiceCollection->getSize());
            if (!empty($invoiceCollection)) {
                $lastInvoice = $invoiceCollection->getLastItem();
                $this->writeValue('invoice_increment_id', $lastInvoice->getIncrementId());
                $this->writeValue('invoice_created_at_timestamp', Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($lastInvoice->getCreatedAt()));
                $this->writeValue('invoice_updated_at_timestamp', Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($lastInvoice->getUpdatedAt()));
            }
        }
        if ($order->getShipmentsCollection() && $order->hasShipments() && ($this->fieldLoadingRequired('shipment_increment_id') || $this->fieldLoadingRequired('shipment_created_at_timestamp') || $this->fieldLoadingRequired('shipment_updated_at_timestamp') || $this->fieldLoadingRequired('shipment_count'))) {
            $shipmentCollection = $order->getShipmentsCollection();
            $this->writeValue('shipment_count', $shipmentCollection->getSize());
            if (!empty($shipmentCollection)) {
                $lastShipment = $shipmentCollection->getLastItem();
                $this->writeValue('shipment_increment_id', $lastShipment->getIncrementId());
                $this->writeValue('shipment_created_at_timestamp', Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($lastShipment->getCreatedAt()));
                $this->writeValue('shipment_updated_at_timestamp', Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($lastShipment->getUpdatedAt()));
            }
        }
        if ($order->getCreditmemosCollection() && $order->hasCreditmemos() && ($this->fieldLoadingRequired('creditmemo_increment_id') || $this->fieldLoadingRequired('creditmemo_created_at_timestamp') || $this->fieldLoadingRequired('creditmemo_updated_at_timestamp') || $this->fieldLoadingRequired('creditmemo_count'))) {
            $creditmemoCollection = $order->getCreditmemosCollection();
            $this->writeValue('creditmemo_count', $creditmemoCollection->getSize());
            if (!empty($creditmemoCollection)) {
                $lastCreditmemo = $creditmemoCollection->getLastItem();
                $this->writeValue('creditmemo_increment_id', $lastCreditmemo->getIncrementId());
                $this->writeValue('creditmemo_created_at_timestamp', Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($lastCreditmemo->getCreatedAt()));
                $this->writeValue('creditmemo_updated_at_timestamp', Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($lastCreditmemo->getUpdatedAt()));
            }
        }

        // Gift message
        if ($order->getGiftMessageId() && $this->fieldLoadingRequired('gift_message')) {
            $giftMessageModel = Mage::getModel('giftmessage/message')->load($order->getGiftMessageId());
            if ($giftMessageModel->getId()) {
                $this->writeValue('gift_message_sender', $giftMessageModel->getSender());
                $this->writeValue('gift_message_recipient', $giftMessageModel->getRecipient());
                $this->writeValue('gift_message', $giftMessageModel->getMessage());
            }
        } else {
            $this->writeValue('gift_message_sender', '');
            $this->writeValue('gift_message_recipient', '');
            $this->writeValue('gift_message', '');
        }

        // Enterprise Gift Wrapping information
        if ($this->fieldLoadingRequired('enterprise_giftwrapping') && Mage::helper('xtcore/utils')->getIsPEorEE()) {
            if ($order->getGwId()) {
                $this->_writeArray['enterprise_giftwrapping'] = array();
                $this->_writeArray =& $this->_writeArray['enterprise_giftwrapping'];
                $wrapping = Mage::getModel('enterprise_giftwrapping/wrapping')->load($order->getGwId());
                if ($wrapping->getId()) {
                    foreach ($wrapping->getData() as $key => $value) {
                        $this->writeValue($key, $value);
                    }
                    $this->writeValue('image_url', $wrapping->getImageUrl());
                }
                $this->_writeArray = & $this->_origWriteArray;
            }
        }

        // Serialized gift_cards column on sales/order level
        if ($this->fieldLoadingRequired('giftcards')) {
            $this->_writeArray['giftcards'] = array();
            $giftCardsArray = & $this->_writeArray['giftcards'];
            if ($order->getData('gift_cards')) {
                #$giftCardSerialized = 'a:1:{i:0;a:5:{s:1:"i";s:1:"1";s:1:"c";s:12:"01S003ZRDKQD";s:1:"a";d:10.99;s:2:"ba";d:10.99;s:10:"authorized";d:10.99;}}';
                $giftCardSerialized = $order->getData('gift_cards');
                $giftCards = @unserialize($giftCardSerialized);
                if (!empty($giftCards) && is_array($giftCards)) {
                    foreach ($giftCards as $giftCard) {
                        $this->_writeArray = & $giftCardsArray[];
                        foreach ($giftCard as $key => $value) {
                            $this->writeValue($key, $value);
                        }
                    }
                }
            }
            $this->_writeArray = & $this->_origWriteArray;
        }

        // Done
        return $returnArray;
    }
}
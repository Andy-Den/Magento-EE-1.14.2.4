<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            vPGjkQHqxXo20xCC7zQ8CGcLxhRkBY+cGe1+8TjDIvI=
 * Last Modified: 2012-12-17T16:04:38+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Condition/Address/Shipping.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Condition_Address_Shipping extends Xtento_OrderExport_Model_Export_Condition_Object
{
    public function loadAttributeOptions()
    {
        $attributes = array(
            'postcode' => Mage::helper('salesrule')->__('Shipping Postcode'),
            'region' => Mage::helper('salesrule')->__('Shipping Region'),
            'region_id' => Mage::helper('salesrule')->__('Shipping State/Province'),
            'country_id' => Mage::helper('salesrule')->__('Shipping Country'),
        );

        $this->setAttributeOption($attributes);
        return $this;
    }

    /**
     * Validate Address Rule Condition
     *
     * @param Varien_Object $object
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
        $address = $object;
        if (!$address instanceof Mage_Sales_Model_Order_Address) {
            $address = $object->getShippingAddress();
            if (!$address) {
                return false;
            }
        }

        return $this->validateAttribute($address->getData($this->getAttribute()));
    }
}

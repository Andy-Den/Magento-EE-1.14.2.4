<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            vPGjkQHqxXo20xCC7zQ8CGcLxhRkBY+cGe1+8TjDIvI=
 * Last Modified: 2012-12-17T16:04:48+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Condition/Address/Billing.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Condition_Address_Billing extends Xtento_OrderExport_Model_Export_Condition_Object
{
    public function loadAttributeOptions()
    {
        $attributes = array(
            'postcode' => Mage::helper('salesrule')->__('Billing Postcode'),
            'region' => Mage::helper('salesrule')->__('Billing Region'),
            'region_id' => Mage::helper('salesrule')->__('Billing State/Province'),
            'country_id' => Mage::helper('salesrule')->__('Billing Country'),
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
            $address = $object->getBillingAddress();
        }

        return $this->validateAttribute($address->getData($this->getAttribute()));
    }
}

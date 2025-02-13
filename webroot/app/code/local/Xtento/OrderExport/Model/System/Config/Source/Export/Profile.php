<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            vPGjkQHqxXo20xCC7zQ8CGcLxhRkBY+cGe1+8TjDIvI=
 * Last Modified: 2013-07-25T17:26:20+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/System/Config/Source/Export/Profile.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_System_Config_Source_Export_Profile
{
    public function toOptionArray($all = false, $entity = false, $getLastExportedId = false)
    {
        $profileCollection = Mage::getModel('xtento_orderexport/profile')->getCollection();
        if (!$all) {
            $profileCollection->addFieldToFilter('enabled', 1);
            $profileCollection->addFieldToFilter('manual_export_enabled', 1);
        }
        if ($entity) {
            $profileCollection->addFieldToFilter('entity', $entity);
        }
        $profileCollection->getSelect()->order('entity ASC');
        $returnArray = array();
        foreach ($profileCollection as $profile) {
            $lastExportedId = '';
            if ($getLastExportedId) {
                $lastExportedId = $profile->getLastExportedIncrementId();
            }
            $returnArray[] = array(
                'profile' => $profile,
                'value' => $profile->getId(),
                'label' => $profile->getName(),
                'entity' => $profile->getEntity(),
                'last_exported_increment_id' => $lastExportedId
            );
        }
        if (empty($returnArray)) {
            $returnArray[] = array(
                'profile' => new Varien_Object(),
                'value' => '',
                'label' => Mage::helper('xtento_orderexport')->__('No profiles available. Add and enable export profiles for the %s entity first.', $entity),
                'entity' => '',
                'last_exported_increment_id' => ''
            );
        }
        return $returnArray;
    }
}

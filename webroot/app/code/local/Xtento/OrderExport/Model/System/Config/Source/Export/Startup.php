<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            vPGjkQHqxXo20xCC7zQ8CGcLxhRkBY+cGe1+8TjDIvI=
 * Last Modified: 2012-12-03T21:38:02+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/System/Config/Source/Export/Startup.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_System_Config_Source_Export_Startup
{

    public function toOptionArray()
    {
        $pages = array();
        foreach (Mage::getBlockSingleton('xtento_orderexport/adminhtml_widget_menu')->getMenu() as $controllerName => $entryConfig) {
            if (!$entryConfig['is_link']) {
                continue;
            }
            $pages[] = array('value' => $controllerName, 'label' => Mage::helper('xtento_orderexport')->__($entryConfig['name']));
        }
        return $pages;
    }

}
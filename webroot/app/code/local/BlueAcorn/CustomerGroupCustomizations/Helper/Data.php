<?php
/**
 * @package     BlueAcorn\CustomerGroupCustomizations
 * @version     1.0.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2015 Blue Acorn, Inc.
 */
class BlueAcorn_CustomerGroupCustomizations_Helper_Data extends Mage_Core_Helper_Abstract {

    const NO_CATEGORY_SELECTED = -1;

    public function getCategoriesThatCanBeLinked() {
        $categories = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToFilter('use_in_customer_groups', '1')
            ->addAttributeToSelect('name');

        $options = array();

        $optionFields=array();
        $optionFields['value'] = 'entity_id';
        $optionFields['label'] = 'name';

        //  Add an empty first option
        $options[] = array('value' => -1, 'label' => '-- None --' );

        // Build the options for dropdown from available categories
        if($categories->getSize() > 0) {
            foreach ($categories as $category) {
                foreach ($optionFields as $code => $field) {
                    $data[$code] = $category->getData($field);
                }
                $options[] = $data;
            }
        }
        return $options;
    }

    public function getCategoryFromCurrentCustomerGroup() {
        $customerGroup = Mage::getModel('customer/group')->load(Mage::getSingleton('customer/session')->getCustomerGroupId());

        if(!$customerGroup) {
            return null;
        }
        else {
            $categoryId = $customerGroup->getLinkedCategory();
            // No category is associated with this group
            if($categoryId == self::NO_CATEGORY_SELECTED) {
                return null;
            }
            else {
                return Mage::getModel('catalog/category')->load($categoryId);
            }
        }
    }
}
<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
/* ------ shipperhq_location -------- */
$installer->addAttribute('catalog_product', 'shipperhq_location', array(
    'type'                     => 'text',
    'backend'                  => 'eav/entity_attribute_backend_array',
    'input'                    => 'multiselect',
    'label'                    => 'Pickup Location',
    'global'                   => false,
    'visible'                  => 1,
    'required'                 => 0,
    'visible_on_front'         => 0,
    'is_html_allowed_on_front' => 0,
    'searchable'               => 0,
    'filterable'               => 0,
    'comparable'               => 0,
    'is_configurable'          => 0,
    'unique'                   => false,
    'user_defined'			   => true,
    'used_in_product_listing'  => false
));

$installer->endSetup();




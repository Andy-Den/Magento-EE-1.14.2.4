<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Adminhtml store delete group block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Mage_Adminhtml_Block_System_Store_Delete_Group extends Mage_Adminhtml_Block_Template
{
    protected function _prepareLayout()
    {
        $itemId = $this->getRequest()->getParam('group_id');

        $this->setTemplate('system/store/delete_group.phtml');
        $this->setAction($this->getUrl('*/*/deleteGroupPost', array('group_id'=>$itemId)));
        $this->setChild('confirm_deletion_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('core')->__('Delete Store'),
                    'onclick'   => "deleteForm.submit()",
                    'class'     => 'cancel'
                ))
        );
        $onClick = "setLocation('".$this->getUrl('*/*/editGroup', array('group_id'=>$itemId))."')";
        $this->setChild('cancel_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('core')->__('Cancel'),
                    'onclick'   => $onClick,
                    'class'     => 'cancel'
                ))
        );
        $this->setChild('back_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('core')->__('Back'),
                    'onclick'   => $onClick,
                    'class'     => 'cancel'
                ))
        );
        return parent::_prepareLayout();
    }
}

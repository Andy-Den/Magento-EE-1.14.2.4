<?php

class Cminds_MultiUserAccounts_Block_Adminhtml_SubAccount_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('user_subaccount');
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/subAccount/grid', array('_current' => true));
    }

    /**
     * Retrieve row url
     *
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/subAccount/edit', array('id' => $item->getId()));
    }

    /**
     * Prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('cminds_multiuseraccounts/subAccount_collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('ID'),
            'index' => 'entity_id',
            'type' => 'text'
        ));

        $this->addColumn('email', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('Email'),
            'index' => 'email',
            'type' => 'text'
        ));

        $this->addColumn('firstname', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('First Name'),
            'index' => 'firstname',
            'type' => 'text',
            'escape' => true
        ));

        $this->addColumn('lastname', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('Last Name'),
            'index' => 'lastname',
            'type' => 'text',
            'escape' => true
        ));

        $this->addColumn('permission', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('Permission'),
            'index' => 'permission',
            'type' => 'options',
            'options' => Mage::getSingleton('cminds_multiuseraccounts/subAccount_permission')->getOptionArray()
        ));

        $this->addColumn('is_approver', array(
            'header'            => Mage::helper('cminds_multiuseraccounts')->__('Is Approver'),
            'index'             => 'is_approver',
            'type'              => 'options',
            'options'           => array(
                '0' => Mage::helper('cminds_multiuseraccounts')->__('No'),
                '1' => Mage::helper('cminds_multiuseraccounts')->__('Yes'),
            )
        ));

        $this->addColumn('order_amount_limit_value', array(
            'header'            => Mage::helper('cminds_multiuseraccounts')->__('Orders Limitation'),
            'index'             => 'order_amount_limit_value',
            'renderer' => 'Cminds_MultiUserAccounts_Block_Adminhtml_SubAccount_Grid_Renderer_Limits',
        ));

        $this->addColumn('view_all_orders', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('View All Orders'),
            'index' => 'view_all_orders',
            'type' => 'options',
            'options' => array(
                '0' => Mage::helper('cminds_multiuseraccounts')->__('No'),
                '1' => Mage::helper('cminds_multiuseraccounts')->__('Yes'),
            )
        ));
        $this->addColumn('can_see_cart', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('Can See to Cart Page'),
            'index' => 'can_see_cart',
            'type' => 'options',
            'options' => array(
                '0' => Mage::helper('cminds_multiuseraccounts')->__('No'),
                '1' => Mage::helper('cminds_multiuseraccounts')->__('Yes'),
            )
        ));
        $this->addColumn('have_access_checkout', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('Have Access to Checkout'),
            'index' => 'have_access_checkout',
            'type' => 'options',
            'options' => array(
                '0' => Mage::helper('cminds_multiuseraccounts')->__('No'),
                '1' => Mage::helper('cminds_multiuseraccounts')->__('Yes'),
            )
        ));

        $this->addColumn('get_order_email', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('Get copy of order emails'),
            'index' => 'get_order_email',
            'type' => 'options',
            'options' => array(
                '0' => Mage::helper('cminds_multiuseraccounts')->__('No'),
                '1' => Mage::helper('cminds_multiuseraccounts')->__('Yes'),
            )
        ));

        $this->addColumn('get_order_invoice', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('Get copy of invoice emails'),
            'index' => 'get_order_invoice',
            'type' => 'options',
            'options' => array(
                '0' => Mage::helper('cminds_multiuseraccounts')->__('No'),
                '1' => Mage::helper('cminds_multiuseraccounts')->__('Yes'),
            )
        ));

        $this->addColumn('get_order_shipment', array(
            'header' => Mage::helper('cminds_multiuseraccounts')->__('Get copy of shipment emails'),
            'index' => 'get_order_shipment',
            'type' => 'options',
            'options' => array(
                '0' => Mage::helper('cminds_multiuseraccounts')->__('No'),
                '1' => Mage::helper('cminds_multiuseraccounts')->__('Yes'),
            )
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('catalog')->__('Action'),
                'width' => '100px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Edit'),
                        'url' => array(
                            'base' => '*/subAccount/edit'
                        ),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
            ));

        return parent::_prepareColumns();
    }

    protected function _prepareLayout()
    {
        $addNewUrl = $this->getUrl('*/subAccount/new',
            array('parent_customer_id' => $this->getRequest()->getParam('id')));
        $this->setChild('new_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('adminhtml')->__('Add Sub Account'),
                    'onclick' => "window.location = '" . $addNewUrl . "'",
                ))
        );

        return parent::_prepareLayout();
    }

    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('new_button');
    }

    public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml();
        $html .= $this->getAddNewButtonHtml();

        return $html;
    }
}

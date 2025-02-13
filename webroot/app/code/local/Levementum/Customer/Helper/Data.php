<?php
/**
* @namespace    Levementum
* @module       ${MODULE}
* @file         Data.php
* @author       dbeljic@levementum.com
* @date         4/24/14 2:38 PM
* @brief
* @details
*/

class Levementum_Customer_Helper_Data extends Mage_Core_Helper_Abstract
{

public function getSalesPersons() {

    $collection = Mage::getResourceModel('admin/user_collection');
    $collection->join(array('role' => 'admin/role'),'main_table.user_id=role.user_id');
    $collection->join(array('role2' => 'admin/role'),'role.parent_id=role2.role_id',array('role_group' => 'role_name', 'role_group_id' => 'role_id'));

    //role is admin or salesperson
    $collection->addFieldToFilter(
        array(
            'role2.role_name',
            'role2.role_name',
            'role2.role_name'
        ),
        array(
            array('eq'=>Mage::helper('adminorders')->getSalespersonRoleName()),
            array('eq'=>"Super User"),
            array('eq'=>"Administrators")
        )
    );
    $collection->addFieldToFilter('is_active',"1");
    $collection->setOrder('firstname', 'ASC');
    $collection->setOrder('lastname', 'ASC');
    $collection->setOrder('username', 'ASC');
    $sps = array();

    foreach ($collection as $item) {
        $sps[$item->getUsername()] = $item->getFirstname().' '.$item->getLastname();
    }

    return $sps;
}

}
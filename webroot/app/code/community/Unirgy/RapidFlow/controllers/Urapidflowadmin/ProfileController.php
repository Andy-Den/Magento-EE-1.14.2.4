<?php
/**
 * Unirgy LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.unirgy.com/LICENSE-M1.txt
 *
 * @category   Unirgy
 * @package    Unirgy_RapidFlow
 * @copyright  Copyright (c) 2008-2009 Unirgy LLC (http://www.unirgy.com)
 * @license    http:///www.unirgy.com/LICENSE-M1.txt
 */

class Unirgy_RapidFlow_Urapidflowadmin_ProfileController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction() {
        $this->loadLayout();
        $this->_setActiveMenu('system/urapidflow');
        $this->_addBreadcrumb(Mage::helper('urapidflow')->__('RapidFlow Profile Manager'), Mage::helper('urapidflow')->__('RapidFlow Profile Manager'));
        $this->_addContent($this->getLayout()->createBlock('urapidflow/adminhtml_profile'));

        return $this;
    }

    protected function _validateSecretKey()
    {
        return true;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/urapidflow');
    }

    protected function _getProfile($idField='id')
    {
        $profile = Mage::getModel('urapidflow/profile');
        $id = $this->getRequest()->getParam($idField);

        if ($id) {
            $profile->load($id);
        }
        if (!$profile->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('urapidflow')->__('Invalid Profile ID'));
        }
        $profile = $profile->factory();

        return $profile;
    }

    public function indexAction() {
        $this->_checkIssues();
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction() {
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('urapidflow/profile')->load($id)->factory();

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('profile_data', $model);

/*            try {
                $model->run();
                exit;
            } catch (Exception $e) {
                echo $e->getMessage();
            }*/

            $this->loadLayout();
            $this->_setActiveMenu('system/urapidflow');

            $this->_addBreadcrumb(Mage::helper('urapidflow')->__('SingleFeed Profile Manager'), Mage::helper('adminhtml')->__('SingleFeed Profile Manager'));
            $this->_addBreadcrumb(Mage::helper('urapidflow')->__('New Profile'), Mage::helper('adminhtml')->__('New Profile'));

            $this->getLayout()->getBlock('head')
                ->setCanLoadExtJs(true)
                ->setCanLoadRulesJs(true);

            $this->_addContent($this->getLayout()->createBlock('urapidflow/adminhtml_profile_edit'))
                ->_addLeft($this->getLayout()->createBlock('urapidflow/adminhtml_profile_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('urapidflow')->__('Profile does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function ajaxStartAction()
    {
        try {
            $profile = $this->_getProfile();
            switch ($profile->getRunStatus()) {
            case 'pending':
                $profile->start()->save()->run();
                $result = array('success'=>true);
                break;
            case 'running':
                $result = array('warning'=>Mage::helper('urapidflow')->__('The profile is already running'));
                break;
            default:
                $result = array('error'=>Mage::helper('urapidflow')->__('Invalid profile run status'));
            }
        } catch (Exception $e) {
            $result = array('error'=>$e->getMessage());
        }

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json', 1)
            ->setBody(Zend_Json::encode($result));
    }

    public function ajaxStatusAction()
    {
        $profile = $this->_getProfile();

        $result = array(
            'run_status' => $profile->getRunStatus(),
            'html' => $this->getLayout()->createBlock('urapidflow/adminhtml_profile_status')->setProfile($profile)->toHtml()
        );

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json', 1)
            ->setBody(Zend_Json::encode($result));
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            try {
                $model = Mage::getModel('urapidflow/profile');

                if (($id = $this->getRequest()->getParam('id'))) {
                    $model->load($id);
                }
                if (!isset($data['columns_post'])) {
                    $data['columns_post'] = array();
                }
                if (isset($data['conditions'])) {
                    $data['conditions_post'] = $data['conditions'];
                    unset($data['conditions']);
                }
                if (isset($data['options']['reindex'])) {
                    $data['options']['reindex'] = array_flip($data['options']['reindex']);
                }
                if (isset($data['options']['refresh'])) {
                    $data['options']['refresh'] = array_flip($data['options']['refresh']);
                }
                $model->addData($data);
                $model = $model->factory();

                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Profile was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if (($invokeStatus = $this->getRequest()->getParam('start'))) {
                    $model->pending($invokeStatus)->save();
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('urapidflow')->__('Profile started successfully'));
                }

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find profile to save'));
        $this->_redirect('*/*/');
    }

    public function uploadAction()
    {
        try {
            $id = $this->getRequest()->getParam('id');
            $target = Mage::getConfig()->getVarDir('urapidflow/import');
            if ($id) {
                /** @var Unirgy_RapidFlow_Model_Profile $model */
                $model = Mage::getModel('urapidflow/profile')->load($id);
                $target = $model->getFileBaseDir();
            }
            Mage::getConfig()->createDirIfNotExists($target);

            $uploader = new Varien_File_Uploader('file');
            $uploader->setAllowedExtensions(array('csv', 'txt', '*'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);

            $result = $uploader->save($target);

            $result['cookie'] = array(
                'name'     => session_name(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain()
            );
        } catch(Exception $e) {
            $result = array('error' => $e->getMessage(), 'errorcode' => $e->getCode());
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function downloadLogAction()
    {
        $profile = $this->_getProfile();

        $this->_pipeFile(
            $profile->getLogBaseDir().DS.$profile->getLogFilename(),
            $profile->getLogFilename(),
            'application/vnd.ms-excel'
        );
    }

    public function exportExcelReportAction()
    {
        $profile = $this->_getProfile();

        $profile->exportExcelReport();

        $this->_pipeFile(
            $profile->getExcelReportBaseDir().DS.$profile->getExcelReportFilename(),
            $profile->getExcelReportFilename(),
            'application/vnd.ms-excel'
        );
    }

    public function testAction()
    {
        try {
            $profile = $this->_getProfile();
            try { $profile->stop(); } catch (Exception $e) { };
            $profile->start()->save()->run();
        } catch (Exception $e) {
            var_dump($e);
        }
        exit;
    }

    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('urapidflow/profile');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Profile was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $profileIds = $this->getRequest()->getParam('profiles');
        if(!is_array($profileIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select profile(s)'));
        } else {
            try {
                foreach ($profileIds as $profileId) {
                    $profile = Mage::getModel('urapidflow/profile')->load($profileId);
                    $profile->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($profileIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $profileIds = $this->getRequest()->getParam('profiles');
        if(!is_array($profileIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select profile(s)'));
        } else {
            try {
                foreach ($profileIds as $profileId) {
                    $profile = Mage::getSingleton('urapidflow/profile')
                        ->load($profileId)
                        ->setProfileStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($profileIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'urapidflow_profiles.csv';
        $content    = $this->getLayout()->createBlock('urapidflow/adminhtml_profile_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'singefeed_profiles.xml';
        $content    = $this->getLayout()->createBlock('urapidflow/adminhtml_profile_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($filename, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$filename);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        exit;
    }

    protected function _pipeFile($filepath, $filename, $contentType='application/octet-stream')
    {
        if (!is_readable($filepath)) {
            header('HTTP/1.1 404 Not Found');
            echo "<h1>Not found</h1>";
            exit;
        }

        header('HTTP/1.1 200 OK');
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0', true);
        header('Content-Disposition: attachment; filename='.$filename);
        header('Last-Modified: '.date('r'));
        header('Accept-Ranges: bytes');
        header('Content-Length: '.filesize($filepath));
        header('Content-Type: ', $contentType);

        $from = fopen($filepath, 'rb');
        $to = fopen('php://output', 'wb');

        stream_copy_to_stream($from, $to);
        exit;
    }

    public function pauseAction()
    {
        try {
            $id = $this->getRequest()->getParam('id', false);
            if (!$id) {
                Mage::throwException("INVALID ID");
            }
            Mage::getModel('urapidflow/profile')->load($id)->factory()->pause()->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('urapidflow')->__('Profile paused successfully'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/edit', array('_current'=>true));
    }

    public function resumeAction()
    {
        try {
            $id = $this->getRequest()->getParam('id', false);
            if (!$id) {
                Mage::throwException("INVALID ID");
            }
            Mage::getModel('urapidflow/profile')->load($id)->factory()->resume()->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('urapidflow')->__('Profile resumed successfully'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/edit', array('_current'=>true));
    }

    public function stopAction()
    {
        try {
            $id = $this->getRequest()->getParam('id', false);
            if (!$id) {
                Mage::throwException("INVALID ID");
            }
            Mage::getModel('urapidflow/profile')->load($id)->factory()->stop()->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('urapidflow')->__('Profile stopped successfully'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/edit', array('_current'=>true));
    }

    public function newConditionHtmlAction()
    {
        $id = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $form = $this->getRequest()->getParam('form');

        $model = Mage::getModel($type)
            ->setId($id)
            ->setType($type)
            ->setRule(Mage::getModel('urapidflow/rule'))
            ->setPrefix($form);
        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof Mage_Rule_Model_Condition_Abstract
            || $model instanceof Mage_Rule_Model_Action_Abstract) {
            $model->setJsFormObject('rule_'.$form.'_fieldset');
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }

    public function chooserAction()
    {
        Mage::app()->getRequest()->setParam('form', '');
        switch ($this->getRequest()->getParam('attribute')) {
            case 'sku':
                $type = 'adminhtml/promo_widget_chooser_sku';
                break;

            case 'categories':
                $type = 'adminhtml/promo_widget_chooser_categories';
                break;
        }
        if (!empty($type)) {
            $block = $this->getLayout()->createBlock($type);
            if ($block) {
                $this->getResponse()->setBody($block->toHtml());
            }
        }
    }

    public function fixissuesAction()
    {
        $issueId = $this->getRequest()->getParam('id');
        switch ($issueId) {
            case '1':
                $fixed = $this->_fixEavAttributeIssue();
                break;
            case '2':
                $fixed = $this->_fixWebsitePriceInGlobalScope();
                break;
            default :
                $fixed = false;
                break;
        }

        if($fixed){
            $message = $this->__("The problem has been fixed");
        } else {
            $message = $this->__("The problem could not be fixed");
        }

        $this->_getSession()->addNotice($message);
        $this->_forward('index');

        //var_dump($issueId);
        //die;
    }
    protected function _checkIssues()
    {
        $issue1 = $this->_checkEavAttributeIssue();
        $warn = $this->__("This will modify your database, are you sure?");
        if($issue1){
            // add warning message with link to fix
            $this->_getSession()->addWarning($this->__(
                "Core Eav Model has potential bug.
                    Click <a href='%s' onclick='return confirm(\"%s\")'>here</a>, to  fix it.",
                $this->getUrl("*/*/fixissues",array('id'=>1)), $warn));
        }

        $issue2 = $this->_checkWebsitePriceInGlobalScope();
        if($issue2){
            // add warning with link to fix
            $this->_getSession()->addWarning($this->__(
                "You have website scope prices and global scope config, this is a bug which can prevent Rapidflow from correctly importing prices.
                    Click <a href='%s' onclick='return confirm(\"%s\")'>here</a>, to  fix it.",
                $this->getUrl("*/*/fixissues",array('id'=>2)), $warn));
        }
    }

    /**
     * Checks for pre 1.4.1 magento bug
     * issues with Mage_Eav_Model_Config::_createAttribute():641 until it's fixed in Magento core
     * @return bool
     */
    protected function _checkEavAttributeIssue()
    {
        try {
            $reflMethod = new ReflectionMethod('Mage_Eav_Model_Config', '_createAttribute');
            $filename = $reflMethod->getFileName();
            $start_line = $reflMethod->getStartLine() - 1; // it's actually - 1, otherwise you wont get the function() block
            $end_line = $reflMethod->getEndLine();
            $length = $end_line - $start_line;

            $source = file($filename);
            $body = implode("", array_slice($source, $start_line, $length));
            $oldCodeFound = stripos($body, 'isset($attributeData[\'attribute_model\']');
            return $oldCodeFound !== false;
        } catch(ReflectionException $e) {
            // method not found
            return false;
        }
    }

    /**
     * Checks if there are website level prices when global price scope is configured
     *
     * @return bool
     * @throws Zend_Db_Statement_Exception
     */
    protected function _checkWebsitePriceInGlobalScope()
    {
        $isGlobal = Mage::helper('catalog')->isPriceGlobal();
        if ($isGlobal) {
            /** @var Mage_Core_Model_Resource $resource */
            $resource = Mage::getSingleton('core/resource');
            /** @var Varien_Db_Adapter_Pdo_Mysql $conn */
            $conn          = $resource->getConnection('urapidflow_setup');
            $delAttrIdsSel = $conn->select()
                                  ->from(array('a' => $resource->getTableName('eav/attribute')), array('attribute_id'))
                                  ->join(array('e' => $resource->getTableName('eav/entity_type')),
                                      'e.entity_type_id=a.entity_type_id', array())
                                  ->where("e.entity_type_code='catalog_product'")
                                  ->where("a.backend_model='catalog/product_attribute_backend_price'");

            $sql = sprintf("SELECT count(*) FROM %s WHERE store_id!=0 AND attribute_id IN (%s)",
                $resource->getTableName('catalog/product') . '_decimal', $delAttrIdsSel);
            try {
                $stmt   = $conn->query($sql);
                $result = $stmt->fetchAll();

                return !empty($result) && !empty($result[0][0]) && $result[0][0] != 0;
            } catch(Zend_Db_Statement_Exception $e) {
                Mage::log($e->getMessage(), null, 'rf.log', true);
            }

        }

        return false;
    }

    protected function _fixEavAttributeIssue()
    {
        /** @var Mage_Core_Model_Resource $resource */
        $resource = Mage::getSingleton('core/resource');
        /** @var Varien_Db_Adapter_Pdo_Mysql $conn */
        $conn          = $resource->getConnection('urapidflow_setup');
        try {
            $conn->exec("UPDATE {$resource->getTableName('eav_attribute')} SET attribute_model=null WHERE attribute_model=''");
        } catch(Zend_Db_Adapter_Exception $e) {
            Mage::log($e->getMessage(), null, 'rf.log', true);

            return false;
        }
        return true;
    }

    /**
     * @return bool
     * @throws Zend_Db_Adapter_Exception
     */
    protected function _fixWebsitePriceInGlobalScope()
    {
        if (Mage::helper('catalog')->isPriceGlobal()) {
            /** @var Mage_Core_Model_Resource $resource */
            $resource = Mage::getSingleton('core/resource');
            /** @var Varien_Db_Adapter_Pdo_Mysql $conn */
            $conn          = $resource->getConnection('urapidflow_setup');
            $delAttrIdsSel = $conn->select()
                ->from(array('a' => $resource->getTableName('eav/attribute')), array('attribute_id'))
                ->join(array('e' => $resource->getTableName('eav/entity_type')), 'e.entity_type_id=a.entity_type_id', array())
                ->where("e.entity_type_code='catalog_product'")
                ->where("a.backend_model='catalog/product_attribute_backend_price'");

            $delAttrValuesSql = sprintf('DELETE FROM %s WHERE store_id!=0 AND attribute_id IN (%s)',
                $resource->getTableName('catalog/product').'_decimal',
                $delAttrIdsSel
            );
            try {
                $conn->exec($delAttrValuesSql);
            } catch(Zend_Db_Adapter_Exception $e) {
                Mage::log($e->getMessage(), null, 'rf.log', true);
                return false;
            }
        }
        return true;
    }
}

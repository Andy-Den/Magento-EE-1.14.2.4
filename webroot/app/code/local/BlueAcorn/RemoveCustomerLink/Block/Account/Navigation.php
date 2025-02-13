<?php
/**
 * Author: Robert Henderson
 * Company: Blue Acorn
 * Date: 6/27/12
 * Time: 11:48 AM
 * Description:
 */
class BlueAcorn_RemoveCustomerLink_Block_Account_Navigation extends Mage_Core_Block_Template
{
    protected $_links = array();

    protected $_activeLink = false;

    public function addLink($name, $path, $label, $urlParams=array())
    {
        $this->_links[$name] = new Varien_Object(array(
            'name' => $name,
            'path' => $path,
            'label' => $label,
            'url' => $this->getUrl($path, $urlParams),
        ));
        return $this;
    }

    public function setActive($path)
    {
        $this->_activeLink = $this->_completePath($path);
        return $this;
    }

    public function getLinks()
    {
        return $this->_links;
    }

    public function isActive($link)
    {
        if (empty($this->_activeLink)) {
            $this->_activeLink = $this->getAction()->getFullActionName('/');
        }
        if ($this->_completePath($link->getPath()) == $this->_activeLink) {
            return true;
        }
        return false;
    }

    protected function _completePath($path)
    {
        $path = rtrim($path, '/');
        switch (sizeof(explode('/', $path))) {
            case 1:
                $path .= '/index';
            // no break

            case 2:
                $path .= '/index';
        }
        return $path;
    }

    /**
     * Removes link by name
     *
     * @param string $name
     * @return Mage_Page_Block_Template_Links
     */
    public function removeLinkByName($name)
    {
        foreach ($this->_links as $link => $linkName) {
            if ($linkName->getName() == $name) {
                unset($this->_links[$link]);
            }
        }
        return $this;
    }
}

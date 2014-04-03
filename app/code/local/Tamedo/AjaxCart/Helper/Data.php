<?php
/**
 * Created by Tamedo.
 * User: Daniel Rafique
 * Copyright all rights reserved to author of this content.
 */

class Tamedo_AjaxCart_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_CONFIG_PATH = 'ajaxcart/general/';

    const NAME_DIR_JS = 'ajaxcart/jquery/';

    protected $_files = array(
        'jquery-1.8.1.min.js',
        'jquery.noconflict.js',
    );

    public function isJqueryEnabled()
    {
        return (bool) $this->_getConfigValue('jquery', $store = '');
    }

    public function getJQueryPath($file)
    {
        return self::NAME_DIR_JS . $file;
    }

    public function getFiles()
    {
        return $this->_files;
    }

    protected function _getConfigValue($key, $store)
    {
        return Mage::getStoreConfig(self::XML_CONFIG_PATH . $key, $store = '');
    }
	
}
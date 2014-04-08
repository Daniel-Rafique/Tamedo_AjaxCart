<?php

    /**
     * Created by Tamedo.
     * User: Daniel Rafique
     * Copyright all rights reserved to author of this content.
     */
    class Tamedo_AjaxCart_Model_System_Config_Backend_Image_Loader extends Mage_Adminhtml_Model_System_Config_Backend_Image
    {

        const UPLOAD_DIR = 'ajaxcart';

        const UPLOAD_ROOT = 'media';

        protected function _getUploadDir()
        {
            $uploadDir = $this->_appendScopeInfo( self::UPLOAD_DIR );
            $uploadRoot = $this->_getUploadRoot( self::UPLOAD_ROOT );
            $uploadDir = $uploadRoot . '/' . $uploadDir;

            return $uploadDir;
        }

        protected function _addWhetherScopeInfo()
        {
            return true;
        }

        protected function _getAllowedExtensions()
        {
            return array( 'gif', 'png', 'jpeg', 'jpg' );
        }

        protected function _getUploadRoot( $token )
        {
            return Mage::getBaseDir( $token );
        }
    }

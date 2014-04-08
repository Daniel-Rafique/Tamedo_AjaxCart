<?php

    /**
     * Created by Tamedo.
     * User: Daniel Rafique
     * Copyright all rights reserved to author of this content.
     */
    class Tamedo_AjaxCart_Block_AjaxCart extends Mage_Core_Block_Template
    {
        public function _prepareLayout()
        {
            return parent::_prepareLayout();
        }

        public function getAjaxCart()
        {
            if ( !$this->hasData( 'ajaxcart' ) ) {
                $this->setData( 'ajaxcart', Mage::registry( 'ajaxcart' ) );
            }

            return $this->getData( 'ajaxcart' );

        }
    }
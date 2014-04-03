<?php
/**
 * Created by Tamedo.
 * User: Daniel Rafique
 * Copyright all rights reserved to author of this content.
 */

class Tamedo_AjaxCart_Model_Observer
{

   public function prepareLayoutBefore(Varien_Event_Observer $observer)
   {
	
	   if (!Mage::helper('ajaxcart')->isJqueryEnabled()) {
            return $this;
        }
       $block = $observer->getEvent()->getBlock();

       if ("head" == $block->getNameInLayout()) {
           foreach (Mage::helper('ajaxcart')->getFiles() as $file) {
               $block->addJs(Mage::helper('ajaxcart')->getJQueryPath($file));
           }
       }
       return $this;
   }
   
   public function postdispatch(Varien_Event_Observer $event) {
		$controller = $event->getControllerAction();
		if (!$controller->getRequest()->getHeader('X-Requested-With')) {
			return;
		}
		
		$param = array();
		$headers = Mage::app()->getRequest()->getParams();
		
		foreach ($headers as $headerName => $headerValue) {
			$headerName = strtolower($headerName);
			if (!preg_match('/nb(.*)/',$headerName,$regs))
				continue;
			$param[str_replace('_','.',$regs[1])] = $headerValue;
		}
		
		if (!count($param)) {
			return;
		}
		
		$layout = Mage::app()->getLayout();
		$blocks = array();
		
		foreach ($param as $blockName => $selector) {
			$temp = $layout->getBlock($blockName);
			$blocks[$blockName] = array(
				'selector'	=> $selector,
				'html'		=> ($temp)?$temp->toHtml():''
			);
		}
		echo json_encode($blocks);
		
		exit;
	}
}
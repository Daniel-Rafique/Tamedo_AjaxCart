<?php
/**
 * Created by Tamedo.
 * User: Daniel Rafique
 * Copyright all rights reserved to author of this content.
 */

require_once 'Mage/Checkout/controllers/CartController.php';
class Tamedo_AjaxCart_IndexController extends Mage_Checkout_CartController
{
	public function addAction()
	{
		$cart   = $this->_getCart();
		$params = $this->getRequest()->getParams();
		
		if($params['isAjax'] == 1){
			$response = array();
			try {
				if (isset($params['qty'])) {
					$filter = new Zend_Filter_LocalizedToNormalized(
					array('locale' => Mage::app()->getLocale()->getLocaleCode())
					);
					$params['qty'] = $filter->filter($params['qty']);
				}

				$product = $this->_initProduct();
				$related = $this->getRequest()->getParam('related_product');

				if (!$product) {
					$response['status'] = 'ERROR';
					$response['message'] = $this->__('Unable to find Product ID');
				}

				$cart->addProduct($product, $params);
				if (!empty($related)) {
					$cart->addProductsByIds(explode(',', $related));
				}

				$cart->save();

				$this->_getSession()->setCartWasUpdated(true);

				Mage::dispatchEvent('checkout_cart_add_product_complete',
				array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
				);

				if (!$cart->getQuote()->getHasError()){

					if(Mage::getStoreConfig('ajaxcart/addtocart/popupboxtext')):
						$popupboxtext = Mage::getStoreConfig('ajaxcart/addtocart/popupboxtext'); 
					else:
					 	$popupboxtext = "was successfully added to your shopping cart.";
					endif;
					
					$message = $this->__('%s  ', Mage::helper('core')->htmlEscape($product->getName()));
					$message .= $this->__($popupboxtext);
					
					$response['status'] = 'SUCCESS';
					$response['message'] = $message;
					
					$this->loadLayout();
					$toplink = $this->getLayout()->getBlock('top.links')->toHtml();
					$sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
					Mage::register('referrer_url', $this->_getRefererUrl());
					$sidebar = $sidebar_block->toHtml();
					$response['toplink'] = $toplink;
					$response['sidebar'] = $sidebar;
				}
			} catch (Mage_Core_Exception $e) {
				$msg = "";
				if ($this->_getSession()->getUseNotice(true)) {
					$msg = $e->getMessage();
				} else {
					$messages = array_unique(explode("\n", $e->getMessage()));
					foreach ($messages as $message) {
						$msg .= $message.'<br/>';
					}
				}

				$response['status'] = 'ERROR';
				$response['message'] = $msg;
			} catch (Exception $e) {
				$response['status'] = 'ERROR';
				$response['message'] = $this->__('Cannot add the item to shopping cart.');
				Mage::logException($e);
			}
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
			return;
		}else{
			return parent::addAction();
		}
	}
	public function optionsAction(){
		$productId = $this->getRequest()->getParam('product_id');
		$viewHelper = Mage::helper('catalog/product_view');

		$params = new Varien_Object();
		$params->setCategoryId(false);
		$params->setSpecifyOptions(false);

		try {
			$viewHelper->prepareAndRender($productId, $this, $params);
		} catch (Exception $e) {
			if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
				if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
					$this->_redirect('');
				} elseif (!$this->getResponse()->isRedirect()) {
					$this->_forward('noRoute');
				}
			} else {
				Mage::logException($e);
				$this->_forward('noRoute');
			}
		}
	}
	
	 public function deleteAction(){
	    $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)->save();
                $this->_getSession()->setCartWasUpdated(true);
                if (!$this->_getCart()->getQuote()->getHasError()){                    
                    $response['status'] = 'SUCCESS';
                    $response['message'] = $message;

                    $this->loadLayout();
                    $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                    $sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
                    Mage::register('referrer_url', $this->_getRefererUrl());
                    $sidebar = $sidebar_block->toHtml();                                      
                    $response['toplink'] = $toplink;
                    $response['sidebar'] = $sidebar;                                    
                }

                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot remove the item.'));
                Mage::logException($e);
            }
        }        
	}
}
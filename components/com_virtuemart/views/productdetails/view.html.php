<?php

/**
 *
 * Product details view
 *
 * @package VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 8975 2015-09-09 06:58:22Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('VmView'))
    require(VMPATH_SITE . DS . 'helpers' . DS . 'vmview.php');

/**
 * Product details
 *
 * @package VirtueMart
 * @author RolandD
 * @author Max Milbers
 */
class VirtueMartViewProductdetails extends VmView {

    /**
         * Collect all data to show on the template
         *
         * @author RolandD, Max Milbers
         */
        function display($tpl = null) {

            $show_prices = VmConfig::get('show_prices', 1);

            $this->assignRef('show_prices', $show_prices);

            $document = JFactory::getDocument();

            $app = JFactory::getApplication();

            $menus    = $app->getMenu();
            $menu = $menus->getActive();

            if(!empty($menu->id)){
                ShopFunctionsF::setLastVisitedItemId($menu->id);
            } else if($itemId = vRequest::getInt('Itemid',false)){
                ShopFunctionsF::setLastVisitedItemId($itemId);
            }

            $pathway = $app->getPathway();
            $task = vRequest::getCmd('task');

            if (!class_exists('VmImage'))
                require(VMPATH_ADMIN . DS . 'helpers' . DS . 'image.php');

            // Load the product
            //$product = $this->get('product');    //Why it is sensefull to use this construction? Imho it makes it just harder
            $product_model = VmModel::getModel('product');
            $this->assignRef('product_model', $product_model);
            $virtuemart_product_idArray = vRequest::getInt('virtuemart_product_id', 0);
            if (is_array($virtuemart_product_idArray) and count($virtuemart_product_idArray) > 0) {
                $virtuemart_product_id = (int)$virtuemart_product_idArray[0];
            } else {
                $virtuemart_product_id = (int)$virtuemart_product_idArray;
            }

            $quantityArray = vRequest::getInt ('quantity', array()); //is sanitized then

            $quantity = 1;
            if (!empty($quantityArray[0])) {
                $quantity = $quantityArray[0];
            }
            $ratingModel = VmModel::getModel('ratings');
            $product_model->withRating = $this->showRating = $ratingModel->showRating($virtuemart_product_id);
            $product = $product_model->getProduct($virtuemart_product_id,TRUE,TRUE,TRUE,$quantity);

            if(!class_exists('shopFunctionsF'))require(VMPATH_SITE.DS.'helpers'.DS.'shopfunctionsf.php');
            $last_category_id = shopFunctionsF::getLastVisitedCategoryId();

            $customfieldsModel = VmModel::getModel ('Customfields');

            if ($product->customfields){

                if (!class_exists ('vmCustomPlugin')) {
                    require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');
                }
                $customfieldsModel -> displayProductCustomfieldFE ($product, $product->customfields);
            }

            if (empty($product->slug)) {

                //Todo this should be redesigned to fit better for SEO
                $app->enqueueMessage(vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND'));

                $categoryLink = '';
                if (!$last_category_id) {
                    $last_category_id = vRequest::getInt('virtuemart_category_id', false);
                }
                if ($last_category_id) {
                    $categoryLink = '&virtuemart_category_id=' . $last_category_id;
                }

                if (VmConfig::get('handle_404',1)) {
                    $app->redirect(JRoute::_('index.php?option=com_virtuemart&view=category' . $categoryLink . '&error=404', FALSE));
                } else {
                    JError::raise(E_ERROR,'404','Not found');
                }

                return;
            }

            $isCustomVariant = false;
            if (!empty($product->customfields)) {
                foreach ($product->customfields as $k => $custom) {
                    if($custom->field_type == 'C' and $custom->virtuemart_product_id != $virtuemart_product_id){
                        $isCustomVariant = $custom;
                    }
                    if (!empty($custom->layout_pos)) {
                        $product->customfieldsSorted[$custom->layout_pos][] = $custom;
                    } else {
                        $product->customfieldsSorted['normal'][] = $custom;
                    }
                    unset($product->customfields);
                }

            }

            $product->event = new stdClass();
            $product->event->afterDisplayTitle = '';
            $product->event->beforeDisplayContent = '';
            $product->event->afterDisplayContent = '';
            if (VmConfig::get('enable_content_plugin', 0)) {
                shopFunctionsF::triggerContentPlugin($product, 'productdetails','product_desc');
            }

            $product_model->addImages($product);

            if (isset($product->min_order_level) && (int) $product->min_order_level > 0) {
                $min_order_level = $product->min_order_level;
            } else {
                $min_order_level = 1;
            }
            $this->assignRef('min_order_level', $min_order_level);
            if (isset($product->step_order_level) && (int) $product->step_order_level > 0) {
                $step_order_level = $product->step_order_level;
            } else {
                $step_order_level = 1;
            }
            $this->assignRef('step_order_level', $step_order_level);

            // Load the neighbours
            if (VmConfig::get('product_navigation', 1)) {
                $product->neighbours = $product_model->getNeighborProducts($product);
            }

            $this->assignRef('product', $product);

            if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
                $manModel = VmModel::getModel('manufacturer');
                $mans = array();
                // Gebe die Hersteller aus
                foreach($this->product->virtuemart_manufacturer_id as $manufacturer_id) {

                    $mans[] = $manModel->getManufacturer( $manufacturer_id );
                }
                $this->product->manufacturers = $mans;
            }

            // Load the category
            $category_model = VmModel::getModel('category');

            shopFunctionsF::setLastVisitedCategoryId($product->virtuemart_category_id);

            if ($category_model) {

                $category = $category_model->getCategory($product->virtuemart_category_id);

                $category_model->addImages($category, 1);
                $this->assignRef('category', $category);

                //Seems we dont need this anylonger, destroyed the breadcrumb
                if ($category->parents) {
                    foreach ($category->parents as $c) {
                        if(is_object($c) and isset($c->category_name)){
                            $pathway->addItem(strip_tags(vmText::_($c->category_name)), JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $c->virtuemart_category_id, FALSE));
                        } else {
                            vmdebug('Error, parent category has no name, breadcrumb maybe broken, category',$c);
                        }
                    }
                }

                $category->children = $category_model->getChildCategoryList($product->virtuemart_vendor_id, $product->virtuemart_category_id);
                $category_model->addImages($category->children, 1);
            }

            $pathway->addItem(strip_tags(html_entity_decode($product->product_name,ENT_QUOTES)));

            if (!empty($tpl)) {
                $format = $tpl;
            } else {
                $format = vRequest::getCmd('format', 'html');
            }
            if ($format == 'html') {
                // remove joomla canonical before adding it
                foreach ( $document->_links as $k => $array ) {
                    if ( $array['relation'] == 'canonical' ) {
                        unset($document->_links[$k]);
                        break;
                    }
                }
                // Set Canonic link
                if($isCustomVariant !==false and !empty($isCustomVariant->usecanonical) and !empty($product->product_parent_id)){
                    $parent = $product_model ->getProduct($product->product_parent_id);
                    $document->addHeadLink($parent->canonical, 'canonical', 'rel', '');
                } else {
                    $document->addHeadLink($product->canonical, 'canonical', 'rel', '');
                }

            } else if($format == 'pdf'){
                defined('K_PATH_IMAGES') or define ('K_PATH_IMAGES', VMPATH_ROOT);
            }

            // Set the titles
            // $document->setTitle should be after the additem pathway
            if ($product->customtitle) {
                $document->setTitle(strip_tags(html_entity_decode($product->customtitle,ENT_QUOTES)));
            } else {
                $document->setTitle(strip_tags(html_entity_decode(($category->category_name ? (vmText::_($category->category_name) . ' : ') : '') . $product->product_name,ENT_QUOTES)));
            }

            $this->allowReview = $ratingModel->allowReview($product->virtuemart_product_id);
            $this->showReview = $ratingModel->showReview($product->virtuemart_product_id);
            $this->rating_reviews='';
            if ($this->showReview) {
                $this->review = $ratingModel->getReviewByProduct($product->virtuemart_product_id);
                $this->rating_reviews = $ratingModel->getReviews($product->virtuemart_product_id);
            }

            if ($this->showRating) {
                $this->vote = $ratingModel->getVoteByProduct($product->virtuemart_product_id);
            }

            $this->allowRating = $ratingModel->allowRating($product->virtuemart_product_id);

            $superVendor = vmAccess::isSuperVendor();

            if($superVendor == 1 or $superVendor==$product->virtuemart_vendor_id or ($superVendor)){
                $edit_link = JURI::root() . 'index.php?option=com_virtuemart&tmpl=component&manage=1&view=product&task=edit&virtuemart_product_id=' . $product->virtuemart_product_id;
                $this->edit_link = $this->linkIcon($edit_link, 'COM_VIRTUEMART_PRODUCT_FORM_EDIT_PRODUCT', 'edit', false, false);
            } else {
                $this->edit_link = "";
            }

            // Load the user details
            $this->user = JFactory::getUser();

            // More reviews link
            $uri = JURI::getInstance();
            $uri->setVar('showall', 1);
            $uristring = vmURI::getCleanUrl();
            $this->assignRef('more_reviews', $uristring);

            if ($product->metadesc) {
                $document->setDescription( strip_tags(html_entity_decode($product->metadesc,ENT_QUOTES)) );
            } else {
                $document->setDescription( strip_tags(html_entity_decode($product->product_name,ENT_QUOTES)) . " " . $category->category_name . " " . strip_tags(html_entity_decode($product->product_s_desc,ENT_QUOTES)) );
            }

            if ($product->metakey) {
                $document->setMetaData('keywords', $product->metakey);
            }

            if ($product->metarobot) {
                $document->setMetaData('robots', $product->metarobot);
            }

            if ($app->getCfg('MetaTitle') == '1') {
                $document->setMetaData('title', $product->product_name);  //Maybe better product_name
            }
            if ($app->getCfg('MetaAuthor') == '1') {
                $document->setMetaData('author', $product->metaauthor);
            }


            $user = JFactory::getUser();
            $showBasePrice = (vmAccess::manager() or vmAccess::isSuperVendor());
            $this->assignRef('showBasePrice', $showBasePrice);

            $productDisplayShipments = array();
            $productDisplayPayments = array();

            if (!class_exists('vmPSPlugin'))
                require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
            JPluginHelper::importPlugin('vmshipment');
            JPluginHelper::importPlugin('vmpayment');
            $dispatcher = JDispatcher::getInstance();
            $returnValues = $dispatcher->trigger('plgVmOnProductDisplayShipment', array($product, &$productDisplayShipments));
            $returnValues = $dispatcher->trigger('plgVmOnProductDisplayPayment', array($product, &$productDisplayPayments));

            $this->assignRef('productDisplayPayments', $productDisplayPayments);
            $this->assignRef('productDisplayShipments', $productDisplayShipments);

            if (empty($category->category_template)) {
                $category->category_template = VmConfig::get('categorytemplate');
            }

            shopFunctionsF::setVmTemplate($this, $category->category_template, $product->product_template, $category->category_product_layout, $product->layout);

            shopFunctionsF::addProductToRecent($virtuemart_product_id);

            $currency = CurrencyDisplay::getInstance();
            $this->assignRef('currency', $currency);

            if(vRequest::getCmd( 'layout', 'default' )=='notify') $this->setLayout('notify'); //Added by Seyi Awofadeju to catch notify layout
            VmConfig::loadJLang('com_virtuemart');

            vmJsApi::chosenDropDowns();

//This must be loaded after the customfields are rendered (they may need to overwrite the handlers)
            vmJsApi::jDynUpdate();

            if ($show_prices == '1') {
                if (!class_exists('calculationHelper'))
                    require(VMPATH_ADMIN . DS . 'helpers' . DS . 'calculationh.php');
                vmJsApi::jPrice();
            }

            parent::display($tpl);
    }

    function renderMailLayout ($doVendor, $recipient) {
        $tpl = VmConfig::get('order_mail_html') ? 'mail_html_notify' : 'mail_raw_notify';

        $this->doVendor=$doVendor;
        $this->fromPdf=false;
        $this->uselayout = $tpl;
        $this->subject = !empty($this->subject) ? $this->subject : vmText::_('COM_VIRTUEMART_CART_NOTIFY_MAIL_SUBJECT');
        $this->layoutName = $tpl;
        $this->setLayout($tpl);
        $this->isMail = true;
        $this->user=new stdClass();
        $this->user->name=$this->vendor->vendor_store_name;
        $this->user->email=$this->vendorEmail;
        parent::display();
    }
    public function showLastCategory($tpl) {
        $this->prepareContinueLink();
        parent::display ($tpl);
    }


}

// pure php no closing tag

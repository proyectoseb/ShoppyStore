<?php
/**
 *
 * renders a customfield
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @version $Id: addtocartbtn.php 8024 2014-06-12 15:08:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

class VirtueMartCustomFieldRenderer {


	
	static function renderCustomfieldsFE(&$product,&$customfields,$virtuemart_category_id){


		static $calculator = false;
		if(!$calculator){
			if (!class_exists ('calculationHelper')) {
				require(VMPATH_ADMIN . DS . 'helpers' . DS . 'calculationh.php');
			}
			$calculator = calculationHelper::getInstance ();
		}

		$selectList = array();

		$dynChilds = 1;

		static $currency = false;
		if(!$currency){
			if (!class_exists ('CurrencyDisplay'))
				require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
			$currency = CurrencyDisplay::getInstance ();
		}

		foreach($customfields as $k => $customfield){


			if(!isset($customfield->display))$customfield->display = '';

			$calculator->_product = $product;

			if (!class_exists ('vmCustomPlugin')) {
				require(VMPATH_PLUGINLIBS . DS . 'vmcustomplugin.php');
			}

			if ($customfield->field_type == "E") {

				JPluginHelper::importPlugin ('vmcustom');
				$dispatcher = JDispatcher::getInstance ();
				$ret = $dispatcher->trigger ('plgVmOnDisplayProductFEVM3', array(&$product, &$customfields[$k]));
				continue;
			}

			$fieldname = 'field['.$product->virtuemart_product_id.'][' . $customfield->virtuemart_customfield_id . '][customfield_value]';
			$customProductDataName = 'customProductData['.$product->virtuemart_product_id.']['.$customfield->virtuemart_custom_id.']';

			//This is a kind of fallback, setting default of custom if there is no value of the productcustom
			$customfield->customfield_value = empty($customfield->customfield_value) ? $customfield->custom_value : $customfield->customfield_value;

			$type = $customfield->field_type;

			$idTag = 'customProductData_'.(int)$product->virtuemart_product_id.'_'.$customfield->virtuemart_customfield_id;
			$idTag = VmHtml::ensureUniqueId($idTag);

			$emptyOption = new stdClass();
			$emptyOption->text = vmText::_ ('COM_VIRTUEMART_ADDTOCART_CHOOSE_VARIANT');
			$emptyOption->value = 0;
			switch ($type) {

				case 'C':

					$html = '';

					$dropdowns = array();

					if(isset($customfield->options->{$product->virtuemart_product_id})){
						$productSelection = $customfield->options->{$product->virtuemart_product_id};
					} else {
						$productSelection = false;
					}
					$stockhandle = VmConfig::get('stockhandle', 'none');

					$q = 'SELECT `virtuemart_product_id` FROM #__virtuemart_products WHERE product_parent_id = "'.$customfield->virtuemart_product_id.'" and ( published = "0" ';
					if($stockhandle == 'disableit_children'){
						$q .= ' OR (`product_in_stock` - `product_ordered`) <= "0"';
					}
					$q .= ');';
					$db = JFactory::getDbo();
					$db->setQuery($q);
					$ignore = $db->loadColumn();
					//vmdebug('my q '.$q,$ignore);

					foreach($customfield->options as $product_id=>$variants){

						if($ignore and in_array($product_id,$ignore)){
							//vmdebug('$customfield->options Product to ignore, continue ',$product_id);
							continue;
						}

						foreach($variants as $k => $variant){

							if(!isset($dropdowns[$k]) or !is_array($dropdowns[$k])) $dropdowns[$k] = array();
							if(!in_array($variant,$dropdowns[$k])  ){
								if($k==0 or !$productSelection){
									$dropdowns[$k][] = $variant;
								} else if($k>0 and $productSelection[$k-1] == $variants[$k-1]){
									$break=false;
									for($h=1;$h<=$k;$h++){
										if($productSelection[$h-1] != $variants[$h-1]){
											//$ignore[] = $variant;
											$break=true;
										}
									}
									if(!$break){
										$dropdowns[$k][] = $variant;
									}

								} else {
									//	break;
								}
							}
						}
					}

					$tags = array();

					foreach($customfield->selectoptions as $k => $soption){

						$options = array();
						$selected = false;
						foreach($dropdowns[$k] as $i=> $elem){

							$elem = trim((string)$elem);
							$text = $elem;

							if($soption->clabel!='' and in_array($soption->voption,VirtueMartModelCustomfields::$dimensions) ){
								$rd = $soption->clabel;
								if(is_numeric($rd) and is_numeric($elem)){
									$text = number_format(round((float)$elem,(int)$rd),$rd);
								}
								//vmdebug('($dropdowns[$k] in DIMENSION value = '.$elem.' r='.$rd.' '.$text);
							} else if  ($soption->voption === 'clabels' and $soption->clabel!='') {
								$text = vmText::_($elem);
							}

							if($elem=='0'){
								$text = vmText::_('COM_VIRTUEMART_LIST_EMPTY_OPTION');
							}
							$options[] = array('value'=>$elem,'text'=>$text);

							if($productSelection and $productSelection[$k] == $elem){
								$selected = $elem;
							}
						}

						if(empty($selected)){
							$product->orderable=false;
						}
						$idTagK = $idTag.'cvard'.$k;
						if($customfield->showlabels){
							if( in_array($soption->voption,VirtueMartModelCustomfields::$dimensions) ){
								$soption->slabel = vmText::_('COM_VIRTUEMART_'.strtoupper($soption->voption));
							} else if(!empty($soption->clabel) and !in_array($soption->voption,VirtueMartModelCustomfields::$dimensions) ){
								$soption->slabel = vmText::_($soption->clabel);
							}
							if(isset($soption->slabel)){
								$html .= '<span class="vm-cmv-label" >'.$soption->slabel.'</span>';
							}

						}

						$attribs = array('class'=>'vm-chzn-select cvselection no-vm-bind','data-dynamic-update'=>'1','style'=>'min-width:70px;');
						if('productdetails' != vRequest::getCmd('view')){
							$attribs['reload'] = '1';
						}

						$html .= JHtml::_ ('select.genericlist', $options, $fieldname, $attribs , "value", "text", $selected,$idTagK);
						$tags[] = $idTagK;
					}

					$Itemid = vRequest::getInt('Itemid',''); // '&Itemid=127';
					if(!empty($Itemid)){
						$Itemid = '&Itemid='.$Itemid;
					}

					//create array for js
					$jsArray = array();

					$url = '';
					foreach($customfield->options as $product_id=>$variants){

						if($ignore and in_array($product_id,$ignore)){continue;}

						$url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $virtuemart_category_id . '&virtuemart_product_id='.$product_id.$Itemid);
						$jsArray[] = '["'.$url.'","'.implode('","',$variants).'"]';
					}

					vmJsApi::addJScript('cvfind',false,false);

					$jsVariants = implode(',',$jsArray);
					$j = "
						jQuery('#".implode(',#',$tags)."').off('change',Virtuemart.cvFind);
						jQuery('#".implode(',#',$tags)."').on('change', { variants:[".$jsVariants."] },Virtuemart.cvFind);
					";
					$hash = md5(implode('',$tags));
					vmJsApi::addJScript('cvselvars'.$hash,$j,false);

					//Now we need just the JS to reload the correct product
					$customfield->display = $html;

					break;

				case 'A':

					$html = '';

					$productModel = VmModel::getModel ('product');

					//Note by Jeremy Magne (Daycounts) 2013-08-31
					//Previously the the product model is loaded but we need to ensure the correct product id is set because the getUncategorizedChildren does not get the product id as parameter.
					//In case the product model was previously loaded, by a related product for example, this would generate wrong uncategorized children list
					$productModel->setId($customfield->virtuemart_product_id);

					$uncatChildren = $productModel->getUncategorizedChildren ($customfield->withParent);

					$options = array();

					if(!$customfield->withParent or ($customfield->withParent and $customfield->parentOrderable)){
						$options[0] = $emptyOption;
						$options[0]->value = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $virtuemart_category_id . '&virtuemart_product_id=' . $customfield->virtuemart_product_id,FALSE);
						//$options[0] = array('value' => JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $virtuemart_category_id . '&virtuemart_product_id=' . $customfield->virtuemart_product_id,FALSE), 'text' => vmText::_ ('COM_VIRTUEMART_ADDTOCART_CHOOSE_VARIANT'));
					}

					$selected = vRequest::getInt ('virtuemart_product_id',0);
					$selectedFound = false;

					$parentStock = 0;
					foreach ($uncatChildren as $k => $child) {
						if(!isset($child[$customfield->customfield_value])){
							vmdebug('The child has no value at index '.$customfield->customfield_value,$customfield,$child);
						} else {

							$productChild = $productModel->getProduct((int)$child['virtuemart_product_id'],false);
							if(!$productChild) continue;
							$available = $productChild->product_in_stock - $productChild->product_ordered;
							if(VmConfig::get('stockhandle','none')=='disableit_children' and $available <= 0){
								continue;
							}
							$parentStock += $available;
							$priceStr = '';
							if($customfield->wPrice){
								//$product = $productModel->getProductSingle((int)$child['virtuemart_product_id'],false);
								$productPrices = $calculator->getProductPrices ($productChild);
								$priceStr =  ' (' . $currency->priceDisplay ($productPrices['salesPrice']) . ')';
							}
							$options[] = array('value' => JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $virtuemart_category_id . '&virtuemart_product_id=' . $child['virtuemart_product_id']), 'text' => $child[$customfield->customfield_value].$priceStr);
							if($selected==$child['virtuemart_product_id']){
								$selectedFound = true;
								vmdebug($customfield->virtuemart_product_id.' $selectedFound by vRequest '.$selected);
							}
							//vmdebug('$child productId ',$child['virtuemart_product_id'],$customfield->customfield_value,$child);
						}
					}
					if(!$selectedFound){
						$pos = array_search($customfield->virtuemart_product_id, $product->allIds);
						if(isset($product->allIds[$pos-1])){
							$selected = $product->allIds[$pos-1];
							//vmdebug($customfield->virtuemart_product_id.' Set selected to - 1 allIds['.($pos-1).'] = '.$selected.' and count '.$dynChilds);
							//break;
						} elseif(isset($product->allIds[$pos])){
							$selected = $product->allIds[$pos];
							//vmdebug($customfield->virtuemart_product_id.' Set selected to allIds['.$pos.'] = '.$selected.' and count '.$dynChilds);
						} else {
							$selected = $customfield->virtuemart_product_id;
							//vmdebug($customfield->virtuemart_product_id.' Set selected to $customfield->virtuemart_product_id ',$selected,$product->allIds);
						}
					}

					$url = 'index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id='.
					$virtuemart_category_id .'&virtuemart_product_id='. $selected;
					$html .= JHtml::_ ('select.genericlist', $options, $fieldname, 'onchange="window.top.location.href=this.options[this.selectedIndex].value" size="1" class="vm-chzn-select no-vm-bind" data-dynamic-update="1" ', "value", "text",
					JRoute::_ ($url,false),$idTag);

					vmJsApi::chosenDropDowns();

					if($customfield->parentOrderable==0){
						if($product->virtuemart_product_id==$customfield->virtuemart_product_id){
							$product->orderable = FALSE;
						} else {
							$product->product_in_stock = $parentStock;
						}

					} else {


					}

					$dynChilds++;
					$customfield->display = $html;

					break;

				/*Date variant*/
				case 'D':
					if(empty($customfield->custom_value)) $customfield->custom_value = 'LC2';
					//Customer selects date
					if($customfield->is_input){
						$customfield->display =  '<span class="product_custom_date">' . vmJsApi::jDate ($customfield->customfield_value,$customProductDataName) . '</span>'; //vmJsApi::jDate($field->custom_value, 'field['.$row.'][custom_value]','field_'.$row.'_customvalue').$priceInput;
					}
					//Customer just sees a date
					else {
						$customfield->display =  '<span class="product_custom_date">' . vmJsApi::date ($customfield->customfield_value, $customfield->custom_value, TRUE) . '</span>';
					}

					break;
				/* text area or editor No vmText, only displayed in BE */
				case 'X':
				case 'Y':
					$customfield->display =  $customfield->customfield_value;

					break;
				/* string or integer */
				case 'B':
				case 'S':
				case 'M':

					//vmdebug('Example for params ',$customfield);
					if(isset($customfield->selectType)){
						if(empty($customfield->selectType)){
							$selectType = 'select.genericlist';
							if(!empty($customfield->is_input)){
								vmJsApi::chosenDropDowns();
								$class = 'class="vm-chzn-select"';
							}
						} else {
							$selectType = 'select.radiolist';
							$class = '';
						}
					} else {
						if($type== 'M'){
							$selectType = 'select.radiolist';
							$class = '';
						} else {
							$selectType = 'select.genericlist';
							if(!empty($customfield->is_input)){
								vmJsApi::chosenDropDowns();
								$class = 'class="vm-chzn-select"';
							}
						}
					}

					if($customfield->is_list and $customfield->is_list!=2){

						if(!empty($customfield->is_input)){


							$options = array();

							if($customfield->addEmpty){
								$options[0] = $emptyOption;
							}

							$values = explode (';', $customfield->custom_value);

							foreach ($values as $key => $val) {
								if($val == 0 and $customfield->addEmpty){
									continue;
								}
								if($type == 'M'){
									$tmp = array('value' => $val, 'text' => VirtueMartModelCustomfields::displayCustomMedia ($val,'product',$customfield->width,$customfield->height));
									$options[] = (object)$tmp;
								} else {
									$options[] = array('value' => $val, 'text' => vmText::_($val));
								}
							}

							$currentValue = $customfield->customfield_value;

							$customfield->display = JHtml::_ ($selectType, $options, $customProductDataName.'[' . $customfield->virtuemart_customfield_id . ']', $class, 'value', 'text', $currentValue,$idTag);
						} else {
							if($type == 'M'){
								$customfield->display =  VirtueMartModelCustomfields::displayCustomMedia ($customfield->customfield_value,'product',$customfield->width,$customfield->height);
							} else {
								$customfield->display =  vmText::_ ($customfield->customfield_value);
							}
						}
					} else {

						if(!empty($customfield->is_input)){

							if(!isset($selectList[$customfield->virtuemart_custom_id])) {
								$selectList[$customfield->virtuemart_custom_id] = $k;
								if($customfield->addEmpty){
									if(empty($customfields[$selectList[$customfield->virtuemart_custom_id]]->options)){
										$customfields[$selectList[$customfield->virtuemart_custom_id]]->options[0] = $emptyOption;
										$customfields[$selectList[$customfield->virtuemart_custom_id]]->options[0]->virtuemart_customfield_id = $emptyOption->value;
										//$customfields[$selectList[$customfield->virtuemart_custom_id]]->options['nix'] = array('virtuemart_customfield_id' => 'none', 'text' => vmText::_ ('COM_VIRTUEMART_ADDTOCART_CHOOSE_VARIANT'));
									}
								}

								$tmpField = clone($customfield);
								$tmpField->options = null;
								$customfield->options[$customfield->virtuemart_customfield_id] = $tmpField;

								$customfield->customProductDataName = $customProductDataName;

							} else {
								$customfields[$selectList[$customfield->virtuemart_custom_id]]->options[$customfield->virtuemart_customfield_id] = $customfield;
								unset($customfields[$k]);

							}

							$default = reset($customfields[$selectList[$customfield->virtuemart_custom_id]]->options);
							foreach ($customfields[$selectList[$customfield->virtuemart_custom_id]]->options as &$productCustom) {
								$price = VirtueMartModelCustomfields::_getCustomPrice($productCustom->customfield_price, $currency, $calculator);
								if($type == 'M'){
									$productCustom->text = VirtueMartModelCustomfields::displayCustomMedia ($productCustom->customfield_value,'product',$customfield->width,$customfield->height).' '.$price;
								} else {
									$trValue = vmText::_($productCustom->customfield_value);
									if($productCustom->customfield_value!=$trValue and strpos($trValue,'%1')!==false){
										$productCustom->text = vmText::sprintf($productCustom->customfield_value,$price);
									} else {
										$productCustom->text = $trValue.' '.$price;
									}
								}
							}


							$customfields[$selectList[$customfield->virtuemart_custom_id]]->display = JHtml::_ ($selectType, $customfields[$selectList[$customfield->virtuemart_custom_id]]->options,
							$customfields[$selectList[$customfield->virtuemart_custom_id]]->customProductDataName,
							$class, 'virtuemart_customfield_id', 'text', $default->customfield_value,$idTag);	//*/
						} else {
							if($type == 'M'){
								$customfield->display = VirtueMartModelCustomfields::displayCustomMedia ($customfield->customfield_value,'product',$customfield->width,$customfield->height);
							} else {
								$customfield->display = vmText::_ ($customfield->customfield_value);
							}
						}
					}

					break;

				// Property
				case 'P':
					//$customfield->display = vmText::_ ('COM_VIRTUEMART_'.strtoupper($customfield->customfield_value));
					$attr = $customfield->customfield_value;
					$lkey = 'COM_VIRTUEMART_'.strtoupper($customfield->customfield_value).'_FE';
					$trValue = vmText::_ ($lkey);
					$options[] = array('value' => 'product_length', 'text' => vmText::_ ('COM_VIRTUEMART_PRODUCT_LENGTH'));
					$options[] = array('value' => 'product_width', 'text' => vmText::_ ('COM_VIRTUEMART_PRODUCT_WIDTH'));
					$options[] = array('value' => 'product_height', 'text' => vmText::_ ('COM_VIRTUEMART_PRODUCT_HEIGHT'));
					$options[] = array('value' => 'product_weight', 'text' => vmText::_ ('COM_VIRTUEMART_PRODUCT_WEIGHT'));

					$dim = '';

					if($attr == 'product_length' or $attr == 'product_width' or $attr == 'product_height'){
						$dim = $product->product_lwh_uom;
					} else if($attr == 'product_weight') {
						$dim = $product->product_weight_uom;
					}
					$val= $product->$attr;
					if($customfield->round!=''){
						$val = round($val,$customfield->round);
					}
					if($lkey!=$trValue and strpos($trValue,'%1')!==false) {
						$customfield->display = vmText::sprintf( $customfield->customfield_value, $val , $dim );
					} else if($lkey!=$trValue) {
						$customfield->display = $trValue.' '.$val;
					} else {
						$customfield->display = vmText::_ ('COM_VIRTUEMART_'.strtoupper($customfield->customfield_value)).' '.$val.$dim;
					}

					break;
				case 'Z':
					if(empty($customfield->customfield_value)) break;
					$html = '';
					$q = 'SELECT * FROM `#__virtuemart_categories_' . VmConfig::$vmlang . '` as l INNER JOIN `#__virtuemart_categories` AS c using (`virtuemart_category_id`) WHERE `published`=1 AND l.`virtuemart_category_id`= "' . (int)$customfield->customfield_value . '" ';
					$db = JFactory::getDBO();
					$db->setQuery ($q);
					if ($category = $db->loadObject ()) {

						if(empty($category->virtuemart_category_id)) break;

						$q = 'SELECT `virtuemart_media_id` FROM `#__virtuemart_category_medias`WHERE `virtuemart_category_id`= "' . $category->virtuemart_category_id . '" ';
						$db->setQuery ($q);
						$thumb = '';
						if ($media_id = $db->loadResult ()) {
							$thumb = VirtueMartModelCustomfields::displayCustomMedia ($media_id,'category',$customfield->width,$customfield->height);
						}
						$customfield->display = JHtml::link (JRoute::_ ('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id), $thumb . ' ' . $category->category_name, array('title' => $category->category_name,'target'=>'_blank'));
					}

					break;
				case 'R':
					if(empty($customfield->customfield_value)){
						$customfield->display = 'customfield related product has no value';
						break;
					}
					$pModel = VmModel::getModel('product');
					$related = $pModel->getProduct((int)$customfield->customfield_value,TRUE,$customfield->wPrice,TRUE,1);

					if(!$related) break;

					$thumb = '';
					if($customfield->wImage){
						if (!empty($related->virtuemart_media_id[0])) {
							$thumb = VirtueMartModelCustomfields::displayCustomMedia ($related->virtuemart_media_id[0],'product',$customfield->width,$customfield->height).' ';
						} else {
							$thumb = VirtueMartModelCustomfields::displayCustomMedia (0,'product',$customfield->width,$customfield->height).' ';
						}
					}

					$customfield->display = shopFunctionsF::renderVmSubLayout('related',array('customfield'=>$customfield,'related'=>$related, 'thumb'=>$thumb));

					break;
			}

			$viewData['customfields'][$k] = $customfield;
			//vmdebug('my customfields '.$type,$viewData['customfields'][$k]->display);
		}

	}

	static function renderCustomfieldsCart($product, $html, $trigger){
		if(isset($product->param)){
			vmTrace('param found, seek and destroy');
			return false;
		}
		$row = 0;
		if (!class_exists ('shopFunctionsF'))
			require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');

		$variantmods = isset($product -> customProductData)?$product -> customProductData:$product -> product_attribute;

		if(empty($variantmods)){
			$productDB = VmModel::getModel('product')->getProduct($product->virtuemart_product_id);
			if($productDB){
				$product->customfields = $productDB->customfields;
			}
		}
		if(!is_array($variantmods)){
			$variantmods = json_decode($variantmods,true);
		}

		$productCustoms = array();
		foreach( (array)$product->customfields as $prodcustom){

			//We just add the customfields to be shown in the cart to the variantmods
			if(is_object($prodcustom)){
				if($prodcustom->is_cart_attribute and !$prodcustom->is_input){
					if(!isset($variantmods[$prodcustom->virtuemart_custom_id]) or !is_array($variantmods[$prodcustom->virtuemart_custom_id])){
						$variantmods[$prodcustom->virtuemart_custom_id] = array();
					}
					$variantmods[$prodcustom->virtuemart_custom_id][$prodcustom->virtuemart_customfield_id] = false;

				} else if(!empty($variantmods) and !empty($variantmods[$prodcustom->virtuemart_custom_id])){

				}
				$productCustoms[$prodcustom->virtuemart_customfield_id] = $prodcustom;
			}
		}

		foreach ( (array)$variantmods as $custom_id => $customfield_ids) {

			if(!is_array($customfield_ids)){
				$customfield_ids = array( $customfield_ids =>false);
			}

			foreach($customfield_ids as $customfield_id=>$params){

				if(empty($productCustoms) or !isset($productCustoms[$customfield_id])){
					vmdebug('displayProductCustomfieldSelected continue');
					continue;
				}
				$productCustom = $productCustoms[$customfield_id];
				//vmdebug('displayProductCustomfieldSelected ',$customfield_id,$productCustom);
				//The stored result in vm2.0.14 looks like this {"48":{"textinput":{"comment":"test"}}}
				//and now {"32":[{"invala":"100"}]}
				if (!empty($productCustom)) {
					$otag = ' <span class="product-field-type-' . $productCustom->field_type . '">';
					$tmp = '';
					if ($productCustom->field_type == "E") {

						if (!class_exists ('vmCustomPlugin'))
							require(VMPATH_PLUGINLIBS . DS . 'vmcustomplugin.php');
						JPluginHelper::importPlugin ('vmcustom');
						$dispatcher = JDispatcher::getInstance ();
						$dispatcher->trigger ($trigger.'VM3', array(&$product, &$productCustom, &$tmp));
					}
					else {
						$value = '';

						if (($productCustom->field_type == 'G')) {
							$db = JFactory::getDBO ();
							$db->setQuery ('SELECT  `product_name` FROM `#__virtuemart_products_' . VmConfig::$vmlang . '` WHERE virtuemart_product_id=' . (int)$productCustom->customfield_value);
							$child = $db->loadObject ();
							$value = $child->product_name;
						}
						elseif (($productCustom->field_type == 'M')) {
							$customFieldModel = VmModel::getModel('customfields');
							$value = $customFieldModel->displayCustomMedia ($productCustom->customfield_value,'product',$productCustom->width,$productCustom->height,VirtueMartModelCustomfields::$useAbsUrls);
						}
						elseif (($productCustom->field_type == 'S')) {

							if($productCustom->is_list and $productCustom->is_input){
								$value = vmText::_($params);
							} else {
								$value = vmText::_($productCustom->customfield_value);
							}
						}
						elseif (($productCustom->field_type == 'A')) {
							if(!property_exists($product,$productCustom->customfield_value)){
								$productDB = VmModel::getModel('product')->getProduct($product->virtuemart_product_id);
								if($productDB){
									$attr = $productCustom->customfield_value;
									$product->$attr = $productDB->$attr;
								}
							}
							$value = vmText::_( $product->{$productCustom->customfield_value} );
						}
						elseif (($productCustom->field_type == 'C')) {

							foreach($productCustom->options->{$product->virtuemart_product_id} as $k=>$option){
								$value .= '<span> ';
								if(!empty($productCustom->selectoptions[$k]->clabel) and in_array($productCustom->selectoptions[$k]->voption,VirtueMartModelCustomfields::$dimensions)){
									$value .= vmText::_('COM_VIRTUEMART_'.$productCustom->selectoptions[$k]->voption);
									$rd = $productCustom->selectoptions[$k]->clabel;
									if(is_numeric($rd) and is_numeric($option)){
										$value .= ' '.number_format(round((float)$option,(int)$rd),$rd);
									}
								} else {
									if(!empty($productCustom->selectoptions[$k]->clabel)) $value .= vmText::_($productCustom->selectoptions[$k]->clabel);
									$value .= ' '.vmText::_($option).' ';
								}
								$value .= '</span><br>';
							}
							$value = trim($value);
							if(!empty($value)){
								$html .= $otag.$value.'</span><br />';
							}

							continue;
						}
						else {
							$value = vmText::_($productCustom->customfield_value);
						}
						$trTitle = vmText::_($productCustom->custom_title);
						$tmp = '';

						if($productCustom->custom_title!=$trTitle and strpos($trTitle,'%1')!==false){
							$tmp .= vmText::sprintf($productCustom->custom_title,$value);
						} else {
							$tmp .= $trTitle.' '.$value;
						}
					}
					if(!empty($tmp)){
						$html .= $otag.$tmp.'</span><br />';
					}


				}
				else {
					foreach ((array)$customfield_id as $key => $value) {
						$html .= '<br/ >Couldnt find customfield' . ($key ? '<span>' . $key . ' </span>' : '') . $value;
					}
					vmdebug ('customFieldDisplay, $productCustom is EMPTY '.$customfield_id);
				}
			}

		}

		return $html . '</div>';
	}
}


<?php
/**
 * @package Sj Quick View for VirtueMart
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class plgSystemPlg_Sj_Vm_QuickView extends JPlugin
{
    protected $app = array();
    protected $_params = null;

    function __construct($subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
        $this->app = JFactory::getApplication();
        $this->_params = $this->params;

    }

    function onBeforeRender()
    {
        if ($this->app->isAdmin()) return;
        $app = JFactory::getApplication();
        $option = $app->input->get('option');
        $view = $app->input->get('view');
        $tmpl = $app->input->get('tmpl');
        $document = JFactory::getDocument();
        if ($app->isSite() && $tmpl != 'component') {
            if (!defined('SMART_JQUERY') && ( int )$this->params->get('include_jquery', '1')) {
                $document->addScript(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/js/jquery-1.8.2.min.js');
                $document->addScript(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/js/jquery-noconflict.js');
                define('SMART_JQUERY', 1);
            }
            if (!class_exists ('VmConfig')) {
                require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
            }
            VmConfig::loadConfig ();
            if(class_exists('vmJsApi')){
                vmJsApi::jPrice();
             }
            $document->addScript(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/js/jquery.fancybox.js');
            $document->addStyleSheet(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/css/jquery.fancybox.css');
            $document->addStyleSheet(JURI::root(true) . '/plugins/system/plg_sj_vm_quickview/assets/css/quickview.css');
        }
        return true;
    }

    public function onAfterRender()
    {
        if ($this->app->isAdmin()) return;
        $app = JFactory::getApplication();
        $option = $app->input->get('option');
        $view = $app->input->get('view');
        $tmpl = $app->input->get('tmpl');
        $body = JResponse::GetBody();

        if ($app->isSite() && $tmpl != 'component') {
            $_cls = explode(',', $this->_params->get('item_class'));
            if(empty($_cls)) return;
            $cls = array();
            for($i = 0 ; $i < count($_cls) ; $i++) {
                $cls[] = trim($_cls[$i]);
            }

            $cls_str = implode(', ', $cls);
            $body = str_replace('</body>', $this->_addScriptQV($cls_str) . '</body>', $body);
            JResponse::setBody($body);
            return true;
        }

        $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $is_ajax_qv = (int)JRequest::getVar('isajax_qv', 0);
        if ($is_ajax && $is_ajax_qv) {
                $body = JResponse::GetBody();
                preg_match("~<body.*?>(.*?)<\/body>~is", $body, $match);
                echo '<div id="sj_quickview">'.$match[1].'</div>';
                die;
                //die(json_encode('<div id="sj_quickview">'.$match[1].'</div>'));
        }

    }

    function onAfterDispatch(){

    }

    protected function _addScriptQV($cls_str)
    {
        ob_start();

        ?>
        <script type="text/javascript">
            //<![CDATA[
            if(typeof  Virtuemart !== 'undefined')
            {
                Virtuemart.updateImageEventListeners = function() {}
            }
            jQuery(document).ready(function ($) {
                function _SJQuickView(){
                    var $item_class = $('<?php echo $cls_str; ?>');
                    if ($item_class.length > 0) {
                        for (var i = 0; i < $item_class.length; i++) {
                            if($($item_class[i]).find('.sj_quickview_handler').length <= 0){
                                var producturlpath = $($item_class[i]).find('a', $(this)).attr('href');
                                if(typeof producturlpath !== 'undefined' && producturlpath.length > 0 ){
                                    producturlpath = ( producturlpath.indexOf('?')  >= 0 ) ? producturlpath + '&tmpl=component' : producturlpath + '?tmpl=component' ;
                                    var _quickviewbutton = "<a title='<?php echo $this->_params->get('label_button','Quick View'); ?>' class='sj_quickview_handler visible-lg' href='" + producturlpath + "'><?php /* echo $this->_params->get('label_button','Quick View'); */?><i class='fa fa-search'></i></a>";
                                    $($item_class[i]).append(_quickviewbutton);
                                }
                            }
                        }
                    }
                }
                $('.sj_quickview_handler')._fancybox({
                    width: '<?php echo $this->_params->get('popup_width');?>',
                    height: '<?php echo $this->_params->get('popup_height');?>',
                    autoSize:  <?php echo $this->_params->get('auto_size');?>,
                    scrolling: 'auto',
                    type: 'ajax',
                    openEffect: '<?php echo $this->_params->get('open_effect'); ;?>',
                    closeEffect: '<?php echo $this->_params->get('close_effect'); ?>',
                    helpers: {
                        <?php if( (int)$this->_params->get('display_overlay') == 1 ) { ?>
                            overlay: {
                            showEarly: true
                        }
                        <?php } else { ?>
                            overlay: null
                        <?php } ?>
                    },
                    ajax:{
                        type:'POST',
                        data:{
                            option:'com_virtuemart',
                            view:'productdetails',
                            tmpl:'component',
                            quickview:'showpoup',
                            isajax_qv:1
                        },
                        dataType:'html'

                    },
                    beforeLoad: function (){

                    },
                    afterLoad :function() {

                    },
                    beforeShow: function (){

                        var $_price_on_qv = $('#sj_quickview').find(".product-price"),
                            _id_price = $_price_on_qv.attr('id') ;
                            $_price_on_qv.addClass('price-on-qv');
                        $('.product-price').each(function(){
                            var $this = $(this);
                            if(!$this.hasClass('price-on-qv')){
                                if($this.attr('id') == _id_price){
                                    $this.attr('data-idprice',_id_price);
                                    $this.attr('id',_id_price+'_clone');
                                }
                            }
                        });

                    },
                    afterShow: function () {
                         if(typeof  Virtuemart !== 'undefined'){
                            var $_form = $("form.product",$('#sj_quickview'));
                            $('.quantity-controls',$_form).unbind();
                            Virtuemart.product($_form);
                            // $("form.js-recalculate").each(function(){
                                // var _cart = $(this);
                                // //if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
                                    // var id= $(this).find('input[name="virtuemart_product_id[]"]').val();
                                    // console.log($('.quantity-controls',_cart));
                                    // $('.quantity-controls',$(this)).unbind();
                                    // $('.quantity-input',$(this)).unbind();
                                    // //Virtuemart.setproducttype($(this),id);
                                // //}
                            // });
                         }
                    },
                    afterClose: function (){
                        $('.product-price').each(function(){
                            var $this = $(this), _id_price = $this.attr('data-idprice') ;
                                if($this.attr('data-idprice') != '') {
                                    $this.removeAttr('data-idprice');
                                    $this.attr('id',_id_price);
                                }
                        });
                    }
                });
                setInterval(function(){ _SJQuickView(); } ,1000);
            });
            //]]>
        </script>
        <?php
        $jq = ob_get_contents();
        ob_end_clean();
        return $jq;
    }
}

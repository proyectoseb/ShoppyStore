<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined('_JEXEC') or die;

class JFormFieldJshelper extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'YtHelper';

	function getInput( ) {
		if (!defined ('YT_JSHELPER')) {
			define('YT_JSHELPER', 1);
			if(J_VERSION >= 3){
				$script = "
					<script>
						jQuery(document).ready(function($) {
							var ref = $('#jform_params_ytext_contenttype');
							var thisElement_mod = $('#jformparamsytext_modules');
							var thisElement_pos = $('#jformparamsytext_positions');				
							var TRmod = thisElement_mod.parent().parent();
							var TRpos = thisElement_pos.parent().parent();
							if (ref.val()=='mod') {
								TRmod.css('display', '');
								TRpos.css('display', 'none');
							} else if (ref.val()=='pos') {
								TRpos.css('display', '');
								TRmod.css('display', 'none');
							} else if (ref.val()!='pos' && ref.val()!='mod') {								
								TRmod.css('display', 'none');
								TRpos.css('display', 'none');
							} else{

							}
							ref.change(function() {
								if ($(this).val()=='mod') {
									TRmod.css('display', '');
									TRpos.css('display', 'none');
								} else if ($(this).val()=='pos') {	
									TRpos.css('display', '');
									TRmod.css('display', 'none');							
								} else if ($(this).val()!='pos' && $(this).val()!='mod') {								
									TRmod.css('display', 'none');
									TRpos.css('display', 'none');
								} else{

								}
							});
						});
					</script>
				";
			}else{
				$script = "
					<script>
						window.addEvent('domready', function(){
							var ref = $('jform_params_ytext_contenttype');
							var thisElement_mod = $('jformparamsytext_modules');
							var thisElement_pos = $('jformparamsytext_positions');				
							var TRmod = thisElement_mod.parentNode;
							var TRpos = thisElement_pos.parentNode;
							if (ref.value=='mod') {
								TRmod.style.display='';
								TRpos.style.display='none';
							} else if (ref.value=='pos') {
								TRpos.style.display='';
								TRmod.style.display='none';
							} else if (ref.value!='pos' && ref.value!='mod') {								
								TRmod.style.display='none';
								TRpos.style.display='none';
							} else{

							}
							ref.addEvent('change', function(){
								if (this.value=='mod') {
									TRmod.style.display='';
									TRpos.style.display='none';
								} else if (this.value=='pos') {	
									TRpos.style.display='';
									TRmod.style.display='none';					
								} else if (this.value!='pos' && this.value!='mod') {								
									TRmod.style.display='none';
									TRpos.style.display='none';
								} else{

								}
							});
						});
					</script>
				";
			}	
		} else {
			$script = "";
		}
		return $script;
	}
} 
<?php
	 /**
    * @package YT Framework
    * @author Smartaddons http://www.Smartaddons.com
    * @copyright Copyright (c) 2009 - 2014 Smartaddons
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldYtFieldset extends JFormField
{
	public $type = 'YtFieldset';

	protected function getInput() {
		$status = (string)$this->element['status'];
		$className = $this->element['class']; 
		$html = '';
		if($status==1){
			$html .='<fieldset class="yt-fieldset '.$className.'">';
			
			if(!empty($this->element['legend'])){
				$html .= '<legend>'.JText::_($this->element['legend']).'</legend>';
			}
			
			if(!empty($this->element['description'])){
				$html .= '<div class="yt-fielddesc">'.JText::_($this->element['description']).'</div>';
			}
			if($this->element['legend']=='FIELDSET_CSS'){
				$html .='<div class =\'less2css\'>';
				$html .='<div class =\'less2css-info\'><span class=\'fa fa-info-circle\'></span>  '.JText::_('LESS2CSS_INFO');
				$html .='<a href=\'#myModal\' id=\'less2css_modal\' role=\'button\' class=\'btn btn-small btn-success\' data-toggle=\'modal\'>Click here</a>';
				$html .='</div>';
				$html .='</div>';
				$html .='
			    <div id=\'myModal\' class=\'modal hide fade\' tabindex=\'-1\' role=\'dialog\' aria-labelledby=\'smallModal\' aria-hidden=\'true\'>
				    <div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class=\'modal-header\'>
								<button type=\'button\' class=\'close\' data-dismiss=\'modal\' aria-hidden=\'true\'>×</button>
								<h4 id=\'myModalLabel\'>'.Jtext::_('LESS2CSS_TITLE').'</h4>
								<div class =\'less2css-warning\'><span class=\'icon-warning\'></span>  '.JText::_('LESS2CSS_WARNING').'</div>
							</div>
							<div class=\'modal-body\'>
								<p style=\'text-align:center\' id=\'less2css_result\'></p>
								<div style=\'text-align:center\'>
								<span class=\'btn btn-primary less2css\'>Yes</span>
								<span class=\'btn\' data-dismiss=\'modal\' aria-hidden=\'true\'>No</span>
								</div>
							</div>
						</div>
					</div>
			    </div>
			    <script language=\'javascript\' type=\'text/javascript\'>
					jQuery(document).ready(function($){
						$(\'a#less2css_modal\').click(function(){
							$(\'p#less2css_result\').html(\' \');
						})
						$(\'.less2css.btn\').click(function(){
							$(\'p#less2css_result\').html(\'Converting...\');
							new Request({url: \'../index.php?less2css=all&compile=server\', method:\'post\',
								onSuccess: function(result){
									$(\'p#less2css_result\').html(\'Convert successful!\');
								},
								onFailure: function(){
									$(\'p#less2css_result\').html(\'Convert successful!\');
								}
							}).send();
						});
					})
				</script>
				';
			}
		}else{
			$html .='
				</fieldset>
			';
		}
		
		return $html;
	}
}

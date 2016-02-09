<?php 
Class YT_Field_Shortcodes{
	
public static function formField($id,$field=array())
	{
		$html = '';
		$type = $field['type'];
		switch ($type)
		{
/* ------------------------------------------------------#media-------------------------------------------- */
			case 'media':
				$html .= '<div class="yt-generator-img-picker-wrapper">
						<input type="text" name="' . $id . '" value="' . htmlentities( $field['default'] ) . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr yt-generator-upload-value" />
						<div class="yt-generator-field-actions">
							<a class="yt_btn yt_btn-primary yt-generator-attr-src-a" title="Select image source" onClick="SqueezeBox.fromElement(this, {handler:\'iframe\', size: {x: 790, y: 580}}); return false;" href="index.php?option=com_media&view=images&tmpl=component&asset=&author=&fieldid=yt-generator-attr-' . $id . '&folder=" rel="{handler: \'iframe\', size: {x: 790, y: 580}}">
								<i class="fa fa-image"></i>Select media
							</a>
						</div>
				   </div>';
			break;
			
/* ------------------------------------------------------#text-------------------------------------------- */
			case 'text':
				$html .= '<input type="text" name="' . $id . '" value="' . $field['default'] . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr" />';
			break;
			
/* ------------------------------------------------------#textarea-------------------------------------------- */
			case 'textarea':
				$html = '<textarea name="' . $id . '" id="yt-generator-attr-' . $id . '" rows="3" class="yt-generator-attr">' .  $field['default']  . '</textarea>';
			break;
			
/* ------------------------------------------------------#color-------------------------------------------- */
			case 'color':
				$html .= '<span class="yt-generator-select-color"><span class="yt-generator-select-color-wheel"></span><input type="text" name="' . $id . '" value="' . $field['default'] . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr yt-generator-select-color-value" /> </span>';
			break;
			
/* ------------------------------------------------------#select-------------------------------------------- */
			case 'select':
				$multiple = ( isset( $field['multiple'] ) ) ? ' multiple' : '';
				$class = (isset($field['class'])) ? $field['class'] : '';
				$html .= "<select name='" . $id . "' id='yt-generator-attr-" . $id . "' class='yt-generator-attr ".$class."'" . $multiple . " >";
				foreach($field['values'] as $option_value => $option_title){
					$selected = ( $field['default'] === $option_value ) ? ' selected="selected"' : '';
						$html .= '<option value="'.$option_value.'" ' . $selected . '>'.$option_title.'</option>';
					}
				$html .= "</select>";
			break;
			
/* -------------------------------------------------------#bool-------------------------------------------- */
			case 'bool':
				$html .= '<span class="yt-generator-switch yt-generator-switch-' . $field['default'] . '"><span class="yt-generator-yes">Yes</span><span class="yt-generator-no">No</span></span><input type="hidden" name="' . $id . '" value="' . $field['default'] . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr yt-generator-switch-value" />';
			break;
			
/* ------------------------------------------------------#slider-------------------------------------------- */
			case 'slider':
				$html .= '<div class="yt-generator-range-picker yt-generator-clearfix"><input type="number" name="' . $id . '" value="' . $field['default'] . '" id="yt-generator-attr-' . $id . '" min="' . $field['min'] . '" max="' . $field['max'] . '" step="' . $field['step'] . '" class="yt-generator-attr" /></div>';
			break;
			
/* ------------------------------------------------------#border-------------------------------------------- */
			case 'border':
				$defaults = ($field['default'] === 'none' ) ? array ('0', 'solid', '#000000') : explode(' ', str_replace( 'px', '', $field['default']));
				$border = array(
					'none' => "None",
					'solid' => "Solid",
					'dotted' => "Dotted",
					'dashed' => "Dashed",
					'double' => "Double",
					'groove' => "Groove",
					'ridge' => "Ridge",
				);
				$borders ='';
					$borders .= '<select class="yt-generator-bp-style">';
					foreach ($border as $option_value => $option_title)
					{
						$selected = ($defaults[1] == $option_value) ? 'selected' : '';
						$borders .= '<option value="'.$option_value.'" '.$selected.'>'.$option_title.'</option>';
					}
					$borders .='</select>';
					$html .= '<div class="yt-generator-border-picker"><span class="yt-generator-border-picker-field"><input type="number" min="-1000" max="1000" step="1" value="'.$defaults[0].'" class="yt-generator-bp-width" /><small>Border width (px)</small></span><span class="yt-generator-border-picker-field">' . $borders . '<small> Border style</small></span><span class="yt-generator-border-picker-field yt-generator-border-picker-color"><span class="yt-generator-border-picker-color-wheel"></span><input type="text" value="'.$defaults[2].'" class="yt-generator-border-picker-color-value" /><small>Border color</small></span><input type="hidden" name="' . $id . '" value="' .  $field['default'] . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr" /></div>';
			break;
			
/* -----------------------------------------------------#shadow-------------------------------------------- */
			case 'shadow':
				$defaults = ( $field['default'] === 'none' ) ? array ('0', '0', '0', '#000000') : explode(' ', str_replace( 'px', '', $field['default']));
				$html .= '<div class="yt-generator-shadow-picker"><span class="yt-generator-shadow-picker-field"><input type="number" min="-1000" max="1000" step="1" value="' . $defaults[0] . '" class="yt-generator-sp-hoff" /><small>Horizontal offset (px)</small></span><span class="yt-generator-shadow-picker-field"><input type="number" min="-1000" max="1000" step="1" value="' . $defaults[1] . '" class="yt-generator-sp-voff" /><small>Vertical offset (px)</small></span><span class="yt-generator-shadow-picker-field"><input type="number" min="-1000" max="1000" step="1" value="' . $defaults[2] . '" class="yt-generator-sp-blur" /><small>Blur (px)</small></span><span class="yt-generator-shadow-picker-field yt-generator-shadow-picker-color"><span class="yt-generator-shadow-picker-color-wheel"></span><input type="text" value="' . $defaults[3] . '" class="yt-generator-shadow-picker-color-value" /><small>Color</small></span><input type="hidden" name="' . $id . '" value="' .  $field['default']  . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr" /></div>';
			break;
			
/* ------------------------------------------------------#number-------------------------------------------- */
			case 'number':
				$html .= '<input type="number" name="' . $id . '" value="' . $field['default'] . '" id="yt-generator-attr-' . $id . '" min="' . $field['min'] . '" max="' . $field['max'] . '" step="' . $field['step'] . '" class="yt-generator-attr" />';
			break;
			
/* ------------------------------------------------------#note-------------------------------------------- */
			case 'note':
				$html .= '<span>' . $field['default']  . '</span><input style="display: none;" type="text" name="' . $id . '" value="' .  $field['default']  . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr" />';	
			break;
			
/* -------------------------------------------------------#icon-------------------------------------------- */
			case 'icon':
				$icons = YT_Data::icons();
				$html .= '<div class="yt-generator-icon-picker-wrapper">
							<input type="text" name="' . $id . '" value="' .  $field['default']  . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr yt-generator-icon-picker-value" />
							<div class="yt-generator-field-actions">
								<a class="yt_btn yt_btn-primary yt-generator-attr-' . $id . '-a" title="Select image" onClick="SqueezeBox.fromElement(this, {handler:\'iframe\', size: {x: 790, y: 580}}); return false;" href="index.php?option=com_media&view=images&tmpl=component&asset=&author=&fieldid=yt-generator-attr-' . $id . '&folder=" rel="{handler: \'iframe\', size: {x: 790, y: 580}}">
									<i class="fa fa-image"></i>Select image
								</a>
								<a href="javascript:;" class="yt_btn yt_btn-warning yt-generator-icon-picker-button yt-generator-field-action">
									<i class="fa fa-magic"></i>Icon picker
								</a>
							</div>
						</div>
						<div class="yt-generator-icon-picker yt-generator-clearfix ">
							<input type="text" class="yt-icon-picker-search" placeholder="Filter Icons" />';
							foreach($icons as $icon)
							{
								$html .='<i style="display: block;" class="fa fa-'.$icon.'" title="'.$icon.'"></i>';
							}
							
			$html .='</div>';
			break;
			
/* ------------------------------------------------------#livicon-------------------------------------------- */
			case 'livicon':
				$livicons = YT_Data::livicons();
				$html .= '<select name="icon" id="yt-generator-attr-icon" class="yt-generator-attr">';
				
				foreach ($livicons as $livicon)
				{
					$selected = ($livicon == $field['default'] ) ? ' selected="selected"' : '';
					$html .= '<option value="'.$livicon.'" ' . $selected . '>'.$livicon.'</option>';	
				}
				$html .= '</select>';
			break;
			
/* ------------------------------------------------------#source-------------------------------------------- */
			case 'source':
				if (JComponentHelper::isEnabled('com_k2', true)) {
					$sources = "<select class='yt-generator-isp-sources'>";
						$sources .= '<option value="media" >Media</option>';
						$sources .= '<option value="category" >Category</option>';
						$sources .= '<option value="k2-category" >K2-category</option>';
						$sources .= '<option value="0" selected >Select image source...</option>';	
					$sources .= "</select>";
				}
				else {
					$sources = "<select class='yt-generator-isp-sources'>";
						$sources .= '<option value="media" >Media</option>';
						$sources .= '<option value="category" >Category</option>';
						$sources .= '<option value="0" selected >Select image source...</option>';	
					$sources .= "</select>";
				}
				$categories = '<select class="yt-generator-isp-categories" multiple>';
					foreach (get_terms( 'category' ) as $option_value => $option_title)
					{
						$categories .= '<option value="'.$option_value.'">'.$option_title.'</option>';
					}
				
				$categories .= '</select>';
				if (JComponentHelper::isEnabled('com_k2', true)) {
					$k2_categories = '<select class="yt-generator-isp-k2-categories" multiple>';
					foreach (get_k2_terms( 'k2-category' ) as $option_value => $option_title)
					{
						$k2_categories .= '<option value="'.$option_value.'">'.$option_title.'</option>';
					}
					$k2_categories .= '</select>';
				} else {
					$k2_categories = null;
				}
				$html  .= '<div class="yt-generator-isp">' . $sources;
					$html .= '<div class="yt-generator-isp-source yt-generator-isp-source-media">';
		        		$html .= '<div class="yt-generator-clearfix">';
		        			$html .= '<a class="yt_btn button button-primary yt-generator-isp-add-media" title="Select image" onClick="SqueezeBox.fromElement(this, {handler:\'iframe\', size: {x: 830, y: 600}}); return false;" href="index.php?option=com_media&view=images&tmpl=component&asset=&author=&fieldid=yt-generator-attr-source&folder=" rel="{handler: \'iframe\', size: {x: 830, y: 600}}">';
		        				$html .= '<i class="fa fa-plus"></i>&nbsp;&nbsp;Add image';
		    				$html .= '</a>';
		        		$html .= '</div>';
						$html .= '<div id="yt-generator-attr-image" class="yt-generator-isp-images yt-generator-clearfix">';
							$html .= '<em class="description">Click the button above and select images.<br>You can select multimple images with Ctrl (Cmd) key</em>';
						$html .= '</div>';
					$html .= '</div>';
					$html .= '<div class="yt-generator-isp-source yt-generator-isp-source-category">';
						$html .= '<em class="description">Select category from list below.<br>You can select multimple category with Ctrl (Cmd) key</em>';
						$html .= $categories;
					$html .= '</div>';
					$html .= '<div class="yt-generator-isp-source yt-generator-isp-source-k2-category">';
						$html .= '<em class="description">Select K2 category from list below.<br>You can select multimple category with Ctrl (Cmd) key</em>';
						$html .= $k2_categories;
					$html .= '</div>';
					$html .= '<input type="hidden" name="' . $id . '" value="' . $field['default'] . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr" />';
				$html .= '</div>';
			break;
			
/* ---------------------------------------------------#addElement-------------------------------------------- */
			case 'addElement':
				return '';
			break;
			
/* -----------------------------------------------#article_source-------------------------------------------- */
			case 'article_source':
				if (JComponentHelper::isEnabled('com_k2', true)) {
			    	$sources = "<select class='yt-generator-isp-sources'>";
						$sources .= '<option value="category" >Category</option>';
						$sources .= '<option value="k2-category" >K2-category</option>';
						$sources .= '<option value="0" selected >'.JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SELECT_ARTICLE_SOURCE').'</option>';	
					$sources .= "</select>";
				} else {
					$sources = "<select class='yt-generator-isp-sources'>";
						$sources .= '<option value="category" >Category</option>';
						$sources .= '<option value="0" selected >'.JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SELECT_ARTICLE_SOURCE').'</option>';	
					$sources .= "</select>";
				}
				$categories = '<select class="yt-generator-isp-categories" multiple>';
					foreach (get_terms( 'category' ) as $option_value => $option_title)
					{
						$categories .= '<option value="'.$option_value.'">'.$option_title.'</option>';
					}
				
				$categories .= '</select>';
				if (JComponentHelper::isEnabled('com_k2', true)) {
					$k2_categories = '<select class="yt-generator-isp-k2-categories" multiple>';
					foreach (get_k2_terms( 'k2-category' ) as $option_value => $option_title)
					{
						$k2_categories .= '<option value="'.$option_value.'">'.$option_title.'</option>';
					}
					$k2_categories .= '</select>';
				} else {
					$k2_categories = null;
				}
				
				$return  = '<div class="yt-generator-isp">' . $sources;
					$return .= '<div class="yt-generator-isp-source yt-generator-isp-source-category">';
						$return .= '<em class="description">' . JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CATEGORY_DESC') . '</em>';
						$return .= $categories;
					$return .= '</div>';
					$return .= '<div class="yt-generator-isp-source yt-generator-isp-source-k2-category">';
						$return .= '<em class="description">' . JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_K2_CATEGORY_DESC'). '</em>';
						$return .= $k2_categories;
					$return .= '</div>';
					$return .= '<input type="hidden" name="' . $id . '" value="' . $field['default'] . '" id="yt-generator-attr-' . $id . '" class="yt-generator-attr" />';
				$return .= '</div>';
				return $return;
		}
		return $html;
	}

}
?>
<?php 
Class AddElementShortcodes{
	/* ------------------------------------------------------------------------------------- */	
	public static function yt_shortcodes_FormElement($element,$name,$desc,$value = ''){
		$element = strtolower($element);
		$html = '';
		require_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'shortcodes'.DIRECTORY_SEPARATOR.$element.DIRECTORY_SEPARATOR.'config.php';
		$name_shortcodes = 'YT_Shortcode_'.$element.'_config';
		$field_form = $name_shortcodes::get_config();
		$field_ = $field_form;
		$buildShortCode = 'var tagOpen = $(".yt_shortcodes_open").text();
					   var tagClose = $(".yt_shortcodes_close").text();
					   var data;
					   var value;
					   var html = "[yt_'.$element.'";
					   var group = $("#yt-generator-attr-type_change").val();
						$(".yt_shortcodes_parent_form_element .yt-generator-field-container").each(function (index)
						{
							if(($(this).data("group")) == group || ($(this).data("group"))=="all")
							{
								data = $(this).data("name");
								value = $(this).find(".yt-generator-attr").val();
								if(data !="content") 
								{
									html += " "+data+"=\""+value+"\" ";
								}
							}
						});
						html += "]";
						';
		// kiểm tra xem có field son không
		if(!is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'shortcodes'.DIRECTORY_SEPARATOR.$element.'_item'))
		{
			$check_content =0;
			foreach($field_form as $index => $field){
				if($index == "content")
				{
					$check_content =1;
				}
			}
			if($check_content ==1)
			{
				$buildShortCode .= 'html += " "+$("#yt-generator-attr-content").val();';
				$buildShortCode .= 'html +=" [/yt_'.$element.']";return html;';
			}else{
				$buildShortCode .= 'html += "<br>"; return html;';
			}
		}
		$html .= '<div id="yt-generator-breadcrumbs"><a href="javascript:void(0);" class="yt-generator-home" title="Click to return to the shortcodes list">All shortcodes</a> → <span>'.$name.'</span> <small class="alignright">'.$desc.'</small><div class="yt-generator-clear"></div></div>';
		$html .='<div class="yt_shortcodes_wrap_form_element">';
		$html .='<div class="yt_shortcodes_parent_form_element" data-shortcodes="'.$element.'">';
			foreach($field_form as $index => $field){
				if(!isset($field['type'])){
					$field['type'] ="text";
				}
				$data_group ='';
				if(isset($field['group'])){
					$data_group = 'data-group="'.$field['group'].'"';
				}else{
					$data_group = 'data-group="all"';
				}
				if(isset($field['child']) && count($field['child']) > 0)
				{
					$html .='<div class="yt-generator-field-group">';
						$html .='<div class="yt-generator-field-container yt-field-type-'.$field['type'].' yt-generator-skip" data-default="'.$field['default'].'" data-name="'.$index.'" '.$data_group.'>
					<h5>'.$field['name'].'</h5>';
						$html .= YT_Field_Shortcodes::formField($index,$field);
						$html .='<div class="yt-generator-attr-desc">'.$field['desc'].'</div></div>';
						foreach($field['child'] as $index__ => $field__)
						{
							if(!isset($field__['type']))
							{
								$field__['type'] ="text";
							}
							if(isset($field__['group'])){
								$data_group_son = 'data-group="'.$field__['group'].'"';
							}else{
								$data_group_son = 'data-group="all"';
							}
							$html .='<div class="yt-generator-field-container yt-field-type-'.$field__['type'].' yt-generator-skip" data-default="'.$field__['default'].'" data-name="'.$index__.'" '.$data_group_son.'>
							<h5>'.$field__['name'].'</h5>';
							$html .= YT_Field_Shortcodes::formField($index__,$field__);
							$html .='<div class="yt-generator-attr-desc">'.$field__['desc'].'</div>';
							$html .= '</div>';
						}
					$html .='</div>';
				}else
				{
					$html .='<div class="yt-generator-field-container yt-field-type-'.$field['type'].' yt-generator-skip" data-default="'.$field['default'].'" data-name="'.$index.'" '.$data_group.'>
					<h5>'.$field['name'].'</h5>';
					$html .= YT_Field_Shortcodes::formField($index,$field);
					if($index != "content")
					{
						$html .='<div class="yt-generator-attr-desc">'.$field['desc'].'</div>';
					}
					$html .= '</div>';
				}
					
				
			}
		$html .='</div>';//End parent
		// Son
		if(file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'shortcodes'.DIRECTORY_SEPARATOR.$element.'_item'.DIRECTORY_SEPARATOR.'config.php'))
		{
			require_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'shortcodes'.DIRECTORY_SEPARATOR.$element.'_item'.DIRECTORY_SEPARATOR.'config.php';
			$name_son = 'YT_Shortcode_'.$element.'_item_config';
			$field_form_son = $name_son::get_config();
			$buildShortCode .='
							$checkcontentson = 0;
							 length = $(".yt_shortcodes_son_form_element .yt_shortcodes_son_wrap").length;
							 for(i = 0; i < length; i++)
							 {
							 	html +=" [yt_'.$element.'_item";
								for (var a = 0;a < $(".yt_shortcodes_son_form_element .yt_shortcodes_son_wrap").eq(i).find(".yt_shortcodes_wrap_form .yt-generator-field-container_son .yt-generator-field-container").length; a++){								
									data = $(".yt_shortcodes_son_form_element .yt_shortcodes_son_wrap").eq(i).find(".yt_shortcodes_wrap_form .yt-generator-field-container_son .yt-generator-field-container").eq(a).attr("data-name");
									
		                            value = $(".yt_shortcodes_son_form_element .yt_shortcodes_son_wrap").eq(i).find(".yt_shortcodes_wrap_form .yt-generator-field-container_son .yt-generator-field-container").eq(a).find(".yt-generator-attr").val();
		                            
		                            if(data=="content") { $checkcontentson = 1; continue;}
									html += " "+data+"=\""+value+"\" ";
								}
								html += "] ";
								if($checkcontentson == 1)
								{
									html += $("#yt-generator-attr-content-"+i+"").val();
									html += " [/yt_'.$element.'_item] ";
								}
							 }
							 html += " [/yt_'.$element.'] ";return html;';
			$html .= '<input type="button" class="yt_btn yt_btn-info yt_shortcodes_add_element" value="Add '.ucfirst($name).' Item">';
			$html .= '	<div class="yt_shortcodes_son_form_element">';
			
			$html .= '	<div class="yt_shortcodes_son_wrap">';
			$html .= ' 	<input type="button" class="yt_btn yt_shortcodes_son_button" data-active="active" value="'.ucfirst($name).' item 1">';
			$html .= ' 	<div class="yt_shortcodes_wrap_form">';
			foreach($field_form_son as $index => $field)
			{
				if(!isset($field['type'])){
					$field['type'] ="text";
				}
				$html .= '<div class="yt-generator-field-container_son">';
					if(isset($field['child']) && count($field['child']) > 0){
						$html .='<div class="yt-generator-field-group">';
							$html .='<div class="yt-generator-field-container yt-field-type-'.$field['type'].' yt-generator-skip" data-default="'.$field['default'].'" data-name="'.$index.'">
						<h5>'.$field['name'].'</h5>';
							$html .= YT_Field_Shortcodes::formField($index,$field);
							$html .='<div class="yt-generator-attr-desc">'.$field['desc'].'</div></div>';
							foreach($field['child'] as $index__ => $field__){
								if(!isset($field__['type']))
								{
									$field__['type'] = 'text';
								}
								$html .='<div class="yt-generator-field-container yt-field-type-'.$field__['type'].' yt-generator-skip" data-default="'.$field__['default'].'" data-name="'.$index__.'">
								<h5>'.$field__['name'].'</h5>';
								$html .= YT_Field_Shortcodes::formField($index__,$field__);
								if($index != "content")
								{
									$html .='<div class="yt-generator-attr-desc">'.$field__['desc'].'</div>';
								}
								$html .= '</div>';
							}
						$html .='</div>';
					}else
					{
						$html .='<div class="yt-generator-field-container yt-field-type-'.$field['type'].' yt-generator-skip" data-default="'.htmlentities($field['default']).'" data-name="'.$index.'">
						<h5>'.$field['name'].'</h5>';
						$html .= YT_Field_Shortcodes::formField($index,$field);
						if($index != "content")
						{
							$html .='<div class="yt-generator-attr-desc">'.$field['desc'].'</div>';
						}
						$html .= '</div>';
					}
				$html .= '</div>';
			}
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}
		//insert shortcode
		$html .= '<div class="yt-generator-preview"><h5>Preview</h5><div class="show-yt_shortcode"></div></div><div class="yt-generator-actions yt-generator-clearfix" ><a href="javascript:void(0);" class="yt_btn yt_btn-primary yt-generator-insert" ><i class="fa fa-save"></i> Save</a> 	<a href="javascript:void(0);" class="yt_btn yt_btn-primary yt-generator-home yt-generator-cancel" ><i class="fa fa-close"></i> Cancel</a> <a href="javascript:void(0);" class="yt_btn yt_btn-primary yt-generator-show-code" style="float:right;" ><i class="fa fa-plus"></i> Show code</a>  </div>';
		$html .= '<script>
		jQuery(document).ready(function($){
			function buildShortCode()
			{
				'.$buildShortCode.'
			}
								
			$(".yt-generator-insert").click(function(){
				var html = buildShortCode();
				
				//Editor jform_content
				if(document.getElementById("jform_content") != null) {
					jInsertEditorText(html, "jform_content");
				}
				
				//Editor Content
				if(document.getElementById("jform_articletext") != null) {
					jInsertEditorText(html, "jform_articletext");
				}
				if(document.getElementById("jform_description") != null) {
					jInsertEditorText(html, "jform_description");
				}
				
				//Editor K2
				if(document.getElementById("description") != null) {
					jInsertEditorText(html, "description");
				}
				if(document.getElementById("text") != null) {
					jInsertEditorText(html, "text");
				}
				
				//Editor VirtueMart 
				if(document.getElementById("category_description") != null) {
					jInsertEditorText(html, "category_description");
				}
				if(document.getElementById("product_desc") != null) {
					jInsertEditorText(html, "product_desc");
				}
				
				//Editor Contact
				if(document.getElementById("jform_misc") != null) {
					jInsertEditorText(html, "jform_misc");
				}
				//Editor Joomshoping
				if(document.getElementById("description1") != null) {
					jInsertEditorText(text, "description1");
				}
				$(".yt_shortcode_element_config").hide();
				$(".yt_shortcode_overlay").hide();
				$(".yt_shortcodes_plugin").hide();
				$(".yt_shortcodes_close").hide();
			});
			var showshortcode = 0;
			$(".yt_shortcodes_plugin").on("click", ".yt-generator-show-code", function () {
				var html = buildShortCode();
				$ShowCode = $(".show-yt_shortcode")	;
				$(".yt-generator-preview").show();
				$ShowCode.html(html);
				showshortcode = 1;
			});
			$(".yt_shortcodes_plugin").on("click", ".yt-generator-live-preview", function () {
				var ajax_url = window.location.href;
				var html = buildShortCode();
				$.ajax({
						type: "POST",
						url: ajax_url,
						data: {
							live_show_shortcodes: 1,
							html: html,
							action: "yt_shortcode_live_preview"
						},
						beforeSend: function () {
							// Show loading animation
							$(".show-yt_shortcode").addClass("yt-generator-loading").show();
						},
						success: function (data) {
							
							$(".yt-generator-preview").show();
							$(".show-yt_shortcode").removeClass("yt-generator-loading");
							$(".show-yt_shortcode").html(data["html"]);
						},
					dataType: "json"
				});
			});
			$(".yt_shortcodes_plugin").on("change", ".yt-generator-attr", function () {
				if(showshortcode ==1)
				{
					var html = buildShortCode();
					$ShowCode = $(".show-yt_shortcode")	;
					$ShowCode.html(html);
				}
			});
			var slideup = 0;
			$(".yt_shortcodes_son_wrap").first().find("#yt-generator-attr-content").attr("id","yt-generator-attr-content-0");	
			$(".yt_shortcodes_add_element").click(function(){
				$(".slider").html("");
				$(".slider").remove();
				var dem = $(".yt_shortcodes_son_button").length;
				$(".yt_shortcodes_son_button").attr("data-active","");
				$(".yt_shortcodes_son_button").parent().find(".yt_shortcodes_wrap_form").slideUp();
				$(".yt_shortcodes_son_form_element").append("<div class=\'yt_shortcodes_son_wrap\'></div>");
				$(".yt_shortcodes_son_form_element .yt_shortcodes_son_wrap").last().append("<input type=\"button\" class=\"yt_btn yt_shortcodes_son_button\" data-active=\"active\" value=\"'.ucfirst($name).' Item "+(dem+1)+"\">");
				var html = $(".yt_shortcodes_wrap_form").html();
				$(".yt_shortcodes_son_form_element .yt_shortcodes_son_wrap").last().append("<div class=\'yt_shortcodes_wrap_form\'>"+html+"</div>");
				$(".yt_shortcodes_wrap_form").last().find("#yt-generator-attr-title").attr("id","yt-generator-attr-title-"+(dem+1)+"");
				$(".yt_shortcodes_wrap_form").last().find("#yt-generator-attr-icon_size-slider").attr("id","yt-generator-attr-icon_size-"+(dem+1)+"-slider");
				$(".yt_shortcodes_wrap_form").last().find("#yt-generator-attr-icon_size").attr("name","icon_size-"+(dem+1)+"");
				$(".yt_shortcodes_wrap_form").last().find("#yt-generator-attr-icon_size").attr("id","yt-generator-attr-icon_size-"+(dem+1)+"");
				$(".yt_shortcodes_wrap_form").last().find("#yt-generator-attr-icon").attr("id","yt-generator-attr-icon-"+(dem+1)+"");
				$(".yt_shortcodes_wrap_form").last().find(".yt-generator-attr-icon-a").attr("href","index.php?option=com_media&view=images&tmpl=component&asset=&author=&fieldid=yt-generator-attr-icon-"+(dem+1)+"&folder=");
				
				var media = $(".yt_shortcodes_wrap_form").last().find(".yt-generator-img-picker-wrapper input").attr("id");
				$(".yt_shortcodes_wrap_form").last().find("#"+media+"").attr("id",""+media+"-"+(dem+1)+"");
				$(".yt_shortcodes_wrap_form").last().find(".yt-generator-attr-src-a").attr("href","index.php?option=com_media&view=images&tmpl=component&asset=&author=&fieldid="+media+"-"+(dem+1)+"&folder=");	
				$(".yt_shortcodes_son_wrap").last().find("#yt-generator-attr-content-0").attr("id","yt-generator-attr-content-"+dem+"");
				$(".yt_shortcodes_son_wrap").last().find("#yt-generator-attr-active").attr("id","yt-generator-attr-active-"+dem+"");
				$(".yt_shortcodes_son_wrap").last().find("#yt-generator-attr-active").attr("name","active-"+dem+"");
				
			});	
		});</script>';
		return $html;
	}	
}

?>
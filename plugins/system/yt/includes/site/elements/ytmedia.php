<?php

defined('JPATH_BASE') or die;
	/**	
    * @package YT Framework
    * @author Smartaddons http://www.Smartaddons.com
    * @copyright Copyright (c) 2009 - 2014 Smartaddons
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */
if(J_VERSION=='3'){
	class JFormFieldYtMedia extends JFormFieldMedia {
		protected $type = 'YtMedia';
	}
} elseif(J_VERSION=='2'){

	class JFormFieldYtMedia extends JFormField
	{
		protected $type = 'YtMedia';
		protected static $initialised = false;
		protected function getInput()
		{
			$assetField = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
			$authorField = $this->element['created_by_field'] ? (string) $this->element['created_by_field'] : 'created_by';
			$asset = $this->form->getValue($assetField) ? $this->form->getValue($assetField) : (string) $this->element['asset_id'];
			if ($asset == '')
			{
				$asset = JFactory::getApplication()->input->get('option');
			}

			$link = (string) $this->element['link'];
			if (!self::$initialised)
			{
				// Load the modal behavior script.
				JHtml::_('behavior.modal');

				// Build the script.
				$script = array();
				$script[] = '	function jInsertFieldValue(value, id) {';
				$script[] = '		var old_value = document.id(id).value;';
				$script[] = '		if (old_value != value) {';
				$script[] = '			var elem = document.id(id);';
				$script[] = '			elem.value = value;';
				$script[] = '			elem.fireEvent("change");';
				$script[] = '			if (typeof(elem.onchange) === "function") {';
				$script[] = '				elem.onchange();';
				$script[] = '			}';
				$script[] = '			jMediaRefreshPreview(id);';
				$script[] = '		}';
				$script[] = '	}';

				$script[] = '	function jMediaRefreshPreview(id) {';
				$script[] = '		var value = document.id(id).value;';
				$script[] = '		var img = document.id(id + "_preview");';
				$script[] = '		if (img) {';
				$script[] = '			if (value) {';
				$script[] = '				img.src = "' . JURI::root() . '" + value;';
				$script[] = '				document.id(id + "_preview_empty").setStyle("display", "none");';
				$script[] = '				document.id(id + "_preview_img").setStyle("display", "");';
				$script[] = '			} else { ';
				$script[] = '				img.src = ""';
				$script[] = '				document.id(id + "_preview_empty").setStyle("display", "");';
				$script[] = '				document.id(id + "_preview_img").setStyle("display", "none");';
				$script[] = '			} ';
				$script[] = '		} ';
				$script[] = '	}';

				$script[] = '	function jMediaRefreshPreviewTip(tip)';
				$script[] = '	{';
				$script[] = '		var img = tip.getElement("img.media-preview");';
				$script[] = '		tip.getElement("div.tip").setStyle("max-width", "none");';
				$script[] = '		var id = img.getProperty("id");';
				$script[] = '		id = id.substring(0, id.length - "_preview".length);';
				$script[] = '		jMediaRefreshPreview(id);';
				$script[] = '		tip.setStyle("display", "block");';
				$script[] = '	}';

				// Add the script to the document head.
				JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

				self::$initialised = true;
			}

			$html = array();
			$attr = '';

			// Initialize some field attributes.
			$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
			$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

			// Initialize JavaScript field attributes.
			$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

			// The text field.
			$html[] = '<div class="ytmedia input-prepend input-append">';

			// The Preview.
			$preview = (string) $this->element['preview'];
			$showPreview = true;
			$showAsTooltip = false;
			switch ($preview)
			{
				case 'no': // Deprecated parameter value
				case 'false':
				case 'none':
					$showPreview = false;
					break;

				case 'yes': // Deprecated parameter value
				case 'true':
				case 'show':
					break;

				case 'tooltip':
				default:
					$showAsTooltip = true;
					$options = array(
						'onShow' => 'jMediaRefreshPreviewTip',
					);
					JHtml::_('behavior.tooltip', '.hasTipPreview', $options);
					break;
			}

			if ($showPreview)
			{
				if ($this->value && file_exists(JPATH_ROOT . '/' . $this->value))
				{
					$src = JURI::root() . $this->value;
				}
				else
				{
					$src = '';
				}

				$width = isset($this->element['preview_width']) ? (int) $this->element['preview_width'] : 300;
				$height = isset($this->element['preview_height']) ? (int) $this->element['preview_height'] : 200;
				$style = '';
				$style .= ($width > 0) ? 'max-width:' . $width . 'px;' : '';
				$style .= ($height > 0) ? 'max-height:' . $height . 'px;' : '';

				$imgattr = array(
					'id' => $this->id . '_preview',
					'class' => 'media-preview',
					'style' => $style,
				);
				$img = JHtml::image($src, JText::_('JLIB_FORM_MEDIA_PREVIEW_ALT'), $imgattr);
				$previewImg = '<div id="' . $this->id . '_preview_img"' . ($src ? '' : ' style="display:none"') . '>' . $img . '</div>';
				$previewImgEmpty = '<div id="' . $this->id . '_preview_empty"' . ($src ? ' style="display:none"' : '') . '>'
					. JText::_('JLIB_FORM_MEDIA_PREVIEW_EMPTY') . '</div>';

				$html[] = '<div class="media-preview add-on">';
				if ($showAsTooltip)
				{
					$tooltip = $previewImgEmpty . $previewImg;
					$options = array(
						'title' => JText::_('JLIB_FORM_MEDIA_PREVIEW_SELECTED_IMAGE'),
						'text' => '<i class="icon-eye-open"></i>',
						'class' => 'hasTipPreview'
					);
					$html[] = JHtml::tooltip($tooltip, $options);
				}
				else
				{
					$html[] = ' ' . $previewImgEmpty;
					$html[] = ' ' . $previewImg;
				}
				$html[] = '</div>';
			}

			$html[] = '	<input type="text" class="input-small" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' readonly="readonly"' . $attr . ' />';

			$directory = (string) $this->element['directory'];
			if ($this->value && file_exists(JPATH_ROOT . '/' . $this->value))
			{
				$folder = explode('/', $this->value);
				array_diff_assoc($folder, explode('/', JComponentHelper::getParams('com_media')->get('image_path', 'images')));
				array_pop($folder);
				$folder = implode('/', $folder);
			}
			elseif (file_exists(JPATH_ROOT . '/' . JComponentHelper::getParams('com_media')->get('image_path', 'images') . '/' . $directory))
			{
				$folder = $directory;
			}
			else
			{
				$folder = '';
			}

			// The button.
			if ($this->element['disabled'] != true)
			{
				
				$html[] = '<a class="modal btn" title="' . JText::_('JLIB_FORM_BUTTON_SELECT') . '"' . ' href="'
					. ($this->element['readonly'] ? ''
					: ($link ? $link
						: 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=' . $asset . '&amp;author='
						. $this->form->getValue($authorField)) . '&amp;fieldid=' . $this->id . '&amp;folder=' . $folder) . '"'
					. ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
				$html[] = JText::_('JLIB_FORM_BUTTON_SELECT') . '</a><a class="btn hasTooltip" title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
				$html[] = 'jInsertFieldValue(\'\', \'' . $this->id . '\');';
				$html[] = 'return false;';
				$html[] = '">';
				$html[] = '<i class="icon-remove"></i></a>';
			}

			$html[] = '</div>';

			return implode("\n", $html);
		}
	}

}

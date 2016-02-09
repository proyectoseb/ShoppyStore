<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.0.1
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="maincontent" style="border: 1px solid rgb(233, 233, 233);">
	<form method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" style="margin:0px;">
		<div id="folderarea" style="box-shadow: 0px 4px 4px -4px rgba(0, 0, 0, 0.3);padding:15px;">
			<?php
			$folders = acymailing_generateArborescence($this->uploadFolders);

			foreach($folders as $folder){
				$this->values[] = JHTML::_('select.option', $folder, $folder);
			}

			echo JHTML::_('select.genericlist', $this->values, 'currentFolder', 'class="inputbox chzn-done" size="1" onchange="submitbutton();" style="width:350px;" ', 'value', 'text', $this->uploadFolder).'<br />';
			?>
		</div>
		<div id="filesarea" style="width:100%;height:460px;overflow-x: hidden;text-align: center;">
			<?php
			if(file_exists($this->uploadPath)) $files = JFolder::files($this->uploadPath);
			$imageExtensions = array('jpg', 'jpeg', 'png', 'gif', 'ico', 'bmp');

			if(in_array($this->map, array('thumb', 'readmore'))){
				$allowedExtensions = $imageExtensions;
			}else{
				$allowedExtensions = explode(',', $this->config->get('allowedfiles'));
				$allowedExtensions = array_merge($allowedExtensions, $imageExtensions);
			}

			if(!empty($files)){
				$k = 0;
				foreach($files as $file){
					if(strrpos($file, '.') === false) continue;

					$ext = strtolower(substr($file, strrpos($file, '.') + 1));
					if(!in_array($ext, $allowedExtensions)) continue;

					$filesFound = true;

					?>
					<div style="display:inline-block; text-align: center;">
						<a href="#" style="text-decoration:none;" onclick="<?php
						echo "parent.document.getElementById('".$this->map."').value = '".str_replace(DS, '/', $this->uploadFolder)."/$file';";
						if(in_array($this->map, array('thumb', 'readmore'))){
							echo "parent.document.getElementById('".$this->map."preview').src = '".JURI::root().str_replace(DS, '/', $this->uploadFolder)."/$file';";
						}else{
							echo "parent.document.getElementById('".$this->map."selection').innerHTML = '$file';";
						}?>
							window.parent.SqueezeBox.close();">
							<div style="width: 160px;height: 160px;margin: 14px;border: 1px solid rgb(233, 233, 233);border-radius:4px;" onmouseover="this.style.opacity = 0.5;" onmouseout="this.style.opacity = 1;">
								<?php
								if(strlen($file) > 20){
									echo substr(rtrim($file, $ext), 0, 17).'...'.$ext;
								}else echo $file;
								if(in_array($ext, $imageExtensions)){
									$imgPath = ACYMAILING_LIVE.$this->uploadFolder.'/'.$file;
								}else $imgPath = ACYMAILING_LIVE.'media/com_acymailing/images/file.png';
								echo '<br /><img src="'.$imgPath.'" style="margin-top:5px;width:150px;height:132px;"/>';
								?>
							</div>
						</a>
					</div>
					<?php
					$k++;
				}
			}

			if(empty($filesFound)) acymailing_display(JText::_('NO_FILE_FOUND'), 'warning');
			?>
		</div>
		<div id="uploadarea" style="text-align: center;box-shadow: 0px -4px 4px -4px rgba(0, 0, 0, 0.3);padding: 10px 0px 10px 0px;">
			<input type="file" style="width:auto;" name="uploadedFile"/><br/>
			<input type="hidden" name="task" value="select"/>
			<input type="hidden" name="selected_folder" value="<?php echo htmlspecialchars($this->uploadFolder, ENT_COMPAT, 'UTF-8'); ?>"/>
			<?php echo JHTML::_('form.token'); ?>
			<button class="btn btn-primary" type="button" onclick="submit();"> <?php echo JText::_('IMPORT'); ?> </button>
		</div>
	</form>
</div>

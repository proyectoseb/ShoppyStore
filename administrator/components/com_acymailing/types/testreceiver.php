<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.0.1
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class testreceiverType{
	function display($selection = '', $group = '', $emails = ''){
		$app = JFactory::getApplication();
		$my = JFactory::getUser();
		if(empty($emails)) $emails = $my->email;

		$js = 'function timeoutAddNewTestAddress(currentValue){
					if(currentValue.length > 1 && ((currentValue.indexOf("@") != -1 && currentValue.slice(-1) == " ") || currentValue.slice(-1) == ";" || currentValue.slice(-1) == ",")){
						currentValue = currentValue.substring(0, currentValue.length - 1);
						setUser(currentValue);
						return;
					}
					setTimeout(function(){addNewTestAddress(currentValue);}, 500);
				}

			function addNewTestAddress(currentValue){';
		if($app->isAdmin()){
			$js .= 'if(currentValue != document.getElementById("message_receivers").value) return;

				try{
					var ajaxCall = new Ajax("index.php?option=com_acymailing&tmpl=component&ctrl=subscriber&task=getSubscribersByEmail&search="+currentValue,{
						method: "get",
						onComplete: function(responseText, responseXML) {
							document.getElementById("acymailing_divSelectReceiver").style.display = "block";
							document.getElementById("acymailing_receiversTable").innerHTML = responseText;
							receiversList = document.getElementById("acymailing_receiversTable");
							if(receiversList.getElementsByClassName("row_user").length==0) {
								document.getElementById("acymailing_divSelectReceiver").style.display = "none";
							}
						}
					}).request();

				}catch(err){
					new Request({
						url:"index.php?option=com_acymailing&tmpl=component&ctrl=subscriber&task=getSubscribersByEmail&search="+currentValue,
						method: "get",
						onSuccess: function(responseText, responseXML) {
							document.getElementById("acymailing_divSelectReceiver").style.display = "block";
							document.getElementById("acymailing_receiversTable").innerHTML = responseText;
							receiversList = document.getElementById("acymailing_receiversTable");
							if(receiversList.getElementsByClassName("row_user").length==0) {
								document.getElementById("acymailing_divSelectReceiver").style.display = "none";
							}
						}
					}).send();
				}';
		}
		$js .= '}

			var selected = new Array("'.str_replace(',', '","', $emails).'");
			function setUser(userEmail){
				userEmail = userEmail.replace(/^\s+|\s+$/gm,"");
				if(validateEmail(userEmail, "'.str_replace('"', '\\"', JText::_('SEND_TEST_TO')).'") && selected.indexOf(userEmail) == -1){
					selected.push(userEmail);
					document.getElementById("usersSelected").innerHTML += "<span class=\"selectedUsers\">"+userEmail+"<span class=\"removeUser\" onclick=\"removeUser(this, \'"+userEmail+"\');\"></span></span>";
					document.getElementById("test_emails").value = selected.join(",");
				}
				document.getElementById("message_receivers").value = "";
				document.getElementById("acymailing_divSelectReceiver").style.display = "none";
			}

			function removeUser(element, userEmail){
				var toRemove = element.parentElement;
				toRemove.parentElement.removeChild(toRemove);
				var index = selected.indexOf(userEmail);
				if (index > -1) {
					selected.splice(index, 1);
				}
				document.getElementById("test_emails").value = selected.join(",");
			}

			function showOptions(selection){
				if(selection == "users"){
					document.getElementById("userSelection").style.display = "";
					document.getElementById("groupSelection").style.display = "none";
				}else{
					document.getElementById("userSelection").style.display = "none";
					document.getElementById("groupSelection").style.display = "";
				}
			}';

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js );
	?>
		<style>
			.removeUser {
				width: 20px;
				background-image: url(<?php echo ACYMAILING_LIVE; ?>/media/com_acymailing/images/closecross.png);
				background-size: cover;
				height: 20px;
				cursor: pointer;
				float: right;
			}
			.selectedUsers {
				background-color: #F5F5F5;
				padding-left: 5px;
				display: inline-block;
				border: solid 1px #C5C4C4;
				border-radius: 4px;
				margin-right: 3px;
				margin-top: 5px;
				line-height: 20px;
			}
			#acymailing_divSelectReceiver {
				position: absolute;
				width: 400px;
				border: solid 1px #858585;
				z-index:9999;
				background: white;
			}
			#acymailing_receiversTable .row_user:hover {
				background-color: #EBEBEB;
				cursor:pointer;
			}
			.row_user{
				border-top:solid 1px #EBEBEB;
			}
			#usersSelected {
				margin-bottom:2px;
				width:100%;
				display:block;
			}
		</style>
	<?php
		echo acymailing_getFunctionsEmailCheck();
		if($app->isAdmin()){
			$values = array();
			$values[] = JHTML::_('select.option', 'users',JText::_('ACY_SUBSCRIBER'));
			$values[] = JHTML::_('select.option', 'group',JText::_('ACY_GROUP'));
			echo JHTML::_('select.genericlist', $values, 'test_selection' , 'size="1" style="margin:0;" onchange="showOptions(this.value);"', 'value', 'text', $selection);
		}else{
			echo '<input class="inputbox" type="hidden" id="test_selection" name="test_selection" value="users" />';
		}
	?>
		<div id="userSelection" style="margin-top:5px;<?php if($selection == 'group') echo 'display:none;'; ?>">
			<input style="width:212px;margin:0;" placeholder="<?php echo JText::_('EMAIL_ADDRESS'); ?>..." type="text" id="message_receivers" onkeyup="timeoutAddNewTestAddress(this.value);" class="inputbox" autocomplete="off"/>
			<span id="usersSelected">
				<?php
					$allEmails = explode(',', $emails);
					foreach($allEmails as $oneEmail){
						echo '<span class="selectedUsers">'.htmlspecialchars($oneEmail,ENT_COMPAT, 'UTF-8').'<span class="removeUser" onclick="removeUser(this, \''.htmlspecialchars($oneEmail,ENT_COMPAT, 'UTF-8').'\');"></span></span>';
					}
				?>
			</span>
			<div id="acymailing_divSelectReceiver" style="display:none; overflow-y:scroll !important;">
				<div id="acymailing_receiversTable"></div>
			</div>
			<input class="inputbox" type="hidden" id="test_emails" name="test_emails" value="<?php echo htmlspecialchars($emails,ENT_COMPAT, 'UTF-8'); ?>" />
		</div>
	<?php
		if($app->isAdmin()){
			$db = JFactory::getDBO();
			if(ACYMAILING_J16){
				$db->setQuery('SELECT ug.id, ug.parent_id, ug.title AS text, COUNT(ugm.user_id) AS nbusers ' .
								'FROM #__usergroups AS ug ' .
								'LEFT JOIN #__user_usergroup_map ugm ' .
									'ON ug.id = ugm.group_id ' .
								'GROUP BY ug.id');
			}else{
				$db->setQuery('SELECT ug.id, ug.parent_id, ug.name AS text, COUNT(u.id) AS nbusers ' .
								'FROM #__core_acl_aro_groups AS ug ' .
								'LEFT JOIN #__users u ' .
									'ON ug.id = u.gid ' .
								'GROUP BY ug.id');
			}
			$values = $db->loadObjectList();
			$this->cats = array();
			if(!empty($values)){
				foreach($values as $oneCat){
					$this->cats[$oneCat->parent_id][] = $oneCat;
				}
			}
			$this->catvalues = array();
			$this->catvalues[] = JHTML::_('select.option', -1, '- - -');
			$this->_handleChildren();
			echo '<div id="groupSelection" style="'.($selection != 'group' ? 'display:none;' : '').'margin-top:5px;">'.JHTML::_('select.genericlist', $this->catvalues, 'test_group' , 'size="1"', 'value', 'text', $group).'</div>';
		}
	}

	private function _handleChildren($parent_id = 0, $level = 0){
		if(empty($this->cats[$parent_id])) return;
		foreach($this->cats[$parent_id] as $cat){
			$addValue = JHTML::_('select.option', $cat->id,str_repeat(" - - ",$level).$cat->text);
			if($cat->nbusers > 10 || $cat->nbusers == 0) $addValue->disable = true;
			$this->catvalues[] = $addValue;
			$this->_handleChildren($cat->id,$level+1);
		}
	}
}

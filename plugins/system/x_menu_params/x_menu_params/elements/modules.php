<?php
// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Radio List Element
 *
 */
if (class_exists('JFormField')){
	class JFormFieldModules extends JFormField
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'SjModules';
		function getInput() {
			$db =& JFactory::getDBO();
			$query = "SELECT e.extension_id, a.id, a.title, a.note, a.position, a.module, a.language,a.checked_out, a.checked_out_time, a.published, a.access, a.ordering, a.publish_up, a.publish_down,l.title AS language_title,uc.name AS editor,ag.title AS access_level,MIN(mm.menuid) AS pages,e.name AS name
						FROM `#__modules` AS a
						LEFT JOIN `#__languages` AS l ON l.lang_code = a.language
						LEFT JOIN #__users AS uc ON uc.id=a.checked_out
						LEFT JOIN #__viewlevels AS ag ON ag.id = a.access
						LEFT JOIN #__modules_menu AS mm ON mm.moduleid = a.id
						LEFT JOIN #__extensions AS e ON e.element = a.module
						WHERE (a.published IN (0, 1)) AND a.client_id = 0
						GROUP BY a.id";
			$db->setQuery($query);
			$groups = $db->loadObjectList();
				
			$groupHTML = array();
			if ($groups && count ($groups)) {
				foreach ($groups as $v=>$t){
					$groupHTML[] = JHTML::_('select.option', $t->id, $t->title);
				}
			}
			$lists = JHTML::_('select.genericlist', $groupHTML, "{$this->name}[]", ' multiple="multiple"  size="10" ', 'value', 'text', $this->value);
				
			return $lists;
		}
	}
}
if (class_exists('JElement')){
	class JElementModules extends JElement
	{
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'SjModules';

		function fetchElement( $name, $value, &$node, $control_name ) {
			$showon = $node->attributes('showon');
			$script = "";
			if (gettype($showon)=='string'){
				list($ref, $val)=explode('==', $showon, 2);
				if (!empty($ref) || !empty($val)){
					$script ="
					<script>
						window.addEvent('domready', function(){
							var ref = $('params$ref');
							var thisElement = $('params$name');						
							var TR$name = thisElement.parentNode.parentNode;
							if (ref.value=='$val'){
								TR$name.style.display='';
							} else {								
								TR$name.parentNode.parentNode.parentNode.style.height='auto';
								TR$name.style.display='none';
							}
							ref.addEvent('change', function(){
								if (this.value=='$val'){
									TR$name.style.display='';
								} else {								
									TR$name.parentNode.parentNode.parentNode.style.height='auto';
									TR$name.style.display='none';
								}
							});
						});
					</script>
					";
				}
			}
				
			$db =& JFactory::getDBO();
				
			$query = "SELECT * FROM #__modules WHERE (client_id=0) AND (published=1) ORDER BY title ASC";
			$db->setQuery($query);
			$groups = $db->loadObjectList();
			$groupHTML = array();
			if ($groups && count ($groups)) {
				foreach ($groups as $tvalue=>$item){
					$groupHTML[] = JHTML::_('select.option', $item->id, $item->title);
				}
			}
			if( !empty($value) && !is_array($value) )
			$value = explode("|", $value);
			$lists = JHTML::_('select.genericlist', $groupHTML, "params[".$name."][]", ' style="width:200px;"', 'value', 'text', $value);
			return $lists.$script;
		}
	}
}
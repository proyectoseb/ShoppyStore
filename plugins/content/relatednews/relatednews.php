<?php
/**
 * @package Related News
 * @version 1.7.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

class plgContentRelatedNews extends JPlugin {
	
	/**
	 * Plugin that loads content within content
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	public function onContentPrepare($context, &$article, &$params, $page=0) {
		// simple performance check to determine whether bot should process further
		$depends = $this->params->get('depends');
		// var_dump($depends);
		// echo 'vao day';
		if (!is_array($depends) || count($depends)==0 || !isset($article->catid) || !in_array($article->catid, $depends)){
			return;
		}
		
		if ($context == 'com_content.article'){
			// helper
			require_once dirname(__FILE__).'/core/helper.php';
			
			// vars
			$article_id = $article->id;
			$category_id = $article->catid;
			
			// patterns
			$patterns = array();
			$regex = "#{relatednews\s?(.*?)}#s";
			preg_match_all($regex, $article->text, $matches);
			
			if (isset($matches[0]) && count($matches[0])){
				foreach ($matches[0] as $i => $match) {
					$key = trim($matches[0][$i]);
					$val = trim($matches[1][$i]);
					$patterns[$key] = $val;
				}
			}
			
			// load template for each pattern
			$_load_templates = array();
			if (count($patterns)){
				foreach ($patterns as $replace => $_params) {
					$_params = $this->_parseParams($_params);
					$_params->set('catid', $category_id);
					$_params->def('template', 'default');
					$items = RelatedNews::getList($_params);
					$patterns[$replace] = array($_params, $items);
				}
			} else {
				$this->params->set('catid', $category_id);
				$this->params->def('template', 'default');
				$items = RelatedNews::getList($this->params);
				$patterns = array();
				$patterns[] = array($this->params, $items);
			}
			
			// append template loaded to article content
			foreach ($patterns as $search => $vars){
				$_params = $vars[0];
				$items = $vars[1];
				ob_start();
				require $this->_getLayoutPath($_params->get('template'));
				$replace = ob_get_contents();
				ob_end_clean();
				
				if (is_string($search)){
					$article->text = preg_replace("#$search#s", $replace, $article->text, 1);
				} else {
					$article->text .= $replace;
				}
				
			}
			
		}
	}
			
	private function _parseParams($_params){
		$clone = clone($this->params);
		if (!empty($_params)){
			$array = JUtility::parseAttributes($_params);
			count($array) && $clone->loadArray($array);
// 			if (count($array)){
// 				foreach ($array as $param => $value){
// 					$clone->set($param, $value);
// 				}
// 			}
		}
		return $clone;
	}
	
	private function _getLayoutPath($tpl='default'){
		$filename = $tpl . '.php';
		$dirname  = dirname(__FILE__).'/tmpl';
		if (file_exists($dirname.'/'.$filename)){
			return $dirname.'/'.$filename;
		}
		return $dirname.'/default.php';
	}
	
}
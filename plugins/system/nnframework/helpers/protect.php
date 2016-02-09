<?php
/**
 * NoNumber Framework Helper File: Protect
 *
 * @package         NoNumber Framework
 * @version         14.11.6
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/cache.php';

class nnProtect
{
	static $protect_a = '<!-- >> NN_PROTECTED >>';
	static $protect_b = ' << NN_PROTECTED << -->';
	static $protect_tags_a = '<!-- >> NN_PROTECTED_TAGS >>';
	static $protect_tags_b = ' << NN_PROTECTED_TAGS << -->';
	static $sourcerer_tag = null;

	/**
	 * check if page should be protected for certain extensions
	 */
	public static function isProtectedPage($extension_alias = '', $hastags = 0, $exclude_formats = array('pdf'))
	{
		// return if disabled via url
		if (($extension_alias && JFactory::getApplication()->input->get('disable_' . $extension_alias)))
		{
			return true;
		}

		$hash = md5('isProtectedPage_' . $hastags . '_' . json_encode($exclude_formats));

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		// return if current page is pdf format
		// return if current page is an image
		// return if current page is NoNumber QuickPage
		// return if current page is a JoomFish or Josetta page
		$isprotected = (
			in_array(JFactory::getApplication()->input->get('format'), $exclude_formats)
			|| in_array(JFactory::getApplication()->input->get('view'), array('image', 'img'))
			|| in_array(JFactory::getApplication()->input->get('type'), array('image', 'img'))
			|| ($hastags
				&& (
					JFactory::getApplication()->input->getInt('nn_qp', 0)
					|| in_array(JFactory::getApplication()->input->get('option'), array('com_joomfishplus', 'com_josetta'))
				)
			)
			|| (JFactory::getApplication()->isAdmin()
				&& in_array(JFactory::getApplication()->input->get('option'), array('com_jdownloads'))
			)
		);

		return nnCache::set($hash,
			$isprotected
		);
	}

	/**
	 * check if this is a Joomla 3 setup
	 * used in Joomla 2.5 code to show error after upgrade to Joomla 3
	 */
	public static function isJoomla3($extension_name = '')
	{
		if (version_compare(JVERSION, '3.0', '<'))
		{
			return false;
		}

		if ($extension_name)
		{
			nnProtect::throwError(
				JText::sprintf('NN_JOOMLA2_VERSION_ON_JOOMLA3', JText::_($extension_name))
			);
		}

		return true;
	}

	/**
	 * check if page is an admin page
	 */
	public static function isAdmin($block_login = 0)
	{
		return (
			JFactory::getApplication()->isAdmin()
			&& (!$block_login || JFactory::getApplication()->input->get('option') != 'com_login')
			&& JFactory::getApplication()->input->get('task') != 'preview'
		);
	}

	/**
	 * check if page is an edit page
	 */
	public static function isEditPage()
	{
		$hash = md5('isEditPage');

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		$option = JFactory::getApplication()->input->get('option');
		// always return false for these components
		if (in_array($option, array('com_rsevents', 'com_rseventspro')))
		{
			return nnCache::set($hash,
				false
			);
		}

		$task = JFactory::getApplication()->input->get('task');
		if (strpos($task, '.') !== false)
		{
			$task = explode('.', $task);
			$task = array_pop($task);
		}

		$view = JFactory::getApplication()->input->get('view');
		if (strpos($view, '.') !== false)
		{
			$view = explode('.', $view);
			$view = array_pop($view);
		}

		$isedit = (
			in_array($task, array('edit', 'form', 'submission'))
			|| in_array($view, array('edit', 'form'))
			|| in_array(JFactory::getApplication()->input->get('do'), array('edit', 'form'))
			|| in_array(JFactory::getApplication()->input->get('layout'), array('edit', 'form', 'write'))
			|| in_array(JFactory::getApplication()->input->get('option'), array('com_contentsubmit', 'com_cckjseblod'))
			|| nnProtect::isAdmin()
		);

		return nnCache::set($hash,
			$isedit
		);
	}

	/**
	 * Check if the component is installed
	 *
	 * @return bool
	 */
	public static function isComponentInstalled($extension_alias)
	{
		jimport('joomla.filesystem.file');

		return JFile::exists(JPATH_ADMINISTRATOR . '/components/com_' . $extension_alias . '/' . $extension_alias . '.php');
	}

	/**
	 * Check if the component is installed
	 *
	 * @return bool
	 */
	public static function isSystemPluginInstalled($extension_alias)
	{
		jimport('joomla.filesystem.file');

		return JFile::exists(JPATH_PLUGINS . '/system/' . $extension_alias . '/' . $extension_alias . '.php');
	}

	/**
	 * the regular expression to mach the edit form
	 */
	public static function getFormRegex($regex_format = 0)
	{
		$regex = '(<' . 'form\s[^>]*((id|name)="(adminForm|postform|submissionForm|default_action_user)|action="[^"]*option=com_myjspace&(amp;)?view=see)")';

		if (!$regex_format)
		{
			return $regex;
		}

		return '#' . $regex . '#si';
	}

	/**
	 * protect all text based form fields
	 */
	public static function protectFields(&$string)
	{
		if (strpos($string, '<input') === false && strpos($string, '<textarea') === false)
		{
			return;
		}

		self::protectByRegex(
			$string,
			'#(?:(?:'
			. '(?:<' . 'input\s[^>]*type\s*=\s*(?:\'|")(?:text|email|hidden)(?:\'|")[^>]*>)'
			. '|(?:<' . 'textarea(\s[^>]*)?>.*?</textarea>)'
			. ')\s*)+#si'
		);
	}

	/**
	 * protect all text based form fields
	 */
	public static function protectScripts(&$string)
	{
		if (strpos($string, '</script>') === false)
		{
			return;
		}

		self::protectByRegex(
			$string,
			'#<script[\s>].*?</script>#si'
		);
	}

	/**
	 * protect all html tags with some type of attributes/content
	 */
	public static function protectHtmlTags(&$string)
	{
		self::protectByRegex($string, '#<[a-z][^>]*(="[^"]*"|=\'[^\']*\')+[^>]*>#si');
	}

	/**
	 * protect text by given regex
	 */
	private static function protectByRegex(&$string, $regex)
	{
		if (preg_match_all($regex, $string, $matches) < 1)
		{
			return;
		}

		$matches = array_unique($matches['0']);

		foreach ($matches as $match)
		{
			$replacements[] = self::protectString($match);
		}

		$string = str_replace($matches, $replacements, $string);
	}

	/**
	 * protect given plugin style tags
	 */
	public static function protectTags(&$string, $tags = array(), $include_closing_tags = true)
	{
		list($tags, $protected) = self::prepareTags($tags, $include_closing_tags);

		$string = str_replace($tags, $protected, $string);
	}

	/**
	 * replace any protected tags to original
	 */
	public static function unprotectTags(&$string, $tags = array(), $include_closing_tags = true)
	{
		list($tags, $protected) = self::prepareTags($tags, $include_closing_tags);

		$string = str_replace($protected, $tags, $string);
	}

	/**
	 * protect array of strings
	 */
	public static function protectInString(&$string, $unprotected = array(), $protected = array())
	{
		$protected = empty($protected) ? self::protectArray($unprotected) : $protected;

		$string = str_replace($unprotected, $protected, $string);
	}

	/**
	 * replace any protected tags to original
	 */
	public static function unprotectInString(&$string, $unprotected = array(), $protected = array())
	{
		$protected = empty($protected) ? self::protectArray($unprotected) : $protected;

		$string = str_replace($protected, $unprotected, $string);
	}

	private static function getSourcererTag()
	{
		if (isset(self::$sourcerer_tag))
		{
			return self::$sourcerer_tag;
		}

		require_once JPATH_PLUGINS . '/system/nnframework/helpers/parameters.php';
		$params = nnParameters::getInstance()->getPluginParams('sourcerer');
		self::$sourcerer_tag = isset($params->syntax_word) ? $params->syntax_word : '';

		return self::$sourcerer_tag;
	}

	/**
	 * protect all Sourcerer blocks
	 */
	public static function protectSourcerer(&$string)
	{
		if (self::getSourcererTag())
		{
			return;
		}

		if (strpos($string, '{/' . self::$sourcerer_tag . '}') === false)
		{
			return;
		}

		$regex = '#' . preg_quote('{' . self::$sourcerer_tag, '#') . '[\s\}].*?' . preg_quote('{/' . self::$sourcerer_tag . '}', '#') . '#si';

		if (preg_match_all($regex, $string, $matches) < 1)
		{
			return;
		}

		$matches = array_unique($matches['0']);

		foreach ($matches as $match)
		{
			$string = str_replace($match, self::protectString($match), $string);
		}
	}

	/**
	 * protect complete adminForm
	 */
	public static function protectForm(&$string, $tags = array(), $include_closing_tags = true)
	{
		if (!self::isEditPage())
		{
			return;
		}

		list($tags, $protected_tags) = self::prepareTags($tags, $include_closing_tags);

		$string = preg_replace(self::getFormRegex(1), '<!-- TMP_START_EDITOR -->\1', $string);
		$string = explode('<!-- TMP_START_EDITOR -->', $string);

		foreach ($string as $i => &$string_part)
		{
			if (empty($string_part) || !fmod($i, 2))
			{
				continue;
			}

			self::protectFormPart($string_part, $tags, $protected_tags);
		}

		$string = implode('', $string);
	}

	private static function protectFormPart(&$string, $tags = array(), $protected_tags = array())
	{
		if (strpos($string, '</form>') === false)
		{
			return;
		}

		// Protect entire form
		if (empty($tags))
		{
			$form_parts = explode('</form>', $string, 2);
			$form_parts['0'] = self::protectString($form_parts['0'] . '</form>');
			$string = implode('', $form_parts);

			return;
		}

		if (!preg_match('#(?:' . implode('|', $tags) . ')#si', $string))
		{
			return;
		}

		$form_parts = explode('</form>', $string, 2);
		// protect tags only inside form fields
		if (preg_match_all('#(?:<textarea[^>]*>.*?<\/textarea>|<input[^>]*>)#si', $form_parts['0'], $matches) < 1)
		{
			return;
		}

		$matches = array_unique($matches['0']);

		foreach ($matches as $match)
		{
			$field = str_replace($tags, $protected_tags, $match);
			$form_parts['0'] = str_replace($match, $field, $form_parts['0']);
		}

		$string = implode('</form>', $form_parts);
	}

	/**
	 * replace any protected text to original
	 */
	public static function unprotect(&$string)
	{
		$regex = '#' . preg_quote(self::$protect_a, '#') . '(.*?)' . preg_quote(self::$protect_b, '#') . '#si';
		while (preg_match_all($regex, $string, $matches, PREG_SET_ORDER) > 0)
		{
			foreach ($matches as $match)
			{
				$string = str_replace($match['0'], base64_decode($match['1']), $string);
			}
		}
	}

	/**
	 * prepare the tags and protected tags array
	 */
	private static function prepareTags($tags, $include_closing_tags = true)
	{
		if (!is_array($tags))
		{
			$tags = array($tags);
		}

		$hash = md5('prepareTags_' . json_encode($tags) . '_' . $include_closing_tags);

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		foreach ($tags as $i => $tag)
		{
			if ($tag['0'] == '{')
			{
				continue;
			}

			$tags[$i] = '{' . $tag;

			if ($include_closing_tags)
			{
				$tags[] = '{/' . $tag;
			}
		}

		return nnCache::set($hash,
			array($tags, self::protectArray($tags, 1))
		);
	}

	/**
	 * encode string
	 */
	public static function protectString($string, $is_tag = 0)
	{
		if ($is_tag)
		{
			return self::$protect_tags_a . base64_encode($string) . self::$protect_tags_b;
		}

		return self::$protect_a . base64_encode($string) . self::$protect_b;
	}

	/**
	 * decode string
	 */
	public static function unprotectString($string, $is_tag = 0)
	{
		if ($is_tag)
		{
			return self::$protect_tags_a . base64_decode($string) . self::$protect_tags_b;
		}

		return self::$protect_a . base64_decode($string) . self::$protect_b;
	}

	/**
	 * encode tag string
	 */
	public static function protectTag($string)
	{
		return self::protectString($string, 1);
	}

	/**
	 * encode array of strings
	 */
	public static function protectArray($array, $is_tag = 0)
	{
		foreach ($array as &$string)
		{
			$string = self::protectString($string, $is_tag);
		}

		return $array;
	}

	/**
	 * decode array of strings
	 */
	public static function unprotectArray($array, $is_tag = 0)
	{
		foreach ($array as &$string)
		{
			$string = self::unprotectString($string, $is_tag);
		}

		return $array;
	}

	/**
	 * replace any protected tags to original
	 */
	public static function unprotectForm(&$string, $tags = array())
	{
		// Protect entire form
		if (empty($tags))
		{
			nnProtect::unprotect($string);

			return;
		}

		nnProtect::unprotectTags($string, $tags);
	}

	/**
	 * remove inline comments in scrips and styles
	 */
	public static function removeInlineComments(&$string, $name)
	{
		$string = preg_replace('#\s*/\* (START|END): ' . $name . ' [a-z]* \*/\s*#s', "\n", $string);
	}

	/**
	 * remove tags from title tags
	 */
	public static function removeFromHtmlTagContent(&$string, $tags, $include_closing_tags = true, $html_tags = array('title'))
	{
		list($tags, $protected) = self::prepareTags($tags, $include_closing_tags);

		if (!is_array($html_tags))
		{
			$html_tags = array($html_tags);
		}

		if (preg_match_all('#(<(' . implode('|', $html_tags) . ')(?:\s[^>]*?)>)(.*?)(</\2>)#si', $string, $matches, PREG_SET_ORDER) < 1)
		{
			return;
		}

		foreach ($matches as $match)
		{
			$content = $match['2'];
			foreach ($tags as $tag)
			{
				$content = preg_replace('#' . preg_quote($tag, '#') . '.*?\}#si', '', $content);
			}
			$string = str_replace($match['0'], $match['1'] . $content . $match['3'], $string);
		}
	}

	/**
	 * remove tags from tag attributes
	 */
	public static function removeFromHtmlTagAttributes(&$string, $tags, $attributes = array('title', 'alt'), $include_closing_tags = true)
	{
		list($tags, $protected) = self::prepareTags($tags, $include_closing_tags);

		if (!is_array($attributes))
		{
			$attributes = array($attributes);
		}

		if (preg_match_all('#\s(?:' . implode('|', $attributes) . ')\s*=\s*".*?"#si', $string, $matches) < 1)
		{
			return;
		}

		$matches = array_unique($matches['0']);

		foreach ($matches as $match)
		{
			$title = $match;
			foreach ($tags as $tag)
			{
				$title = preg_replace('#' . preg_quote($tag, '#') . '.*?\}#si', '', $title);
			}
			$string = str_replace($match, $title, $string);
		}
	}

	/**
	 * Check if article passes security levels
	 */
	static function articlePassesSecurity(&$article, $securtiy_levels = array())
	{
		if (!isset($article->created_by))
		{
			return true;
		}

		if (empty($securtiy_levels))
		{
			return true;
		}

		if (is_string($securtiy_levels))
		{
			$securtiy_levels = array($securtiy_levels);
		}

		if (
			!is_array($securtiy_levels)
			|| in_array('-1', $securtiy_levels)
		)
		{
			return true;
		}

		// Lookup group level of creator
		$user_groups = new JAccess;
		$user_groups = $user_groups->getGroupsByUser($article->created_by);

		// Return true if any of the security levels are found in the users groups
		return count(array_intersect($user_groups, $securtiy_levels));
	}

	/**
	 * Place an error in the message queue
	 */
	static function throwError($error)
	{
		// Return if page is not an admin page or the admin login page
		if (
			!JFactory::getApplication()->isAdmin()
			|| JFactory::getUser()->get('guest')
		)
		{
			return;
		}

		// Check if message is not already in queue
		$messagequeue = JFactory::getApplication()->getMessageQueue();
		foreach ($messagequeue as $message)
		{
			if ($message['message'] == $error)
			{
				return;
			}
		}

		JFactory::getApplication()->enqueueMessage($error, 'error');
	}
}

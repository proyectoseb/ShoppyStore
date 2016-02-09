<?php
/**
 * NoNumber Framework Helper File: Text
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

class nnText
{
	public static function dateToDateFormat($dateFormat)
	{
		$caracs = array(
			// Day
			'%d'  => 'd',
			'%a'  => 'D',
			'%#d' => 'j',
			'%A'  => 'l',
			'%u'  => 'N',
			'%w'  => 'w',
			'%j'  => 'z',
			// Week
			'%V'  => 'W',
			// Month
			'%B'  => 'F',
			'%m'  => 'm',
			'%b'  => 'M',
			// Year
			'%G'  => 'o',
			'%Y'  => 'Y',
			'%y'  => 'y',
			// Time
			'%P'  => 'a',
			'%p'  => 'A',
			'%l'  => 'g',
			'%I'  => 'h',
			'%H'  => 'H',
			'%M'  => 'i',
			'%S'  => 's',
			// Timezone
			'%z'  => 'O',
			'%Z'  => 'T',
			// Full Date / Time
			'%s'  => 'U'
		);

		return strtr((string) $dateFormat, $caracs);
	}

	public static function dateToStrftimeFormat($dateFormat)
	{
		$caracs = array(
			// Day - no strf eq : S
			'd'  => '%d',
			'D'  => '%a',
			'jS' => '%#d[TH]',
			'j'  => '%#d',
			'l'  => '%A',
			'N'  => '%u',
			'w'  => '%w',
			'z'  => '%j',
			// Week - no date eq : %U, %W
			'W'  => '%V',
			// Month - no strf eq : n, t
			'F'  => '%B',
			'm'  => '%m',
			'M'  => '%b',
			// Year - no strf eq : L; no date eq : %C, %g
			'o'  => '%G',
			'Y'  => '%Y',
			'y'  => '%y',
			// Time - no strf eq : B, G, u; no date eq : %r, %R, %T, %X
			'a'  => '%P',
			'A'  => '%p',
			'g'  => '%l',
			'h'  => '%I',
			'H'  => '%H',
			'i'  => '%M',
			's'  => '%S',
			// Timezone - no strf eq : e, I, P, Z
			'O'  => '%z',
			'T'  => '%Z',
			// Full Date / Time - no strf eq : c, r; no date eq : %c, %D, %F, %x
			'U'  => '%s'
		);

		return strtr((string) $dateFormat, $caracs);
	}

	public static function html_entity_decoder($given_html, $quote_style = ENT_QUOTES, $charset = 'UTF-8')
	{
		if (is_array($given_html))
		{
			foreach ($given_html as $i => $html)
			{
				$given_html[$i] = self::html_entity_decoder($html);
			}

			return $given_html;
		}

		return html_entity_decode($given_html, $quote_style, $charset);
	}

	public static function getTagRegex($tags, $include_no_attributes = true, $include_ending = true, $required_attributes = array())
	{
		$tags = self::toArray($tags);
		$tags = '(?:' . implode('|', $tags) . ')';

		$attribs = '(?:\s|&nbsp;|&\#160;)[^>"]*(?:"[^"]*"[^>"]*)+';

		$required_attributes = self::toArray($required_attributes);
		if (!empty($required_attributes))
		{
			$attribs = '(?:\s|&nbsp;|&\#160;)[^>"]*(?:"[^"]*"[^>"]*)*(?:' . implode('|', $required_attributes) . ')\s*=\s*(?:"[^"]*"[^>"]*)+';
		}

		if ($include_no_attributes)
		{
			$attribs = '(?:' . $attribs . ')?';
		}

		if (!$include_ending)
		{
			return '<' . $tags . $attribs . '>';
		}

		return '<(?:\/' . $tags . '|' . $tags . $attribs . ')(?:\s|&nbsp;|&\#160;)*>';
	}

	public static function toArray($string, $separator = '')
	{
		if (is_array($string))
		{
			return $string;
		}

		if (is_object($string))
		{
			return (array) $string;
		}

		if ($separator == '')
		{
			return array($string);
		}

		return explode($separator, $string);
	}

	public static function cleanTitle($string, $striptags = 0)
	{
		// remove comment tags
		$string = preg_replace('#<\!--.*?-->#s', '', $string);

		// replace weird whitespace
		$string = str_replace(chr(194) . chr(160), ' ', $string);

		if ($striptags)
		{
			// remove html tags
			$string = preg_replace('#</?[a-z][^>]*>#usi', '', $string);
			// remove comments tags
			$string = preg_replace('#<\!--.*?-->#us', '', $string);
		}

		return trim($string);
	}

	public static function prepareSelectItem($string, $published = 1, $type = '', $remove_first = 0)
	{

		$string = str_replace(array('&nbsp;', '&#160;'), ' ', $string);
		$string = preg_replace('#- #', '  ', $string);
		for ($i = 0; $remove_first > $i; $i++)
		{
			$string = preg_replace('#^  #', '', $string);
		}
		preg_match('#^( *)(.*)$#', $string, $match);
		list($string, $pre, $name) = $match;

		$pre = preg_replace('#  #', ' ·  ', $pre);
		$pre = preg_replace('#(( ·  )*) ·  #', '\1 »  ', $pre);
		$pre = str_replace('  ', ' &nbsp; ', $pre);

		switch (true)
		{
			case ($type == 'separator'):
				$pre = '[[:font-weight:normal;font-style:italic;color:grey;:]]' . $pre;
				break;
			case (!$published):
				$pre = '[[:font-style:italic;color:grey;:]]' . $pre;
				$name = $name . ' [' . JText::_('JUNPUBLISHED') . ']';
				break;
			case ($published == 2):
				$pre = '[[:font-style:italic;:]]' . $pre;
				$name = $name . ' [' . JText::_('JARCHIVED') . ']';
				break;
		}

		return $pre . $name;
	}

	public static function strReplaceOnce($search, $replace, $string)
	{
		$replace = str_replace(array('\\', '$'), array('\\\\', '\\$'), $replace);

		return preg_replace('#' . preg_quote($search, '#') . '#', $replace, $string, 1);
	}

	/**
	 * Gets the full uri and optionally adds/replaces the hash
	 */
	public static function getURI($hash = '')
	{
		$uri = JURI::getInstance();

		if (version_compare(JVERSION, '3.0', '<'))
		{
			$uri = $uri->get('_uri');

			$uri = ($hash != '')
				? preg_replace('#\#.*$#', '', $uri) . '#' . $hash
				: $uri;

			return $uri;
		}

		if ($hash != '')
		{
			$uri->setFragment($hash);
		}

		return $uri->toString();
	}

	/**
	 * gets attribute from a tag string
	 */
	public static function fixHtmlTagStructure(&$string, $remove_surrounding_p_tags = 1)
	{
		// Combine duplicate <p> tags
		nnText::combinePTags($string);

		// Move div nested inside <p> tags outside of it
		nnText::moveDivBlocksOutsidePBlocks($string);

		// Remove duplicate ending </p> tags
		nnText::removeDuplicateTags($string, '/p');

		if ($remove_surrounding_p_tags)
		{
			// Remove surrounding <p></p> blocks
			nnText::removeSurroundingPBlocks($string);
		}
	}

	/**
	 * Move div nested inside <p> tags outside of it
	 * input: <p><div>...</div></p>
	 *  output: </p><div>...</div><p>
	 */
	public static function moveDivBlocksOutsidePBlocks(&$string)
	{
		$p_start_tag = '<p(?: [^>]*)?>';
		$p_end_tag = '</p>';
		$optional_tags = '\s*(?:<br ?/?>|<\!-- [^>]*-->|&nbsp;|&\#160;)*\s*';

		$string = trim(preg_replace('#(' . $p_start_tag . ')(' . $optional_tags . '<div(?: [^>]*)?>.*?</div>' . $optional_tags . ')(' . $p_end_tag . ')#si', '\2', $string));
	}

	/**
	 * Combine duplicate <p> tags
	 * input: <p class="aaa" a="1"><!-- ... --><p class="bbb" b="2">
	 * output: <p class="aaa bbb" a="1" b="2"><!-- ... -->
	 */
	public static function combinePTags(&$string)
	{
		$p_start_tag = '<p(?: [^>]*)?>';
		$optional_tags = '\s*(?:<\!-- [^>]*-->|&nbsp;|&\#160;)*\s*';
		if (!preg_match_all('#(' . $p_start_tag . ')(' . $optional_tags . ')(' . $p_start_tag . ')#si', $string, $tags, PREG_SET_ORDER) > 0)
		{
			return;
		}

		foreach ($tags as $tag)
		{
			$string = str_replace($tag['0'], $tag['2'] . nnText::combineTags($tag['1'], $tag['3']), $string);
		}
	}

	/**
	 * Remove surrounding <p></p> blocks
	 * input: <p ...><!-- ... --></p>...<p ...><!-- ... --></p>
	 * output: <!-- ... -->...<!-- ... -->
	 */
	public static function removeSurroundingPBlocks(&$string)
	{
		nnText::removeStartingPTag($string);
		nnText::removeEndingPTag($string);
	}

	public static function removeStartingPTag(&$string)
	{
		$p_start_tag = '<p(?: [^>]*)?>';

		if (!preg_match('#^\s*' . $p_start_tag . '#si', $string))
		{
			return;
		}

		$test = preg_replace('#^(\s*)' . $p_start_tag . '#si', '\1', $string);
		if (stripos($test, '<p') > stripos($test, '</p'))
		{
			return;
		}

		$string = $test;
	}

	public static function removeEndingPTag(&$string)
	{
		$p_end_tag = '</p>';

		if (!preg_match('#' . $p_end_tag . '\s*$#si', $string))
		{
			return;
		}

		$test = preg_replace('#' . $p_end_tag . '(\s*)$#si', '\1', $string);
		if (strrpos($test, '<p') > strrpos($test, '</p'))
		{
			return;
		}

		$string = $test;
	}

	/**
	 * Combine tags
	 */
	public static function combineTags($tag1, $tag2)
	{
		// Return if tags are the same
		if ($tag1 == $tag2)
		{
			return $tag1;
		}

		if (!preg_match('#<([a-z][a-z0-9]*)#si', $tag1, $tag_type))
		{
			return $tag2;
		}

		$tag_type = $tag_type[1];

		if (!$attribs = nnText::combineAttributes($tag1, $tag2))
		{
			return '<' . $tag_type . '>';
		}

		return '<' . $tag_type . ' ' . $attribs . '>';
	}

	/**
	 * gets attribute from a tag string
	 */
	public static function getAttribute($attributes, $string)
	{
		// get attribute from string
		if (preg_match('#' . preg_quote($attributes, '#') . '="([^"]*)"#si', $string, $match))
		{
			return $match['1'];
		}

		return '';
	}

	/**
	 * gets attributes from a tag string
	 */
	public static function getAttributes($string)
	{
		if (preg_match_all('#([a-z0-9-_]+)="([^"]*)"#si', $string, $matches, PREG_SET_ORDER) < 1)
		{
			return array();
		}

		$attribs = array();

		foreach ($matches as $match)
		{
			$attribs[$match['1']] = $match['2'];
		}

		return $attribs;
	}

	/**
	 * combine attribute values in a tag string
	 */
	public static function combineAttributes($string1, $string2)
	{
		$attribs1 = is_array($string1) ? $string1 : nnText::getAttributes($string1);
		$attribs2 = is_array($string2) ? $string2 : nnText::getAttributes($string2);

		$dublicate_attribs = array_intersect_key($attribs1, $attribs2);

		// Fill $attribs with the unique ids
		$attribs = array_diff_key($attribs1, $attribs2) + array_diff_key($attribs2, $attribs1);

		// Add/combine the duplicate ids
		$single_value_attributes = array('id', 'href');
		foreach ($dublicate_attribs as $key => $val)
		{
			if (in_array($key, $single_value_attributes))
			{
				$attribs[$key] = $attribs2[$key];
				continue;
			}
			// Combine strings, but remove duplicates
			// "aaa bbb" + "aaa ccc" = "aaa bbb ccc"

			// use a ';' as a concatenated for javascript values (keys beginning with 'on')
			$glue = substr($key, 0, 2) == 'on' ? ';' : ' ';
			$attribs[$key] = implode($glue, array_merge(explode($glue, $attribs1[$key]), explode($glue, $attribs2[$key])));
		}

		foreach ($attribs as $key => &$val)
		{
			$val = $key . '="' . $val . '"';
		}

		return implode(' ', $attribs);
	}

	/**
	 * remove duplicate </p> tags
	 * input: </p><!-- ... --></p>
	 * output: </p><!-- ... -->
	 */
	public static function removeDuplicateTags(&$string, $tag_type = 'p')
	{
		$string = preg_replace('#(<' . $tag_type . '(?: [^>]*)?>\s*(<!--.*?-->\s*)?)<' . $tag_type . '(?: [^>]*)?>#si', '\1', $string);
	}

	/**
	 * Creates an alias from a string
	 * Based on stringURLUnicodeSlug method from the unicode slug plugin by infograf768
	 */
	public static function createAlias($string)
	{
		// Remove < > html entities
		$string = str_replace(array('&lt;', '&gt;'), '', $string);

		// Convert html entities
		$string = html_entity_decode($string, ENT_COMPAT, 'UTF-8');

		// remove html tags
		$string = preg_replace('#</?[a-z][^>]*>#usi', '', $string);
		// remove comments tags
		$string = preg_replace('#<\!--.*?-->#us', '', $string);

		// Replace double byte whitespaces by single byte (East Asian languages)
		$string = preg_replace('/\xE3\x80\x80/', ' ', $string);

		// Remove any '-' from the string as they will be used as concatenator.
		// Would be great to let the spaces in but only Firefox is friendly with this
		$string = str_replace('-', ' ', $string);

		// Replace forbidden characters by whitespaces
		$string = preg_replace('#[,:\#\$\*"@+=;!&\.%()\]\/\'\\\\|\[]#', "\x20", $string);

		// Delete all '?'
		$string = str_replace('?', '', $string);

		// Trim white spaces at beginning and end of alias and make lowercase
		$string = trim($string);

		// Remove any duplicate whitespace and replace whitespaces by hyphens
		$string = preg_replace('#\x20+#', '-', $string);

		// Remove leading and trailing hyphens
		$string = trim($string, '-');

		return JString::strtolower($string);
	}

	/**
	 * Creates an array of different syntaxes of titles to match against a url variable
	 */
	public static function createUrlMatches($titles = array())
	{
		$matches = array();
		foreach ($titles as $title)
		{
			$matches[] = $title;
			$matches[] = JString::strtolower($title);
		}

		$matches = array_unique($matches);

		foreach ($matches as $title)
		{
			$matches[] = htmlspecialchars(html_entity_decode($title, ENT_COMPAT, 'UTF-8'));
		}

		$matches = array_unique($matches);

		foreach ($matches as $title)
		{
			$matches[] = urlencode($title);
			$matches[] = utf8_decode($title);
			$matches[] = str_replace(' ', '', $title);
			$matches[] = trim(preg_replace('#[^a-z0-9]#i', '', $title));
			$matches[] = trim(preg_replace('#[^a-z]#i', '', $title));
		}

		$matches = array_unique($matches);

		foreach ($matches as $i => $title)
		{
			$matches[$i] = trim(str_replace('?', '', $title));
		}

		$matches = array_diff(array_unique($matches), array('', '-'));

		return $matches;
	}

	static function getBody($html)
	{
		if (strpos($html, '<body') === false || strpos($html, '</body>') === false)
		{
			return array('', $html, '');
		}

		$html_split = explode('<body', $html, 2);
		$pre = $html_split['0'];
		$body = '<body' . $html_split['1'];
		$body_split = explode('</body>', $body);
		$post = array_pop($body_split);
		$body = implode('</body>', $body_split) . '</body>';

		return array($pre, $body, $post);
	}

	static function createArray($string, $separator = ',')
	{
		return array_filter(explode($separator, trim($string)));
	}
}

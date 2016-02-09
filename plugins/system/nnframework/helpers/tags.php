<?php
/**
 * NoNumber Framework Helper File: Tags
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

class nnTags
{
	public static function getTagValues($string = '', $keys = array('title'), $separator = '|', $equal = '=', $limit = 0)
	{
		$temp_separator = '[[S]]';
		$temp_equal = '[[E]]';
		$tag_start = '[[T]]';
		$tag_end = '[[/T]]';

		// replace separators and equal signs with special markup
		$string = str_replace(array($separator, $equal), array($temp_separator, $temp_equal), $string);
		// replace protected separators and equal signs back to original
		$string = str_replace(array('\\' . $temp_separator, '\\' . $temp_equal), array($separator, $equal), $string);

		// protect all html tags
		if (preg_match_all('#</?[a-z][^>]*>#si', $string, $tags, PREG_SET_ORDER) > 0)
		{
			foreach ($tags as $tag)
			{
				$string = str_replace(
					$tag['0'],
					$tag_start . base64_encode(str_replace(array($temp_separator, $temp_equal), array($separator, $equal), $tag['0'])) . $tag_end,
					$string
				);
			}
		}

		// split string into array
		$vals = $limit
			? explode($temp_separator, $string, (int) $limit)
			: explode($temp_separator, $string);

		// initialize return vars
		$tag_values = new stdClass;
		$tag_values->params = array();

		// loop through splits
		foreach ($vals as $i => $keyval)
		{
			// spit part into key and val by equal sign
			$keyval = explode($temp_equal, $keyval, 2);
			if (isset($keyval['1']))
			{
				$keyval['1'] = str_replace(array($temp_separator, $temp_equal), array($separator, $equal), $keyval['1']);
			}

			// unprotect tags in key and val
			foreach ($keyval as $key => $val)
			{
				if (preg_match_all('#' . preg_quote($tag_start, '#') . '(.*?)' . preg_quote($tag_end, '#') . '#si', $val, $tags, PREG_SET_ORDER) > 0)
				{
					foreach ($tags as $tag)
					{
						$val = str_replace($tag['0'], base64_decode($tag['1']), $val);
					}
					$keyval[trim($key)] = $val;
				}
			}

			if (isset($keys[$i]))
			{
				$key = trim($keys[$i]);
				// if value is in the keys array add as defined in keys array
				// ignore equal sign
				$val = implode($equal, $keyval);
				if (substr($val, 0, strlen($key) + 1) == $key . '=')
				{
					$val = substr($val, strlen($key) + 1);
				}
				$tag_values->{$key} = $val;
				unset($keys[$i]);
			}
			else
			{
				// else add as defined in the string
				if (isset($keyval['1']))
				{
					$tag_values->{$keyval['0']} = $keyval['1'];
				}
				else
				{
					$tag_values->params[] = implode($equal, $keyval);
				}
			}
		}

		return $tag_values;
	}

	public static function setSurroundingTags($pre, $post, $tags = 0)
	{
		if ($tags == 0)
		{
			// tags that have a matching ending tag
			$tags = array(
				'div', 'p', 'span', 'pre', 'a',
				'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
				'strong', 'b', 'em', 'i', 'u', 'big', 'small', 'font',
				// html 5 stuff
				'header', 'nav', 'section', 'article', 'aside', 'footer',
				'figure', 'figcaption', 'details', 'summary', 'mark', 'time'
			);
		}
		$a = explode('<', $pre);
		$b = explode('</', $post);

		if (count($b) > 1 && count($a) > 1)
		{
			$a = array_reverse($a);
			$a_pre = array_pop($a);
			$b_pre = array_shift($b);
			$a_tags = $a;
			foreach ($a_tags as $i => $a_tag)
			{
				$a[$i] = '<' . trim($a_tag);
				$a_tags[$i] = preg_replace('#^([a-z0-9]+).*$#', '\1', trim($a_tag));
			}
			$b_tags = $b;
			foreach ($b_tags as $i => $b_tag)
			{
				$b[$i] = '</' . trim($b_tag);
				$b_tags[$i] = preg_replace('#^([a-z0-9]+).*$#', '\1', trim($b_tag));
			}
			foreach ($b_tags as $i => $b_tag)
			{
				if ($b_tag && in_array($b_tag, $tags))
				{
					foreach ($a_tags as $j => $a_tag)
					{
						if ($b_tag == $a_tag)
						{
							$a_tags[$i] = '';
							$b[$i] = trim(preg_replace('#^</' . $b_tag . '.*?>#', '', $b[$i]));
							$a[$j] = trim(preg_replace('#^<' . $a_tag . '.*?>#', '', $a[$j]));
							break;
						}
					}
				}
			}
			foreach ($a_tags as $i => $tag)
			{
				if ($tag && in_array($tag, $tags))
				{
					array_unshift($b, trim($a[$i]));
					$a[$i] = '';
				}
			}
			$a = array_reverse($a);
			list($pre, $post) = array(implode('', $a), implode('', $b));
		}

		return array(trim($pre), trim($post));
	}
}

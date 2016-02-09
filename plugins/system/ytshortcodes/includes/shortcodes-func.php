<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
if (file_exists(JPATH_SITE . '/components/com_k2/helpers/route.php'))
    require_once JPATH_SITE . '/components/com_k2/helpers/route.php';

$shortcode_tags = array();


function add_ytshortcode($tag, $func) {
	global $shortcode_tags;
	if(is_callable($func))
		$shortcode_tags[$tag] = $func;
}

function parse_shortcode($content) {
	global $shortcode_tags;
	//Remove auto added <p>
	$array = array(
		'&nbsp;['    => '[',
		']&nbsp;'    => ']',
		'<p>['    => '[',
		']</p>'   => ']',
		']<br />' => ']'
	);
	$content = strtr($content, $array);
	if(empty($shortcode_tags) || !is_array($shortcode_tags))
		return $content;

	$pattern = get_ytshortcode_regex();
	return preg_replace_callback('/' . $pattern . '/s', 'parse_shortcode_tag', $content);
}


function get_ytshortcode_regex() {
	global $shortcode_tags;
	$tagnames  = array_keys($shortcode_tags);
	$tagregexp = implode('|', array_map('preg_quote', $tagnames));
	// WARNING! Do not change this regex without changing parse_shortcode_tag() and strip_ytshortcodes()
	return '(.?)\[('.$tagregexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';
}


function parse_shortcode_tag($m) {
	global $shortcode_tags;
	// allow [[foo]] syntax for escaping a tag
	if($m[1] == '[' && $m[6] == ']') {
		return substr($m[0], 1, -1);
	}

	$tag = $m[2];
	$attr = ytshortcode_parse_atts($m[3]);
	if(isset($m[5])) {
		// enclosing tag - extra parameter
		return $m[1] . call_user_func($shortcode_tags[$tag], $attr, $m[5], $tag) . $m[6];
	}else {
		// self-closing tag
		return $m[1] . call_user_func($shortcode_tags[$tag], $attr, NULL,  $tag) . $m[6];
	}
	
}


function ytshortcode_parse_atts($text) {
	$atts    = array();
	$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
	$text    = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

	if(preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
		foreach($match as $m) {
			if(!empty($m[1]))
				$atts[strtolower($m[1])] = stripcslashes($m[2]);
			elseif(!empty($m[3]))
				$atts[strtolower($m[3])] = stripcslashes($m[4]);
			elseif(!empty($m[5]))
				$atts[strtolower($m[5])] = stripcslashes($m[6]);
			elseif(isset($m[7]) and strlen($m[7]))
				$atts[] = stripcslashes($m[7]);
			elseif(isset($m[8]))
				$atts[] = stripcslashes($m[8]);
		}
	}
	else {
		$atts = ltrim($text);
	}
	return $atts;
}


function ytshortcode_atts($pairs, $atts) {
	$atts =(array)$atts;
	$out  = array();
	
	foreach($pairs as $name => $default) {
		if(array_key_exists($name, $atts))
			$out[$name] = $atts[$name];
		else
			$out[$name] = $default;
	}
	return $out;
}


function strip_ytshortcodes($content) {
	global $shortcode_tags;
	
	if(empty($shortcode_tags) || !is_array($shortcode_tags))
		return $content;

	$pattern = get_ytshortcode_regex();
	return preg_replace('/' . $pattern . '/s', '$1$6', $content);
}
function yt_hexToRgb($hexStr, $returnAsString = false, $seperator = ','){
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
        $rgbArray = array();

        if (strlen($hexStr) == 6){
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3){
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false;
        }

        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
    }
function _yt_hexToRgb($hexStr, $returnAsString = false, $seperator = ',') {
	$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
	$rgbArray = array();
	$rgbArray[] = 'color';
	if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
		$colorVal = hexdec($hexStr);
		$rgbArray[] = 0xFF & ($colorVal >> 0x10);
		$rgbArray[] = 0xFF & ($colorVal >> 0x8);
		$rgbArray[] = 0xFF & $colorVal;
	} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
		$rgbArray[] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
		$rgbArray[] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
		$rgbArray[] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
	} else {
		return false; //Invalid hex color code
	}

	return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}
function yt_colorArgs($args) {
	if ($args[0] != 'list' || count($args[2]) < 2) {
		return array(array('color', 0, 0, 0));
	}
	list($color, $delta) = $args[2];
	if ($color[0] != 'color')
		$color = array('color', 0, 0, 0);

	$delta = floatval($delta[1]);

	return array($color, $delta);
}
function toHSL($color) {
	if ($color[0] == 'hsl') return $color;

	$r = $color[1] / 255;
	$g = $color[2] / 255;
	$b = $color[3] / 255;

	$min = min($r, $g, $b);
	$max = max($r, $g, $b);

	$L = ($min + $max) / 2;
	if ($min == $max) {
		$S = $H = 0;
	} else {
		if ($L < 0.5)
			$S = ($max - $min)/($max + $min);
		else
			$S = ($max - $min)/(2.0 - $max - $min);

		if ($r == $max) $H = ($g - $b)/($max - $min);
		elseif ($g == $max) $H = 2.0 + ($b - $r)/($max - $min);
		elseif ($b == $max) $H = 4.0 + ($r - $g)/($max - $min);

	}

	$out = array('hsl',
		($H < 0 ? $H + 6 : $H)*60,
		$S*100,
		$L*100,
	);

	if (count($color) > 4) $out[] = $color[4]; // copy alpha
	return $out;
}
function clamp($v, $max = 1, $min = 0) {
        return min($max, max($min, $v));
    }
function toRGB_helper($comp, $temp1, $temp2) {
        if ($comp < 0) $comp += 1.0;
        elseif ($comp > 1) $comp -= 1.0;

        if (6 * $comp < 1) return $temp1 + ($temp2 - $temp1) * 6 * $comp;
        if (2 * $comp < 1) return $temp2;
        if (3 * $comp < 2) return $temp1 + ($temp2 - $temp1)*((2/3) - $comp) * 6;

        return $temp1;
    }
function toRGB($color) {
        if ($color == 'color') return $color;

        $H = $color[1] / 360;
        $S = $color[2] / 100;
        $L = $color[3] / 100;

        if ($S == 0) {
            $r = $g = $b = $L;
        } else {
            $temp2 = $L < 0.5 ?
                $L*(1.0 + $S) :
                $L + $S - $L * $S;

            $temp1 = 2.0 * $L - $temp2;

            $r = toRGB_helper($H + 1/3, $temp1, $temp2);
            $g = toRGB_helper($H, $temp1, $temp2);
            $b = toRGB_helper($H - 1/3, $temp1, $temp2);
        }

        $out = array('color', round($r*255), round($g*255), round($b*255));
        if (count($color) > 4) $out[] = $color[4]; // copy alpha
        return $out;
    }
function lib_darken($args) {
	list($color, $delta) = yt_colorArgs($args);

	$hsl = toHSL($color);
	$hsl[3] = clamp($hsl[3] - $delta, 100);
	return toRGB($hsl);
}
function rgbaToHex($color) {
        if ($color[0] != 'color')
            throw new exception("color expected for rgbahex");

        return sprintf("#%02x%02x%02x",
            $color[1],$color[2], $color[3]);
    }
function darken($color, $pc = '5%'){
        $pc = str_replace('%', '', $pc);
        $args = array('list', ',', array(_yt_hexToRgb($color), array('%', $pc)));
        $rgb = array_slice(lib_darken($args), 1);

        return rgbaToHex(lib_darken($args));
    }
function yt_acssc($classes) {
    $classes = implode($classes, ' ');
    $abs_classes = trim(preg_replace('/\s\s+/', ' ', $classes));
    return $abs_classes;
}
function yt_image_media($image) {
    if (strpos($image, 'http://') === false && strpos($image, 'https://') === false) {
        return JUri::root() . $image;
    } else {
        return $image;
    }
}
function select($args) {
        $args = yt_parse_args($args, array(
            'id'       => '',
            'name'     => '',
            'class'    => '',
            'multiple' => '',
            'size'     => '',
            'disabled' => '',
            'selected' => '',
            'none'     => '',
            'options'  => array(),
            'style'    => '',
            'format'   => 'keyval', // keyval/idtext
            'noselect' => '' // return options without <select> tag
        ));
        $options = array();
        if (!is_array($args['options']))
            $args['options'] = array();
        if ($args['id'])
            $args['id'] = ' id="' . $args['id'] . '"';
        if ($args['name'])
            $args['name'] = ' name="' . $args['name'] . '"';
        if ($args['class'])
            $args['class'] = ' class="' . $args['class'] . '"';
        if ($args['style'])
            $args['style'] = ' style="' . esc_attr($args['style']) . '"';
        if ($args['multiple'])
            $args['multiple'] = ' multiple="multiple"';
        if ($args['disabled'])
            $args['disabled'] = ' disabled="disabled"';
        if ($args['size'])
            $args['size'] = ' size="' . $args['size'] . '"';
        if ($args['none'] && $args['format'] === 'keyval')
            $args['options'][0] = $args['none'];
        if ($args['none'] && $args['format'] === 'idtext')
            array_unshift($args['options'], array('id' => '0', 'text' => $args['none']));
        if ($args['format'] === 'keyval')
            foreach ($args['options'] as $id => $text) {
                $options[] = '<option value="' . (string) $id . '">' . (string) $text . '</option>';
            } elseif ($args['format'] === 'idtext')
            foreach ($args['options'] as $option) {
                if (isset($option['id']) && isset($option['text']))
                    $options[] = '<option value="' . (string) $option['id'] . '">' . (string) $option['text'] . '</option>';
            }
        $options = implode('', $options);
        $options = str_replace('value="' . $args['selected'] . '"', 'value="' . $args['selected'] . '" selected="selected"', $options);
        return ( $args['noselect'] ) ? $options : '<select' . $args['id'] . $args['name'] . $args['class'] . $args['multiple'] . $args['size'] . $args['disabled'] . $args['style'] . '>' . $options . '</select>';
    }

    function get_categories() {
        $cats = array();
        foreach ((array) get_terms('category', array('hide_empty' => false)) as $cat)
            $cats[$cat->slug] = $cat->name;
        return $cats;
    }

    function get_types() {
        $types = array();
        foreach ((array) get_post_types('', 'objects') as $cpt => $cpt_data)
            $types[$cpt] = $cpt_data->label;
        return $types;
    }

    function get_users() {
        $users = array();
        foreach ((array) get_users() as $user)
            $users[$user->ID] = $user->data->display_name;
        return $users;
    }

    function get_taxonomies() {
        $taxes = array();
        foreach ((array) get_taxonomies('', 'objects') as $tax)
            $taxes[$tax->name] = $tax->label;
        return $taxes;
    }

    function getOptions() {
        $options = array();
        $published = array(1);
        $extension = 'com_content';
        // Let's get the id for the current item, either category or content item.
        $jinput = JFactory::getApplication()->input;
        // Load the category options for a given extension.

        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
                ->select('a.id, a.title, a.level, a.published')
                ->from('#__categories AS a')
                ->join('LEFT', $db->quoteName('#__categories') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

        $query->where('(a.extension = ' . $db->quote($extension) . ' )');

        // Filter on the published state

        $query->where('a.published IN (' . implode(',', $published) . ')');
        $query->order('a.lft ASC');
        $db->setQuery($query);
        $row = $db->loadObject();
        // Get the options.

        try {
            $options = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage);
        }

        // Merge any additional options in the XML definition.
        return $options;
    }

    function get_category($type, $option) {
        return getOptions();
    }

    function get_terms($tax = 'category', $key = 'id') {
        $terms = array();
        if ($key === 'id') {
            foreach (get_category($tax, array('hide_empty' => false)) as $term) {
                $terms[$term->id] = $term->title;
            }
        } elseif ($key === 'slug') {
            foreach ((array) get_terms($tax, array('hide_empty' => false)) as $term) {
                $terms[$term->slug] = $term->name;
            }
        }
        return $terms;
    }

    /* ==========for k2============ */

    function get_k2_Options() {
        $options = array();
        $published = array(1);

        // Let's get the id for the current item, either category or content item.
        $jinput = JFactory::getApplication()->input;
        // Load the category options for a given extension.

        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
                ->select('a.id, a.name, a.parent, a.published')
                ->from('#__k2_categories AS a');

        // Filter on the published state

        $query->where('a.published IN (' . implode(',', $published) . ')');

        $query->order('a.name ASC');
        $db->setQuery($query);
        $row = $db->loadObject();
        // Get the options.

        try {
            $options = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage);
        }

        // Merge any additional options in the XML definition.
        return $options;
    }

    function get_k2_category($type, $option) {
        return get_k2_Options();
    }

    function get_k2_terms($tax = 'k2-category', $key = 'id') {
        $terms = array();
        if ($key === 'id') {
            foreach (get_k2_category($tax, array('hide_empty' => false)) as $term) {
                $terms[$term->id] = $term->name;
            }
        } elseif ($key === 'slug') {
            foreach ((array) get_k2_terms($tax, array('hide_empty' => false)) as $term) {
                $terms[$term->slug] = $term->name;
            }
        }
        return $terms;
    }
    function get_k2_Articles($categoryId, $orderbyType, $orderby) {

        $published = array(1);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // Select the required fields from the table.
        $query->select('a.*');
        $query->from('#__k2_items AS a');
        // Join over the language
        $query->select('l.title AS language_title')
                ->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

        // Join over the users for the checked out user.
        $query->select('uc.name AS editor')
                ->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the asset groups.
        $query->select('ag.title AS access_level')
                ->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

        // Join over the categories.
        $query->select('c.alias AS categoryalias,c.name AS category_title, c.alias AS category_alias')
                ->join('LEFT', '#__k2_categories AS c ON c.id = a.catid');

        // Join over the users for the author.
        $query->select('ua.name AS author_name')
                ->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

        if (JLanguageAssociations::isEnabled()) {
            $query->select('COUNT(asso2.id)>1 as association')
                    ->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_content.item'))
                    ->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
                    ->group('a.id');
        }
        // Filter on the published state

        $query->where('a.published IN (' . implode(',', $published) . ')');

        if (is_array($categoryId)) {
            JArrayHelper::toInteger($categoryId);
            $categoryId = implode(',', $categoryId);
            $query->where('a.catid IN (' . $categoryId . ')');
        }
        if ($orderbyType == '') {

        } else {
            $query->order($orderby);
        }
        $db->setQuery($query);
        $row = $db->loadObjectList();
        return $row;
    }

    /* ==========for k2============ */
	
    function yt_getArticles($categoryId, $orderbyType, $orderby) {
        $published = '';
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select('a.*');
        $query->from('#__content AS a');

        // Join over the language
        $query->select('l.title AS language_title')
                ->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

        // Join over the users for the checked out user.
        $query->select('uc.name AS editor')
                ->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the asset groups.
        $query->select('ag.title AS access_level')
                ->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

        // Join over the categories.
        $query->select('c.title AS category_title, c.alias AS category_alias')
                ->join('LEFT', '#__categories AS c ON c.id = a.catid');

        // Join over the users for the author.
        $query->select('ua.name AS author_name')
                ->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
		

        if (JLanguageAssociations::isEnabled()) {
            $query->select('COUNT(asso2.id)>1 as association')
                    ->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_content.item'))
                    ->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
                    ->group('a.id');
        }

        // Filter by published state
        if (is_numeric($published)) {
            $query->where('a.state = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(a.state = 0 OR a.state = 1)');
        }

        // Filter by a single or group of categories.
        $baselevel = 1;

        if (is_numeric($categoryId)) {
            $cat_tbl = JTable::getInstance('Category', 'JTable');
            $cat_tbl->load($categoryId);
            $rgt = $cat_tbl->rgt;
            $lft = $cat_tbl->lft;
            $baselevel = (int) $cat_tbl->level;
            $query->where('c.lft >= ' . (int) $lft)
                    ->where('c.rgt <= ' . (int) $rgt);
        } elseif (is_array($categoryId)) {
            JArrayHelper::toInteger($categoryId);
            $categoryId = implode(',', $categoryId);
            $query->where('a.catid IN (' . $categoryId . ')');
        }
        if ($orderbyType == '') {

        } else {
            $query->order($orderby);
        }
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function get_slides($args) {
        $args = yt_parse_args($args, array(
            'source'   => 'none',
            'limit'    => 20,
            'gallery'  => null,
            'type'     => '',
            'order'    => '',
            'order_by' => 'desc',
            'link'     => 'attachment'
        ));
        // Prepare empty array for slides
        $slides = array();
        // Loop through source types
        foreach (array('media', 'posts', 'category', 'k2-category') as $type)
            if (strpos(trim($args['source']), $type . ':') === 0) {
                $args['source'] = array(
                    'type' => $type,
                    'val' => (string) trim(str_replace(array($type . ':', ' '), '', $args['source']), ',')
                );
                break;
            }
        // Source is not parsed correctly, return empty array
        if (!is_array($args['source']))
            return $slides;
        // Source: media
        if ($args['source']['type'] === 'media') {
            $images = (array) explode(',', $args['source']['val']);
            foreach ($images as $post) {

                $slide = array(
                    'image' => $post,
                    'link' => $post,
                    'url' => $post,
                    'title' => '',
                    'text' => $post
                );
                if ($args['link'] === 'image') {
                    $slide['link'] = $slide['image'];
                }
                $slides[] = $slide;
            }
            return $slides;
        }
        //end media

        // Source: category
        elseif ($args['source']['type'] === 'category') {
            $catid = (array) explode(',', $args['source']['val']);

            $order = $args['order'];    //  title/created/ordering/hits
            $order_by = $args['order_by'];     // asc/desc
            if ($order == '') {
                $orderby = '';
            } else if ($order == 'title') {
                $orderby = 'a.title ' . $order_by . ' ';
            } else if ($order == 'created') {
                $orderby = 'a.created ' . $order_by . ' ';
            } else if ($order == 'modified') {
                $orderby = 'a.modified ' . $order_by . ' ';
            } else if ($order == 'publish_up') {
                $orderby = 'a.publish_up ' . $order_by . ' ';
            } else if ($order == 'ordering') {
                $orderby = 'a.ordering ' . $order_by . ' ';
            } else if ($order == 'hits') {
                $orderby = 'a.hits ' . $order_by . ' ';
            }

            $results = yt_getArticles($catid, $order, $orderby);
        }
        // Source: k2-category
        elseif ($args['source']['type'] === 'k2-category') {
            $catid = (array) explode(',', $args['source']['val']);

            $order = $args['order'];  //    title/created/ordering/hits
            $order_by = $args['order_by']; // asc/desc
            if ($order == '') {
                $orderby = '';
            } else if ($order == 'title') {
                $orderby = 'a.title ' . $order_by . ' ';
            } else if ($order == 'created') {
                $orderby = 'a.created ' . $order_by . ' ';
            } else if ($order == 'modified') {
                $orderby = 'a.modified ' . $order_by . ' ';
            } else if ($order == 'publish_up') {
                $orderby = 'a.publish_up ' . $order_by . ' ';
            } else if ($order == 'ordering') {
                $orderby = 'a.ordering ' . $order_by . ' ';
            } else if ($order == 'hits') {
                $orderby = 'a.hits ' . $order_by . ' ';
            }
            $results = get_k2_Articles($catid, $order, $orderby);
        }

        // Loop through posts
        if (is_array($results))
            foreach ($results as $post) {
                // Get post thumbnail ID
                if ($args['source']['type'] === 'k2-category') {
                    $k2_img = JPATH_SITE . '/media/k2/items/cache/' . md5("Image" . $post->id) . '_XL.jpg';
                    if (file_exists($k2_img)) {
                        $thumb = 'media/k2/items/cache/' . md5("Image" . $post->id) . '_XL.jpg';
                    } else {
                        $thumb = null;
                    }
                    $link = K2HelperRoute::getItemRoute($post->id . ':' . urlencode($post->alias), $post->catid . ':' . urlencode($post->categoryalias));
                } elseif( $args['source']['type'] === 'category') {
                    $thumb = yt_get_post_image($post);
                    $slug = $post->id . ':' . $post->alias;
                    $catslug = $post->catid . ':' . $post->category_alias;
                    $link = JRoute::_(ContentHelperRoute::getArticleRoute($slug, $catslug));
                } elseif( $args['source']['type'] === 'media')  {
                    $thumb = $post->id;
                    $link = $slide['image'];
                } else {
                    $thumb = null;
                }
				
                // post array
                $slide = array(
                    'alias'     => ($post->alias),
                    'category'  => ($post->category_title),
					'category_alias' =>($post->category_alias),
                    'title'     => ($post->title),
                    'introtext' => ($post->introtext),
                    'fulltext'  => ($post->fulltext),
                    'image'     => $thumb,
                    'link'      => $link,
                    'created'   => ($post->created),
                    'hits'      => ($post->hits),
					'author_name'=> ($post->author_name),
					'created_by_alias' =>($post->hits) ,
					
                );
                $slides[] = $slide;
            }
        // Return slides

        return $slides;
    }
function yt_get_post_image($post, $internal = false) {
    if (isset($post->images)) {
        $images = $post->images;
        if($images) {
            $images = json_decode($images);
            if($images->image_fulltext){
                return $images->image_fulltext;
            }
            elseif($images->image_intro) {
              return $images->image_intro;
            };
        }
    } elseif($internal) {
        return getFirstImageFromHTML($post->introtext);
    } else {
        return false;
    }
}
function yt_parse_args( $args, $defaults = '' ) {
if ( is_object( $args ) )
    $r = get_object_vars( $args );
elseif ( is_array( $args ) )
    $r =& $args;

if ( is_array( $defaults ) )
    return array_merge( $defaults, $r );
return $r;
}
function yt_alert_box($content, $alert_type = 'info', $close_button = false) {
    $close = ($close_button) ? '<button type="button" class="close" data-dismiss="alert">&times;</button>' : '';
    $dismissible = ($close_button) ? 'alert-dismissible' : '';
    return '<div class="alert alert-' . $alert_type . ' ' . $dismissible . ' " role="alert">' . $close . $content . '</div>';
}
// Character limit
function yt_char_limit($str, $limit = 150, $end_char = '...') {
    if (trim($str) == '')
        return $str;

    // always strip tags for text
    $str = strip_tags(trim($str));

    $find = array("/\r|\n/u", "/\t/u", "/\s\s+/u");
    $replace = array(" ", " ", " ");
    $str = preg_replace($find, $replace, $str);
	
    if (strlen($str) > $limit)
    {
        $str = substr($str, 0, $limit);       
        return rtrim($str).'...';  
    }
    else
    {
        return $str;
    }

}
function yt_image_resize($url, $width = NULL, $height = NULL, $crop = true, $quality=95) {

    //if gd library doesn't exists - output normal image without resizing.
    if (function_exists("gd_info") == false) {
        $image_array = array(
            'url'    => $url,
            'width'  => $width,
            'height' => $height,
            'type'   => ''
        );
        return $image_array;
    }

    $thumb_folder = 'cache/shortcodes/';
    if (!is_dir(JPATH_SITE .'/'. $thumb_folder)) {
        mkdir(JPATH_SITE .'/'. $thumb_folder, 0777);
    }

    $fileExtension = strrchr($url, ".");

    $thumb_width = $width;
    $thumb_height = $height;


    if ($url!=null) {
        $url = JPATH_SITE .'/'.$url;
    } else {
        $image_array = array(
            'url'    => $url,
            'width'  => $width,
            'height' => $height,
            'type'   => ''
        );
        return $image_array;
    }

    $imageData = @getimagesize($url);
    $owidth    = $imageData[0];
    $oheight   = $imageData[1];

    if ( $imageData['mime'] == 'image/jpeg' || $imageData['mime'] == 'image/pjpeg' || $imageData['mime'] == 'image/jpg') {
        $image = @imagecreatefromjpeg($url);
    } elseif ($imageData['mime'] == 'image/gif') {
        $image = @imagecreatefromgif($url);
    } else {
        $image = @imagecreatefrompng($url);
    }

    // check if the proper image resource was created
    if (!$image) {
        $image_array = array(
            'url'    => $url,
            'width'  => $thumb_width,
            'height' => $thumb_height,
            'type'   => $fileExtension
        );
        return $image_array;
    }

    $original_aspect = $owidth / $oheight;
    $thumb_aspect = $thumb_width / $thumb_height;

    if ($crop) {
        $thumb_path = basename($url, $fileExtension) . '-' . $width . 'x' . $height .'-'.md5($url) . $fileExtension; // $file is set to "index";
        $thumb_path = JPATH_SITE . '/' . $thumb_folder . $thumb_path;
        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $thumb_height;
            $new_width = $owidth / ($oheight / $thumb_height);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $thumb_width;
            $new_height = $oheight / ($owidth / $thumb_width);
        }
        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
        $color = imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 255, 255, 255, 127));
        imagefill($thumb, 0, 0, $color);
        imagesavealpha($thumb, true);
        // Resize and crop
        imagecopyresampled($thumb, $image, 0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                0, 0, $new_width, $new_height, $owidth, $oheight);
    } else {
        $new_width = $thumb_width;
        $new_height = (int) ( 1 / $original_aspect * $new_width);
        $thumb_path = basename($url, $fileExtension) . '-' . $new_width . 'x' . $new_height . $fileExtension; // $file is set to "index";
        $thumb_path = JPATH_SITE . '/' . $thumb_folder . $thumb_path;
        $thumb = imagecreatetruecolor($new_width, $new_height);
        $color = imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 255, 255, 255, 127));
        imagefill($thumb, 0, 0, $color);
        imagesavealpha($thumb, true);
        // Resize and crop
        imagecopyresampled($thumb, $image, 0, // Center the image horizontally
                0, // Center the image vertically
                0, 0, $new_width, $new_height, $owidth, $oheight);
    }
    if ($imageData['mime'] == 'image/jpeg' || $imageData['mime'] == 'image/pjpeg' || $imageData['mime'] == 'image/jpg') {
        imagejpeg($thumb, $thumb_path, $quality);
    } elseif ($imageData['mime'] == 'image/gif') {
        imagegif($thumb, $thumb_path, $quality);
    } else {
        imagepng($thumb, $thumb_path, 9);
    }
    $thumb_url = $thumb_folder . basename($thumb_path, $fileExtension) . $fileExtension; // $file is set to "index";

    $image_array = array(
        'url' => $thumb_url,
        'width' => $thumb_width,
        'height' => $thumb_height,
        'type' => $fileExtension
    );
    return $image_array;
}
function yt_lib_lighten($args) {
        list($color, $delta) = yt_colorArgs($args);

        $hsl    = toHSL($color);
        $hsl[3] = clamp($hsl[3] + $delta, 100);
        return toRGB($hsl);
    }
function yt_lighten($color, $pc = '5%'){
        $pc   = str_replace('%', '', $pc);
        $args = array('list', ',', array(_yt_hexToRgb($color), array('%', $pc)));
        $rgb  = array_slice(yt_lib_lighten($args), 1);

        return rgbaToHex(yt_lib_lighten($args));
    }
function yt_get_plugin_color( $color, $opacity = null ) {

    if ( in_array( $color, array( "warning", "error", "success", "info", "inverse", "muted", "primary", "boxed" ) ) ) {
        if ( $color == "primary" ) {
            $color = "main"; // main color is primary color
        } else if ( $color == "muted" || empty( $color ) ) {
            $color = "boxed"; // boxed color is muted color
        } else if ( $color == "danger" ) {
            $color = "error";
        }

        $color = $intense_visions_options['intense_' . $color . '_color'];
    }

    if ( isset( $opacity ) ) {
      $color = yt_get_rgb_color( $color, $opacity );
    }

    return $color;
}
function yt_get_rgb_color( $hexcolor, $opacity = null ) {
    $returnRGB = '';
    $hex = str_replace( "#", "", $hexcolor );
    $a = 0;

    if ( isset( $opacity ) && $opacity > 1 ) {
        $a = $opacity / 100;
    }

    if ( strlen( $hex ) == 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }

    if ( isset( $opacity ) ) {
        $returnRGB = "rgba(" . $r . "," . $g . "," . $b . "," . $a . ")";
    } else {
        $returnRGB = "rgb(" . $r . "," . $g . "," . $b . ")";
    }

    return $returnRGB;
}
function yt_coalesce() {
  $args = func_get_args();
  foreach ( $args as $arg ) {
    if ( !empty( $arg ) ) {
      return $arg;
    }
  }
  return $args[0];
}

function yt_coalesce_isset() {
  $args = func_get_args();
  foreach ( $args as $arg ) {
    if ( isset( $arg ) ) {
      return $arg;
    }
  }
  return $args[0];
}
function yt_all_images($post) {

    $images = array();
    preg_match_all('/(img|src)\=(\"|\')[^\"\'\>]+/i', $post, $media);
    unset($post);
    $post=preg_replace('/(img|src)(\"|\'|\=\"|\=\')(.*)/i',"$3",$media[0]);
    foreach($post as $url)
    {
        $info = pathinfo($url);
        if (isset($info['extension']))
        {
            if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') || ($info['extension'] == 'gif') || ($info['extension'] == 'png'))
            array_push($images, $url);
        } else {
            return false;
        }
    }

    return $images;
}


<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die('Restricted access');

class Plugin_googleMaps_base {

	function init_google_maps($row, $pluginParams, $is_mod) {

		$param_list = array('api_key', 'width', 'height', 'zoom');
		foreach($param_list as $var) {
			$this->$var = $pluginParams->$var;
		}

		$regex = "/\[".$this->tag."\s+(.*?)\]/is";
		$contents = $row->text;
		if (preg_match_all( $regex, $contents, $matches, PREG_SET_ORDER )) {
			$count = count( $matches[0] );
			if ($count==0) return true;

			static $map_id = 0;
			$is_mod2 = '_plugin';
			$GET_var = "mod_{$this->tag}_id";
			if ($is_mod) {
					if (!isset($_GET[$GET_var]))
					$_GET[$GET_var] = 131;
				$is_mod2 = '_mod';
			}

			$js_var = '';
			$js = '';
			$js2 = '';
			$this->lang = '';
			$this->options_js = '';
			foreach($matches as $matches2) {
				if ($is_mod) {
					$map_id = $_GET[$GET_var]++;
				}
				#$var = $this->mod.'2'.$map_id;
				#$js_var .= "var {$var};";
				$js .= $this->process($row, $matches2, $map_id);

				if ($this->z_options!='') {
					$this->options_js .= "if (id==$map_id) map.setOptions({".$this->z_options."});\n";
				}

				#$js2 .= "$var.checkResize();\n";
				if (!$is_mod) ++$map_id;
			}

			$init_script = "<script type=\"text/javascript\">
<!--
$js_var
function load_gmap() {
	{$js}
}
onload_{$this->mod}(load_gmap());
//window.onunload = GUnload;
-->
</script>
";
			$row->text = preg_replace( $regex, '', $row->text );
			$codes = $this->js_lib();
			if(defined('_VALID_MOS')) {
				$codes .= "<style type=\"text/css\">\n<!--".$this->setup_css()."-->\n</style>";
			} else {
				JFactory::getDocument()->addStyleDeclaration($this->setup_css());
			}
			$codes .= $this->setup_gmap();
			$row->text = $codes.$row->text;
			$row->text .= $init_script; 
		}

		return true;
	}

	function process(&$row, $matches, $map_id) {
		$this->process_controls($row, $matches, $map_id);
		$this->process_params($row, $matches, $map_id);
		$js = $this->output_map($row, $matches, $map_id);
		return $js;
	}

	function process_controls(&$row, $matches, $map_id) {
		$this->lat = 0;
		$this->long = 0;
		$description = '';
		$this->startzoom = $this->zoom;
		$this->description = '';
		#$this->width = $this->width;
		#$this->height = $this->height;
		$this->align = '';
		$this->control = '';
		$this->maptype = '';
		$this->kml = '';
		$this->marker = 1;
		$this->addscale = 0;
		$this->addoverview = 1;
		$this->streetview = 1;
		$this->addr = '';
		$this->add_p = 0;
		$this->w3c = 1;
		$this->z_options = '';
		$type='';
		$this->type = '';
		if (preg_match('/lat=([\+\-]?[0-9\.]+)/', $matches[1], $matches2)) $this->lat = $matches2[1];
		if (preg_match('/long=([\+\-]?[0-9\.]+)/', $matches[1], $matches2)) $this->long = $matches2[1];
		if (preg_match('/zoom=(\d+)/', $matches[1], $matches2)) $this->startzoom = $matches2[1];
		if (preg_match('/width=(\d+%?)/', $matches[1], $matches2)) $this->width = $matches2[1];
		if (preg_match('/height=(\d+)/', $matches[1], $matches2)) $this->height = $matches2[1];
		// dungnv
		if (preg_match('/align="([^"]+)"/', $matches[1], $matches2));
			if (isset($matches2[1])) $this->align = ' pull-'.$matches2[1];
			
		
		# added on 090510
		if (preg_match('/options=([^\s]+)/', $matches[1], $matches2)) $this->z_options = $matches2[1];
		if (preg_match('/control=([^\s]+)/', $matches[1], $matches2)) $this->control = $matches2[1];
		if (preg_match('/maptype=([^\s]+)/', $matches[1], $matches2)) $this->maptype = $matches2[1];
		if (preg_match('/lang=([^\s]+)/', $matches[1], $matches2)) $this->lang = $matches2[1];
		if (preg_match('/marker=([^\s]+)/', $matches[1], $matches2)) $this->marker = $matches2[1];
		if (preg_match('/addoverview=(\d+)/', $matches[1], $matches2)) $this->addoverview = $matches2[1];
		if (preg_match('/addscale=(\d+)/', $matches[1], $matches2)) $this->addscale = $matches2[1];
		if (preg_match('/addgoogle=(\d+)/', $matches[1], $matches2)) $this->addgoogle = $matches2[1];

		if (preg_match('/w3c=(\d+%?)/', $matches[1], $matches2)) {
			$this->w3c = $matches2[1];
		} else if (preg_match('/add_p=(\d+%?)/', $matches[1], $matches2)) {
			$this->add_p = $matches2[1];
			$this->w3c = $this->add_p;
		}
		if (preg_match('/streetview=(\d+)/', $matches[1], $matches2)) $this->streetview = $matches2[1];

		if (preg_match('/options="([^"]+)"/', $this->fix_str2($matches[1]), $matches2)) {
			$this->z_options = $this->fix_str2($matches2[1]);
			$this->z_options = $this->fix_str2($this->z_options);
		}

		$this->maptype = strtoupper($this->maptype);
		if ($this->maptype=='G_SATELLITE_MAP') $this->maptype = 'SATELLITE';
		if ($this->maptype=='G_HYBRID_MAP') $this->maptype = 'HYBRID';
		if ($this->maptype=='G_NORMAL_MAP') $this->maptype = 'ROADMAP';

		# added on 090509
		if (preg_match('/kml=([^\s]+)/', $matches[1], $matches2)) $this->kml = $matches2[1];

		if (preg_match('/description="([^"]+)"/', $this->fix_str2($matches[1]), $matches2)) $description = $this->fix_str2($matches2[1]);
		if (preg_match('/type="([^"]+)"/', $this->fix_str2($matches[1]), $matches2)) $type = $this->fix_str2($matches2[1]);
		if (preg_match('/label="([^"]+)"/', $this->fix_str2($matches[1]), $matches2)) $description = $this->fix_str2($matches2[1]);
		$description = $this->fix_str2($description);
		$description = str_replace('~', '<br />', $description);
		#$description = str_replace('~', '&lt;br /&gt;', $description);
		$description = str_replace("'", '\\'."'", $description);
		$description = str_replace("\r\n", "\n", $description);
		$description = str_replace("\n", '<br />', $description);
		$this->description = $description;
		$this->type = $type;
		# added on 090528
		if (preg_match('/addr="([^"]+)"/', $this->fix_str2($matches[1]), $matches2)) $this->addr = $this->fix_str2($matches2[1]);
		$this->addr = str_replace("'", "\\"."'", $this->addr);

		if ($this->startzoom<1 || $this->startzoom>18) $this->startzoom = 15;
		if ($this->height<10 || $this->height>4096) $this->height = 480;

	}

	function process_params(&$row, $matches, $map_id) {
	}

	function fix_str2($str) {
		$str = str_replace('&#39;', "'", $str);
		$str = str_replace('&quot;', '"', $str);
		$str = str_replace('&lt;', '<', $str);
		$str = str_replace('&gt;', '>', $str);
		$str = str_replace('&amp;', '&', $str);
		$str = str_replace('&nbsp;', ' ', $str);
		return $str;
	}

	function fix_str3($str) {
		$str = str_replace('~', '@', $str);
		$str = str_replace('<br>', " ", $str);
		$str = str_replace('<br />', " ", $str);
		$str = str_replace('<p>', " ", $str);
		$str = str_replace('</p>', " ", $str);
		$str = str_replace('&nbsp;', ' ', $str);
		$str = str_replace("\n", " ", $str);
		$str = str_replace("\r", "", $str);
		return $str;
	}

	function js_lib() {
		$js = "\n<script type=\"text/javascript\">
<!--
function onload_{$this->mod}(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {window.onload = func;} else {
    window.onload = function() {if (oldonload){oldonload();}func();}
  }
}

function display_{$this->mod}_gmap(id, centerLatitude, centerLongitude, startZoom, description, kml, control, maptype, show_marker, addoverview, addscale, addgoogle, streetview, type) {
    var latlng = new google.maps.LatLng(centerLatitude, centerLongitude);
    if (startZoom==0 || startZoom=='') startZoom=10;
    var mapdiv = document.getElementById(\"{$this->mod}_gmap\"+id);
	switch(type)
	{
		case 'style2':	
			var myOptions = {
			  zoom: startZoom,
			  center: latlng,
				styles:[{'featureType':'landscape','stylers':[{'saturation':-100},{'lightness':65},{'visibility':'on'}]},{'featureType':'poi','stylers':[{'saturation':-100},{'lightness':51},{'visibility':'simplified'}]},{'featureType':'road.highway','stylers':[{'saturation':-100},{'visibility':'simplified'}]},{'featureType':'road.arterial','stylers':[{'saturation':-100},{'lightness':30},{'visibility':'on'}]},{'featureType':'road.local','stylers':[{'saturation':-100},{'lightness':40},{'visibility':'on'}]},{'featureType':'transit','stylers':[{'saturation':-100},{'visibility':'simplified'}]},{'featureType':'administrative.province','stylers':[{'visibility':'off'}]},{'featureType':'water','elementType':'labels','stylers':[{'visibility':'on'},{'lightness':-25},{'saturation':-100}]},{'featureType':'water','elementType':'geometry','stylers':[{'hue':'#ffff00'},{'lightness':-25},{'saturation':-97}]}]
			};
			break;
		case 'style3':
			var myOptions = {
			  zoom: startZoom,
			  center: latlng,
				styles:[{'stylers':[{'hue':'#16a085'},{'saturation':0}]},{'featureType':'road','elementType':'geometry','stylers':[{'lightness':100},{'visibility':'simplified'}]},{'featureType':'road','elementType':'labels','stylers':[{'visibility':'off'}]}]
			};
			break;
		case 'style4':
			var myOptions = {
			  zoom: startZoom,
			  center: latlng,
				styles:[{'featureType':'water','stylers':[{'visibility':'on'},{'color':'#333333'}]},{'featureType':'landscape.natural','elementType':'geometry.fill','stylers':[{'visibility':'on'},{'color':'#666666'}]},{'featureType':'landscape.man_made','stylers':[{'visibility':'off'}]},{'featureType':'transit','stylers':[{'visibility':'off'}]},{'featureType':'poi','elementType':'geometry','stylers':[{'color':'#df2f23'},{'visibility':'off'}]},{'featureType':'road.highway.controlled_access','elementType':'geometry.fill','stylers':[{'visibility':'on'},{'color':'#cccccc'}]},{'featureType':'road.highway.controlled_access','elementType':'geometry.stroke','stylers':[{'color':'#999999'}]},{'featureType':'road.local','stylers':[{'visibility':'off'}]},{'featureType':'road.arterial','elementType':'geometry.fill','stylers':[{'color':'#aaaaaa'}]},{'featureType':'road.arterial','elementType':'geometry.stroke','stylers':[{'visibility':'off'}]},{'featureType':'road.highway','elementType':'geometry.fill','stylers':[{'color':'#808080'}]},{'featureType':'administrative','elementType':'geometry.stroke','stylers':[{'color':'#aaaaaa'}]},{'featureType':'administrative','elementType':'labels.text'},{'featureType':'road.highway','elementType':'geometry.stroke','stylers':[{'color':'#c6eeee'}]},{}]
			};
			break;
		case 'style5':
			var myOptions = {
			  zoom: startZoom,
			  center: latlng,
				styles:[{'featureType':'landscape','stylers':[{'visibility':'simplified'},{'color':'#2b3f57'},{'weight':0.1}]},{'featureType':'administrative','stylers':[{'visibility':'on'},{'hue':'#ff0000'},{'weight':0.4},{'color':'#ffffff'}]},{'featureType':'road.highway','elementType':'labels.text','stylers':[{'weight':1.3},{'color':'#FFFFFF'}]},{'featureType':'road.highway','elementType':'geometry','stylers':[{'color':'#f55f77'},{'weight':3}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'color':'#f55f77'},{'weight':1.1}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#f55f77'},{'weight':0.4}]},{},{'featureType':'road.highway','elementType':'labels','stylers':[{'weight':0.8},{'color':'#ffffff'},{'visibility':'on'}]},{'featureType':'road.local','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'road.arterial','elementType':'labels','stylers':[{'color':'#ffffff'},{'weight':0.7}]},{'featureType':'poi','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'poi','stylers':[{'color':'#6c5b7b'}]},{'featureType':'water','stylers':[{'color':'#f3b191'}]},{'featureType':'transit.line','stylers':[{'visibility':'on'}]}]
			};
			break;
		case 'style6':
			var myOptions = {
			  zoom: startZoom,
			  center: latlng,
				styles:[{'featureType':'water','stylers':[{'color':'#021019'}]},{'featureType':'landscape','stylers':[{'color':'#08304b'}]},{'featureType':'poi','elementType':'geometry','stylers':[{'color':'#0c4152'},{'lightness':5}]},{'featureType':'road.highway','elementType':'geometry.fill','stylers':[{'color':'#000000'}]},{'featureType':'road.highway','elementType':'geometry.stroke','stylers':[{'color':'#0b434f'},{'lightness':25}]},{'featureType':'road.arterial','elementType':'geometry.fill','stylers':[{'color':'#000000'}]},{'featureType':'road.arterial','elementType':'geometry.stroke','stylers':[{'color':'#0b3d51'},{'lightness':16}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#000000'}]},{'elementType':'labels.text.fill','stylers':[{'color':'#ffffff'}]},{'elementType':'labels.text.stroke','stylers':[{'color':'#000000'},{'lightness':13}]},{'featureType':'transit','stylers':[{'color':'#146474'}]},{'featureType':'administrative','elementType':'geometry.fill','stylers':[{'color':'#000000'}]},{'featureType':'administrative','elementType':'geometry.stroke','stylers':[{'color':'#144b53'},{'lightness':14},{'weight':1.4}]}]
			};
			break;
		case 'style7':
			var myOptions = {
			  zoom: startZoom,
			  center: latlng,
				styles:[{'stylers':[{'hue':'#ff1a00'},{'invert_lightness':true},{'saturation':-100},{'lightness':33},{'gamma':0.5}]},{'featureType':'water','elementType':'geometry','stylers':[{'color':'#2D333C'}]}]
			};
			break;
		case 'style8':
			var myOptions = {
			  zoom: startZoom,
			  center: latlng,
				styles:[{'featureType':'landscape.natural','elementType':'geometry.fill','stylers':[{'visibility':'on'},{'color':'336d75'}]},{'featureType':'poi','elementType':'geometry.fill','stylers':[{'visibility':'on'},{'hue':'#1900ff'},{'color':'#d064a4'}]},{'featureType':'landscape.man_made','elementType':'geometry.fill'},{'featureType':'road','elementType':'geometry','stylers':[{'lightness':100},{'visibility':'simplified'}]},{'featureType':'road','elementType':'labels','stylers':[{'visibility':'off'}]},{'featureType':'water','stylers':[{'color':'#6bb1e1'}]},{'featureType':'transit.line','elementType':'geometry','stylers':[{'visibility':'on'},{'lightness':700}]}]
			};
			break;
		default:
			var myOptions = {
		  zoom: startZoom,
		  center: latlng
				};
	}
    
    var map = new google.maps.Map(mapdiv, myOptions);
    if (kml!='') {
    	var myKMLOptions = {
		  //suppressInfoWindows: true,
          map: map,
          preserveViewport: true
		};
    	var georssLayer = new google.maps.KmlLayer(kml, myKMLOptions);
  		georssLayer.setMap(map);
    }
    format_{$this->mod}(map, control, maptype, show_marker, addoverview, addscale, addgoogle);
    format2_{$this->mod}(map, id, streetview);

    if (show_marker) show_gmap_marker(map, latlng, centerLatitude, centerLongitude, description, type);
    return map;
}

function format_{$this->mod}(map, control, maptype, show_marker, addoverview, addscale, addgoogle) {
	if (addscale==1) map.setOptions({scaleControl: true});
	//if (addoverview=='1') {map.setOptions({overviewMapControl: true, overviewMapControlOptions:{opened: true}});} else {map.setOptions({overviewMapControl: false});}
	if (addoverview==1) map.setOptions({overviewMapControl: true, overviewMapControlOptions:{opened: true}});
	//map.setOptions({google.maps.MapTypeId: ROADMAP});
	if (maptype=='SATELLITE') {map.setOptions({MapTypeId: google.maps.MapTypeId.SATELLITE});}
	else if (maptype=='TERRAIN') {map.setOptions({MapTypeId: google.maps.MapTypeId.TERRAIN});}
	else if (maptype=='HYBRID') {map.setOptions({MapTypeId: google.maps.MapTypeId.HYBRID});}
	else {map.setOptions({MapTypeId: google.maps.MapTypeId.ROADMAP});}
}

function format2_{$this->mod}(map, id, streetview) {
	if (streetview==1) {map.setOptions({streetViewControl: true});}
	else {map.setOptions({streetViewControl: false});}
	$this->options_js
}

function show_gmap_marker(map, latlng, centerLatitude, centerLongitude, description, type) {
    var desc = description;
    if (desc=='') desc = '('+centerLatitude+', '+centerLongitude+')';
    var title_desc = desc.replace(/<br \/>/g, ' ');
    title_desc = title_desc.replace(/~/g, '');
    var marker = new google.maps.Marker({
        position:latlng,
        map:map,
        title:title_desc
    });
    var infowindow = new google.maps.InfoWindow({content:desc.replace(/~/g, '<br />')});
    google.maps.event.addListener(marker, 'click', function() {infowindow.open(map, marker);});
}

-->
</script>
";
		return $js;
	}

	function setup_css() {
		$output = '';
		return $output;
	}

	function gmap_code() {
		$js = "\n<script type=\"text/javascript\">
<!--
function init_{$this->mod}_gmap(id, addr, centerLatitude, centerLongitude, startZoom, description, kml, control, maptype, show_marker, addoverview, addscale, addgoogle, streetview, type) {
    var map;
	if (addr!='') {
		var addr2 = addr.replace(/~/g, '');
		var latlng_RegExp = /(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/;
		if (latlng_RegExp.test(addr2)) {
			var str = addr2.match(latlng_RegExp);
    		map = display_{$this->mod}_gmap(id, str[1], str[2], startZoom, description, kml, control, maptype, show_marker, addoverview, addscale, addgoogle, streetview, type);
		} else {
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode( { 'address': addr2}, function(results, status) {
		      if (status == google.maps.GeocoderStatus.OK) {
		        if (description=='') description = addr;
		      	map = display_{$this->mod}_gmap(id, results[0].geometry.location.lat(), results[0].geometry.location.lng(), startZoom, description, kml, control, maptype, show_marker, addoverview, addscale, addgoogle, streetview, type);
		      } else {
		        alert('Google cannot decode your address: '+addr);
		      }
	   		});
	   	}
    } else {
    	map = display_{$this->mod}_gmap(id, centerLatitude, centerLongitude, startZoom, description, kml, control, maptype, show_marker, addoverview, addscale, addgoogle, streetview, type);
    }
}

-->
</script>
";

		return $js;
	}

}

class Plugin_googleMaps extends Plugin_googleMaps_base {

	function Plugin_googleMaps( &$row, $pluginParams, $is_mod=0 ) {
		$this->mod = 'gmap';
		$this->tag = 'yt_google_map';
		$this->addoverview = 1;
		$this->addgoogle = 1;
		$this->init_google_maps($row, $pluginParams, $is_mod);
	}

	function output_map(&$row, $matches, $map_id) {
		if (preg_match('/(\d+)%/', $this->width, $matches3)) {
			if ($matches3[1]<1 || $matches3[1]>100) $this->width = '100%';
		} elseif ($this->width<10 || $this->width>4096) $this->width = 640;

		if (preg_match('/%/', $this->width)) $width2 = $this->width;
		else $width2 =  $this->width.'px';
		$output = '';
		if ($this->w3c) $output .= '</p>';
		$output .= "\n<div class=\"yt-gmap {$this->align}\" id=\"{$this->mod}_gmap{$map_id}\" style=\"width: {$width2}; height: {$this->height}px;\"></div>\n";
		$output .= "<div style=\"width: {$width2};\"></div>\n";
		if ($this->w3c) $output .= '<p>';
		$row->text = str_replace($matches[0], $output, $row->text);

		$js = "init_{$this->mod}_gmap('$map_id', '$this->addr', $this->lat, $this->long, $this->startzoom, '$this->description', '$this->kml', '$this->control', '$this->maptype', $this->marker, '$this->addoverview', '$this->addscale', '$this->addgoogle', '$this->streetview', '$this->type')\n";

		return $js;
	}

	function setup_gmap() {
		$output = "";

		$lang = '';
		if ($this->lang!='') $lang = "&amp;hl=".$this->lang;
		$output .= "\n"."<script type=\"text/javascript\" src=\"http://maps.googleapis.com/maps/api/js?sensor=false&amp;language=".$this->lang.'"></script>';
		$output .= $this->gmap_code();
		return $output;
	}

}

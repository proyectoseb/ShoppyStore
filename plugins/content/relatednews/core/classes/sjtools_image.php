<?php
/**
 * @package SjTools
 * @subpackage Image
 * @version 1.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2009-2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

class SjTools_Image {

	const POSITION_TOP_LEFT = 'top-left';
	const POSITION_TOP_RIGHT = 'top-right';
	const POSITION_BOTTOM_LEFT = 'bottom-left';
	const POSITION_BOTTOM_RIGHT = 'bottom-right';
	const POSITION_STRETCH = 'stretch';
	const POSITION_TILE = 'tile';
	const POSITION_CENTER = 'center';

	private $_callbacks = array(
		IMAGETYPE_GIF  => array('output' => 'imagegif',  'create' => 'imagecreatefromgif'),
		IMAGETYPE_JPEG => array('output' => 'imagejpeg', 'create' => 'imagecreatefromjpeg'),
		IMAGETYPE_PNG  => array('output' => 'imagepng',  'create' => 'imagecreatefrompng'),
		IMAGETYPE_XBM  => array('output' => 'imagexbm',  'create' => 'imagecreatefromxbm'),
		IMAGETYPE_WBMP => array('output' => 'imagewbmp', 'create' => 'imagecreatefromxbm')
	);
	
	private $_functions = array(
		'resize',
		'crop',
		'rotate',
		'flip_horizontal',
		'flip_vertical'
	);
	
	private $_modes = array(
		'center',
		'fill',
		'fit',
		'stretch'
	);

	protected $sFile;
	protected $sImage;
	protected $sImageType;
	protected $sW;
	protected $sH;

	protected $oFile;
	protected $oImage;
	protected $oImageType;
	protected $oW;
	protected $oH;

	protected $outputCache;
	protected $quality;
	protected $background;
	protected $function;

	protected $watermarkFile;
	protected $watermarkImage;
	protected $watermarkHeight;
	protected $watermarkWidth;
	protected $watermarkOpacity;
	protected $watermarkPosition;

	protected $logMessages;
	protected static $_instance = null;

	public function __construct($filename=null){
		is_null($filename) || $this->load($filename);
		$this->quality = 100;
	}
	
	public function __destruct(){
		$this->_destroy($this->sImage);
		$this->_destroy($this->oImage);
	}
	
	public function __toString(){
		return $this->getOutputFile();
	}

	public static function getInstance(){
		if( self::$_instance === null ){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function applyConfig($config=array()){
		if (is_array($config) || is_object($config)){
			foreach ($config as $key => $value){
				$keys = (false===strpos($key, '_')) ? array($key) : explode('_', $key);
				$keys = array_map('strtolower', $keys);
				$keys = array_map('ucfirst', $keys);
				$func = 'set' . implode('', $keys);
				if (method_exists($this, $func)){
					call_user_func(array($this, $func), $value);
				} else {
					$this->log('SjTools_Image::'.$func.' is not exists!');
				}
			}
		}
		return $this;
	}

	public function display($image_type=null){
		if ($image_type!=null){
			$this->setOutputImageType($image_type);
		}
		$args = array();
		$args[] = $this->oImage;
		call_user_func_array($this->_getCallback('output', $this->oImageType), $args);
	}
	
	public function load($filename){
		if (!is_null($this->sFile) && $this->sFile==$filename){
			$this->log('Image: ' . $filename . ' is loaded.');
			return $this;
		}
		$this->reset();
		$this->setSourceFile($filename);
		$this->_initType();
		$this->_initImage();
		$this->_initDimensions();
		return $this;
	}
	
	public function reset(){
		$this->sFile		= null;
		$this->_destroy($this->sImage);;
		$this->sImageType	= null;
		$this->sW			= 0;
		$this->sH			= 0;

		$this->oFile		= null;
		$this->_destroy($this->oImage);
		$this->oImageType	= null;
		$this->oW			= 0;
		$this->oH			= 0;
		return $this;
	}
	
	public function save(){
		$fileout = $this->getOutputFile();
		if (file_exists($fileout)){
			return $this;
		} else {
			if ($this->_prepareCacheFolder($fileout)){
				$args = array();
				$args[] = $this->oImage;
				$args[] = $fileout;

				// set quality param for JPG file type
				if (IMAGETYPE_JPEG == $this->oImageType) {
					$args[] = $this->quality;
				} else if (IMAGETYPE_PNG == $this->oImageType){
					$png_quality = round(($this->oImageType / 100) * 10);
					$png_quality = $png_quality < 1 ? 1 : $png_quality;
					$png_quality = $png_quality >10 ? 10: $png_quality;
					$png_quality = 10 - $png_quality;
					$args[] = $png_quality;
				}
				call_user_func_array($this->_getCallback('output', $this->oImageType), $args);
			} else {
				$this->log('Cannot create file: ' . $fileout . '! Please set cache directory permission.');
			}
		}
		return $this;
	}
	
	public function crop($x, $y, $w=null, $h=null){
		if (is_null($w)) $w=$this->sW-$x;
		if (is_null($h)) $h=$this->sH-$y;
		$this->setOutputWidth($w);
		$this->setOutputHeight($h);
		$this->getOutputImageType();
		$this->setFunction('crop');
		$fileout = $this->getOutputFile(true);
		if (file_exists($fileout)){
			return $this;
		} else {
			return $this->_crop($x, $y);
		}
	}
	
	public function fit($max_size){
		return $this->resize($max_size);
	}
	
	public function flip($direction = 'horizontal'){
		$this->setOutputHeight($this->sH);
		$this->setOutputWidth($this->sW);
		$this->getOutputImageType();
		if ($direction == 'horizontal'){
			return $this->flip_horizontal();
		} else {
			return $this->flip_vertical();
		}
	}
	private function flip_horizontal(){
		$this->setFunction('flip_horizontal');
		$fileout = $this->getOutputFile(true);
		if (!file_exists($fileout)){
			$this->getOutputImage(true);
			for($i=0; $i<$this->sW; $i++){
				@imagecopyresampled($this->oImage, $this->sImage, $i, 0, $this->oW-$i-1, 0, 1, $this->oH, 1, $this->sH);
			}
		}
		return $this;
	}
	private function flip_vertical(){
		$this->setFunction('flip_vertical');
		$fileout = $this->getOutputFile(true);
		if (!file_exists($fileout)){
			$this->getOutputImage(true);
			for ($i=0; $i<$this->sH; $i++){
				imagecopyresampled($this->oImage, $this->sImage, 0, $i, 0, $this->oH-$i-1, $this->oW, 1, $this->sW, 1);
			}
		}
		return $this;
	}

	public function resize($w=null, $h=null, $mode=null){
		if (is_null($w) && is_null($h)){
			$this->log('Invalid dimension (0x0)');
		} else if (is_null($h)){
			$mode = $this->_modes[2];
			if ($this->sH > $this->sW){
				$h = $w;
				$w = 0;
			} else {
				$h = 0;
			}
		} else if (is_null($w)){
			$w = 0;
		}

		if ($w + $h == 0){
			$this->log('Cannot resize to 0x0');
		} else if ($w == 0){
			$mode = $this->_modes[3];
			$w = round( $h * $this->sW / $this->sH );
		} else if ($h == 0){
			$mode = $this->_modes[3];
			$h = round( $w * $this->sH / $this->sW );
		} else if ( abs($h-round($w*$this->sH/$this->sW)) <= 1){
			$mode = $this->_modes[3];
			$h = round( $w * $this->sH / $this->sW );
		}

		$this->setOutputWidth($w);
		$this->setOutputHeight($h);
		$this->getOutputImageType();
		$this->setFunction('resize_' . $mode);

		$fileout = $this->getOutputFile(true);
		if (file_exists($fileout)){
			return $this;
		} else {
			return $this->_resize();
		}
	}
	
	public function rotate($angle){
		
	}
	
	public function scale($ratio=1){
		if ($ratio>0 && $ratio!=1){
			$this->setOutputWidth($this->sW*$ratio);
			$this->setOutputHeight($this->sH*$ratio);
			$this->getOutputImageType();
			$this->setFunction('resize_stretch');
			$fileout = $this->getOutputFile(true);
			if (!file_exists($fileout)){
				return $this->_resize();
			}
		} else {
			$this->log("Please use function: scale with ratio>0 and ratio!=1");
		}
		return $this;
	}
	
	public function watermark($image, $position=null, $opacity=null){
		if (!is_resource($this->oImage)){
			$this->log("Output image is not resource!");
			return $this;
		}
		$this->setWatermarkPosition($position);
		$this->setWatermarkOpacity($opacity);

		$this->setWatermarkImage($image);

		if (is_resource($this->watermarkImage)){
			switch ($this->getWatermarkPosition()){
				case self::POSITION_BOTTOM_LEFT:
					$dx = 0;
					$dy = $this->oH - $this->watermarkHeight;
					break;
				default:
				case self::POSITION_BOTTOM_RIGHT:
					$dx = $this->oW - $this->watermarkWidth;
				$dy = $this->oH - $this->watermarkHeight;
				break;
				case self::POSITION_CENTER:
					$dx = ($this->oW - $this->watermarkWidth)/2;
					$dy = ($this->oH - $this->watermarkHeight)/2;
					break;
				case self::POSITION_TILE: break;
				case self::POSITION_STRETCH:
				case self::POSITION_TOP_LEFT:
					$dx = 0;
					$dy = 0;
					break;
				case self::POSITION_TOP_RIGHT: break;
			}
			imagealphablending($this->oImage, true);
			imagecopy($this->oImage, $this->watermarkImage, $dx, $dy, 0, 0, $this->watermarkWidth, $this->watermarkHeight);
			imagealphablending($this->oImage, false);
			imagesavealpha($this->oImage, true);
		}
		return $this;
	}

	public function html($alt=null, $title=null, $attributes=array()){
		!is_null($alt)   || $alt   = basename($this->getSourceFile());
		!is_null($title) || $title = basename($this->getSourceFile());
		$img_url = str_replace("\\", "/", $this->getOutputFile());
		$img_tag = "<img src=\"$img_url\" title=\"$title\" alt=\"$alt\"";
		if (count($attributes)>0){
			foreach ($attributes as $attr => $val){
				$img_tag .= " $attr=\"" . htmlentities($val) . "\"";
			}
		}
		$img_tag .= " />";
		return $img_tag;
	}

	public function getSourceFile(){
		return $this->sFile;
	}
	
	public function setSourceFile($filename){
		if (is_readable($filename)){
			$this->sFile = $filename;
		} else {
			$this->log("File: $filename is not readable!");
		}
		return $this;
	}
	
	public function getSourceImage(){
		return $this->sImage;
	}
	
	public function setSourceImage($image){
		if (is_resource($image)){
			$this->sImage = $image;
		} else {
			$this->log('setSourceImage: Image is not resource type.');
		}
		return $this;
	}
	
	public function getSourceImageType(){
		return $this->sImageType;
	}
	
	public function setSourceImageType($image_type=null){
		$supportTypes = array_keys($this->_callbacks);
		if (in_array($image_type, $supportTypes)){
			$this->sImageType = $image_type;
		} else {
			$this->log("setSourceImageType: Invalid image type.");
		}
		return $this;
	}
	
	public function getSourceWidth(){
		return $this->sW;
	}
	
	public function getSourceHeight(){
		return $this->sH;
	}
	
	public function getOutputFile($renew=false){
		if(is_null($this->oFile) || $renew){
			$values = array(
				$this->sFile,
				$this->oImageType,
				$this->oH,
				$this->oW,
				$this->quality,
				$this->background,
				$this->function,
				$this->watermarkOpacity,
				$this->watermarkPosition,
				$this->watermarkHeight,
				$this->watermarkWidth
			);
			$cache = $this->getOutputCache() ? $this->getOutputCache() . DS : '';
			$serialize = serialize($values);
			$this->oFile = $cache .  md5($serialize) . image_type_to_extension($this->oImageType);
		}
		return $this->oFile;
	}
	
	public function setOutputFile($filename){
		$this->oFile = $filename;
		return $this;
	}
	
	public function getOutputImage($renew=false){
		if($renew){
			$this->oImage = $this->_getOutputImage();
		}
		return $this->oImage;
	}
	
	public function setOutputImage($image){
		if (is_resource($image)){
			$this->oImage = $image;
		} else {
			$this->log('setOutputImage: Image is not resource type.');
		}
		return $this;
	}
	
	public function getOutputImageType(){
		if(is_null($this->oImageType)){
			$this->oImageType = $this->sImageType;
		}
		return $this->oImageType;
	}
	
	public function setOutputImageType($image_type){
		$supportTypes = array_keys($this->_callbacks);
		if (in_array($image_type, $supportTypes)){
			$this->oImageType = $image_type;
		} else {
			$this->log("setOutputImageType: Invalid image type.");
		}
		return $this;
	}
	
	public function getOutputWidth(){
		return $this->oW;
	}
	
	public function setOutputWidth($imagesx){
		$this->oW = $imagesx > 0 ? $imagesx : 0;
		return $this;
	}
	
	public function getOutputHeight(){
		return $this->oH;
	}
	
	public function setOutputHeight($imagesy){
		$this->oH = $imagesy > 0 ? $imagesy : 0;
		return $this;
	}
	
	public function getQuality(){
		return $this->quality;
	}
	
	public function setQuality($quality){
		$quality = (int)$quality;
		$quality = $quality < 10 ? 10 : $quality;
		$quality = $quality >100 ? 100: $quality;
		$this->quality = $quality;
	}
	
	public function getBackground(){
		return $this->background;
	}
	
	public function setBackground($color){
		if (is_array($color)){
			foreach ($color as $c => $v){
				$v = (int)$v;
				$v = $v < 0 ? 0 : $v;
				$v = $v > 255 ? 255 : $v;
				switch ($c){
					case 0:
					case 'r':
						$this->background['r'] = $v;
						break;
					case 1:
					case 'g':
						$this->background['g'] = $v;
						break;
					case 2:
					case 'b':
						$this->background['b'] = $v;
						break;
				}
			}
		} else if (is_string($color)) {
			if (preg_match('/^#?([a-f]|[0-9]){3}(([a-f]|[0-9]){3})?$/i', $color)){
				$color = ltrim($color);
				if (strlen($color)==3){
					$color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
				}
				$intc = hexdec($color);
				$this->background = array(
					'r' => 0xFF & ($intc >> 0x10),
					'g' => 0xFF & ($intc >> 0x8),
					'b' => 0xFF & $intc
				);
			}
		} else {
			$this->log('Invalid color input');
		}
		return $this;
	}
	
	public function getFunction(){
		return $this->function;
	}
	
	public function setFunction($function){
		$mode = str_replace('resize_', '', $function);
		if (in_array($function, $this->_functions) || in_array($mode, $this->_modes)){
			$this->function = $function;
		} else {
			$this->log("setFunction: Invalid function!");
		}
		return $this;
	}
	
	public function getWatermarkImage(){
		return is_resource($this->watermarkImage) ? $this->watermarkImage : false;
	}
	
	public function setWatermarkImage($image){
		if (is_string($image)) {
			if ( $this->_watermarkFileLoaded($image) ){
				$this->log('Watermark Image: ' . $image . ' is loaded.');
				return $this;
			}
			list($wmx, $wmy, $wmt) = getimagesize($image);
			$this->watermarkWidth = $wmx;
			$this->watermarkHeight= $wmy;
			if (isset($this->_callbacks[$wmt])){
				$fn_create = $this->_callbacks[$wmt]['create'];
			} else {
				$fn_create = 'imagecreatefromgd2';
			}
			$watermarkImage = call_user_func($fn_create, $image);
			if (is_resource($watermarkImage)){
				if ($this->getWatermarkPosition()==self::POSITION_STRETCH){
					$newWatermark = imagecreatetruecolor($this->oW, $this->oH);
					$color = imagecolorallocatealpha($newWatermark, 255, 255, 255, 127);
					imagefilledrectangle($newWatermark, 0, 0, $this->oW, $this->oH, $color);
					imagealphablending($newWatermark, false);
					imagecopyresampled($newWatermark, $watermarkImage, 0, 0, 0, 0, $this->oW, $this->oH, $wmx, $wmy);
					imagesavealpha($newWatermark, true);
					$watermarkImage = $newWatermark;
					$this->watermarkWidth  = $this->oW;
					$this->watermarkHeight = $this->oH;
				} else {
					$newWatermark = imagecreatetruecolor($this->watermarkWidth, $this->watermarkHeight);
					$color = imagecolorallocatealpha($newWatermark, 255, 255, 255, 64);
					imagefilledrectangle($newWatermark, 0, 0, $this->watermarkWidth, $this->watermarkHeight, $color);
					imagealphablending($newWatermark, false);
					imagecopyresampled($newWatermark, $watermarkImage, 0, 0, 0, 0, $this->watermarkWidth, $this->watermarkHeight, $wmx, $wmy);
					imagesavealpha($newWatermark, true);
					$watermarkImage = $newWatermark;
				}
				for ($y = 0; $y < $this->watermarkHeight; $y++) {
					for ($x = 0; $x < $this->watermarkWidth; $x++) {
						$color = imagecolorat($watermarkImage, $x, $y);
						$cindex = imagecolorsforindex($watermarkImage, $color);
						$newAlpha = 127-$cindex['alpha'];
						$newAlpha = round($newAlpha * $this->getWatermarkOpacity()/100.0);
						$newAlpha = 127 - $newAlpha;
						$newColor = imagecolorallocatealpha($watermarkImage, $cindex['red'], $cindex['green'], $cindex['blue'], $newAlpha);
						imagesetpixel($watermarkImage, $x, $y, $newColor);
					}
				}
			}
			$this->watermarkImage = $watermarkImage;
		} else {
			$this->log('setWatermarkImage: Image is not resource type or file not exists.');
		}
		return $this;
	}
	
	public function getWatermarkOpacity(){
		return $this->watermarkOpacity;
	}
	
	public function setWatermarkOpacity($opacity){
		$this->watermarkOpacity = $opacity;
		return $this;
	}
	
	public function getWatermarkPosition(){
		return $this->watermarkPosition;
	}
	
	public function setWatermarkPosition($position){
		switch($position){
			case self::POSITION_BOTTOM_LEFT:
			case self::POSITION_BOTTOM_RIGHT:
			case self::POSITION_TOP_LEFT:
			case self::POSITION_TOP_RIGHT:
			case self::POSITION_CENTER:
			case self::POSITION_STRETCH:
			case self::POSITION_TILE:
				$this->watermarkPosition = $position;
				break;
			default:
				$this->watermarkPosition = self::POSITION_BOTTOM_RIGHT;
		}
		return $this;
	}
	
	public function getWatermarkWidth(){
		return $this->watermarkWidth;
	}

	public function getWatermarkHeight(){
		return $this->watermarkHeight;
	}

	public function getOutputCache(){
		if (is_null($this->outputCache)){
			$this->outputCache = SjTools::getModuleCache();
		}
		return $this->outputCache;
	}
	
	public function setOutputCache($path){
		$this->outputCache = $path;
		return $this;
	}

	public function log($message){
		if (is_null($this->logMessages)){
			$this->logMessages = array();
		}
		$date = date('Y-m-d H:i:s - ');
		array_push($this->logMessages, $date.$message);
	}
	
	public function clearLog(){
		$this->logMessages = array();
	}
	
	public function showLog(){
		if(!is_null($this->logMessages)){
			foreach ($this->logMessages as $msg){
				echo "<br>" . htmlentities($msg);
			}
		}
	}

	private function _getCallback($cb_type, $image_type){
		if (isset($this->_callbacks[$image_type][$cb_type])){
			return $this->_callbacks[$image_type][$cb_type];
		} else if ($cb_type=='create'){
			$this->log("Unsupported create image in this format. Try with function: imagecreatefromgd2");
			return 'imagecreatefromgd2';
		} else if ($cb_type=='output'){
			$this->log("Unsupported output image in this format. Try with function: imagegd2");
			return 'imagegd2';
		}
	}
	
	private function _initType(){
		if (!is_null($this->sFile)){
			if (function_exists('exif_imagetype')) {
				$exif_imagetype = exif_imagetype($this->sFile);
				$this->setSourceImageType($exif_imagetype);
			} else {
				$imageinfo = getimagesize($this->sFile);
				$this->setSourceImageType($imageinfo[2]);
			}
		}
	}

	private function _initImage(){
		if (!is_null($this->sImageType)){
			if (isset($this->_callbacks[$this->sImageType])){
				$fn_create = $this->_callbacks[$this->sImageType]['create'];
			} else {
				$fn_create = 'imagecreatefromgd2';
			}
			$this->sImage = call_user_func($fn_create, $this->sFile);
		} else {
			$this->log('Image Type not detected.');
		}
	}
	private function _initDimensions(){
		if (!is_null($this->sImage) && is_resource($this->sImage)){
			$this->sW = imagesx($this->sImage);
			$this->sH = imagesy($this->sImage);
		} else {
			$this->log('Have not image resource.');
		}
	}

	private function _destroy($image){
		if(is_resource($image)){
			imagedestroy($image);
		} else {
			$image = null;
		}
	}

	private function _resize(){
		$args = $this->_getArguments();
		$this->getOutputImage(true);
		count($args) && imagecopyresampled($this->oImage, $this->sImage, $args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]);
	}
	private function _getArguments(){
		$args = array();
		if ($this->sW<=0 || $this->sH<=0 || $this->oW<=0 || $this->oH<=0){
			$this->log("Image size == 0.");
		} else {
			$sx = $sy = 0;
			$sw = $this->sW;
			$sh = $this->sH;

			$dx = $dy = 0;
			$dw = $this->sW;
			$dh = $this->sH;

			switch(str_replace('resize_', '', $this->function)){
				case 'center':
					if ($this->oW > $dw) {
						$dx = ($this->oW-$dw)/2;
					}
					if ($this->oH > $dh) {
						$dy = ($this->oH-$dh)/2;
					}
					if ($this->sW > $this->oW){
						$sx = ($this->sW-$this->oW)/2;
					}
					if ($this->sH > $this->oH){
						$sy = ($this->sH-$this->oH)/2;
					}
					break;
				case 'fill':
					$or = 1.0*$this->sW/$this->sH;
					if ($this->oW < $this->oH*$or){
						$dx = round($this->oH*$or);
						$dh = $this->oH;
						$dx = ($this->oW-$dx)/2;
						$dy = 0;
					} else {
						$dx = $this->oW;
						$dh = round($this->oW/$or);
						$dx = 0;
						$dy = ($this->oH-$dh)/2;
					}
					break;
				case 'fit':
					$or = 1.0*$this->sW/$this->sH;
					if ($this->oW > $this->oH*$or){
						$dw = round($this->oH*$or);
						$dh = $this->oH;
						$dx = ($this->oW-$dw)/2;
						$dy = 0;
					} else {
						$dw = $this->oW;
						$dh = round($this->oW/$or);
						$dx = 0;
						$dy = ($this->oH-$dh)/2;
					}
					break;
				default:
				case 'stretch':
					$dw = $this->oW;
					$dh = $this->oH;
				break;
			}
			$args = array($dx, $dy, $sx, $sy, $dw, $dh, $sw, $sh);
		}
		return $args;
	}

	private function _crop($x, $y){
		$args = $this->_cropArguments($x, $y);
		$this->getOutputImage(true);
		count($args) && imagecopyresampled($this->oImage, $this->sImage, $args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]);
	}
	private function _cropArguments($x, $y){
		$nw = $this->sW - $x;
		$nh = $this->sH - $y;
		$args = array(
			0,
			0,
			$x,
			$y,
			$nw,
			$nh,
			$nw,
			$nh
		);
		return $args;
	}

	private function _getOutputImage(){
		$oImage = imagecreatetruecolor($this->oW, $this->oH);
		if (IMAGETYPE_GIF == $this->oImageType){
			$trans_index = imagecolortransparent($this->sImage);
			if ($trans_index >= 0) {
				$trans_color = imagecolorsforindex($this->sImage, $trans_index);
				$trans_index = imagecolorallocate($oImage, $trans_color['red'], $trans_color['green'], $trans_color['blue']);
				imagefill($oImage, 0, 0, $trans_index);
				imagecolortransparent($oImage, $trans_index);
			}
		} else if(IMAGETYPE_PNG == $this->oImageType) {
			$png_alpha = ((ord(file_get_contents($this->sFile, false, null, 25, 1)) & 6) & 4) == 4;
			if ($png_alpha){
				imagealphablending($oImage, false);
				$transparent = imagecolorallocatealpha($oImage, 0, 0, 0, 127);
				imagefilledrectangle($oImage, 0, 0, $this->oW, $this->oH, $transparent);
				imagesavealpha($oImage, true);
			}
		} else if (is_array($this->background)
			&& isset($this->background['r'])
			&& isset($this->background['g'])
			&& isset($this->background['b'])
			&& in_array($this->function, array('resize_center', 'resize_fit'))){
			$background = imagecolorallocate(
				$oImage,
				$this->background['r'],
				$this->background['g'],
				$this->background['b']
			);
			imagefilledrectangle($oImage, 0, 0, $this->oW, $this->oH, $background);
		}
		return $oImage;
	}

	private function _watermarkFileLoaded($filename){
		$hash = md5(serialize(array($filename, $this->watermarkPosition)));
		if ($this->watermarkFile != $hash){
			$this->watermarkFile = $hash;
			return false;
		}
		return true;
	}

	private function _prepareCacheFolder($filename){
		$folder = dirname($filename);
		$stack = array();
		while($folder && !file_exists($folder)){
			$top = basename($folder);
			$folder = dirname($folder);
			array_push($stack, $top);
		}
		while(count($stack)){
			$top = array_pop($stack);
			$folder = $folder . DIRECTORY_SEPARATOR . $top;
			if (!@mkdir($folder, 0755)){
				return false;
			}
		}
		return true;
	}
}
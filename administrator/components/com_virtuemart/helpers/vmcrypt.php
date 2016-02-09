<?php
defined('VMPATH_ROOT') or die();
/**
 * virtuemart encrypt class, with some additional behaviours.
 *
 *
 * @package    VirtueMart
 * @subpackage Helpers
 * @author Max Milbers, Valérie Isaksen
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

class vmCrypt {

	const ENCRYPT_SAFEPATH="keys";

	static function encrypt ($string) {

		$key = self::_getKey ();

		if(function_exists('mcrypt_encrypt')){
			// create a random IV to use with CBC encoding
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			//vmdebug('vmCrypt encrypt the used key '.$key);
			return base64_encode ($iv.mcrypt_encrypt (MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC,$iv));
		} else {
			vmdebug('vmCrypt no mcrypt_encrypt available');
			return base64_encode ($string);
		}

	}

	static function decrypt ($string,$date=0) {

		if(empty($string)) return '';

		$key = self::_getKey ($date);
		if(!empty($key)){
			$ciphertext_dec = base64_decode($string);
			if(function_exists('mcrypt_decrypt')){
				$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
				//vmdebug('decrypt $iv_size', $iv_size ,MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
				// retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
				$iv_dec = substr($ciphertext_dec, 0, $iv_size);
				//retrieves the cipher text (everything except the $iv_size in the front)
				$ciphertext_dec = substr($ciphertext_dec, $iv_size);
				//vmdebug('decrypt $iv_dec',$iv_dec,$ciphertext_dec);
				if(empty($iv_dec) and empty($ciphertext_dec)){
					//vmdebug('Seems something not encrytped should be decrypted, return default ',$string);
					return $string;
				} else {
					$mcrypt_decrypt = mcrypt_decrypt (MCRYPT_RIJNDAEL_256, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
					//vmdebug('vmCrypt mcrypt_decrypt available '.$mcrypt_decrypt,$ciphertext_dec);
					return rtrim ($mcrypt_decrypt, "\0");
				}

			} else {
				vmdebug('vmCrypt no mcrypt_encrypt available');
				return $ciphertext_dec;
			}

		} else {
			return $string;
		}

	}

	private static function _getKey ($date = 0) {

		$key = self::_checkCreateKeyFile($date);

		return $key;

	}

	private static function _checkCreateKeyFile($date){
		jimport('joomla.filesystem.file');

		vmSetStartTime('check');
		static $existingKeys = false;

		$keyPath = self::_getEncryptSafepath ();

		if(!$existingKeys){
			$dir = opendir($keyPath);
			if(is_resource($dir)){
				$existingKeys = array();
				while(false !== ( $file = readdir($dir)) ) {
					if (( $file != '.' ) && ( $file != '..' )) {
						if ( !is_dir($keyPath .DS. $file)) {
							$ext = Jfile::getExt($file);
							if($ext=='ini' and file_exists($keyPath .DS. $file)){
								$content = parse_ini_file($keyPath .DS. $file);
								if($content and is_array($content) and isset($content['unixtime'])){
									$key = $content['unixtime'];
									unset($content['unixtime']);
									$existingKeys[$key] = $content;
									//vmdebug('Reading '.$keyPath .DS. $file,$content);
								}

							} else {
								//vmdebug('Resource says there is file, but does not exists? '.$keyPath .DS. $file);
							}
						} else {
							//vmdebug('Directory in they keyfolder?  '.$keyPath .DS. $file);
						}
					} else {
						//vmdebug('Directory in the keyfolder '.$keyPath .DS. $file);
					}
				}
			} else {
				static $warn = false;
				if(!$warn)vmWarn('Key folder in safepath unaccessible '.$keyPath);
				$warn = true;
			}
		}

		if($existingKeys and is_array($existingKeys) and count($existingKeys)>0){
			ksort($existingKeys);

			if(!empty($date)){
				$key = '';
				foreach($existingKeys as $unixDate=>$values){
					if(($unixDate-30) >= $date ){
						vmdebug('$unixDate '.$unixDate.' >= $date '.$date);
						continue;
					}
					vmdebug('$unixDate < $date '.$date);
					$key = $values['key'];
					$usedKey = $values;
				}
				if(!isset($usedKey['b64']) or $usedKey['b64']){
					vmdebug('Doing base64_decode ',$usedKey);
					$key = base64_decode($key);
				}

			} else {
				$usedKey = end($existingKeys);
				$key = $usedKey['key'];
				//No key means, we wanna encrypt something, when it has not the new attribute,
				//it is an old key and must be replaced
				$ksize = VmConfig::get('keysize',24);
				if(empty($key) or !isset($usedKey['b64']) or !isset($usedKey['size']) or $usedKey['size']!=$ksize){
					$key = self::_createKeyFile($keyPath,$ksize);
					$existingKeys[$key['unixtime']] = $key;
					return $key['key'];
				}
			}

			//vmdebug('Length of key',strlen($key));
			//vmTime('my time','check');
			return $key;
		} else {
			$key = self::_createKeyFile($keyPath,VmConfig::get('keysize',24));
			$existingKeys[$key['unixtime']] = $key;
			return $key['key'];
		}
	}


	private static function _createKeyFile($keyPath, $size = 32){

		$usedKey = date("ymd");
		$filename = $keyPath . DS . $usedKey . '.ini';
		if (!JFile::exists ($filename)) {

			$key = self::crypto_rand_secure($size);

			vmdebug('create key file ',$size);
			$date = JFactory::getDate();
			$today = $date->toUnix();
			$dat = date("Y-m-d H:i:s");
			$content = ';<?php die(); */
						[keys]
						key = "'.$key.'"
						unixtime = "'.$today.'"
						date = "'.$dat.'"
						b64 = "0"
						size = "'.$size.'"
						; */ ?>';
			$result = JFile::write($filename, $content);

			return array('key'=>$key,'unixtime'=>$today,'date'=>$dat,'b64'=>0,'size'=>$size);
		} else {
			return false;
		}
	}

	private static function _getEncryptSafepath () {

		if (!class_exists('ShopFunctions'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
		$safePath = ShopFunctions::checkSafePath();
		if (empty($safePath)) {
			return NULL;
		}
		$encryptSafePath = $safePath . self::ENCRYPT_SAFEPATH;
		//echo 'my $encryptSafePath '.$encryptSafePath;
		//if(!JFolder::exists($encryptSafePath)){
			self::createEncryptFolder($encryptSafePath);
		//}
		return $encryptSafePath;
	}

	private static function createEncryptFolder ($folderName) {

		//$folderName = self::_getEncryptSafepath ();

		$exists = JFolder::exists ($folderName);
		if ($exists) {
			return TRUE;
		}
		$created = JFolder::create ($folderName);
		if ($created) {
			return TRUE;
		}
		$uri = JFactory::getURI ();
		$link = $uri->root () . 'administrator/index.php?option=com_virtuemart&view=config';
		VmError (vmText::sprintf ('COM_VIRTUEMART_CANNOT_STORE_CONFIG', $folderName, '<a href="' . $link . '">' . $link . '</a>', vmText::_ ('COM_VIRTUEMART_ADMIN_CFG_MEDIA_FORSALE_PATH')));
		return FALSE;
	}

	/**
	 * Creates a token for inputs by human, some chars are removed to reduce mistyping,
	 * All chars are upper case, 0 and O are omitted
	 *
	 * @author Max Milbers
	 * @param $length
	 * @return string
	 */
	static function getHumanToken($length) {
		return self::getToken( $length, "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ" );
	}

	/**
	 * Creates a token
	 *
	 * @author Max Milbers
	 * @param $length Only keys of sizes 16, 24 or 32 are supported
	 * @param $pool pool to chose from
	 * @return string
	 */
	static function getToken($length=24, $pool = false)
	{
		$token = "";
		if(!$pool){
			$pool = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$pool.= "abcdefghijklmnopqrstuvwxyz";
			$pool.= "0123456789";
		}
		$max = strlen($pool);

		for ($i=0; $i < $length; $i++) {

			$token .= $pool[self::crypto_rand_secure_cover($max)];
		}

		return $token;
	}

	static function getFilteredBytes($size = 32, $filter = '"'){

		$key = self::crypto_rand_secure($size);
		$i = 0;

		if(!is_array($filter)) $filter = array($filter);
		foreach ($filter as $f){
			while(strpos($key,$f)!==false){
				$pos = strpos($key,$f);
				$r = self::crypto_rand_secure(1);
				$key[$pos] = $r;
				if($i++>=($size*2))break;
			}
		}

		return $key;
	}

	static function crypto_rand_secure_cover($range) {

		//$range = $max - $min;
		//if ($range < 1) return $min; // not so random...
		$log = ceil( log( $range, 2 ) );
		$bytes = (int)($log/8) + 1; // length in bytes
		$bits = (int)$log + 1; // length in bits
		$filter = (int)(1 << $bits) - 1; // set all lower bits to 1
		do {

			$rnd = hexdec( bin2hex( self::crypto_rand_secure( $bytes ) ) );
			$rnd = $rnd & $filter; // discard irrelevant bits

		} while( $rnd>=$range );
		//vmdebug('crypto_rand_secure_cover '.$rnd);
		return $rnd;
	}


	/**
	 * Returns random bytes of the desired length
	 * The function with "CAPICOM" is not tested and there for other who may need and fix it.
	 * @author Max Milbers
	 * @param $r
	 * @param int $gen
	 * @return string
	 */
	static function crypto_rand_secure($r) {

		$bytes = '';
		static $used = false;

		if((strlen($bytes) < $r) && function_exists('openssl_random_pseudo_bytes')) {
			$bytes = openssl_random_pseudo_bytes($r);
			if(!$used){
				vmdebug('with openssl_random_pseudo_bytes',$bytes); $used = true;
			}
		}

		if((strlen($bytes) < $r) && function_exists('mcrypt_create_iv'))
		{
			// Use MCRYPT_RAND on Windows hosts with PHP < 5.3.7, otherwise use MCRYPT_DEV_URANDOM
			// (http://bugs.php.net/55169).
			$flag = (version_compare(PHP_VERSION, '5.3.7', '<') && strncasecmp(PHP_OS, 'WIN', 3) == 0) ? MCRYPT_RAND : MCRYPT_DEV_URANDOM ;
			$bytes = mcrypt_create_iv($r,$flag);
			if(!$used){
				vmdebug('with mcrypt_create_iv',$bytes); $used = true;
			}
		}

		if((strlen($bytes) < $r) && is_readable('/dev/urandom') && ($urandom = fopen('/dev/urandom', 'rb')) !== false) {
			$bytes = @fread($urandom, $r);
			@fclose($urandom);
			if(!$used){
				vmdebug('with urandom',$bytes); $used = true;
			}
		}

		/*if ((strlen($bytes) < $r) && class_exists('COM')) {
			// Officially deprecated in Windows 7
			// http://msdn.microsoft.com/en-us/library/aa388182%28v=vs.85%29.aspx
			try
			{
				// @noinspection PhpUndefinedClassInspection
				$CAPI_Util = new COM('CAPICOM.Utilities.1');
				if(is_callable(array($CAPI_Util,'GetRandom')))
				{
					// @noinspection PhpUndefinedMethodInspection
					$bytes = $CAPI_Util->GetRandom($r,0);
					$bytes = base64_decode($bytes);
				}
			}
			catch (Exception $e){
			}
			if(!$used){
				vmdebug('with CAPICOM',$bytes); $used = true;
			}
		}//*/

		if (strlen($bytes) < $r) {

			for($j=0;$j<$r;$j+=16){
				$mt_rand = mt_rand();
				/*$getmypid = '';
				if (function_exists('getmypid'))
					$getmypid .= getmypid();*/
				$memory_get_usage = 1/memory_get_usage();
				$ms = microtime(true);
				$frac = (int)substr((string)$ms - floor($ms),2);

				$random_state = $frac+$mt_rand+$memory_get_usage;
				$t = sha1( $random_state ,true);
				if($j+16>$r){
					$rest = $r-$j;
					$ran = mt_rand(0,15-$rest);
					$bytes .= substr($t,$ran,$rest);
					//vmdebug('mt_rand substr '.$bytes,$t);
				} else {
					$bytes .= $t;
					//vmdebug('just added it '.$bytes,$t);
				}
			}
		}
		//vmdebug('returning '.$bytes.' '.base64_encode($bytes));
		//*/
		/*if (strlen($bytes) < $r) {
			// do something to warn system owner that
			// pseudorandom generator is missing
			vmdebug('crypto_rand_secure pseudorandom generator is missing',$bytes);
		} else {
			//vmdebug('crypto_rand_secure pseudorandom bytes '.$bytes);
		}*/

		return $bytes;
	}

}
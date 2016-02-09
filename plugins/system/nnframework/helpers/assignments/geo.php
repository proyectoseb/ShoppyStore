<?php
/**
 * NoNumber Framework Helper File: Assignments: Geo
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

/**
 * Assignments: Geo
 */
class nnFrameworkAssignmentsGeo
{
	var $geo = null;

	/**
	 * passContinents
	 */
	function passContinents(&$parent, &$params, $selection = array(), $assignment = 'all')
	{
		if (!$geo = self::getGeo($params->service))
		{
			return $parent->pass(0, $assignment);
		}

		$selection = $parent->makeArray($selection);

		return $parent->passSimple($geo->continent, $selection, $assignment);
	}

	/**
	 * passCountries
	 */
	function passCountries(&$parent, &$params, $selection = array(), $assignment = 'all')
	{
		if (!$geo = self::getGeo($params->service))
		{
			return $parent->pass(0, $assignment);
		}

		$selection = $parent->makeArray($selection);

		return $parent->passSimple($geo->country, $selection, $assignment);
	}

	/**
	 * passRegions
	 */
	function passRegions(&$parent, &$params, $selection = array(), $assignment = 'all')
	{
		if (!$geo = self::getGeo($params->service))
		{
			return $parent->pass(0, $assignment);
		}

		$selection = $parent->makeArray($selection);
		$region = $geo->country . '-' . $geo->region;

		return $parent->passSimple($region, $selection, $assignment);
	}

	function getGeo($service)
	{
		if ($this->geo !== null)
		{
			return $this->geo;
		}

		require_once JPATH_PLUGINS . '/system/nnframework/helpers/functions.php';
		$func = new nnFrameworkFunctions;

		$ip = $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? '' : $_SERVER['REMOTE_ADDR'];

		switch ($service)
		{
			case 'geoplugin':
				if (!$geo = json_decode($func->getContents('http://www.geoplugin.net/json.gp?ip=' . $ip)))
				{
					$this->geo = false;

					return false;
				}

				if (isset($geo->geoplugin_status) && $geo->geoplugin_status == 404)
				{
					$this->geo = false;

					return false;
				}

				$this->geo = (object) array(
					'continent' => isset($geo->geoplugin_continentCode) ? $geo->geoplugin_continentCode : '',
					'country'   => isset($geo->geoplugin_countryCode) ? $geo->geoplugin_countryCode : '',
					'region'    => isset($geo->geoplugin_regionCode) ? $geo->geoplugin_regionCode : '',
				);
				break;

			case 'telize':
			default:
				if (!$geo = json_decode($func->getContents('http://www.telize.com/geoip/' . $ip)))
				{
					$this->geo = false;

					return false;
				}

				if (isset($geo->code))
				{
					$this->geo = false;

					return false;
				}

				$this->geo = (object) array(
					'continent' => isset($geo->continent_code) ? $geo->continent_code : '',
					'country'   => isset($geo->country_code) ? $geo->country_code : '',
					'region'    => isset($geo->region_code) ? $geo->region_code : '',
				);
				break;
		}

		return $this->geo;
	}
}

<?php
/**
 * NoNumber Framework Helper File: Assignments: IPs
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
 * Assignments: IPs
 */
class nnFrameworkAssignmentsIPs
{
	function passIPs(&$parent, &$params, $selection = array(), $assignment = 'all')
	{
		if (is_array($selection))
		{
			$selection = implode(',', $selection);
		}
		$selection = explode(',', str_replace(array(' ', "\r", "\n"), array('', '', ','), $selection));

		$pass = $this->checkIPList($selection);

		return $parent->pass($pass, $assignment);
	}

	function checkIPList($selection)
	{
		foreach ($selection as $range)
		{
			// Check next range if this one doesn't match
			if (!$this->checkIP($range))
			{
				continue;
			}

			// Match found, so return true!
			return true;
		}

		// No matches found, so return false
		return false;
	}

	function checkIP($range)
	{
		if (empty($range))
		{
			return false;
		}

		if (strpos($range, '-') !== false)
		{
			// Selection is an IP range
			return $this->checkIPRange($range);
		}

		// Selection is a single IP (part)
		return $this->checkIPPart($range);
	}

	function checkIPRange($range)
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		// Return if no IP address can be found (shouldn't happen, but who knows)
		if (empty($ip))
		{
			return false;
		}

		// check if IP is between or equal to the from and to IP range
		list($min, $max) = explode('-', trim($range), 2);

		// Return false if IP is smaller than the range start
		if ($ip < trim($min))
		{
			return false;
		}

		// make the range end value the maximum full IP it can be
		// So 127.0 becomes 127.0.255.255
		$max .= str_repeat('.255', 4 - count(explode('.', $max)));

		// Return false if IP is larger than the range end
		if ($ip > trim($max))
		{
			return false;
		}

		return true;
	}

	function checkIPPart($range)
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		// Return if no IP address can be found (shouldn't happen, but who knows)
		if (empty($ip))
		{
			return false;
		}

		$ip_parts = explode('.', $ip);
		$range_parts = explode('.', trim($range));

		// Trim the IP to the part length of the range
		$ip = implode('.', array_slice($ip_parts, 0, count($range_parts)));

		// Return false if ip does not match the range
		if ($range != $ip)
		{
			return false;
		}

		return true;
	}
}

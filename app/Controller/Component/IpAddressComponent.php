<?php
/**
 * IpAddress Component
 * Class used for working with IP addresses
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @link http://pgregg.com/blog/2009/04/php-algorithms-determining-if-an-ip-is-within-a-specific-range/
 */
class IpAddressComponent extends Component {

/**
 * Pad an ip address to 32 characters
 * In order to simplify working with IP addresses (in binary) and their
 * netmasks, it is easier to ensure that the binary strings are padded
 * with zeros out to 32 characters - IP addresses are 32 bit numbers
 *
 * @param string $dec the string to pad
 * @return string the 32 char long padded with 0 string
 */
	public static function decbin32($dec) {
		return str_pad(decbin($dec), 32, '0', STR_PAD_LEFT);
	}

/**
 * Tells if an ip address is part of a range of ips
 * This function takes 2 arguments, an IP address and a "range" in several
 * different formats
 *
 * Network ranges can be specified as:
 * 1. Regular ip 127.0.0.1
 * 2. Wildcard format: 1.2.3.*
 * 3. CIDR format: 1.2.3/24  OR  1.2.3.4/255.255.255.0
 * 4. Start-End IP format: 1.2.3.0-1.2.3.255
 *
 * The function will return true if the supplied IP is within the range.
 * Note little validation is done on the range inputs - it expects you to
 * use one of the above 3 formats.
 *
 * @param string $ip the IP Address
 * @param string $range the IP range in one of the supported format
 * @return bool true if in range, false otherwise
 */
	public static function inRange($ip, $range) {
		if ($ip == $range) {
			return true;
		}
		if (strpos($range, '/') !== false) {
			// $range is in IP/NETMASK format
			list($range, $netmask) = explode('/', $range, 2);
			if (strpos($netmask, '.') !== false) {
				// $netmask is a 255.255.0.0 format
				$netmask = str_replace('*', '0', $netmask);
				$netmaskDec = ip2long($netmask);
				return ((ip2long($ip) & $netmaskDec) == (ip2long($range) & $netmaskDec));
			} else {
				// $netmask is a CIDR size block
				// fix the range argument
				$x = explode('.', $range);
				$count = count($x);
				while ($count < 4) {
					$x[] = '0';
					$count = count($x);
				}
				list($a, $b, $c, $d) = $x;
				$range = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c,
					empty($d) ? '0' : $d);
				$rangeDec = ip2long($range);
				$ipDec = ip2long($ip);

				# Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
				#$netmaskDec = bindec(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));

				# Strategy 2 - Use math to create it
				$wildcardDec = pow(2, (32 - $netmask)) - 1;
				$netmaskDec = ~$wildcardDec;

				return (($ipDec & $netmaskDec) == ($rangeDec & $netmaskDec));
			}
		} else {
			// range might be 255.255.*.* or 1.2.3.0-1.2.3.255
			if (strpos($range, '*') !== false) { // a.b.*.* format
				// Just convert to A-B format by setting * to 0 for A and 255 for B
				$lower = str_replace('*', '0', $range);
				$upper = str_replace('*', '255', $range);
				$range = "$lower-$upper";
			}

			if (strpos($range, '-') !== false) { // A-B format
				list($lower, $upper) = explode('-', $range, 2);
				$lowerDec = (float)sprintf("%u", ip2long($lower));
				$upperDec = (float)sprintf("%u", ip2long($upper));
				$ipDec = (float)sprintf("%u", ip2long($ip));
				return (($ipDec >= $lowerDec) && ($ipDec <= $upperDec));
			}

			return false;
		}
	}
}
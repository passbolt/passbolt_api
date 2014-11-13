<?php
/**
 * Utility methods for which I could not find a better place
 *
 * @author Florian Kr�mer
 * @copyright 2012 Florian Kr�mer
 * @license MIT
 */
class FileStorageUtils {

/**
 * Gaufrette Vendor Classloader
 *
 * @param string $class Classname to be loaded
 * @return void
 */
	public static function gaufretteLoader($class) {
		$base = Configure::read('FileStorage.GaufretteLib');
		if (empty($base)) {
			$base = CakePlugin::path('FileStorage') . 'Vendor' . DS . 'Gaufrette' . DS . 'src' . DS;
		}

		$class = str_replace('\\', DS, $class);
		if (file_exists($base . $class . '.php')) {
			include ($base . $class . '.php');
		}
	}

/**
 * Return file extension from a given filename
 *
 * @param string
 * @return boolean string or false
 */
	public static function fileExtension($name) {
		$list = explode('.', $name);
		if (count($list) > 1) {
			$ext = $list[count($list) - 1];
			return $ext;
		}
		return false;
	}

/**
 * Builds a semi-random path based on a given string to avoid having thousands of files
 * or directories in one directory. This would result in a slowdown on most file systems.
 *
 * Works up to 5 level deep
 *
 * @throws InvalidArgumentException
 * @param mixed $string
 * @param integer $level 1 to 5
 * @return mixed
 */
	public static function randomPath($string, $level = 3) {
		if (!$string) {
			throw new \InvalidArgumentException('First argument is not a string!');
		}
		$string = crc32($string);

		$decrement = 0;
		$path = null;
		for ($i = 0; $i < $level; $i++) {
			$decrement = $decrement - 2;
			$path .= sprintf("%02d" . DS, substr(str_pad('', 2 * $level, '0') . $string, $decrement, 2));
		}
		return $path;
	}

/**
 * Helper method to trim last trailing slash in file path
 *
 * @param string $path Path to trim
 * @return string Trimmed path
 */
	public static function trimPath($path) {
		$len = strlen($path);
		if ($path[$len - 1] == '\\' || $path[$len - 1] == '/') {
			$path = substr($path, 0, $len - 1);
		}
		return $path;
	}

/**
 * Converts windows to linux pathes and vice versa
 *
 * @param string
 * @return string
 */
	public static function normalizePath($string) {
		if (DS == '\\') {
			return str_replace('/', '\\', $string);
		} else {
			return str_replace('\\', '/', $string);
		}
	}

/**
 * Method to normalize the annoying inconsistency of the $_FILE array structure
 *
 * @link http://www.php.net/manual/en/features.file-upload.multiple.php#109437
 * @return array Empty array if $_FILE is empty, if not normalize array of Filedata.{n}
 */
	public static function normalizeGlobalFilesArray($array = null) {
		if (empty($array)) {
			$array = $_FILES;
		}

		$newfiles = array();
		if (!empty($array)) {
			foreach ($array as $fieldname => $fieldvalue) {
				foreach ($fieldvalue as $paramname => $paramvalue) {
					foreach ((array)$paramvalue as $index => $value) {
						$newfiles[$fieldname][$index][$paramname] = $value;
					}
				}
			}
		}
		return $newfiles;
	}

}
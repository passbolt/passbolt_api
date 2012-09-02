<?php
/**
 * Gpg Key Model
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.GpgKey
 * @since        version 2.12.9
 */
class GpgKey extends AppModel {

	public $name = 'GpgKey';

	public $useTable = 'gpgKeys';

/**
 * Import a key in the keyring
 *
 * @param string $key 
 * @return void
 * @access public
 */
	public function import($key) {
		$tmp = APP . 'tmp' . DS . 'gpg' . DS . 'keys' . md5(uniqid(rand()));
		file_put_contents($tmp, $key);
		$cmd = 'gpg --import ' . $tmp;
		$cmd = escapeshellcmd($cmd);
		exec($cmd,$output);
		unlink($tmp);
		return true;
	}

/**
 * Remove a key from the keyring
 *
 * @param string $key 
 * @return void
 * @access public
 */
	public function remove($key) {
		$cmd = 'gpg --batch --delete-key --yes ' . $key;
		$cmd = escapeshellcmd($cmd);
		exec($cmd,$output);
		return $output;
	}

/**
 * Check the fingerprint of a key
 *
 * @param string $id 
 * @return mixed array or false if error
 * @access public
 */
	public function fingerprint($id) {
		$f = false;
		$cmd = 'gpg --fingerprint ' . $id;
		$cmd = escapeshellcmd($cmd);
		exec($cmd,$output);

		if (isset($output) && !empty($output)) {
			$f = $this->_deserializeFingerprint($output);
		}
		return $f;
	}

/**
 * Deserialize the fingerprint 
 * from gpg command line to a proper model object
 *
 * @param string $f command line output 
 * @return mixed array or false if error
 * @access protected
 */
	protected function _deserializeFingerPrint($o) {
		// parse first line
		// ex: pub   2048R/E513B181 2012-08-25
		preg_match('/pub   ([0-9]{4})([A-Z]{1})\/([A-Z0-9]{8}) ([0-9]{4}-[0-9]{2}-[0-9]{2})/', $o[0], $matches);
		if (count($matches) != 5) {
			return false;
		}
		$f['bits'] = $matches[1];
		if ($matches[2] == 'R') {
			$f['type'] = 'RSA';
		} elseif ($matches[2] == 'D') {
			$f['type'] = 'DSA';
		} else {
			return false; // unsupported or legacy
		}
		$f['key_id'] = $matches[3];
		$f['modified'] = $matches[4];

		// parse second line
		// ex: Key fingerprint = EA1B 5DDF 504D 669D B3DD  3B82 18A0 ED3D E513 B181
		preg_match('/      Key fingerprint = ([A-Z0-9\ ]{50})/', $o[1], $matches);
		if (count($matches) != 2) {
			return false;
		}
		$f['fingerprint'] = $matches[1];

		// parse third line
		// ex: uid Lisa <lisa@passbolt.com>
		$email = '[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@' .
			'(?:[-_a-z0-9][-_a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)';
		preg_match('/uid                  ([0-9\w\ ]+) <(' . $email . ')>/', $o[2], $matches);
		if (count($matches) != 3) {
			return false;
		}
		$f['uid'] = $matches[1] . ' <' . $matches[2] . '>';

		return $f;
	}
}

<?php
/**
 * GnuPG Component
 * Class used for encrypting / decrypting using gpg
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.Controller.Component.GpgComponent
 * @since				 version 2.12.9
 */
class GpgComponent extends Component {

/**
 * Encrypt a message for given reciptien function
 *
 * @param string $options 
 * @return void
 * @access public
 */
	public function encrypt($recipient, $data, $options) {
		$defaults = Configure::read('GPG');
		$options = am($defaults, $options);

		//preg_match('^/(always)/$',$options['trustModel']);

		$tmp = APP . 'tmp' . DS . 'gpg' . DS . 'encrypt' . md5(uniqid(rand()));
		$encrypted = $tmp . '.pgp';
		file_put_contents($tmp, $data);

		$use = 'gpg --trust-model ' . $options['trustModel'] . ' -r "' . $recipient .
			'" --out ' . $encrypted . ' --encrypt ' . $tmp;
		$a = `$use`;

		unlink($tmp);
		if (!file_exists($encrypted)) {
			return false;
		}

		$out = file_get_contents($encrypted);
		unlink($encrypted);

		return $out;
	}

/**
 * undocumented function
 *
 * @param string $file 
 * @return void
 * @access public

	function decrypt($file) {
		$tmp = APP . 'tmp' . DS . 'gpg' . DS . 'decrypt' . md5(uniqid(rand()));
		$use = 'gpg --output ' . $tmp . ' --decrypt ' . $file;
		$a = `$use`;
		if (!file_exists($tmp)) {
			return false;
		}
		$out = file_get_contents($tmp);
		unlink($tmp);
		return $out;
	}
 */
}

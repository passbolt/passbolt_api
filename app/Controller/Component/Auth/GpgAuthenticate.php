<?php
/**
 * GpgAuthenticate
 * Manages a GPG based authentication scheme
 *
 * @copyright 	(c) 2015-present Passbolt.com
 * @licence		GNU Public Licence v3 - www.gnu.org/licenses/gpl-3.0.en.html
 */
App::uses('BaseAuthenticate', 'Controller/Component/Auth');
App::uses('Gpgkey', 'Model');

require_once APP . 'Lib' . DS . 'Gpg' . DS . 'gpg.php';

class GpgAuthenticate extends BaseAuthenticate
{

    protected $_config;
    protected $_gpg;

    /**
     * Authenticate
     *
     * @param CakeRequest $request
     * @param CakeResponse $response
     * @return Array|false the user or false if authentication failed
     */
    public function authenticate(CakeRequest $request, CakeResponse $response)
    {
        // Init gpg object and load server key
        $this->_initKeyring();

        // Begin process by checking if the user exist and his key is valid
        $response->header('X-GPGAuth-Authenticated', 'false');
        $response->header('X-GPGAuth-Progress', 'stage0');
        $user = $this->_identifyUserWithFingerprint($request);
        if (!$user) {
            $response->header('X-GPGAuth-Error', 'true');
            return false;
        }

        // Step 0. Server authentication
        // The user is asking the server to identify itself by decrypting a token
        // that was encrypted by the client using the server public key
        if (isset($request->data['gpg_auth']['server_verify_token'])) {
            $nonce = $this->_gpg->decrypt(
                $request->data['gpg_auth']['server_verify_token'], $this->_config['serverKey']['passphrase']);

            // check if the nonce is in valid format to avoid decrypting and returning something sensitive
            list($version, $length, $uuid, $version2) = explode('|', $nonce);
            if ($version == $version2 && $version = 'gpgauthv1.3.0' && Common::isUuid($uuid) && $length == 36) {
                $response->header('X-GPGAuth-Verify-Response', $uuid);
            } else {
                $response->header('X-GPGAuth-Error', 'true');
                return false;
            }
        }

        // Stage 1.
        // The user request an authentication by claiming he owns a given public key
        // We therefore send an encrypted message that must be returned next time in order to verify
        $AuthenticationToken = Common::getModel('AuthenticationToken');
        if (!isset($request->data['gpg_auth']['user_token_result'])) {
            $this->header('X-GPGAuth-Progress', 'stage1');
            // set encryption and signature keys
            $this->_setUserKey($request->data['gpg_auth']['keyid'],$user);
            $this->_gpg->addsignkey(
                $this->_config['serverKey']['fingerprint'], $this->_config['serverKey']['passphrase']);

            // generate token
            $token = $AuthenticationToken->createToken($user['User']['id'], AuthenticationToken::UUID);
            $token = 'gpgauthv1.3.0|36|'. $token .'|gpgauthv1.3.0';

            // encrypt and sign and send
            $msg = $this->_gpg->encryptsign($token);
            $msg = quotemeta(urlencode($msg));
            $response->header('X-GPGAuth-User-Auth-Token', $msg);
            return false;
        }

        // Stage 2.
        // Check if the token provided at stage 1 have been decrypted and is still valid
        $result = $AuthenticationToken->checkTokenIsValid(
            $request->data['gpg_auth']['user_token_result'], $user['User']['id']);
        if (!$result) {
            // we tell the client via the headers that the process failed
            header('X-GPGAuth-Progress: stage2');
            $response->header('X-GPGAuth-Error', 'true');
            return false;
        }

        // Completed
        // we set the user to active and provide some success feedback
        User::setActive($user);
        $response->header('X-GPGAuth-Progress', 'complete');
        $response->header('X-GPGAuth-Authenticated', 'true');
        $response->header('X-GPGAuth-Refer', '/'); // @todo default or controller referer

        return true;
    }

    /**
     * Initialize GPG keyring and load the config
     * @throws CakeException if the config is missing or key is not set or not usable to decrypt
     */
    protected function _initKeyring() {
        // load base configuration
        $this->_config = Configure::read('Auth.gpg');
        if(!isset($this->_config['serverKey']['fingerprint'])) {
            throw new CakeException('The GnuPG config for the server is not available');
        }
        $keyid = $this->_config['serverKey']['fingerprint'];

        // check if the default key is set and available in gpg
        $this->_gpg = new gnupg();
        $info = $this->_gpg->keyinfo($keyid);
        if(empty($info)) {
            $this->_importServerKey($keyid);
        }

        // set the key to be used for decrypting
        if(!$this->_gpg->adddecryptkey($keyid,$this->_config['serverKey']['passphrase'])) {
            throw new CakeException('The GPG Server key defined in the config cannot be used to decrypt');
        }
    }

    /**
     * Load the server key to be used for the authentication scheme in the gpg keyring
     * @param string $keyid
     */
    protected function _importServerKey($keyid) {
        // @TODO handle via command line at install
        // try to init the key if it's not in the keyring
        $keydata = file_get_contents($this->_config['serverKey']['private']);
        $import_results = $this->_gpg->import($keydata);

        if(!$import_results || !isset($import_results['fingerprint'])) {
            throw new CakeException('The GnuPG key for the server could not be imported');
        }

        // check that the imported key match the fingerprint
        if($import_results['fingerprint'] != $keyid) {
            throw new CakeException('The GnuPG server key for the authentication scheme is not available');
        }
    }

    /**
     * Set user key for encryption and import it in the keyring if needed
     * @param string $keyid SHA1 fingerprint
     * @param array $user
     */
    protected function _setUserKey($keyid, $user) {
        $info = $this->_gpg->keyinfo($keyid);
        if(empty($info)) {
            if(!$this->_gpg->import($user['Gpgkey']['key'])) {
                throw new CakeException('The GnuPG key for the user could not be imported');
            }
            // check that the imported key match the fingerprint
            $info = $this->_gpg->keyinfo($keyid);
            if(empty($info)) {
                throw new CakeException('The GnuPG key for the user is not available or not working');
            }
        }
        $this->_gpg->addencryptkey($keyid);
    }

    /**
     * Find a user record from a public key fingerprint
     * @param CakeRequest $request
     * @return bool
     */
    protected function _identifyUserWithFingerprint(CakeRequest $request) {
        // First we check if we can get the user with the key fingerprint
        if (!isset($request->data['gpg_auth']['keyid'])) {
            return false;
        }
        $keyid = $request->data['gpg_auth']['keyid'];

        // validate the fingerprint format
        if (!Gpgkey::isValidFingerprint($keyid)) {
            return false;
        }

        // try to find the user
        $User = Common::getModel('User');
        $user = array('Gpgkey' => array(
            'fingerprint' => $keyid
        ));
        $user = $User->find('first', User::getFindOptions('User::GpgAuth', Role::USER, $user));
        if(empty($user)) {
            return false;
        }

        return $user;
    }

}
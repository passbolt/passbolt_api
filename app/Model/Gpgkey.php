<?php
/**
 * Gpg Key Model
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.Gpgkey
 * @since        version 2.12.9
 */

require_once APP . 'Vendor' . DS . 'openpgp-php' . DS . 'vendor' . DS . 'autoload.php';
require_once APP . 'Vendor' . DS . 'openpgp-php' . DS . 'lib' . DS . 'openpgp.php';
require_once APP . 'Vendor' . DS . 'openpgp-php' . DS . 'lib' . DS . 'openpgp_crypt_rsa.php';
require_once APP . 'Vendor' . DS . 'openpgp-php' . DS . 'lib' . DS . 'openpgp_crypt_symmetric.php';


class Gpgkey extends AppModel {

    public $name = 'Gpgkey';

    public $useTable = 'gpgkeys';

    public $belongsTo = array(
        'User',
    );

    /**
     * Get Marker from a key.
     *
     * @param $keyData
     *
     * @return mixed
     */
    public function getMarker($keyData) {
        $isMarker = preg_match('/-(BEGIN )*([A-Z0-9 ]+)-/', $keyData, $values);
        if (!$isMarker || !isset($values[2])) {
            return false;
        }
        return $values[2];
    }

    /**
     * Check the fingerprint of a key
     *
     * @param string $fingerprint
     * @return mixed array or false if error
     * @access public
     */
    public function info($keyData) {
        // Try to unarmor the key.
        // If it cannot, means the key is in the wrong format.
        $marker = $this->getMarker($keyData);
        if (!$marker) {
            return false;
        }
        $keyUnarmored = OpenPGP::unarmor($keyData, $marker);
        if ($keyUnarmored == false) {
            // Key in wrong format, we return false.
            return false;
        }

        // Get OpenPGP_Message.
        $msg = OpenPGP_Message::parse($keyUnarmored);
        // Get Public key.
        $publicKey = OpenPGP_PublicKeyPacket::parse($keyUnarmored);
        // Get Packets for public key.
        $publicKeyPacket = $msg->packets[0];
        // Get self signatures.
        $self_signatures = $publicKeyPacket->self_signatures($msg);
        // Get userId.
        $userIds = array();
        foreach($msg->signatures() as $signatures) {
            foreach($signatures as $signature) {
                if ($signature instanceof OpenPGP_UserIDPacket) {
                    $userIds[] = sprintf('%s', $signature);
                }
            }
        }

        // Build information array.
        $i = array(
            'fingerprint' => $publicKeyPacket->fingerprint(),
            'bits' => OpenPGP::bitlength($self_signatures[0]->data[0]),
            'type' => $self_signatures[0]->key_algorithm_name(),
            'key_id' => $publicKeyPacket->key_id,
            'key_created' => $publicKey->timestamp,
            'uid' => $userIds[0],
            'expires' => $publicKeyPacket->expires($msg),
        );
        return $i;
    }

    /**
     * Get the validation rules upon context
     *
     * @param string case (optional) The target validation case if any.
     * @return array CakePHP validation rules
     */
    public static function getValidationRules($case = 'default') {
        $default = array(
            'id' => array(
                'uuid' => array(
                    'rule' => 'uuid',
                    'required' => 'update',
                    'message' => __('Uuid must be provided and in correct format'),
                )
            ),
            'user_id' => array(
                'uuid' => array(
                    'rule' => 'uuid',
                    'required' => 'create',
                    'allowEmpty' => false,
                    'message' => __('Id must be in correct format'),
                ),
                'exist' => array(
                    'rule' => array('userExists', null),
                    'message' => __('The user id provided does not exist')
                ),
            ),
            'key' => array(
                'importable' => array(
                    'rule'    => array('checkKeyIsImportable', null),
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => __('The key provided is not in the right format, and couldn\'t be imported'),
                ),
            ),
            'bits' => array(
                'rule'    => 'numeric',
                'required' => false,
                'message' => __('The number of bits should be specified in a numeric format'),
            ),
            'uid' => array(
                'format' => array(
                    'rule'    => array('checkUid', null),
                    'required' => false,
                    'message' => __('The uid uses incorrect characters'),
                ),
            ),
            'key_id' => array(
                'format' => array(
                    'rule'    => '/^[A-Z0-9]{8}$/',
                    'required' => false,
                    'message' => __('The key id has an incorrect format'),
                ),
            ),
            'fingerprint' => array(
                'format' => array(
                    'rule'    => '/^[A-Z0-9]{40}$/',
                    'required' => 'create',
                    'message' => __('The fingerprint has an incorrect format'),
                    'allowEmpty' => false,
                ),
            ),
            'type' => array(
                'format' => array(
                    'rule'    => array('checkTypeExist', null),
                    'message' => __('The type uses an incorrect format'),
                    'allowEmpty' => true,
                ),
            ),
            'expires' => array(
                'is_date' => array(
                    'rule'    => '/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/i',
                    'message' => __('The expiring date has an incorrect format'),
                    'allowEmpty' => true,
                ),
                'is_in_future' => array(
                    'rule' => array('checkExpireIsInFuture', null),
                    'message' => __('The key should expire in future.'),
                ),
            ),
            'key_created' => array(
                'is_date' => array(
                    'rule'    => '/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/i',
                    'message' => __('The key creation date has an incorrect format'),
                    'allowEmpty' => false,
                ),
                'is_in_past' => array(
                    'rule' => array('checkCreatedIsInPast', null),
                    'message' => __('The key should have been created in the past.'),
                ),
            ),

        );
        switch ($case) {
            default:
            case 'default':
                $rules = $default;
                break;
        }
        return $rules;
    }

    /**
     * Check if a user with same id exists
     * @param $check
     * @return bool
     */
    public function userExists($check) {
        if ($check['user_id'] == null) {
            return false;
        } else {
            $exists = $this->User->find('count', array(
                'conditions' => array('User.id' => $check['user_id']),
                'recursive' => -1
            ));
            return $exists > 0;
        }
    }

    /**
     * Check if a key can be imported
     * @param $check
     * @return bool
     */
    public function checkKeyIsImportable($check) {
        if ($check['key'] == null) {
            return false;
        } else {
            $importable = $this->info($check['key']);
            return $importable;
        }
    }

    /**
     * Check if a key can be imported
     * @param $check
     * @return bool
     */
    public function checkExpireIsInFuture($check) {
        if ($check['expires'] == null) {
            return false;
        } else {
            $expire = strtotime($check['expires']);
            $now = time();
            $inFuture = $now < $expire;
            return $inFuture;
        }
    }

    /**
     * Check if a key can be imported
     * @param $check
     * @return bool
     */
    public function checkCreatedIsInPast($check) {
        if ($check['key_created'] == null) {
            return false;
        } else {
            $created = strtotime($check['key_created']);
            $now = time();
            $inPast = $now > $created;
            return $inPast;
        }
    }

    /**
     * Check uid (according to gpg rfc).
     * @param $check
     * @return bool
     */
    public function checkUid($check) {
        if ($check['uid'] == null) {
            return false;
        } else {
            $valid = false;
            try {
                $userIdPacket = new OpenPGP_UserIDPacket($check['uid']);
            }
            catch (Exception $e) {
                return false;
            }
            $valid = ($check['uid'] == sprintf('%s', $userIdPacket));
            return $valid;
        }
    }

    /**
     * Check type exists (according to gpg rfc).
     * @param $check
     * @return bool
     */
    public function checkTypeExist($check) {
        if ($check['type'] == null) {
            return false;
        } else {
            $supported = array_search($check['type'], OpenPGP_PublicKeyPacket::$algorithms);
            return $supported;
        }
    }


    /**
     * Analyze key before validating it, and extract key information.
     *
     * @param array $options
     *
     * @return bool
     */
    public function beforeValidate($options = array()) {
        if (!empty($this->data['Gpgkey']['key']) &&
            empty($this->data['Gpgkey']['fingerprint'])
        ) {
            $data = $this->buildGpgkeyDataFromKey($this->data['Gpgkey']['key']);
            $this->data['Gpgkey'] = array_merge($this->data['Gpgkey'], $data['Gpgkey']);
        }
        return true;
    }

    /**
     * Analyze key before saving it, and extract key information.
     *
     * @param array $options
     *
     * @return bool
     */
    public function beforeSave($options = array()) {
        if (!empty($this->data['Gpgkey']['key']) &&
            empty($this->data['Gpgkey']['fingerprint'])
        ) {
            $data = $this->buildGpgkeyDataFromKey($this->data['Gpgkey']['key']);
            $this->data['Gpgkey'] = array_merge($this->data['Gpgkey'], $data['Gpgkey']);
        }
        return true;
    }

    /**
     * Build data array from a key.
     *
     * @param $key
     *
     * @return mixed
     */
    public function buildGpgkeyDataFromKey($key) {
        $info = $this->info($key);
        if ($info) {
            $data['Gpgkey'] = array_merge (
                array(
                    'key' => $key
                ),
                $info
            );
            if (!empty ($data['Gpgkey']['expires'])) {
                $data['Gpgkey']['expires'] = date('Y-m-d H:i:s', $data['Gpgkey']['expires']);
            }
            if (!empty ($data['Gpgkey']['key_created'])) {
                $data['Gpgkey']['key_created'] = date('Y-m-d H:i:s', $data['Gpgkey']['key_created']);
            }
        }
        else {
            $data['Gpgkey']['key'] = $key;
        }
        return $data;
    }

    /**
     * Return the find conditions to be used for a given context.
     *
     * @param null|string $case The target case.
     * @param null|string $role The user role.
     * @param null|array $data (optional) Optional data to build the find conditions.
     * @return array
     */
    public static function getFindConditions($case = 'view', $role = Role::ANONYMOUS, $data = null) {
        switch ($case) {
            case 'index':
                $conditions = array('Gpgkey.deleted' => 0);
                if (isset($data['modified_after'])) {
                    $conditions['Gpgkey.modified >='] = $data['modified_after'];
                }
                $conditions = array('conditions' => $conditions);
                break;
            case 'view':
                $conditions = array('conditions' => array('Gpgkey.deleted' => 0, 'Gpgkey.user_id' => $data['Gpgkey.user_id']));
                break;
            default:
                $conditions = array('conditions' => array());
                break;
        }
        return $conditions;
    }

    /**
     * Return the list of field to fetch for given context.
     *
     * @param string $case context ex: login, activation
     * @return array
     */
    public static function getFindFields($case = 'view', $role = Role::USER) {
        switch ($case) {
            case 'view':
            case 'index':
                $fields = array('fields' => array(
                    'user_id',
                    'key',
                    'bits',
                    'uid',
                    'key_id',
                    'fingerprint',
                    'type', 'expires',
                    'modified'
                ));
                break;
            case 'delete':
                $fields = array('fields' => array(
                    'deleted'
                ));
                break;
            case 'save':
                $fields = array('fields' => array(
                    'user_id',
                    'key',
                    'bits',
                    'uid',
                    'key_id',
                    'fingerprint',
                    'type',
                    'expires',
                    'parent_id',
                    'key_created'
                ));
                break;
        }
        return $fields;
    }

    static public function isValidFingerprint($fingerprint) {
        // we expect a SHA1 fingerprint
        $pattern = '/[A-Fa-f0-9]{40}/';
        return preg_match($pattern, $fingerprint);
    }
}

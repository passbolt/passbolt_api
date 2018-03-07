<?php
namespace Passbolt\WebInstaller\Form;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Network\Exception\BadRequestException;
use Cake\Utility\Hash;

use Cake\Validation\Validator;

class GpgKeyImportForm extends Form
{
    /**
     * GpgKey generate configuration schema.
     * @param Schema $schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('armored_key', 'string');
    }

    /**
     * Validation rules.
     * @param Validator $validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('armored_key', 'create', __('An armored key is required.'))
            ->notEmpty('armored_key', __('An armored key is required.'))
            ->ascii('armored_key', __('The armored key provided is not a valid ascii string.'));

        return $validator;
    }

    /**
     * Execute implementation.
     * @param array $data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }

    /**
     * Export armored keys in the config folder based on the fingerprint provided.
     * @param $fingerprint
     */
    public function exportArmoredKeys($fingerprint) {
        $gpgKeyGenerateForm = new GpgKeyGenerateForm();
        return $gpgKeyGenerateForm->exportArmoredKeys($fingerprint);
    }

    /**
     * Generate a key pair using system GPG binary.
     * @param $keyData
     * @return string
     */
    public function importKey($keyData) {

    }
}
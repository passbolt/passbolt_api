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
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('armored_key', 'string');
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
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
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }

    /**
     * Export armored keys in the config folder based on the fingerprint provided.
     * @param string $fingerprint key fingerprint
     * @return void
     */
    public function exportArmoredKeys($fingerprint)
    {
        $gpgKeyGenerateForm = new GpgKeyGenerateForm();
        $gpgKeyGenerateForm->exportArmoredKeys($fingerprint);
    }
}

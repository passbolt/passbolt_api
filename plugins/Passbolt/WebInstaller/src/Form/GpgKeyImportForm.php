<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
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

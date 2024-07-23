<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.9.0
 */
namespace Passbolt\SmtpSettings\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class CustomSslOptionsForm extends Form
{
    /**
     * SSL options schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('sslVerifyPeer', ['type' => 'boolean'])
            ->addField('sslVerifyPeerName', ['type' => 'boolean'])
            ->addField('sslAllowSelfSigned', ['type' => 'boolean'])
            ->addField('sslCafile', ['type' => 'string']);
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->boolean('sslVerifyPeer', __('The sslVerifyPeer configuration should be a boolean.'));

        $validator
            ->boolean('sslVerifyPeerName', __('The sslVerifyPeerName configuration should be a boolean.'));

        $validator
            ->boolean('sslAllowSelfSigned', __('The sslAllowSelfSigned configuration should be a boolean.'));

        $validator
            ->allowEmptyString('sslCafile')
            ->add('sslCafile', 'nullOrString', [
                'rule' => function ($value) {
                    return $value === null || is_string($value);
                },
                'message' => __('The sslCafile configuration should be NULL or string.'),
            ]);

        return $validator;
    }
}

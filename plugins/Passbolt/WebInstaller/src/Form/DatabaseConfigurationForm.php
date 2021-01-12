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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class DatabaseConfigurationForm extends Form
{
    /**
     * Path of the ini config file.
     * An ini config file can be placed in tmp and used to pre-populate the database configuration form.
     */
    public const CONFIG_FILE_PATH = CONFIG . 'db_credentials.ini';

    /**
     * Database configuration schema.
     *
     * @param \Cake\Form\Schema $schema shchema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('host', 'string')
            ->addField('port', ['type' => 'string'])
            ->addField('username', ['type' => 'string'])
            ->addField('password', ['type' => 'string'])
            ->addField('database', ['type' => 'string']);
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('host', 'create', __('A host name is required.'))
            ->notEmptyString('host', __('A host name is required.'))
            ->utf8('host', __('The host is not a valid utf8 string.'));

        $validator
            ->requirePresence('port', 'create', __('A port number is required.'))
            ->numeric('port', __('Port number should be numeric'))
            ->range('port', [0, 65535], __('Port should be between 0 and 65535'));

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->notEmptyString('username', __('A username is required.'))
            ->utf8('username', __('The username is not a valid utf8 string.'));

        $validator
            ->allowEmptyString('password')
            ->add('password', 'no_quotes', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return true;
                    }

                    return strpos($value, '"') === false && strpos($value, "'") === false;
                },
                'message' => __('The password cannot contain quotes.'),
            ])
            ->utf8('password', __('The host is not a valid utf8 string.'));

        $validator
            ->requirePresence('database', 'create', __('A database is required.'))
            ->notEmptyString('database', __('A database is required.'))
            ->utf8('database', __('The database is not a valid utf8 string.'))
            ->add('database', 'no_dashes', [
                'rule' => function ($value, $context) {
                    return strpos($value, '-') === false;
                },
                'message' => __('The database name cannot contain dashes.'),
            ]);

        return $validator;
    }

    /**
     * Execute implementation.
     *
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }
}

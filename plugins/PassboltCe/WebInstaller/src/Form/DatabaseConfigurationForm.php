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

use Cake\Database\Driver\Mysql;
use Cake\Database\Driver\Postgres;
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
     * Drivers supported by passbolt on installation
     */
    public const ALLOWED_DRIVERS = [
        Mysql::class,
        Postgres::class,
    ];

    /**
     * Database configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('driver', 'string')
            ->addField('host', 'string')
            ->addField('port', ['type' => 'string'])
            ->addField('username', ['type' => 'string'])
            ->addField('password', ['type' => 'string'])
            ->addField('database', ['type' => 'string'])
            ->addField('schema', ['type' => 'string']);
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
            ->requirePresence('driver', 'create', __('A driver name is required.'))
            ->notEmptyString('driver', __('The driver name should not be empty.'))
            ->inList('driver', self::ALLOWED_DRIVERS, __(
                'The database driver should be one of the following: {0}.',
                implode(', ', self::ALLOWED_DRIVERS)
            ));

        $validator
            ->requirePresence('host', 'create', __('A host name is required.'))
            ->notEmptyString('host', __('The host name should not be empty.'))
            ->utf8('host', __('The host name should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('port', 'create', __('A port number is required.'))
            ->numeric('port', __('The port number should be numeric.'))
            ->range('port', [0, 65535], __('The port number should be between {0} and {1}.', '0', '65535'));

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->notEmptyString('username', __('The username should not be empty.'))
            ->utf8('username', __('The username should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('password')
            ->add('password', 'no_quotes', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return true;
                    }

                    return strpos($value, '"') === false && strpos($value, "'") === false;
                },
                'message' => __('The password should not contain quotes.'),
            ])
            ->utf8('password', __('The password should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('database', 'create', __('A database name is required.'))
            ->notEmptyString('database', __('The database name should not be empty.'))
            ->utf8('database', __('The database name should be a valid BMP-UTF8 string.'))
            ->add('database', 'no_dashes', [
                'rule' => function ($value, $context) {
                    return strpos($value, '-') === false;
                },
                'message' => __('The database name should not contain dashes.'),
            ]);

        $validator
            ->requirePresence(
                'schema',
                function ($data) {
                    return $this->isDriverPostgres($data);
                },
                __('The schema is required on PostgreSQL')
            )
            ->utf8('schema', __('The schema should be a valid BMP-UTF8 string.'));

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $data = $this->sanitizeData($data);

        return parent::execute($data, $options);
    }

    /**
     * @param array $data data to sanitize
     * @return array|null[]
     */
    private function sanitizeData(array $data): array
    {
        $sanitizedData = [
            'driver' => $data['driver'] ?? null,
            'host' => $data['host'] ?? null,
            'port' => $data['port'] ?? null,
            'username' => $data['username'] ?? null,
            'password' => $data['password'] ?? null,
            'database' => $data['database'] ?? null,
        ];
        if ($this->isDriverPostgres($data)) {
            $sanitizedData['schema'] = $data['schema'] ?? null;
            $sanitizedData['encoding'] = $data['encoding'] ?? 'utf8';
        }

        return $sanitizedData;
    }

    /**
     * @param array $data Form data
     * @return bool
     */
    private function isDriverPostgres(array $data): bool
    {
        return isset($data['driver']) && $data['driver'] === Postgres::class;
    }
}

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
 * @since         5.14.0
 */

namespace App\Form\Users;

use Cake\Form\Form;
use Cake\Validation\Validator;

class UsersEditForm extends Form
{
    protected array $allowedKeys = [
        'id',
        'role_id',
        'disabled',
        'profile' => [
            'first_name',
            'last_name',
            'avatar' => [
                'file',
            ],
        ],
    ];

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('id')
            ->uuid('id', 'The user identifier should be a valid UUID.');

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $data = $this->sanitizeData($data, $this->allowedKeys);

        return parent::execute($data, $options);
    }

    /**
     * @param array $data Data to sanitize
     * @return array
     */
    protected function sanitizeData(array $data, array $allowedKeys): array
    {
        $sanitizedData = [];

        foreach ($allowedKeys as $key => $allowed) {
            if (is_int($key)) {
                if (array_key_exists($allowed, $data)) {
                    $sanitizedData[$allowed] = $data[$allowed];
                }
                continue;
            }

            if (!array_key_exists($key, $data) || !is_array($data[$key])) {
                continue;
            }

            if (array_is_list($allowed)) {
                $sanitizedData[$key] = array_intersect_key(
                    $data[$key],
                    array_flip($allowed),
                );
            } else {
                $sanitizedData[$key] = $this->sanitizeData($data[$key], $allowed);
            }
        }

        return $sanitizedData;
    }
}

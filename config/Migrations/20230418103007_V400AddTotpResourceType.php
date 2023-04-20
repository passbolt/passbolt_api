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
 * @since         3.0.0
 */
// @codingStandardsIgnoreStart
use Cake\I18n\FrozenTime;
use Migrations\AbstractMigration;

class V400AddTotpResourceType extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $resourceTypesTable = $this->table('resource_types');
        $data = [
            [
                /**
                 * Standalone TOTP
                 */
                'id' => \App\Utility\UuidFactory::uuid(),
                'slug' => \App\Model\Entity\ResourceType::SLUG_STANDALONE_TOTP,
                'name' => 'Standalone TOTP',
                'description' => 'A resource with standalone TOTP fields.',
                'definition' => self::getTotpStandaloneDefinition(),
                'created' => (new FrozenTime())->toDateTimeString(),
                'modified' => (new FrozenTime())->toDateTimeString(),
            ],
            [
                /**
                 * TOTP with password & description
                 */
                'id' => \App\Utility\UuidFactory::uuid(),
                'slug' => \App\Model\Entity\ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP,
                'name' => 'Password, Description and TOTP',
                'description' => 'A resource with encrypted password, description and TOTP fields.',
                'definition' => self::getTotpWithPasswordDescriptionDefinition(),
                'created' => (new FrozenTime())->toDateTimeString(),
                'modified' => (new FrozenTime())->toDateTimeString(),
            ],
        ];

        $resourceTypesTable->insert($data)->saveData();
    }

    private static function getTotpStandaloneDefinition(): string
    {
        return json_encode([
            'resource' => [
                'type' => 'object',
                'required' => ['name'],
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'maxLength' => 255
                    ],
                    'uri' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 1024],
                            ['type' => 'null'],
                        ],
                    ],
                ]
            ],
            'secret' => [
                'type' => 'object',
                'required' => ['totp'],
                'properties' => [
                    'totp' => [
                        'type' => 'object',
                        'required' => ['type', 'secret_key', 'digits', 'algorithm'],
                        'properties' => [
                            'algorithm' => [
                                'type' => 'string',
                                'minLength' => 4,
                                'maxLength' => 6,
                            ],
                            'secret_key' => [
                                'type' => 'string',
                                'maxLength' => 1024,
                            ],
                            'digits' => [
                                'type' => 'number',
                                'minimum' => 6,
                                'exclusiveMaximum' => 9,
                            ],
                            'period' => [
                                'type' => 'number',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private static function getTotpWithPasswordDescriptionDefinition(): string
    {
        return json_encode([
            'resource' => [
                'type' => 'object',
                'required' => ['name'],
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ],
                    'username' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 255],
                            ['type' => 'null'],
                        ],
                    ],
                    'uri' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 1024],
                            ['type' => 'null'],
                        ],
                    ],
                ]
            ],
            'secret' => [
                'type' => 'object',
                'required' => ['password', 'totp'],
                'properties' => [
                    'password' => [
                        'type' => 'string',
                        'maxLength' => 4096,
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 10000],
                            ['type' => 'null'],
                        ],
                    ],
                    'totp' => [
                        'type' => 'object',
                        'required' => ['type', 'secret_key', 'digits', 'algorithm'],
                        'properties' => [
                            'algorithm' => [
                                'type' => 'string',
                                'minLength' => 4,
                                'maxLength' => 6,
                            ],
                            'secret_key' => [
                                'type' => 'string',
                                'maxLength' => 1024,
                            ],
                            'digits' => [
                                'type' => 'number',
                                'minimum' => 6,
                                'exclusiveMaximum' => 9,
                            ],
                            'period' => [
                                'type' => 'number',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
// @codingStandardsIgnoreEnd

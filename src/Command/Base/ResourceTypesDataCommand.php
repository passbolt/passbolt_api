<?php
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
namespace PassboltTestData\Command\Base;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataCommand;

class ResourceTypesDataCommand extends DataCommand
{
    public $entityName = 'ResourceTypes';

    /**
     * Get the roles data
     *
     * @return array
     */
    public function getData()
    {
        return [
            [
                'id' => UuidFactory::uuid('resource-types.id.password-string'),
                'slug' => 'password-string',
                'name' => 'Simple password',
                'description' => 'The original passbolt resource type, where the secret is a non empty string.',
                'definition' => json_encode([
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
                                    [
                                        'type' => 'string',
                                        'maxLength' => 255,
                                    ],
                                    [
                                        'type' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'secret' => [
                        'type' => 'string',
                        'maxLength' => 4096
                    ],
                ]),
                'created' => date("Y-m-d H:i:s"),
                'modified' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => UuidFactory::uuid('resource-types.id.password-and-description'),
                'slug' => 'password-and-description',
                'name' => 'Password with description',
                'description' => 'A resource with the password and the description encrypted.',
                'definition' => json_encode([
                    'resource' => [
                        'type' => 'object',
                        'required' => [
                            'name',
                        ],
                        'properties' => [
                            'name' => [
                                'type' => 'string',
                                'maxLength' => 255,
                            ],
                            'username' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'maxLength' => 255,
                                    ],
                                    [
                                        'type' => 'null',
                                    ],
                                ],
                            ],
                            'uri' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'maxLength' => 1024,
                                    ],
                                    [
                                        'type' => 'null',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'secret' => [
                        'type' => 'object',
                        'required' => [
                            'password',
                        ],
                        'properties' => [
                            'password' => [
                                'type' => 'string',
                                'maxLength' => 4096,
                            ],
                            'description' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'maxLength' => 10000,
                                    ],
                                    [
                                        'type' => 'null',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]),
                'created' => date("Y-m-d H:i:s"),
                'modified' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => UuidFactory::uuid('resource-types.id.totp'),
                'slug' => 'totp',
                'name' => 'Standalone TOTP',
                'description' => 'A resource with standalone TOTP fields.',
                'definition' => json_encode([
                    'resource' => [
                        'type' => 'object',
                        'required' => [
                            'name',
                        ],
                        'properties' => [
                            'name' => [
                                'type' => 'string',
                                'maxLength' => 255,
                            ],
                            'uri' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'maxLength' => 1024,
                                    ],
                                    [
                                        'type' => 'null',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'secret' => [
                        'type' => 'object',
                        'required' => [
                            'totp',
                        ],
                        'properties' => [
                            'totp' => [
                                'type' => 'object',
                                'required' => ['secret_key', 'digits', 'algorithm'],
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
                ]),
                'created' => date("Y-m-d H:i:s"),
                'modified' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => UuidFactory::uuid('resource-types.id.password-description-totp'),
                'slug' => 'password-description-totp',
                'name' => 'Password, Description and TOTP',
                'description' => 'A resource with encrypted password, description and TOTP fields.',
                'definition' => json_encode([
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
                        ],
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
                                'required' => ['secret_key', 'digits', 'algorithm'],
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
                ]),
                'created' => date("Y-m-d H:i:s"),
                'modified' => date("Y-m-d H:i:s"),
            ],
            [
                'v5-password-string' => [
                    'id' => UuidFactory::uuid('resource-types.id.v5-password-string'),
                    'slug' => ResourceType::SLUG_V5_PASSWORD_STRING,
                    'name' => 'Simple Password (Deprecated)',
                    'description' => 'The original passbolt resource type, kept for backward compatibility reasons.',
                    'definition' => json_encode([]),
                    'created' => date("Y-m-d H:i:s"),
                    'modified' => date("Y-m-d H:i:s"),
                ],
                'v5-default' => [
                    'id' => UuidFactory::uuid('resource-types.id.v5-default'),
                    'slug' => ResourceType::SLUG_V5_DEFAULT,
                    'name' => 'Default resource type',
                    'description' => 'The new default resource type introduced with v5.',
                    'definition' => json_encode([]),
                    'created' => date("Y-m-d H:i:s"),
                    'modified' => date("Y-m-d H:i:s"),
                ],
                'v5-totp-standalone' => [
                    'id' => UuidFactory::uuid('resource-types.id.v5-totp-standalone'),
                    'slug' => ResourceType::SLUG_V5_TOTP_STANDALONE,
                    'name' => 'Standalone TOTP',
                    'description' => 'The new standalone TOTP resource type introduced with v5.',
                    'definition' => json_encode([]),
                    'created' => date("Y-m-d H:i:s"),
                    'modified' => date("Y-m-d H:i:s"),
                ],
                'v5-default-with-totp' => [
                    'id' => UuidFactory::uuid('resource-types.id.v5-default-with-totp'),
                    'slug' => ResourceType::SLUG_V5_DEFAULT_WITH_TOTP,
                    'name' => 'Default resource type with TOTP',
                    'description' => 'The new default resource type with a TOTP introduced with v5.',
                    'definition' => json_encode([]),
                    'created' => date("Y-m-d H:i:s"),
                    'modified' => date("Y-m-d H:i:s"),
                ],
            ]
        ];
    }
}

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
 * @since         4.0.0
 */
namespace Passbolt\ResourceTypes\Model\Definition;

class SlugDefinition
{
    /**
     * @return string|false
     */
    public static function passwordString(): string|false
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
                'type' => 'object',
                'required' => ['password'],
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
                                'type' => null,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return string|false
     */
    public static function passwordAndDescription(): string|false
    {
        return json_encode([
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
        ]);
    }

    /**
     * @return string|false
     */
    public static function totp(): string|false
    {
        return json_encode([
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
        ]);
    }

    /**
     * @return string|false
     */
    public static function passwordDescriptionTotp(): string|false
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
        ]);
    }

    /**
     * @return string|false
     */
    public static function customFields(): string|false
    {
        return json_encode([
            'resource' => [
                'type' => 'object',
                'required' => [
                    'name',
                    'custom_fields',
                ],
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ],
                    'uris' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'maxLength' => 1024,
                            'nullable' => true,
                        ],
                    ],
                    'description' => [
                        'type' => 'string',
                        'maxLength' => 10000,
                        'nullable' => true,
                    ],
                    'custom_fields' => [
                        'type' => 'object',
                        'required' => ['items'],
                        'properties' => [
                            'items' => [
                                'type' => 'array',
                                'maxItems' => 128,
                                'items' => [
                                    'type' => 'object',
                                    'required' => ['id', 'type'],
                                    'properties' => [
                                        'id' => [
                                            'type' => 'string',
                                            'format' => 'uuid',
                                        ],
                                        'type' => [
                                            'type' => 'string',
                                            'enum' => ['text', 'password', 'boolean', 'number', 'uri'],
                                        ],
                                        'metadata_key' => [
                                            'type' => 'string',
                                            'maxLength' => 255,
                                            'nullable' => true,
                                        ],
                                        'metadata_value' => [
                                            'anyOf' => [
                                                ['type' => 'string', 'maxLength' => 5000],
                                                ['type' => 'number'],
                                                ['type' => 'boolean'],
                                            ],
                                            'nullable' => true,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'secret' => [
                'type' => 'object',
                'required' => [
                    'custom_fields',
                ],
                'properties' => [
                    'custom_fields' => [
                        'type' => 'object',
                        'required' => ['items'],
                        'properties' => [
                            'items' => [
                                'type' => 'array',
                                'maxItems' => 128,
                                'items' => [
                                    'type' => 'object',
                                    'required' => ['id', 'type'],
                                    'properties' => [
                                        'id' => [
                                            'type' => 'string',
                                            'format' => 'uuid',
                                        ],
                                        'type' => [
                                            'type' => 'string',
                                            'enum' => ['text', 'password', 'boolean', 'number', 'uri'],
                                        ],
                                        'secret_key' => [
                                            'type' => 'string',
                                            'maxLength' => 255,
                                            'nullable' => true,
                                        ],
                                        'secret_value' => [
                                            'anyOf' => [
                                                ['type' => 'string', 'maxLength' => 5000],
                                                ['type' => 'number'],
                                                ['type' => 'boolean'],
                                            ],
                                            'nullable' => true,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}

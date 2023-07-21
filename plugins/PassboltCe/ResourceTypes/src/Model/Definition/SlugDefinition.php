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
     * @return string
     */
    public static function passwordString(): string
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
     * @return string
     */
    public static function passwordAndDescription(): string
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
     * @return string
     */
    public static function totp(): string
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
     * @return string
     */
    public static function passwordDescriptionTotp(): string
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
}

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
 * @since         5.4.0
 */

namespace Passbolt\ResourceTypes\Test\TestCase\Model\Definition;

use Passbolt\ResourceTypes\Model\Definition\SlugDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Passbolt\ResourceTypes\Model\Definition\SlugDefinition
 */
class SlugDefinitionTest extends TestCase
{
    public function testPasswordString(): void
    {
        $expected = json_encode([
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
            'secret' => [
                'type' => 'string',
                'maxLength' => 4096,
            ],
        ]);

        $result = SlugDefinition::passwordString();
        $this->assertEquals($expected, $result);
    }

    public function testPasswordAndDescription(): void
    {
        $expected = json_encode([
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

        $result = SlugDefinition::passwordAndDescription();
        $this->assertEquals($expected, $result);
    }

    public function testTotp(): void
    {
        $expected = json_encode([
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

        $result = SlugDefinition::totp();
        $this->assertEquals($expected, $result);
    }

    public function testPasswordDescriptionTotp(): void
    {
        $expected = json_encode([
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

        $result = SlugDefinition::passwordDescriptionTotp();
        $this->assertEquals($expected, $result);
    }

    public function testV5PasswordString(): void
    {
        $expected = json_encode([
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
                    'uris' => [
                        'type' => 'array',
                        'items' => [
                            'anyOf' => [
                                ['type' => 'string', 'maxLength' => 1024],
                                ['type' => 'null'],
                            ],
                        ],
                        'maxItems' => 32,
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 10000],
                            ['type' => 'null'],
                        ],
                    ],
                    'icon' => [
                        'type' => 'object',
                        'required' => [],
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'enum' => ['keepass-icon-set', 'passbolt-icon-set'],
                            ],
                            'value' => [
                                'type' => 'integer',
                                'minimum' => 0,
                            ],
                            'background_color' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'pattern' => '^#(?:[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$',
                                    ],
                                    ['type' => 'null'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'secret' => [
                'type' => 'string',
                'maxLength' => 4096,
            ],
        ]);

        $result = SlugDefinition::v5PasswordString();
        $this->assertEquals($expected, $result);
    }

    public function testV5Default(): void
    {
        $expected = json_encode([
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
                    'uris' => [
                        'type' => 'array',
                        'items' => [
                            'anyOf' => [
                                ['type' => 'string', 'maxLength' => 1024],
                                ['type' => 'null'],
                            ],
                        ],
                        'maxItems' => 32,
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 10000],
                            ['type' => 'null'],
                        ],
                    ],
                    'icon' => [
                        'type' => 'object',
                        'required' => [],
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'enum' => ['keepass-icon-set', 'passbolt-icon-set'],
                            ],
                            'value' => [
                                'type' => 'integer',
                                'minimum' => 0,
                            ],
                            'background_color' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'pattern' => '^#(?:[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$',
                                    ],
                                    ['type' => 'null'],
                                ],
                            ],
                        ],
                    ],
                    'custom_fields' => [
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
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 255],
                                        ['type' => 'null'],
                                    ],
                                ],
                                'metadata_value' => [
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 20000],
                                        ['type' => 'number'],
                                        ['type' => 'boolean'],
                                        ['type' => 'null'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'secret' => [
                'type' => 'object',
                'required' => ['password'],
                'properties' => [
                    'object_type' => [
                        'type' => 'string',
                        'enum' => ['PASSBOLT_SECRET_DATA'],
                    ],
                    'password' => [
                        'type' => 'string',
                        'maxLength' => 4096,
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 50000],
                            ['type' => 'null'],
                        ],
                    ],
                    'custom_fields' => [
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
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 255],
                                        ['type' => 'null'],
                                    ],
                                ],
                                'secret_value' => [
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 20000],
                                        ['type' => 'number'],
                                        ['type' => 'boolean'],
                                        ['type' => 'null'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $result = SlugDefinition::v5Default();
        $this->assertEquals($expected, $result);
    }

    public function testV5DefaultTotp(): void
    {
        $expected = json_encode([
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
                    'uris' => [
                        'type' => 'array',
                        'items' => [
                            'anyOf' => [
                                ['type' => 'string', 'maxLength' => 1024],
                                ['type' => 'null'],
                            ],
                        ],
                        'maxItems' => 32,
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 10000],
                            ['type' => 'null'],
                        ],
                    ],
                    'icon' => [
                        'type' => 'object',
                        'required' => [],
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'enum' => ['keepass-icon-set', 'passbolt-icon-set'],
                            ],
                            'value' => [
                                'type' => 'integer',
                                'minimum' => 0,
                            ],
                            'background_color' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'pattern' => '^#(?:[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$',
                                    ],
                                    ['type' => 'null'],
                                ],
                            ],
                        ],
                    ],
                    'custom_fields' => [
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
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 255],
                                        ['type' => 'null'],
                                    ],
                                ],
                                'metadata_value' => [
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 20000],
                                        ['type' => 'number'],
                                        ['type' => 'boolean'],
                                        ['type' => 'null'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'secret' => [
                'type' => 'object',
                'required' => ['password', 'totp'],
                'properties' => [
                    'object_type' => [
                        'type' => 'string',
                        'enum' => ['PASSBOLT_SECRET_DATA'],
                    ],
                    'password' => [
                        'type' => 'string',
                        'maxLength' => 4096,
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 50000],
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
                    'custom_fields' => [
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
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 255],
                                        ['type' => 'null'],
                                    ],
                                ],
                                'secret_value' => [
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 20000],
                                        ['type' => 'number'],
                                        ['type' => 'boolean'],
                                        ['type' => 'null'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $result = SlugDefinition::v5DefaultTotp();
        $this->assertEquals($expected, $result);
    }

    public function testV5Totp(): void
    {
        $expected = json_encode([
            'resource' => [
                'type' => 'object',
                'required' => ['name'],
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ],
                    'uris' => [
                        'type' => 'array',
                        'items' => [
                            'anyOf' => [
                                ['type' => 'string', 'maxLength' => 1024],
                                ['type' => 'null'],
                            ],
                        ],
                        'maxItems' => 32,
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 10000],
                            ['type' => 'null'],
                        ],
                    ],
                    'icon' => [
                        'type' => 'object',
                        'required' => [],
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'enum' => ['keepass-icon-set', 'passbolt-icon-set'],
                            ],
                            'value' => [
                                'type' => 'integer',
                                'minimum' => 0,
                            ],
                            'background_color' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'pattern' => '^#(?:[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$',
                                    ],
                                    ['type' => 'null'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'secret' => [
                'type' => 'object',
                'required' => ['totp'],
                'properties' => [
                    'object_type' => [
                        'type' => 'string',
                        'enum' => ['PASSBOLT_SECRET_DATA'],
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

        $result = SlugDefinition::v5Totp();
        $this->assertEquals($expected, $result);
    }

    public function testV5CustomFields(): void
    {
        $expected = json_encode([
            'resource' => [
                'type' => 'object',
                'required' => ['name', 'custom_fields'],
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ],
                    'uris' => [
                        'type' => 'array',
                        'items' => [
                            'anyOf' => [
                                ['type' => 'string', 'maxLength' => 1024],
                                ['type' => 'null'],
                            ],
                        ],
                        'maxItems' => 32,
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 10000],
                            ['type' => 'null'],
                        ],
                    ],
                    'icon' => [
                        'type' => 'object',
                        'required' => [],
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'enum' => ['keepass-icon-set', 'passbolt-icon-set'],
                            ],
                            'value' => [
                                'type' => 'integer',
                                'minimum' => 0,
                            ],
                            'background_color' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'pattern' => '^#(?:[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$',
                                    ],
                                    ['type' => 'null'],
                                ],
                            ],
                        ],
                    ],
                    'custom_fields' => [
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
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 255],
                                        ['type' => 'null'],
                                    ],
                                ],
                                'metadata_value' => [
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 20000],
                                        ['type' => 'number'],
                                        ['type' => 'boolean'],
                                        ['type' => 'null'],
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
                    'object_type',
                    'custom_fields',
                ],
                'properties' => [
                    'object_type' => [
                        'type' => 'string',
                        'enum' => ['PASSBOLT_SECRET_DATA'],
                    ],
                    'custom_fields' => [
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
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 255],
                                        ['type' => 'null'],
                                    ],
                                ],
                                'secret_value' => [
                                    'anyOf' => [
                                        ['type' => 'string', 'maxLength' => 20000],
                                        ['type' => 'number'],
                                        ['type' => 'boolean'],
                                        ['type' => 'null'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $result = SlugDefinition::v5CustomFields();
        $this->assertEquals($expected, $result);
    }

    public function testV5Note(): void
    {
        $expected = json_encode([
            'resource' => [
                'type' => 'object',
                'required' => ['name'],
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ],
                    'uris' => [
                        'type' => 'array',
                        'items' => [
                            'anyOf' => [
                                ['type' => 'string', 'maxLength' => 1024],
                                ['type' => 'null'],
                            ],
                        ],
                        'maxItems' => 32,
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 10000],
                            ['type' => 'null'],
                        ],
                    ],
                    'icon' => [
                        'type' => 'object',
                        'required' => [],
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'enum' => ['keepass-icon-set', 'passbolt-icon-set'],
                            ],
                            'value' => [
                                'type' => 'integer',
                                'minimum' => 0,
                            ],
                            'background_color' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'pattern' => '^#(?:[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$',
                                    ],
                                    ['type' => 'null'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'secret' => [
                'type' => 'object',
                'required' => [
                    'description',
                    'object_type',
                ],
                'properties' => [
                    'object_type' => [
                        'type' => 'string',
                        'enum' => ['PASSBOLT_SECRET_DATA'],
                    ],
                    'description' => [
                        'anyOf' => [
                            ['type' => 'string', 'maxLength' => 50000],
                            ['type' => 'null'],
                        ],
                    ],
                ],
            ],
        ]);

        $result = SlugDefinition::v5Note();
        $this->assertEquals($expected, $result);
    }
}

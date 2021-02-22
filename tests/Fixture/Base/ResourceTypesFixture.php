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
 * @since         3.0.0
 */
namespace App\Test\Fixture\Base;

use App\Utility\UuidFactory;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * ResourceTypes Fixture
 */
class ResourceTypesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => UuidFactory::uuid('resource-types.id.password-string'),
                'slug' => 'password-string',
                'name' => 'Simple password',
                'description' => 'The original passbolt resource type, where only the password is encrypted.',
                'definition' => json_encode([
                    'resource' => [
                        'name' => [
                            'type' => 'string',
                            'maxLength' => 64,
                        ],
                        'username' => [
                            'anyOf' => [[
                                'type' => 'string',
                                'maxLength' => 64,
                            ], [
                                'type' => 'null',
                            ]],
                        ],
                        'uri' => [
                            'anyOf' => [[
                                'type' => 'string',
                                'maxLength' => 1024,
                            ], [
                                'type' => 'null',
                            ]],
                        ],
                        'description' => [
                            'anyOf' => [[
                                'type' => 'string',
                                'maxLength' => 10000,
                            ], [
                                'type' => 'null',
                            ]],
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
                                'maxLength' => 4064,
                            ],
                        ],
                    ],
                ]),
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41',
            ],
            [
                'id' => UuidFactory::uuid('resource-types.id.password-and-description'),
                'slug' => 'password-and-description',
                'name' => 'Password with description',
                'description' => 'A resource with the password and the description encrypted.',
                'definition' => json_encode([
                    'resource' => [
                        'name' => [
                            'type' => 'string',
                            'maxLength' => 64,
                        ],
                        'username' => [
                            'anyOf' => [[
                                'type' => 'string',
                                'maxLength' => 64,
                            ], [
                                'type' => 'null',
                            ]],
                        ],
                        'uri' => [
                            'anyOf' => [[
                                'type' => 'string',
                                'maxLength' => 1024,
                            ], [
                                'type' => 'null',
                            ]],
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
                                'maxLength' => 4064,
                            ],
                            'description' => [
                                'anyOf' => [[
                                    'type' => 'string',
                                    'maxLength' => 10000,
                                ], [
                                    'type' => 'null',
                                ]],
                            ],
                        ],
                    ],
                ]),
                'created' => '2019-07-02 18:51:41',
                'modified' => '2019-07-02 18:51:41',
            ],
        ];
        parent::init();
    }
}

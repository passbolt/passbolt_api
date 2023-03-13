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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Notification\NotificationSettings;

use Cake\Form\Schema;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;
use Passbolt\Folders\Notification\NotificationSettings\FolderNotificationSettingsDefinition;

class FolderNotificationSettingsDefinitionTest extends TestCase
{
    public const EXPECTED_FIELDS = [
        'send_folder_delete',
        'send_folder_create',
        'send_folder_update',
        'send_folder_share',
    ];

    /**
     * @var FolderNotificationSettingsDefinition
     */
    private $sut;

    public function setUp(): void
    {
        $this->sut = new FolderNotificationSettingsDefinition();

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testThatBuildSchemaReturnFields()
    {
        $schema = new Schema();

        $this->sut->buildSchema($schema);
        $expectedFields = static::EXPECTED_FIELDS;
        $fields = $schema->fields();

        $this->assertEquals(sort($expectedFields), sort($fields));
    }

    /**
     * @return void
     */
    public function testThatBuildValidatorReturnFields()
    {
        $validator = new Validator();

        $this->sut->buildValidator($validator);

        foreach (static::EXPECTED_FIELDS as $expectedField) {
            $this->assertTrue($validator->hasField($expectedField));
        }
    }
}

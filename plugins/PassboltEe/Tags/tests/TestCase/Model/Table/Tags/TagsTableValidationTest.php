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
 * @since         5.15.0
 */
namespace Passbolt\Tags\Test\TestCase\Model\Table\Tags;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\Tags\Model\Table\TagsTable;

/**
 * @covers \Passbolt\Tags\Model\Table\TagsTable::validationDefault
 */
class TagsTableValidationTest extends TestCase
{
    public TagsTable $Tags;

    public function setUp(): void
    {
        parent::setUp();
        /** @var \Passbolt\Tags\Model\Table\TagsTable $tags */
        $tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $this->Tags = $tags;
    }

    public function tearDown(): void
    {
        unset($this->Tags);
        parent::tearDown();
    }

    public function testTagsTableValidation_Slug_InvisibleCharacters_Error(): void
    {
        $tag = $this->Tags->newEntity(
            ['slug' => "hidden\u{200B}tag"],
            ['accessibleFields' => ['slug' => true]],
        );
        $this->assertArrayHasKey('slug', $tag->getErrors());
        $this->assertSame(
            'The string should not contain invisible characters.',
            $tag->getErrors()['slug']['noInvisibleCharacters'],
        );
    }
}

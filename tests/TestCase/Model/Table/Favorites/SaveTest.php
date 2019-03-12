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

namespace App\Test\TestCase\Model\Table\Favorites;

use App\Model\Table\FavoritesTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    use FavoritesModelTrait;
    use FormatValidationTrait;

    public $Favorites;

    public $fixtures = ['app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Favorites', 'app.Base/Permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Favorites') ? [] : ['className' => FavoritesTable::class];
        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites', $config);
    }

    public function tearDown()
    {
        unset($this->Favorites);

        parent::tearDown();
    }

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'user_id' => true,
                'foreign_key' => true,
                'foreign_model' => true
            ]
        ];
    }

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationUserId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Favorites, 'user_id', self::getDummyFavorite(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationForeignId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Favorites, 'foreign_key', self::getDummyFavorite(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationForeignModel()
    {
        $testCases = [
            'inList' => self::getInListTestCases(FavoritesTable::ALLOWED_FOREIGN_MODELS),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Favorites, 'foreign_model', self::getDummyFavorite(), self::getEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testSuccess()
    {
        $data = self::getDummyFavorite();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Favorites->newEntity($data, $options);
        $save = $this->Favorites->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The favorite save operation failed.');

        // Check that the favorite is saved as expected.
        $favorite = $this->Favorites->get($save->id);
        $this->assertNotNull($favorite);
        $this->assertEquals($data['user_id'], $favorite->user_id);
        $this->assertEquals($data['foreign_key'], $favorite->foreign_key);
        $this->assertEquals('Resource', $favorite->foreign_model);
    }

    public function testErrorUserExists()
    {
        $data = self::getDummyFavorite();
        $data['user_id'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Favorites->newEntity($data, $options);
        $save = $this->Favorites->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_exists']);
    }

    public function testErrorUserNotSoftDeleted()
    {
        $data = self::getDummyFavorite();
        $data['user_id'] = UuidFactory::uuid('user.id.sofia');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Favorites->newEntity($data, $options);
        $save = $this->Favorites->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_is_not_soft_deleted']);
    }

    public function testErrorResourceExists()
    {
        $data = self::getDummyFavorite();
        $data['foreign_key'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Favorites->newEntity($data, $options);
        $save = $this->Favorites->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_key']['resource_exists']);
    }

    public function testErrorResourceNotSoftDeleted()
    {
        $data = self::getDummyFavorite();
        $data['foreign_key'] = UuidFactory::uuid('resource.id.jquery');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Favorites->newEntity($data, $options);
        $save = $this->Favorites->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_key']['resource_is_not_soft_deleted']);
    }

    public function testErrorFavoriteUniqueRule()
    {
        $data = self::getDummyFavorite();
        $data['user_id'] = UuidFactory::uuid('user.id.dame');
        $data['foreign_key'] = UuidFactory::uuid('resource.id.apache');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Favorites->newEntity($data, $options);
        $save = $this->Favorites->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['favorite_unique']);
    }

    public function testErrorHasResourceAccessRule()
    {
        $data = self::getDummyFavorite();
        $data['user_id'] = UuidFactory::uuid('user.id.dame');
        $data['foreign_key'] = UuidFactory::uuid('resource.id.canjs');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Favorites->newEntity($data, $options);
        $save = $this->Favorites->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['foreign_key']['has_resource_access']);
    }
}

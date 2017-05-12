<?php
/**
 * Resource Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.ResourceTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppTestCase', 'Test');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

class ResourceFindTest extends AppTestCase {

    public $fixtures = array(
        'app.resource',
        'app.secret',
        'app.favorite',
        'app.log',
        'app.user',
        'app.gpgkey',
        'app.profile',
        'app.file_storage',
        'app.group',
        'app.groupsUser',
        'app.role',
        'app.gpgkey',
        'app.email_queue',
        'app.permission',
        'app.permissions_type',
        'app.permission_view',
        'core.cakeSession',
        'app.user_agent',
        'app.controller_log'
    );

    public function setUp() {
        parent::setUp();

        $this->User = ClassRegistry::init('User');

        $this->session = new CakeSession();
        $this->session->init();

        $user = $this->User->findById(Common::uuid('user.id.ada'));
        $this->User->setActive($user);

        $this->Favorite = ClassRegistry::init('Favorite');
        $this->Resource = ClassRegistry::init('Resource');
    }

    public function tearDown() {
        parent::tearDown();
        // Make sure there is no session active after each test
        $this->User->setInactive();
    }

    /*****************************
     * VIEW CASE
     *****************************/

    // The view case should return a target resource.
    public function testFindViewCase() {
        $resourceId = common::uuid('resource.id.apache');
        $findData = ['Resource.id' => $resourceId];
        $findOptions = $this->Resource->getFindOptions('Resource::view', User::get('Role.name'), $findData);
        $resource = $this->Resource->find('first', $findOptions);
        $this->assertEqual($resource['Resource']['id'], $resourceId);
    }

    // The view case shouldn't return soft deleted content.
    public function testFindViewCaseNotDeletedResource() {
        $resourceId = common::uuid('resource.id.apache');

        // soft delete the resource
        $this->Resource->softDelete($resourceId);

        $findData = ['Resource.id' => $resourceId];
        $findOptions = $this->Resource->getFindOptions('Resource::view', User::get('Role.name'), $findData);
        $resource = $this->Resource->find('first', $findOptions);
        $this->assertEmpty($resource);
    }

    /*****************************
     * VIEW CASE CONTAIN
     *****************************/

    // The view case should return some associated models by default.
    public function testFindViewCaseDefaultContainedFields() {
        $resourceId = common::uuid('resource.id.apache');
        $findData = ['Resource.id' => $resourceId];
        $findOptions = $this->Resource->getFindOptions('Resource::view', User::get('Role.name'), $findData);
        $resource = $this->Resource->find('first', $findOptions);
        $this->assertArrayHasKey('Creator', $resource);
        $this->assertArrayHasKey('Favorite', $resource);
        $this->assertArrayHasKey('Modifier', $resource);
        $this->assertArrayHasKey('Permission', $resource);
        $this->assertArrayHasKey('Resource', $resource);
        $this->assertArrayHasKey('Secret', $resource);
        $this->assertArrayHasKey('UserResourcePermission', $resource);
    }

    /*****************************
     * INDEX CASE
     *****************************/

    // The index case should return a list of resources.
    public function testFindIndexCase() {
        $findOptions = $this->Resource->getFindOptions('Resource::index', User::get('Role.name'));
        $resources = $this->Resource->find('all', $findOptions);
        $this->assertNotEmpty($resources);
    }

    // The index case shouldn't return soft deleted content.
    public function testFindIndexCaseNotDeletedResource() {
        $resourceId = common::uuid('resource.id.apache');

        $findOptions = $this->Resource->getFindOptions('Resource::index', User::get('Role.name'));
        $resources = $this->Resource->find('all', $findOptions);
        $this->assertNotEmpty(Hash::extract($resources, "{n}.Resource[id=$resourceId]"));

        // soft delete the resource
        $this->Resource->softDelete($resourceId);

        $resources = $this->Resource->find('all', $findOptions);
        $this->assertEmpty(Hash::extract($resources, "{n}.Resource[id=$resourceId]"));
    }

    /*****************************
     * INDEX CASE CONTAIN
     *****************************/

    // The index case should return some associated models by default.
    public function testFindIndexCaseDefaultContainedFields() {
        $findOptions = $this->Resource->getFindOptions('Resource::index', User::get('Role.name'));
        $resources = $this->Resource->find('all', $findOptions);
        $this->assertArrayHasKey('Creator', $resources[0]);
        $this->assertArrayHasKey('Favorite', $resources[0]);
        $this->assertArrayHasKey('Modifier', $resources[0]);
        $this->assertArrayHasKey('Permission', $resources[0]);
        $this->assertArrayHasKey('Resource', $resources[0]);
        $this->assertArrayHasKey('Secret', $resources[0]);
        $this->assertArrayHasKey('UserResourcePermission', $resources[0]);
    }

    /*****************************
     * INDEX CASE FILTERED BY KEYWORDS
     *****************************/

    // The index case filtered by keywords
    public function testFindIndexCaseKeywordsFilter() {
        $resourceId = common::uuid('resource.id.apache');

        // Filtering on relevant keywords should return the desired resource.
        $findData = ['filter' => ['keywords' => 'Apache']];
        $findOptions = $this->Resource->getFindOptions('Resource::index', User::get('Role.name'), $findData);
        $resources = $this->Resource->find('all', $findOptions);
        $this->assertNotEmpty(Hash::extract($resources, "{n}.Resource[id=$resourceId]"));

        // Filtering on not relevant keywords should not return the previous resource.
        $findData = ['filter' => ['keywords' => 'NOTNOTRELEVANT']];
        $findOptions = $this->Resource->getFindOptions('Resource::index', User::get('Role.name'), $findData);
        $resources = $this->Resource->find('all', $findOptions);
        $this->assertEmpty(Hash::extract($resources, "{n}.Resource[id=$resourceId]"));
    }

    /*****************************
     * INDEX CASE FILTERED ON FAVORITE
     *****************************/

    // The index case filtered by favorite should return favorite resource
    public function testFindIndexCaseFavoriteFilter() {
        $resourceId = Common::uuid('resource.id.inkscape');

        // Filtering favorite resource shouldn't return the resource above.
        $findData = ['filter' => ['is-favorite' => true]];
        $findOptions = $this->Resource->getFindOptions('Resource::index', User::get('Role.name'), $findData);
        $resources = $this->Resource->find('all', $findOptions);
        $this->assertEmpty(Hash::extract($resources, "{n}.Resource[id=$resourceId]"));

        // Mark the resource as favorite.
        $this->Favorite->create();
        $this->Favorite->save([
            'user_id' => User::get('User.id'),
            'foreign_id' => $resourceId,
            'foreign_model' => 'resource'
        ]);

        // The request should return the resource.
        $findData = ['filter' => ['is-favorite' => true]];
        $findOptions = $this->Resource->getFindOptions('Resource::index', User::get('Role.name'), $findData);
        $resources = $this->Resource->find('all', $findOptions);
        $this->assertNotEmpty(Hash::extract($resources, "{n}.Resource[id=$resourceId]"));
    }

    /*****************************
     * INDEX CASE ORDER
     *****************************/

    // The index case should allow some order.
    public function testFindIndexCaseAllowedOrders() {
        $allowedOrders = $this->Resource->getFindAllowedOrder('Resource::index', User::get('Role.name'));
        $this->assertContains('Resource.name', $allowedOrders);
        $this->assertContains('Resource.username', $allowedOrders);
        $this->assertContains('Resource.expiry_date', $allowedOrders);
        $this->assertContains('Resource.uri', $allowedOrders);
        $this->assertContains('Resource.description', $allowedOrders);
        $this->assertContains('Resource.created', $allowedOrders);
        $this->assertContains('Resource.modified', $allowedOrders);
    }

}

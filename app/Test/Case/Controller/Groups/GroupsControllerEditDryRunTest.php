<?php
/**
 * Groups Controller Edit Dry Run Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('GroupsController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

/**
 * Class GroupsControllerTest
 */
class GroupsControllerEditDryRunTest extends ControllerTestCase {

	// Fixtures to be used.
	public $fixtures = array(
		'app.groups_user',
		'app.group',
		'app.user',
		'app.group',
		'app.gpgkey',
		'app.email_queue',
		'app.profile',
		'app.file_storage',
		'app.role',
		'app.authenticationToken',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log',
		'app.resource',
		'app.secret',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
	);

/**
 * It consumes too many resources to encrypt a text x times
 * so we'll use the same message everywhere
 * @var string
 */
	public $dummyPgpMessage = '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----';


/**
 * Setup.
 */
	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Group = Common::getModel('Group');
		$this->Secret = Common::getModel('Secret');
		$this->GroupUser = Common::getModel('GroupUser');
		$this->GroupResourcePermission = Common::getModel('GroupResourcePermission');
		$this->session = new CakeSession();
		$this->session->init();
	}

/**
 * Teardown.
 */
	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

	private function __buildGroupUsers($userIds, $adminUserIds = []) {
		$groupUsers = [];
		foreach ($userIds as $userId) {
			$groupUsers[] = [
				'GroupUser' => [
					'user_id' => $userId,
					'is_admin' => in_array($userId, $adminUserIds) ? '1' : '0'
				]
			];
		}

		return $groupUsers;
	}

/**
 * Test updating name as an admin.
 */
	public function testUpdateDryRun() {
		$user = $this->User->findById(Common::uuid('user.id.hedy'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.board');
		$userId = Common::uuid('user.id.jean');

		// List of users to add.
		$userIdsToAdd = [
			$userId
		];


		// Data to send in the query.
		$data = [
			'GroupUsers' => $this->__buildGroupUsers($userIdsToAdd),
		];

		$res = $this->testAction(
			"/groups/$groupId/dry-run.json",
			[
				'method' => 'put',
				'data' => $data,
				'return' => 'contents'
			]
		);
		$json = json_decode($res, true);
		$this->assertEquals($json['header']['status'], Status::SUCCESS, 'should return success');
		$this->assertEquals($json['body']['changes']['count'], 0);
		$this->assertEmpty($json['body']['changes']['created']);
		$this->assertEmpty($json['body']['changes']['updated']);
		$this->assertEmpty($json['body']['changes']['deleted']);

		// Assert dry run output.
		$this->assertNotEmpty($json['body']['dry-run']);
		$this->assertNotEmpty($json['body']['dry-run']['SecretsNeeded']);
		$this->assertNotEmpty($json['body']['dry-run']['Secrets']);
		$this->assertEquals(count($json['body']['dry-run']['SecretsNeeded']), 3);
		$this->assertEquals(count($json['body']['dry-run']['Secrets']), 3);
	}

}
<?php
/**
 * EmailNotification Component
 * This class offers tools to send notification emails.
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class EmailNotificatorComponent extends Component {

/**
 * @var Permission $Permission model instance
 */
	public $Permission;

/**
 * @var User $User model instance
 */
	public $User;

/**
 * @var Group $Group model instance
 */
	public $Group;

/**
 * @var Group $GroupUser model instance
 */
	public $GroupUser;

/**
 * @var Resource $v model instance
 */
	public $Resource;

/**
 * @var Secret $Secret model instance
 */
	public $Secret;

/**
 * @var EmailNotification $EmailNotification model instance
 */
	public $EmailNotification;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Controller with components to initialize
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		$this->Permission = Common::getModel('Permission');
		$this->User = Common::getModel('User');
		$this->Group = Common::getModel('Group');
		$this->GroupUser = Common::getModel('GroupUser');
		$this->Resource = Common::getModel('Resource');
		$this->Secret = Common::getModel('Secret');
		$this->Comment = Common::getModel('Comment');
		$this->EmailNotification = Common::getModel('EmailNotification');
		parent::initialize($controller);
	}

/**
 * Return the email author information like name and avatar
 * This data is required in the email template
 *
 * @param string $userId UUID
 * @return array $author, empty if not found or null on error
 */
	protected function _getUserInfo($userId) {
		$author = $this->User->find(
			'first',
			[
				'conditions' => [
					'User.id' => $userId,
				],
				'fields' => [
					'id',
					'username'
				],
				'contain' => [
					'Profile' => [
						'fields' => [
							'Profile.first_name',
							'Profile.last_name',
						],
						'Avatar' => [
							'fields' => [
								'Avatar.*'
							]
						]
					],
				]
			]
		);

		return $author;
	}

/**
 * Send a notification email regarding a new password that has been shared with the user.
 *
 * @param string $toUserId uuid of the recipient
 * @param array $data
 *   variables to pass to the template which should contain
 *     resource_id the resource id
 *     sharer_id the user who is sharing the resource
 * @return void
 */
	public function passwordSharedNotification($toUserId, $data) {
		// get resource.
		$resource = $this->Resource->find(
			'first',
			[
				'conditions' => [
					'Resource.id' => $data['resource_id']
				],
				'fields' => [
					'Resource.name',
					'Resource.username',
					'Resource.uri',
					'Resource.description'
				],
				'contain' => [
					'Secret' => [
						'fields' => [
							'Secret.data',
							'Secret.modified',
						],
						'conditions' => [
							'Secret.user_id' => $toUserId
						],
					]
				]
			]
		);

		$recipient = $this->User->find(
			'first', [
				'conditions' => [
					'User.id' => $toUserId
				],
			]
		);

		// Get sharer info.
		$sharer = $this->_getUserInfo($data['sharer_id']);

		// Send notification.
		$this->EmailNotification->send(
			$recipient['User']['username'],
			__("%s shared %s with you", $sharer['Profile']['first_name'], $resource['Resource']['name']),
			[
				'sender' => $sharer,
				'resource' => $resource,
			],
			'new_password_share'
		);
	}

/**
 * Send a notification email regarding a new comment created on a password.
 *
 * @param string $toUserId uuid of the recipient
 * @param array $data variables to pass to the template which should contain
 *     resource_id the resource id
 *     comment_id the comment id
 * @return void
 */
	public function passwordCommentNotification($toUserId, $data) {
		// Get recipient info.
		$recipient = $this->User->findById($toUserId);

		// Load comment.
		$comment = $this->Comment->findById($data['comment_id'], ['content', 'created', 'created_by']);

		// Load resource.
		$resource = $this->Resource->findById($data['resource_id'], ['name']);

		// Get invite sender.
		$sender = $this->_getUserInfo($comment['Comment']['created_by']);

		// Send notification.
		$this->EmailNotification->send(
			$recipient['User']['username'],
			__("%s commented on %s", $sender['Profile']['first_name'], $resource['Resource']['name']),
			[
				'sender' => $sender,
				'resource' => $resource,
				'comment' => $comment
			],
			'password_comment_new'
		);
	}

/**
 * Send a notification email regarding the creation of a new password.
 *
 * @param string $toUserId uuid of the recipient
 * @param array $data
 *   variables to pass to the template which should contain
 *     resource_id the resource id
 * @return void
 */
	public function passwordCreatedNotification($toUserId, $data) {
		// Get recipient info.
		//$recipient = $this->User->findById($toUserId);

		// Get invite sender.
		$sender = $this->_getUserInfo($toUserId);

		// Get resource.
		$resource = $this->Resource->find(
			'first',
			[
				'conditions' => [
					'Resource.id' => $data['resource_id']
				],
				'fields' => [
					'Resource.name',
					'Resource.username',
					'Resource.uri',
					'Resource.description',
					'Resource.created'
				],
				'contain' => [
					'Secret' => [
						'fields' => [
							'Secret.data',
							'Secret.modified',
						],
						'conditions' => [
							'Secret.user_id' => $toUserId
						],
					]
				]
			]
		);

		// Send notification.
		$this->EmailNotification->send(
			$sender['User']['username'],
			__("Password %s has been added", $resource['Resource']['name']),
			[
				'sender' => $sender,
				'resource' => $resource,
			],
			'password_added'
		);
	}

/**
 * Send a notification email regarding the update of a password.
 *
 * @param string $toUserId uuid of the recipient
 * @param array $data
 *   variables to pass to the template which should contain
 *     * resource_id the resource id
 *     * resource_old_name the old name (in case it was changed)
 *     * sender_id the person who updated the password.
 *     * own is the notification sent to the updater
 * @return void
 */
	public function passwordUpdatedNotification($toUserId, $data) {
		// Get recipient info.
		$recipient = $this->User->findById($toUserId);

		// Get sender info.
		$sender = $this->_getUserInfo($data['sender_id']);

		// Get resource.
		$resource = $this->Resource->find(
			'first',
			[
				'conditions' => [
					'Resource.id' => $data['resource_id']
				],
				'fields' => [
					'Resource.name',
					'Resource.username',
					'Resource.uri',
					'Resource.description',
					'Resource.created'
				],
				'contain' => [
					'Secret' => [
						'fields' => [
							'Secret.data',
							'Secret.modified',
						],
						'conditions' => [
							'Secret.user_id' => $toUserId
						],
					]
				]
			]
		);

		// Send notification.
		$this->EmailNotification->send(
			$recipient['User']['username'],
			__("Password %s has been updated", $data['resource_old_name']),
			[
				'sender' => $sender,
				'resource' => $resource,
				'own' => $data['own'],
			],
			'password_updated'
		);
	}

/**
 * Send a notification email regarding the deletion of a password.
 *
 * @param string $toUserId uuid of the recipient
 * @param array $data
 *   variables to pass to the template which should contain
 *     * resource_name the resource name
 *     * deleter_id the person who updated the password.
 *     * own is the notification sent to the updater
 * @return void
 */
	public function passwordDeletedNotification($toUserId, $data) {
		// Get recipient info.
		$recipient = $this->User->findById($toUserId);

		// Get sender info.
		$sender = $this->_getUserInfo($data['deleter_id']);

		// Send notification.
		$this->EmailNotification->send(
			$recipient['User']['username'],
			__("Password %s has been deleted", $data['resource_name']),
			[
				'sender' => $sender,
				'resource_name' => $data['resource_name'],
				'resource_deletion_date' => date('Y-m-d H:i:s'),
				'own' => $data['own'],
			],
			'password_deleted'
		);
	}

/**
 * Send a notification email regarding a new account created for a user.
 *
 * @param uuid $toUserId user id of the recipient
 * @param array $data
 *   variables to pass to the template which should contain
 *     - creator_id the user who has created the account
 *     - token the token
 * @return void
 */
	public function accountCreationNotification($toUserId, $data) {
		// Get recipient info.
		$recipient = $this->User->find(
			'first',
			[
				'conditions' => [
					'User.id' => $toUserId,
				],
				'contain' => [
					'Profile'
				],
			]
		);

		// Get invite sender.
		$sender = $this->_getUserInfo($data['creator_id']);

		$self = isset($data['self']) && $data['self'] == true;

		// Check if account is created by anonymous user (command line).
		$isCreatorAnonymous = ($data['creator_id'] === Common::uuid('user.id.anonymous'));

		// Default subject.
		$subject = __("Welcome to passbolt, %s!", $recipient['Profile']['first_name']);

		// Subject if account is created by somebody who is not an anonymous user.
		if (!$self && !$isCreatorAnonymous) {
			$subject = __("%s created an account for you!", $sender['Profile']['first_name']);
		}

		// Define template.
		$template = $self ? 'account_creation_self' : 'account_creation';
		if (!$self && $isCreatorAnonymous) {
			$template = 'account_creation_anonymous';
		}

		// Send notification.
		$this->EmailNotification->send(
			$recipient['User']['username'],
			$subject,
			[
				'sender' => $sender,
				'account' => $recipient,
				'token' => $data['token'],
			],
			$template
		);
	}

/**
 * Send a notification email regarding an account recovery.
 *
 * @param uuid $toUserId user id of the recipient
 * @param array $data
 *   variables to pass to the template which should contain
 *     - token the token
 * @return void
 */
	public function accountRecoveryNotification($toUserId, $data) {
		// Get account info.
		$recipient = $this->_getUserInfo($toUserId);

		// Default subject.
		$subject = __("Your account recovery, %s!", $recipient['Profile']['first_name']);

		$template = 'account_recovery';

		// Send notification.
		$this->EmailNotification->send(
			$recipient['User']['username'],
			$subject,
			[
				'account' => $recipient,
				'token' => $data['token'],
			],
			$template
		);
	}

/**
 * Send a notification email to the users that have been added to a group.
 *
 * @param $senderId the user who performed the operation
 * @param $group The target group
 * @param $groupUsers the added group users
 * @return void
 */
	public function groupAddUsers($senderId, $group, $groupUsers) {
		// Get sender account info.
		$sender = $this->_getUserInfo($senderId);

		// Notify added users
		$template = 'group_add_user';

		foreach ($groupUsers as $groupUser) {
			// Get recipient account info.
			$recipient = $this->_getUserInfo($groupUser['GroupUser']['user_id']);

			// Default subject.
			$subject = __("%s added you to the group %s", $sender['Profile']['first_name'], $group['Group']['name']);

			// Send notification.
			$this->EmailNotification->send(
				$recipient['User']['username'],
				$subject, [
				'sender' => $sender,
				'groupUser' => $groupUser,
				'group' => $group,
				'user' => $recipient,
			],
				$template
			);
		}
	}

/**
 * Send a notification email to the users that have been removed from a group.
 *
 * @param $senderId the user who performed the operation
 * @param $group The target group
 * @param $groupUsers the removed group users
 * @return void
 */
	public function groupDeleteUsers($senderId, $group, $groupUsers) {
		// Get sender account info.
		$sender = $this->_getUserInfo($senderId);

		// Email template.
		$template = 'group_delete_user';

		foreach ($groupUsers as $groupUser) {
			// Get recipient account info.
			$recipient = $this->_getUserInfo($groupUser['GroupUser']['user_id']);

			// Default subject.
			$subject = __("%s removed you from the group %s", $sender['Profile']['first_name'], $group['Group']['name']);

			// Send notification.
			$this->EmailNotification->send(
				$recipient['User']['username'],
				$subject, [
				'sender' => $sender,
				'groupUser' => $groupUser,
				'group' => $group,
				'user' => $recipient,
				'deletedTime' => time(),
			],
				$template
			);
		}
	}

/**
 * Send a notification email to the group managers after a group update.
 *
 * Except :
 *  - the user who made the changes.
 *  - the new group managers. They will receive another email mentioning  :
 *    - They have been added to a group as group manager
 *    - Their role changed and they are now group manager
 *
 * @param string $senderId the user who performed the operation
 * @param GroupUser $group The target group
 * @param array $data the changes
 *  - created: the created GroupUser
 *  - deleted: the removed GroupUser
 *  - updated: the updated GroupUser
 * @return void
 */
	public function groupUpdatedSummary($senderId, $group, $data = array()) {
		$addedUsers = [];
		$deletedUsers = [];
		$updatedRoles = [];
		$notificationTime = time();

		// Exclude new group managers.
		$groupManagersToExclude = Hash::extract($data['updated'], '{n}.GroupUser[is_admin=true].user_id');

		// The user who made the change shouldn't receive the notification as well.
		$groupManagersToExclude[] = User::get('id');

		// Retrieve the other group managers.
		$groupManagers = $this->GroupUser->find('all', [
			'conditions' => [
				'GroupUser.group_id' => $group['Group']['id'],
				'GroupUser.is_admin' => 1,
				'GroupUser.user_id NOT IN' => $groupManagersToExclude,
			]
		]);

		// If no other group managers to notify.
		if (empty($groupManagers)) {
			return;
		}

		// Get sender account info.
		$sender = $this->_getUserInfo($senderId);

		// Email template.
		$template = 'group_updated_summary';

		// Email subject.
		$subject = __("%s updated members of the group %s", $sender['Profile']['first_name'], $group['Group']['name']);

		// Retrieve added users info.
		if (!empty($data['created'])) {
			foreach ($data['created'] as $groupUser) {
				$addedUsers[] = array_merge(
					$this->_getUserInfo($groupUser['GroupUser']['user_id']),
					$groupUser
				);
			}
		}

		// Retrieve deleted users info.
		if (!empty($data['deleted'])) {
			foreach ($data['deleted'] as $groupUser) {
				$deletedUsers[] = array_merge(
					$this->_getUserInfo($groupUser['GroupUser']['user_id']),
					$groupUser
				);
			}
		}

		// Retrieve updated users info.		if (!empty($data['deleted'])) {
		foreach ($data['updated'] as $groupUser) {
			$updatedRoles[] = array_merge(
				$this->_getUserInfo($groupUser['GroupUser']['user_id']),
				$groupUser
			);
		}

		// Send notifications.
		foreach ($groupManagers as $groupManager) {
			// Get recipient account info.
			$recipient = $this->_getUserInfo($groupManager['GroupUser']['user_id']);

			$this->EmailNotification->send(
				$recipient['User']['username'],
				$subject, [
				'sender' => $sender,
				'group' => $group,
				'addedUsers' => $addedUsers,
				'deletedUsers' => $deletedUsers,
				'updatedRoles' => $updatedRoles,
				'notificationTime' => $notificationTime,
			], $template);
		}
	}
}
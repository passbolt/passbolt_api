<?php
/**
 * EmailNotification Component
 * This class offers tools to send notification emails.
 *
 * @copyright 	(c) 2015-present Passbolt.com
 * @licence			GNU Public Licence v3 - www.gnu.org/licenses/gpl-3.0.en.html
 */
class EmailNotificatorComponent extends Component {

/**
 * Initialize
 *
 * @param Controller $controller
 * @param Array $settings
 */
	public function initialize(Controller $controller, $settings = array()) {
		$this->Controller = $controller;
		$this->Permission = Common::getModel('Permission');
		$this->User = Common::getModel('User');
		$this->Resource = Common::getModel('Resource');
		$this->Secret = Common::getModel('Secret');
		$this->EmailNotification = Common::getModel('EmailNotification');
		parent::initialize($controller);
	}

/**
 * Return the email author information like name and avatar
 * 	This data is required in the email template
 *
 * @param string $userId UUID
 * @return Array $author, empty if not found or null on error
 */
	private function _getAuthorInfo($userId) {
		$author = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'User.id' => $userId,
				),
				'fields' => array(
					'username'
				),
				'contain' => array(
					'Profile' => array(
						'fields' => array(
							'Profile.first_name',
							'Profile.last_name',
						),
						'Avatar' => array(
							'fields' => array(
								'Avatar.*'
							)
						)
					),
				)
			)
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
 */
	public function passwordSharedNotification($toUserId, $data) {
		// get resource.
		$resource = $this->Resource->find(
			'first',
			array(
				'conditions' => array(
					'Resource.id' => $data['resource_id']
				),
				'fields' => array(
					'Resource.name',
					'Resource.username',
					'Resource.uri',
					'Resource.description'
				),
				'contain' => array(
					'Secret' => array(
						'fields' => array(
							'Secret.data',
							'Secret.modified',
						),
						'conditions' => array(
							'Secret.user_id' => $toUserId
						),
					)
				)
			)
		);

		$recipient = $this->User->find(
			'first', array(
				'conditions' => array(
					'User.id' => $toUserId
				),
			)
		);

		// Get sharer info.
		$sharer = $this->_getAuthorInfo($data['sharer_id']);

		// Send notification.
		$this->EmailNotification->send(
			$recipient['User']['username'],
			__("%s shared %s with you", $sharer['Profile']['first_name'], $resource['Resource']['name']),
			array(
				'sender' => $sharer,
				'resource' => $resource,
			),
			'new_password_share'
		);
	}

/**
 * Send a notification email regarding a new account created for a user.
 *
 * @param uuid $toUserId
 *   user id of the recipient
 * @param array $data
 *   variables to pass to the template which should contain
 *     - creator_id the user who has created the account
 *     - token the token
 */
	public function accountCreationNotification($toUserId, $data) {
		// Get recipient info.
		$recipient = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'User.id' => $toUserId,
				),
				'contain' => array(
					'Profile'
				),
			)
		);

		// Get invite sender.
		$sender = $this->_getAuthorInfo($data['creator_id']);

		// Send notification.
		$this->EmailNotification->send(
			$recipient['User']['username'],
			__("%s created an account for you!", $sender['Profile']['first_name']),
			array(
				'sender' => $sender,
				'account' => $recipient,
				'token' => $data['token'],
			),
			'account_creation'
		);
	}

}
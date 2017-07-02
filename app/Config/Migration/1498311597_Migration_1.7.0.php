<?php
App::uses('AnonymousStatistic', 'Model');
App::uses('PassboltMigration', 'Lib');

class Migration_1_7_0 extends PassboltMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Migration_1_7_0';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = [
		'up' => [],
		'down' => [],
	];

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		parent::after($direction);
		$User = ClassRegistry::init('User');
		$GroupUser = ClassRegistry::init('GroupUser');

		// Move anonymous_statistics.php file from app/Config to /tmp
		if (file_exists(APP . 'Config' . DS . 'anonymous_statistics.php')) {
			if (!file_exists(AnonymousStatistic::ConfigFilePath)) {
				mkdir(AnonymousStatistic::ConfigFilePath);
			}
			rename(APP . 'Config' . DS . 'anonymous_statistics.php', AnonymousStatistic::ConfigFilePath . DS . 'anonymous_statistics.php');
		}

		// Remove the deleted users from the groups they were member of.
		$deletedUsers = $User->find('all', ['conditions' => [
			'deleted' => 1
		]]);
		foreach ($deletedUsers as $deletedUser) {
			$groupsUsers = $GroupUser->find('all', ['conditions' => [
				'user_id' => $deletedUser['User']['id'],
			]]);
			foreach ($groupsUsers as $groupUser) {
				$GroupUser->deleteGroupUser($groupUser);
			}
		}
		return true;
	}
}

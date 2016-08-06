<?php
/**
 * Insert User Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataDefault.Console.Command.Task.UserTask
 * @since        version 2.12.11
 */
require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

class SchemaMigrationTask extends ModelTask {

	public $model = 'SchemaMigration';

	protected function getData() {
		// Initial Schema migrations
		$us[] = ['SchemaMigration' => [
			'class' => 'InitMigrations',
			'type' => 'Migrations',
		]];
		$us[] = ['SchemaMigration' => [
			'class' => 'ConvertVersionToClassNames',
			'type' => 'Migrations',
		]];
		$us[] = ['SchemaMigration' => [
			'class' => 'IncreaseClassNameLength',
			'type' => 'Migrations',
		]];
		$us[] = ['SchemaMigration' => [
			'class' => 'SettingHashToDefaultNull',
			'type' => 'FileStorage',
		]];
		$us[] = ['SchemaMigration' => [
			'class' => 'Migration_1_1_0',
			'type' => 'app',
		]];
		return $us;
	}

}

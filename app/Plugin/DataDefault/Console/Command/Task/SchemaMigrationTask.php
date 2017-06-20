<?php
/**
 * SchemaMigrationTask
 * Make sure a fresh install mark the migrations as done
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
		$us[] = ['SchemaMigration' => [
			'class' => 'Migration_1_2_0',
			'type' => 'app',
		]];
		$us[] = ['SchemaMigration' => [
			'class' => 'Migration_1_3_0',
			'type' => 'app',
		]];
		$us[] = ['SchemaMigration' => [
			'class' => 'Migration_1_4_0',
			'type' => 'app',
		]];
		$us[] = ['SchemaMigration' => [
			'class' => 'Migration_1_5_0',
			'type' => 'app',
		]];
		$us[] = ['SchemaMigration' => [
			'class' => 'Migration_1_6_0',
			'type' => 'app',
		]];
		return $us;
	}

}

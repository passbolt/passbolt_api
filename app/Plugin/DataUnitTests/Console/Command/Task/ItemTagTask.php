<?php
/**
 * Insert ItemTag Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.ItemTagTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('ItemTag', 'Model');

class ItemTagTask extends ModelTask {

	public $model = 'ItemTag';

	public function execute() {
		$User = ClassRegistry::init('User');
		$user = $User->findByUsername('root@passbolt.com');
		$User->setActive($user);
		$Model = ClassRegistry::init($this->model);
		$Model->Resource->Behaviors->disable('Permissionable');

		$data = $this->getData();
		foreach ($data as $item) {
			$Model->create();
			$Model->set($item);
			if (!$Model->validates()) {
				var_dump($Model->validationErrors);
			}
			$instance = $Model->save();
			if (!$instance) {
				$this->out('<error>Unable to insert ' . pr($item[$this->model]) . '</error>');
				die;
			}
		}
	}

	protected function getData() {
		$this->Resource = ClassRegistry::init('Resource');
		$this->Tag = ClassRegistry::init('Tag');

		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b59f-3153d7a81fce',
			'tag_id' => Common::uuid('tag.id.banking'),
			'foreign_model' => 'Resource',
			'foreign_id' => Common::uuid('resource.id.bank-password'),
			'created' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b49f-3153d7a82fce',
			'tag_id' => Common::uuid('tag.id.facebook'),
			'foreign_model' => 'Resource',
			'foreign_id' => Common::uuid('resource.id.facebook-account'),
			'created' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b58f-3153d7a83fce',
			'tag_id' => Common::uuid('tag.id.social'),
			'foreign_model' => 'Resource',
			'foreign_id' => Common::uuid('resource.id.facebook-account'),
			'created' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b59f-3153d7a83fce',
			'tag_id' => Common::uuid('tag.id.drupal'),
			'foreign_model' => 'Resource',
			'foreign_id' => Common::uuid('resource.id.dp1-pwd1'),
			'created' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		return $rts;
	}
}

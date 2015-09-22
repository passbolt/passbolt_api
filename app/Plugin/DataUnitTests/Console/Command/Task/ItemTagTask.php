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

	protected function getData() {
		$this->Resource = ClassRegistry::init('Resource');
		$this->Tag = ClassRegistry::init('Tag');

		$tag = $this->Tag->findByName('banking');
		$rs = $this->Resource->findByName('bank password');
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b59f-3153d7a81fce',
			'tag_id' => $tag['Tag']['id'],
			'foreign_model' => 'Resource',
			'foreign_id' => $rs['Resource']['id'],
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$tag = $this->Tag->findByName('facebook');
		$rs = $this->Resource->findByName('facebook account');
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b49f-3153d7a82fce',
			'tag_id' => $tag['Tag']['id'],
			'foreign_model' => 'Resource',
			'foreign_id' => $rs['Resource']['id'],
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$tag = $this->Tag->findByName('social');
		$rs = $this->Resource->findByName('facebook account');
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b58f-3153d7a83fce',
			'tag_id' => $tag['Tag']['id'],
			'foreign_model' => 'Resource',
			'foreign_id' => $rs['Resource']['id'],
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$tag = $this->Tag->findByName('drupal');
		$rs = $this->Resource->findByName('dp1-pwd1');
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b59f-3153d7a83fce',
			'tag_id' => $tag['Tag']['id'],
			'foreign_model' => 'Resource',
			'foreign_id' => $rs['Resource']['id'],
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $rts;
	}
}

<?php
/**
 * Insert ResourceTag Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.ResourceTagTask
 * @since        version 2.12.11
 */

require_once ('plugins' . DS . 'DataExtras' . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('ItemTag', 'Model');

class ItemTagTask extends ModelTask {

	public $model = 'ItemTag';

	protected function getData() {
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b59f-3153d7a81fce',
			'tag_id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c',      // banking
			'foreign_model' => 'Resource',
			'foreign_id' => '509bb871-b964-48ab-94fe-fb098cebc04d', // bank
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b59f-3153d7a82fce',
			'tag_id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c',      // facebook
			'foreign_model' => 'Resource',
			'foreign_id' => '509bb871-5168-49d4-a676-fb098cebc04d', // facebook
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rts[] = array('ItemTag' => array(
			'id' => '10be2d3a-0468-432b-b59f-3153d7a83fce',
			'tag_id' => 'aaa00000-c5cd-11e1-a0c5-080027796c4c',      // social
			'foreign_model' => 'Resource',
			'foreign_id' => '509bb871-5168-49d4-a676-fb098cebc04d', // facebook
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $rts;
	}
}

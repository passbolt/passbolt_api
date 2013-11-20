<?php
/**
 * Insert Comment Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.ProfileTask
 * @since        version 2.12.11
 */

require_once ('plugins' . DS . 'DataExtras' . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Profile', 'Model');

class ProfileTask extends ModelTask {

	public $model = 'Profile';

	protected function getData() {
		$c[] = array('Profile'=>array(
			'id' => '528c2dab-c358-416d-802b-71668cebc04d',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr.',
			'first_name' => 'uTest',
			'last_name' => 'lastname'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-ccca-416d-802b-71668cebc04d',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'root',
			'last_name' => 'lastname'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Dark',
			'last_name' => 'Vador'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccc-416d-802b-71668cebc04d',
			'user_id' => 'abcd6042-c5cd-efef-a0c5-080027796c4c',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Manager',
			'last_name' => 'lastname'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccd-416d-802b-71668cebc04d',
			'user_id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Anon',
			'last_name' => 'Ymous'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-ccce-416d-802b-71668cebc04d',
			'user_id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Test',
			'last_name' => 'Ons'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccf-416d-802b-71668cebc04d',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Aurélie',
			'last_name' => 'Gherards'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccg-416d-802b-71668cebc04d',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Ismail',
			'last_name' => 'Guennouni'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-ccch-416d-802b-71668cebc04d',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'gender' => 'f',
			'date_of_birth' => '1980-12-14',
			'title' => 'Ms',
			'first_name' => 'Myriam',
			'last_name' => 'Djerouni'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Remy',
			'last_name' => 'Bertot'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Kevin',
			'last_name' => 'Muller'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Cedric',
			'last_name' => 'Alfonsi'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccl-416d-802b-71668cebc04d',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Jean-Rene',
			'last_name' => 'Bergamotte'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccm-416d-802b-71668cebc04d',
			'user_id' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'user',
			'last_name' => 'user'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccn-416d-802b-71668cebc04d',
			'user_id' => '50cdab9c-4380-gege-b4cc-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'guest',
			'last_name' => 'apo'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-ccco-416d-802b-71668cebc04d',
			'user_id' => '50cdab9c-4380-adad-b4cc-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'admin',
			'last_name' => 'istrator'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccp-416d-802b-71668cebc04d',
			'user_id' => 'abcdab9c-4380-adad-b4cc-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'user',
			'last_name' => 'inacompany'
		));

		$c[] = array('Profile'=>array(
			'id' => '528c2dab-cccp-416d-802b-71668cebc04d',
			'user_id' => 'fafaab9c-4380-adad-b4cc-2f4fd7a10fce',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Frank',
			'last_name' => 'Leboeuf'
		));

		return $c;
	}
}

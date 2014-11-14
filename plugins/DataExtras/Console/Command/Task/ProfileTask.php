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
		$UserTask = $this->Tasks->load('Data.User');
		$users = $UserTask::getAlias();

		$c[] = array('Profile' => array(
			'id' => '528c2dab-c358-416d-802b-71668cebc04d',
			'user_id' => $users['utt'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'User',
			'last_name' => 'Test'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-ccca-416d-802b-71668cebc04d',
			'user_id' => $users['ins'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Root',
			'last_name' => 'Abaga'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'user_id' => $users['dar'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Darth',
			'last_name' => 'Vader'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-cccc-416d-802b-71668cebc04d',
			'user_id' => $users['mng'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Great',
			'last_name' => 'Manager'
		));

		$c[] = array('Profile' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'user_id' => $users['ano'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Anon',
			'last_name' => 'Ymous'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-ccce-416d-802b-71668cebc04d',
			'user_id' => $users['tst'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'User b',
			'last_name' => 'Test'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-cccf-416d-802b-71668cebc04d',
			'user_id' => $users['aur'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Aurelie',
			'last_name' => 'Gherards'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-cccg-416d-802b-71668cebc04d',
			'user_id' => $users['ism'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Ismail',
			'last_name' => 'Guennouni'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-ccch-416d-802b-71668cebc04d',
			'user_id' => $users['myr'],
			'gender' => 'f',
			'date_of_birth' => '1980-12-14',
			'title' => 'Ms',
			'first_name' => 'Myriam',
			'last_name' => 'Djerouni'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'user_id' => $users['rem'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Remy',
			'last_name' => 'Bertot'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'user_id' => $users['kev'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Kevin',
			'last_name' => 'Muller'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'user_id' => $users['ced'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Cédric',
			'last_name' => 'Alfonsi'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-cccl-416d-802b-71668cebc04d',
			'user_id' => $users['jea'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Jean René',
			'last_name' => 'Bergamotte'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-cccm-416d-802b-71668cebc04d',
			'user_id' => $users['usr'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'User',
			'last_name' => 'Lambda'
		));

		$c[] = array('Profile' => array(
			'id' => '533d3505-a170-4f57-b14b-1768c0a895dc',
			'user_id' => $users['gue'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Guest',
			'last_name' => 'Star'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-ccco-416d-802b-71668cebc04d',
			'user_id' => $users['adm'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Admin',
			'last_name' => 'Istrator'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-cccp-416d-802b-71668cebc04d',
			'user_id' => $users['fra'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Frank',
			'last_name' => 'Leboeuf'
		));

		$c[] = array('Profile' => array(
			'id' => '528c2dab-facp-416d-802b-71668cebc04d',
			'user_id' => $users['au1'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Userone',
			'last_name' => 'Company A'
		));

		return $c;
	}
}

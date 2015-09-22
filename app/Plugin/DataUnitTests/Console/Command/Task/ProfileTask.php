<?php
/**
 * Insert Comment Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.ProfileTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Profile', 'Model');

class ProfileTask extends ModelTask {

	public $model = 'Profile';

	protected function getData() {
		$UserTask = $this->Tasks->load('DataUnitTests.User');
		$users = $UserTask::getAlias();

		// Anonymous user / default for non logged-in user
		$c[] = array('Profile' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'user_id' => $users['ano'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-10',
			'title' => 'Mr',
			'first_name' => 'Anonymous',
			'last_name' => 'User'
		));

		// One user per role
		$c[] = array('Profile' => array(
			'id' => '533d3505-a170-4f57-b14b-1768c0a895dc',
			'user_id' => $users['gue'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-11',
			'title' => 'Mr',
			'first_name' => 'Guest',
			'last_name' => 'User'
		));
		$c[] = array('Profile' => array(
			'id' => '528c2dab-cccm-416d-802b-71668cebc04d',
			'user_id' => $users['usr'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-12',
			'title' => 'Mr',
			'first_name' => 'Default',
			'last_name' => 'User'
		));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-ccco-416d-802b-71668cebc04d',
            'user_id' => $users['adm'],
            'gender' => 'm',
            'date_of_birth' => '1980-12-13',
            'title' => 'Mr',
            'first_name' => 'Admin',
            'last_name' => 'User'
        ));
		$c[] = array('Profile' => array(
			'id' => '528c2dab-ccca-416d-802b-71668cebc04d',
			'user_id' => $users['roo'],
			'gender' => 'm',
			'date_of_birth' => '1980-12-14',
			'title' => 'Mr',
			'first_name' => 'Root',
			'last_name' => 'User'
		));

		// famous scientists
		$c[] = array('Profile' => array(
			'id' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'user_id' => $users['ada'],
			'gender' => 'f',
			'date_of_birth' => '1815-12-10',
			'title' => 'Ms',
			'first_name' => 'Ada',
			'last_name' => 'Lovelace'
		));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-cccj-416d-802b-71668cebc04d',
            'user_id' => $users['bet'],
            'gender' => 'f',
            'date_of_birth' => '1917-03-07',
            'title' => 'Ms',
            'first_name' => 'Betty',
            'last_name' => 'Holberton'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-ccck-416d-802b-71668cebc04d',
            'user_id' => $users['car'],
            'gender' => 'f',
            'date_of_birth' => '1955-01-01',
            'title' => 'Ms',
            'first_name' => 'Carol',
            'last_name' => 'Shaw'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-cccb-416d-802b-71668cebc04d',
            'user_id' => $users['dam'],
            'gender' => 'f',
            'date_of_birth' => '1933-09-16',
            'title' => 'Ms',
            'first_name' => 'Dame Steve',
            'last_name' => 'Shirley'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-ccce-416d-802b-71668cebc04d',
            'user_id' => $users['edi'],
            'gender' => 'f',
            'date_of_birth' => '1983-10-29',
            'title' => 'Ms',
            'first_name' => 'Edith',
            'last_name' => 'Clarke'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-cccp-416d-802b-71668cebc04d',
            'user_id' => $users['fra'],
            'gender' => 'f',
            'date_of_birth' => '1932-08-04',
            'title' => 'Ms',
            'first_name' => 'Frances',
            'last_name' => 'Allen'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-cccf-416d-802b-71668cebc04d',
            'user_id' => $users['gra'],
            'gender' => 'f',
            'date_of_birth' => '1906-12-09',
            'title' => 'Ms',
            'first_name' => 'Grace',
            'last_name' => 'Hopper'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-facp-416d-802b-71668cebc04d',
            'user_id' => $users['hed'],
            'gender' => 'f',
            'date_of_birth' => '1980-12-14',
            'title' => 'Ms',
            'first_name' => 'Hedy',
            'last_name' => 'Lamarr'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-cccg-416d-802b-71668cebc04d',
            'user_id' => $users['ire'],
            'gender' => 'f',
            'date_of_birth' => '1980-12-14',
            'title' => 'Ms',
            'first_name' => 'Irene',
            'last_name' => 'Greif'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-cccl-416d-802b-71668cebc04d',
            'user_id' => $users['jea'],
            'gender' => 'f',
            'date_of_birth' => '1924-12-27',
            'title' => 'Ms',
            'first_name' => 'Jean',
            'last_name' => 'Bartik'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-c358-416d-802b-71668cebc04d',
            'user_id' => $users['kay'],
            'gender' => 'f',
            'date_of_birth' => '1921-02-12',
            'title' => 'Ms',
            'first_name' => 'Kathleen',
            'last_name' => 'Antonelli'
        ));
        $c[] = array('Profile' => array(
            'id' => '528c2dab-cccc-416d-802b-71668cebc04d',
            'user_id' => $users['lyn'],
            'gender' => 'f',
            'date_of_birth' => '1961-06-30',
            'title' => 'Ms',
            'first_name' => 'Lynn',
            'last_name' => 'Jolitz'
        ));
		$c[] = array('Profile' => array(
			'id' => '528c2dab-ccch-416d-802b-71668cebc04d',
			'user_id' => $users['mar'],
			'gender' => 'f',
			'date_of_birth' => '1922-01-01',
			'title' => 'Ms',
			'first_name' => 'Marlyn',
			'last_name' => 'Wescoff'
		));

		return $c;
	}
}

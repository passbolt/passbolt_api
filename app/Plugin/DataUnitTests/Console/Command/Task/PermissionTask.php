<?php
/**
 * Insert Permission Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.PermissionTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Permission', 'Model');

class PermissionTask extends ModelTask {

	public $model = 'Permission';

	protected function getData() {
		$this->Resource = ClassRegistry::init('Resource');

		$GroupTask = $this->Tasks->load('DataUnitTests.Group');
		$groups = $GroupTask::getAlias();
		$UserTask = $this->Tasks->load('DataUnitTests.User');
		$users = $UserTask::getAlias();
		$CategoryTask = $this->Tasks->load('DataUnitTests.Category');
		$cat = $CategoryTask::getAlias();

		// User kathleen@passbolt.com as admin of the root unit test category
		// Sand box for unit tests
		$ps[] = array('Permission' => array(
			'id' => '50e6b4ae-ea4c-4baf-aaf4-23a4d7a10dee',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['utt'],
			'aro' => 'User',
			'aro_foreign_key' => $users['kay'],
			'type' => PermissionType::OWNER
		));
		// kathleen has admin right on everything
		$ps[] = array('Permission' => array(
			'id' => '533d2ecb-3ec8-4437-9ca5-0aafc0a895dc',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['root'],
			'aro' => 'User',
			'aro_foreign_key' => $users['lyn'],
			'type' => PermissionType::OWNER
		));
		// Group Management has admin rights on everything
		$ps[] = array('Permission' => array(
			'id' => '50e6b4ae-ea4c-4baf-aaf4-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['root'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['man'],
			'type' => PermissionType::OWNER
		));
		// Group human resources have read only rights on administration
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-5fa4-493d-bad0-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['adm'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['hr'],
			'type' => PermissionType::READ
		));
		// human resources have no rights on accounts
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-6d20-4e4e-bbcf-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['acc'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['hr'],
			'type' => PermissionType::DENY
		));
		// Group human resources can modify resource salesforce account
		$rSalesforce = $this->Resource->findByName("salesforce account");
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-7768-45e0-890d-23a4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => $rSalesforce['Resource']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['hr'],
			'type' => PermissionType::UPDATE
		));
		// Group human resources cannot access resource facebook account
		$rFacebook = $this->Resource->findByName("facebook account");
		$ps[] = array('Permission' => array(
			'id' => '50e234af-7768-7890-890d-23a4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => $rFacebook['Resource']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['hr'],
			'type' => PermissionType::DENY
		));
		// accounting dpt can access administration>accounts in read only
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-832c-44bf-8a49-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['acc'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['acc'],
			'type' => PermissionType::READ,
		));
		// Group developers drupal have read only rights on Projects > Drupal
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-8824-48f9-89af-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['dru'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['dru'],
			'type' => PermissionType::READ,
		));
		// Group human resources can modify resource salesforce account
		$rDrupalPwd2 = $this->Resource->findByName("dp2-pwd1");
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-7768-45e0-900d-7784d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => $rDrupalPwd2['Resource']['id'],
			'aro' => 'User',
			'aro_foreign_key' => $users['car'],
			'type' => PermissionType::DENY
		));
		// Group cakephp has access to category cakephp in readonly
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-8ab8-4533-a4b4-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['cak'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['cak'],
			'type' => PermissionType::READ,
		));
		// Group developers team leads has access to projects in modify
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-a490-43f5-9cc9-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['pro'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['dtl'],
			'type' => PermissionType::UPDATE,
		));
		// Remy Bertot has admin rights on cp-project1
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-aa58-478c-804d-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['cp1'],
			'aro' => 'User',
			'aro_foreign_key' => $users['ada'],
			'type' => PermissionType::OWNER,
		));
		// Frank has denied right on project
		$ps[] = array('Permission' => array(
			'id' => '50f6b4af-a491-43f5-fac9-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['pro'],
			'aro' => 'User',
			'aro_foreign_key' => $users['fra'],
			'type' => PermissionType::DENY,
		));
		// // Group developers team leads junior has access to projects in modify
//		 $ps[] = array('Permission' => array(
//			 'id' => '50e6b4af-a491-43f5-9cc9-23a4d7a10fce',
//			 'aco' => 'Category',
//			 'aco_foreign_key' => $cat['pro'],
//			 'aro' => 'Group',
//			 'aro_foreign_key' => $groups['tlj'],
//			 'type' => PermissionType::UPDATE,
//		 ));
//		 // Group developers team leads has denied right on cakephp
//		 $ps[] = array('Permission' => array(
//			 'id' => '50e6b4af-a492-43f5-9cc9-23a4d7a10fce',
//			 'aco' => 'Category',
//			 'aco_foreign_key' => $cat['cak'],
//			 'aro' => 'Group',
//			 'aro_foreign_key' => $groups['tlj'],
//			 'type' => PermissionType::DENY,
//		 ));
		// Remy Bertot has admin rights on others
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-ad14-4659-a60d-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['oth'],
			'aro' => 'User',
			'aro_foreign_key' => $users['ada'],
			'type' => PermissionType::OWNER,
		));
		//  Freelancers have read only rights to projects others
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-b124-40e3-988e-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['oth'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['fre'],
			'type' => PermissionType::READ,
		));
		// Jean has readonly access rights on cp-project2
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-b598-42f7-b105-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['cp2'],
			'aro' => 'User',
			'aro_foreign_key' => $users['jea'],
			'type' => PermissionType::READ,
		));
		// Jean has create access rights on "Jean private"
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-c390-4f2e-a8f8-23a4d7a10fcc',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['pjr'],
			'aro' => 'User',
			'aro_foreign_key' => $users['jea'],
			'type' => PermissionType::CREATE,
		));
		// Jean has readonly access rights on cpp1-pwd1
		$rCpp1Pwd1 = $this->Resource->findByName("cpp1-pwd1");
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-c390-4e5e-a8f8-23a4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => $rCpp1Pwd1['Resource']['id'],
			'aro' => 'User',
			'aro_foreign_key' => $users['jea'],
			'type' => PermissionType::READ
		));
		//  company a has read only rights to o-project1
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-d4b0-43d8-947f-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['op1'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['cpa'],
			'type' => PermissionType::READ,
		));
		//  company a has read only rights to o-project1
		$ps[] = array('Permission' => array(
			'id' => '50e6b4af-d4b0-43d8-947f-23a4d7a10ecb',
			'aco' => 'Category',
			'aco_foreign_key' => $cat['op2'],
			'aro' => 'Group',
			'aro_foreign_key' => $groups['cpa'],
			'type' => PermissionType::UPDATE,
		));

		return $ps;
	}
}

<?php
App::import('Model', 'Group');
App::import('Model', 'Category');
App::import('Model', 'Permission');
App::import('Model', 'User');

class InstallationsController extends AppController {
	var $name = 'Installations';
	
	function installStep1(){
		$params = Array(
			'appid'=>$this->params['form']['appid'],
			'username'=>$this->params['form']['email'],
			'password'=>$this->params['form']['password'],
			'name'=>$this->params['form']['email'],
			'email'=>$this->params['form']['email'],
			'company'=>$this->params['form']['company'],
		 	'password'=>$this->params['form']['password']
		);
		
		// TODO : check if app id is unique
		
		// for testing only
		/*$params = Array(
			'appid'=>'test32',
			'username'=>'test32',
			'name'=>'test31 surname',
			'email'=>'test31@jb.com',
			'company'=>'companytest',
			'password'=>'password'
		);*/
		
		$validation_code = rand(10000, 99990);  // generate a validation code. (is not unique)
		App::import('Component', 'PasswordTool');
   		$PasswordTool = new PasswordToolComponent();
   		$db_password = $PasswordTool->generatePassword(); // generate a password for the db
   		//$user_password = $PasswordTool->generatePassword(); //  generate a password for the user
		
		$installation = array(
			'appid'=>$params['appid'],
			'name'=>$params['name'],
			'email'=>$params['email'],
			'username'=>$params['username'],
			'password'=>$params['password'],
			'dbhost'=>'localhost',
			'dbname'=>$params['appid'],
			'dbusername'=>$params['appid'],
			'dbpassword'=>$db_password, //TODO : generate a password for the db
			'company'=>$params['company'],
			'validation_code'=>$validation_code,
			'validated'=>0
		);
		
		
		if($this->Installation->save($installation)){
			$result = array("status"=>1, 'data'=>array('code'=>$validation_code, 'password'=>$params['password'], 'id'=>$this->Installation->id));
		}
		else{
			$result = array("status"=>0, 'data'=>array("errorMsg"=>"Database error."));
		}
		
		// Returns the result
		Configure::write('debug', 0);
		$this->set('json', $result);
		$this->layout = 'ajax';
		$this->render('/javascript/json');
	}
	
	
	function installStep2(){
		/*$id = $this->params['form']['id'];
		$code = $this->params['form']['code'];*/
		
		$id = 37;
		$code = 10027;
		
		$installation = $this->Installation->findById($id);
		if($installation['Installation']['validation_code'] == $code){ // CHECK IF VALIDATION CODE IS OK
			// If the validation code is fine, we can go ahead and create the account
			$params = $installation['Installation'];
				
			$installation['Installation']['validated'] = 1;
			if(!$this->Installation->save($installation)){
				echo "error occured while saving installation";
				exit(0);
			}
			
			$this->Installation->createDb(Configure::read('db_prefix') . $params['appid'], $params['appid'] , $params['dbpassword']);
			$this->Installation->createTables(Configure::read('db_prefix') . $params['appid'], $params['appid'], $params['dbpassword']);	
			
			
			/////////////////////////////////////////////////////
			// Now insert the dummy data inside the new app   //
			////////////////////////////////////////////////////		
			
			/*// Set correct database name
			$config['driver'] = 'mysql';
			$config['persistent'] = false;
			$config['host'] = 'localhost';
			$config['login'] = $installation['dbusername'];
			$config['password'] = $installation['dbpassword'];
			$config['database'] = $installation['dbname'];
			$config['prefix'] = '';*/
			
			Configure::write('appid', $params['appid']); // TRICK : we make the application believe we are a different client
			
			//1) Create a user with its corresponding database
			$permissionModel = new Permission();
			
			$userModel = new User();
			$user = array("username"=>$params['username'], "name"=>$params['name'], "password"=>$params['password'], "email"=>$params['email'], "admin"=>1, "created_by"=>null, "created"=>null, "modified"=>null);
			$userModel->save($user);
			$user_id = $userModel->id;
			
			//2) Create a new database "Default"
			$format = array(
		    'name' => 'Default',
		    'parent_id' => null,
			'created_by' => $user_id
		    );
		    $categoryModel = new Category();
		    $categoryModel->create();
			$categoryModel->save($format);
			
			$categoryModel->create();
			if($categoryModel->save(array('Category'=>array('name'=>'::'. $user_id .'::', 'parent_id'=>null, 'shared'=>0, 'created_by'=>$user_id)))){		// save the category for the personal passwords of the user
				$data['Permission'] = array(
					'aco_id' =>  '1',
					'aro_id' =>  '2',
					'aco_ref_id' =>  $categoryModel->id,
					'aro_ref_id' =>  $user_id,
					'_read' =>  '1',
					'_write' =>  '1',
					'_manage' => '0');
				$permissionModel = new Permission();
				$permissionModel->create();
				$permissionModel->save($data);			// save the corresponding permission (read and write. nobody can share his personal passwords)
			}	
			// 3 : create initial group (parent group).
			$group = array(
		    'name' => 'groups',
		    'parent_id' => null
		    );
		    $groupModel = new Group();
		    $groupModel->create();
			$groupModel->save($group);
			
			Configure::write('appid', 'app'); // TRICK END : get back to normal
			
			// Returns the result
			$result = array("status"=>1);
			$this->set('json', $result);
			$this->render('/javascript/json');
		}
		else{
			// Returns the result
			$result = array("status"=>0, "data"=>array("errorMsg"=>"code_invalid"));
			$this->set('json', $result);
			$this->render('/javascript/json');
		}
	}	
}
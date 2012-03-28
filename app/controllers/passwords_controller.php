<?php
class PasswordsController extends AppController {
	var $name = 'Passwords';
	
	function index() {
   		
   	}
   	
	function add($category_id=0, $live=1) {
		if (!empty($this->data)) {
			$perms = $this->PermissionManager->check(1, $this->data['Password']['category_id']);
			if($perms['write'] == 1){
				$this->data['Password']['live']=$live;
				
				$masterkey = $this->Cookie->read('masterkey');
				if($masterkey){
					// encrypt the password
					$passwordBeforeEncrypting = $this->data["Password"]["password"];
					$passwordEncrypted = $this->Aesctr->encrypt($this->data["Password"]["password"], $masterkey, 256);
					$this->data["Password"]["password"] = $passwordEncrypted;
					
					if($this->Password->save($this->data)) {
						$this->Watcher->logAction(WATCHER_PASSWORD_CREATE, ACO_PASSWORD, $this->Password->id); // logs the action
						$this->data["Password"]["id"] = $this->Password->id;
						$this->data["Password"]["password"] = $passwordBeforeEncrypting; // set it again as it was initially, before the encryption, since the GUI will need it
						$this->set('json', array('status'=>'1', 'data'=>$this->data));
						$this->render('/javascript/json');
					}
					else{
						$this->set('json', array('status'=>'0', 'data'=>array('error_code'=>'invalid_data')));
						$this->render('/javascript/json');
					}
				}
				else{
					$this->set('json', array('status'=>'0', 'data'=>array('error_code'=>'key_not_set')));
					$this->render('/javascript/json');
				}
			}
			else{
				$this->set('json', array('status'=>'0', 'data'=>array('error_code'=>'permission_not_valid')));
				$this->render('/javascript/json');
			}
		}
		$this->set('category_id', $category_id);
	}
	
	/**
	 * edit
	 * Edit a password and send the view
	 * @param $id : the id corresponding to the password
	 */
   	function edit($id=null){
   		$res = 0;
   		//Configure::write('debug', 3); // and forget debug messages
   		
   		/*$this->data["Password"] = array(
   			'category_id' => 81,
            'title' => 'root1',
            'username' => 'root',
            'password' => 'xwNNHjWW0064z9hi+g==',
            'passwordRepeat' => 'xwNNHjWW0064z9hi+g==',
            'url' => 'dev.enova-tech.net',
            'comment' => 'server OVH',
            'live' => 1,
            'id' => 58
		);
   		
		$id = 58;*/
		
   		if($id != null){
		   	if (!empty($this->data)) {
		   		$masterkey = $this->Cookie->read('masterkey');
				//$decrypt = $this->Aesctr->decrypt($this->data["Password"]["password"], $masterkey, 256);
				//echo "decrypt = $decrypt";
		   		if($masterkey){
			   		$perms = $this->PermissionManager->check(1, $this->data['Password']['category_id']);
					if($perms['write'] == 1){
						$this->data['Password']['live']=1;
						$this->data['Password']['id']=$id;
						
						// encrypt the password
						$passwordEncrypted = $this->Aesctr->encrypt($this->data["Password"]["password"], $masterkey, 256);
						$passwordBeforeEncrypting = $this->data["Password"]["password"];
						$this->data["Password"]["password"] = $passwordEncrypted;
						
						if($this->Password->save($this->data)) {
							$this->Watcher->logAction(WATCHER_PASSWORD_MODIFY, ACO_PASSWORD, $id); // logs the action
							$this->data["Password"]["password"] = $passwordBeforeEncrypting; // set it again as it was initially, before the encryption, since the GUI will need it
							$this->set('json', array('status'=>'1', 'data'=>$this->data));
							$this->render('/javascript/json');
						}
						else{
							echo "a problem happened";
							//TODO : display proper error
						}
					}
					else{
						$this->set('json', array('status'=>'0', 'data'=>array('error_code'=>'key_not_set')));
						$this->render('/javascript/json');
					}
				}
				else{
					$this->set('json', array('status'=>'0', 'data'=>array('error_code'=>'permission_not_valid')));
					$this->render('/javascript/json');
				}
			}
			else{
	   			$this->Password->id = $id;
	   			$this->data = $this->Password->read();
	   			$masterkey = $this->Cookie->read('masterkey');
	   			$this->data['Password']['password'] = $this->Aesctr->decrypt($this->data["Password"]["password"], $masterkey, 256);
				$decrypt = $this->Aesctr->decrypt($this->data["Password"]["password"], $masterkey, 256);
			}
   		}
   	}
	
	/**
	 * remove
	 * mark the password as invisible for the other users
	 * only the administrators will be able to see it until they delete it forever
	 */
	function remove(){
		$id = $this->params['form']['id']; 
		
		$data = $this->Password->findById($id);
		$perms = $this->PermissionManager->check(1, $data['Password']['category_id']);
		if($perms['write'] == 1){
			$data["Password"]["live"] = 0;
			if($this->Password->save($data)){
				$this->Watcher->logAction(WATCHER_PASSWORD_DELETE, ACO_PASSWORD, $id); // logs the action
				$json = array("result"=>'1', "status"=>'1');
			}
		}
		else{
			$json = array('status'=>'0', 'data'=>array('error_code'=>'permission_not_valid'));
		}
		$this->set('json', $json);
		$this->render('/javascript/json');
	}
	
	
	function restore(){
		$id = $this->params['form']['id']; 
		$res = 0;

		$data = $this->Password->findById($id);
		$perms = $this->PermissionManager->check(1, $data['Password']['category_id']);
		if($perms['manage'] == 1){
			$data["Password"]["live"] = 1;
			if($this->Password->save($data)){
				$this->Watcher->logAction(WATCHER_PASSWORD_RESTORE, ACO_PASSWORD, $id); // logs the action
				$json = array('status'=>'1', 'result'=>'1');
			}
		}
		else{
			$json = array('status'=>'0', 'data'=>array('error_code'=>'permission_not_valid'));
		}

		$this->set('json', $json);
		$this->render('/javascript/json');
	}
	
	/**
	 * deleteForever
	 * Delete forever a password from the database.
	 */
	function deleteForever(){
		$id = $this->params['form']['id']; 
		$res = 0;
		$data = $this->Password->findById($id);
		$perms = $this->PermissionManager->check(1, $data['Password']['category_id']);
		if($perms["manage"] == 1){
			if($this->Password->delete($id)){
				$this->Watcher->logAction(WATCHER_PASSWORD_DELETE_FOREVER, ACO_PASSWORD, $id); // logs the action
				$json = array('status'=>'1', 'result'=>'1');
			}
		}
		else{
			$json = array('status'=>'0', 'result'=>'0', 'data'=>array('error_code'=>'permission_not_valid'));
		}
		$this->set('json', $json);
		$this->render('/javascript/json');
	}
   	
	function getPasswords(){
		//$category_id = 0;
		//$category_id = "copy_10";
		//TODO : validation of $category_id : while testing, the category id "copy_10" returned all passwords for category "0"
		App::import('Component', 'PasswordTool');
   		$PasswordTool = new PasswordToolComponent();
   		
		$category_id = $this->params['form']['id']; 
		$perms = $this->PermissionManager->check(1, $category_id);
		if($perms["read"] == 1){
			$fields = array("Password.id", "Password.category_id", "Password.title", "Password.username", "Password.password", "Password.url", "Password.comment", "Password.live");
			
			if(isset($category_id)){
				if($this->Session->read('Auth.User.admin')){ // if the user is an admin, we send all the passwords
					$passwords = $this->Password->find('all', array("fields"=>$fields, "conditions"=>array('Password.category_id'=>$category_id)));
				}
				else{  // if the user is not an admin, we send only the passwords that haven't been deleted
					$passwords = $this->Password->find('all', array("fields"=>$fields, "conditions"=>array('Password.category_id'=>$category_id, 'Password.live'=>1)));
				}
				$masterkey = $this->Cookie->read('masterkey');
				if($masterkey){
					//$scores = 0;
					foreach($passwords as $key=>$password){
						$passwordDecrypted = $this->Aesctr->decrypt($password["Password"]["password"], $masterkey, 256);
						//$strength = $PasswordTool->checkStrength($passwordDecrypted);
						//$scores += $strength["score"];
						$passwords[$key]['Password']['password'] = $passwordDecrypted;
						//unset($passwords[$key]['Password']['password']); // remove the password from the list of results
					}
					//$score = round($scores / sizeof($passwords));
					//$strength = $PasswordTool->scoreToStrength($score);
					//$passwords["strength"] = array('score'=>$score, 'strength'=>$strength);
				}
				else{
					$res = array("status"=>0, 'data'=>array('error_code'=>'no_key_set'));
				}
				
				$this->Watcher->logAction(WATCHER_CAT_READ_PASSWORDS, ACO_CATEGORY, $category_id); // logs the action
				$this->set('passwords', $passwords);
			}
		}
		else{
			$json = array('status'=>'0', 'result'=>'0', 'data'=>array('error_code'=>'permission_not_valid'));
			$this->set('json', $json);
			$this->render('/javascript/json');
		}
	}
   	
	/**
	 * Return the deciphered password field
	 * @param $id
	 */
	function getPassword($id){
		$perms = $this->PermissionManager->check(1, $id);
		if($perms["read"] == 1){
			$masterkey = $this->Cookie->read('masterkey');
			if($masterkey){
				//$this->Aes->setKey($this->Aes->_packValue($masterkey, Configure::read('Security.salt'), 32));
				
				$password = $this->Password->findById($id);
				if($password){
					//$passwordDecrypted = base64_decode($this->Aes->decrypt($password["Password"]["password"]));
					$passwordDecrypted =  $this->Aesctr->decrypt($password["Password"]["password"], $masterkey, 256);
					$this->Watcher->logAction(WATCHER_GET_PASSWORD, ACO_PASSWORD, $id, null, $password["Password"]["category_id"]); // logs the create new category action
					$res = array("status"=>1, "data"=>$passwordDecrypted);
				}
				else{
					$res = array("status"=>0, 'data'=>array('error_code'=>'no_corresponding_entry'));
				}
			}
			else{
				$res = array("status"=>0, 'data'=>array('error_code'=>'no_key_set'));
			}
		}
		else{
			$res = array('status'=>'0', 'result'=>'0', 'data'=>array('error_code'=>'permission_not_valid'));
		}
		$this->set('json', $res);
		$this->render('/javascript/json');
	}
	
   	function generate(){
   		App::import('Component', 'PasswordTool');
   		$PasswordTool = new PasswordToolComponent();
   		echo $PasswordTool->generatePassword();
   		exit(0);
   	}
   	
   	function setKey(){
   		//$this->data["Password"]["masterkey"] = "open web innovation"; 
   		if(!empty($this->data)){
   			$key = $this->data["Password"]["masterkey"];
   			$encKey = $this->Auth->password($key);
   			$encKey1 = $this->Auth->password($encKey);
   			$masterKey = $this->Settings->getSetting("master_key");
   			
   			if($masterKey == $encKey1){
   				// cookie duration time
   				$cookie_lifetime = $this->Settings->getSetting('master_key_lifetime');
   				$this->Cookie->write('masterkey',$encKey,false, $cookie_lifetime);
   				$res = array("status"=>1);
   			}
   			else{
   				$res = array('status'=>'0', 'data'=>array('error_code'=>'key_not_valid'));
   			}
   			$this->set('json', $res);
			$this->render('/javascript/json');
   		}
   	}
   
   	/**
   	 * Move the password in another node
   	 * @param $pid, the id of the password to move
   	 * @param $cid, the id of the destination category
   	 * @param $copy, whether it should be a copy instead of a full move
   	 */
   	function move(){
   		$pid = $this->params['form']['pid']; 
   		$cid = $this->params['form']['cid'];
   		
   		$perms = $this->PermissionManager->check(1, $cid);
		if($perms["write"] == 1){
	   		$copy = isset($this->params['form']['copy']) ? $this->params['form']['copy'] : 0;
	   		
	   		$password = $this->Password->findById($pid);
	   		$password['Password']['category_id'] = $cid;
	   		if($copy){
	   			unset($password['Password']['id']);
	   			$this->Password->create();
	   			if($this->Password->save($password)){
		   			$res = array("status"=>1);
		   		}
		   		else{
		   			$res = array('status'=>'0', 'data'=>array('error_code'=>'database_error'));
		   		}
	   		}
	   		else{
		   		if($this->Password->save($password, false, array('category_id'))){
		   			$res = array("status"=>1);
		   		}
		   		else{
		   			$res = array('status'=>'0', 'data'=>array('error_code'=>'database_error'));
		   		}
	   		}
		}
		else{
			$res = array('status'=>'0', 'data'=>array('error_code'=>'permission_not_valid'));
		}
   		$this->set('json', $res);
		$this->render('/javascript/json');
   	}
   	
   	
   	function testPack(){
   		$masterkey = "open web innovation";
   		//$envelope = $this->Aes->keyExpansion($masterkey);
   		echo $envelope."<br/>";
		//$this->Aes->setKey($masterkey);
		//echo $this->Aes->encrypt("test");
		$enc =  $this->Aesctr->encrypt("passbolt - encrypt your passwords", $masterkey, 256);
		//$enc = 'XgNixVmPi02/lx1bV2rtqhWnKbl9SxMdLdpbKgL7/2jXTDWQjqbFiK8=';
		$dec =  $this->Aesctr->decrypt($enc, $masterkey, 256);
		echo "$enc<br/>$dec";

		//$passwordDecrypted = $this->Aes->decrypt($password);
   	}
}
<?php
App::import('Model', 'Password');

class SettingsController extends AppController {
	var $name = 'Settings';
	
	function index(){
		if(!$this->Session->read('Auth.User.admin')){ // Do not allow a non admin to access the settings
			$this->redirect('/');
		}
		$nofetch = array('master_key');
		if(!empty($this->data)){
			if(!empty($this->data['Setting']['master_key'])){
				//echo "save master key<br/>";
				$userKey = $this->data['Setting']['current_master_key'];
				$userKey = $this->Auth->password($userKey);
	   			$currentMasterKey = $this->Settings->getSetting("master_key");
	   			
	   			if($currentMasterKey == $userKey){
	   				//echo "old key ok<br/>";
	   				$newKey = $this->Auth->password($this->data['Setting']['master_key']);
	   				//echo "new Key = $newKey<br/>";
	   				$this->Settings->setSetting('master_key', $newKey); 
	   				$this->Cookie->delete('masterkey'); // delete the current cookie. The user will have to reenter the master key
	   				// NOW rewrite all the passwords with the new key
	   				$passwordModel = new Password();
	   				$passwords = $passwordModel->find('all');
	   				foreach($passwords as $key=>$p){
	   					// Decryption
	   					//echo "master key = $userKeyDec<br/>";
	   					$decrypted = $this->Aesctr->decrypt($p["Password"]["password"], $userKey, 256);
	   					$decrypted = $this->Aesctr->decrypt($decrypted, $userKey, 256);
	   					//echo "{$p['Password']['title']} | {$p['Password']['password']} = $decrypted<br/>";
	   					
	   					// Reencryption
	   					$encrypted = $this->Aesctr->encrypt($decrypted, $newKey, 256);
	   					$encrypted = $this->Aesctr->encrypt($encrypted, $newKey, 256);
	   					$p['Password']['password'] = $encrypted;
	   					//echo "{$p['Password']['title']} | {$p['Password']['password']} => $decrypted => $encrypted<br/>";
	   					$passwordModel->Save($p, array('validate'=>false, 'callbacks'=>false, 'fieldList'=>array('password')));
	   				}
	   				
	   				$res = array("status"=>1);
	   			}
	   			else{
	   				$this->Session->setFlash("The master key you have entered is not correct");
	   			}
			}
			
			// unset the values that require a specific processing
			unset($this->data['Setting']['master_key']);
   			unset($this->data['Setting']['current_master_key']);
			
			foreach($this->data['Setting'] as $k=>$s){
				$this->Settings->setSetting($k, $s);
			}
		}
		$settings = $this->Setting->find('all');
		foreach($settings as $s){
			if(!in_array($s['Setting']['attribute'], $nofetch)){
				$this->data['Setting'][$s['Setting']['attribute']] = $s['Setting']['value'];
			}
		}
		$this->section = 'settings';
	}
	
	function getAjax($attr){
		if($this->Session->read('Auth.User.admin')){
			$attr = $this->params['form']['attribute'];
			$value = $this->params['form']['value'];
			
			$setting = $this->Setting->findByAttr($attr);
			$res = array("status"=>1, "data"=>$setting);
		}
		else{
			$res = array("status"=>0);
		}
		$this->set('json', $res);
		$this->render('/javascript/json');
	}	
	

	function setAjax(){
		$attr = $this->params['form']['attribute'];
		$value = $this->params['form']['value'];
		
		$status = 0;
		
		if($this->Session->read('Auth.User.admin')){
			$setting = array('Setting'=>array("attribute"=>$attr, "value"=>$value));
			$res = $this->Setting->saveSetting($setting);
			$res = ($res == true ? 1 : 0);
		}
		$res = array("status"=>$status);
		$this->set('json', $res);
		$this->render('/javascript/json');
	}
	
	/** Dummy function used only for E-nova's needs
	 *  TODO : Remove it
	 */
	function setKey(){
		$key = "open web innovation";
		$this->Setting->saveSetting(array("Setting"=>array("attribute"=>'master_key', "value"=>$this->Auth->password($this->Auth->password("open web innovation")))));
	}

}
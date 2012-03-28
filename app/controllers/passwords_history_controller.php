<?php
App::import('Model', 'Password');

class PasswordsHistoryController extends AppController {
	var $name = 'PasswordHistory';

	function view($id=null){
		$perms = $this->PermissionManager->check(2, $id);
		if($perms['read'] == 1){
			$this->PasswordHistory = new PasswordHistory();
			$history = $this->PasswordHistory->findAllByPasswordId($id, array(), array('PasswordHistory.created'=>'DESC'));
			$masterkey = $this->Cookie->read('masterkey');
			if($masterkey){
				foreach($history as $k=>$h){
					$decrypt = $this->Aesctr->decrypt($h['PasswordHistory']['value'], $masterkey, 256);
					$decrypt = $this->Aesctr->decrypt($decrypt, $masterkey, 256);
					$history[$k]['PasswordHistory']['value'] = $decrypt;
				}
			}
			$this->set('history', $history);
		}
	}

}
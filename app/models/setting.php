<?php
class Setting extends AppModel {
	var $name = 'Setting';

	function saveSetting($setting){
		$d = $setting["Setting"];
		if(!isset($d["id"])){
			$setting = $this->findByAttribute($d['attribute']);
			if(isset($setting["Setting"]["id"])){
				$setting["Setting"]["value"] = $d["value"];
				$this->save($setting);
			}
			else{
				$this->create();
				$this->save($d);
			}
		}
		else{
			$this->create();
			$this->save($d);
		}
		return true;
	}
	
}

<?php 
class SettingsComponent extends Object {
 
/**
 * Password generator function
 *
 */
    function getSetting ($attribute){
       $settingModel = ClassRegistry::init('Setting');
       $val = $settingModel->findByAttribute($attribute);
       return $val["Setting"]["value"];
    }
    
    function setSetting($attribute, $value){
    	$settingModel = ClassRegistry::init('Setting');
    	$setting = $settingModel->findByAttribute($attribute);
    	if($setting){
    		$setting['Setting']['value']=$value;
    		$settingModel->save($setting);
    	}
    	else{
    		$settingModel->create();
    		$settingModel->save(array('Setting'=>array('attribute'=>$attribute, 'value'=>$value)));
    	}
    }
}

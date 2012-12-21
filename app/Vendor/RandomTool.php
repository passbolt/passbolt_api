<?php

class RandomTool {
	
	public static $mask = array(
		'ALPHA' => array(
			'ASCII' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'ACCENT' => 'àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÚÚÚÝÞß',
			'LATIN' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÚÚÚÝÞß',
			'CHINESE' => '完善的密碼管理解決方案為小型公司和企業的商人',	
			'ARABIC' => 'شغظذخثتسرقضفعصنممكيطحزوهدجبأ',
			'CYRILIC' => 'АБВГДЕЖЅZЗИІКЛМНОПРСТȢѸФХѾЦЧШЩЪЫЬѢꙖѤЮѦѪѨѬѠѺѮѰѲѴ',
			'PONCUTATION' => ',.:;\'"!?',
			'DIGIT' => '0123456789',
			'SPACE' => ' '
		)
	);
	
	public static function string($length = 10, $type='ASCII') {
		$returnValue = '';
		
		for($i=0; $i<$length; $i++) {
			// define the mask to use
			if(is_array($type)) {
				$mask = RandomTool::$mask['ALPHA'][$type[rand(0, count($type)-1)]];
			} else {
				$mask = RandomTool::$mask['ALPHA'][$type];
			}
			
			$returnValue .= substr($mask, rand(0, strlen($mask)-1), 1);
		}
		
		return $returnValue;
	}
	
	public static function permissionType() {
		$mask = array('0', '1', '3', '7', '15');
		return $mask[rand(0, 4)];
	}
}

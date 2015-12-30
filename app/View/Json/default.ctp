<?php
/**
 * Default Json View
 * 
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.json
 * @since        version 2.12.7
 */
if(!isset($json)){
	$json = array(); // @todo error message
}
echo json_encode($json);

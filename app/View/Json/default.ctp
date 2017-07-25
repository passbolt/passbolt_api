<?php
/**
 * Default Json View
 * 
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.View.json
 * @since        version 2.12.7
 */
if(!isset($json)){
	$json = array(); // @todo error message
}
echo json_encode($json);

<?php
/**
 * CanJs layout.
 * Use to manage dynamic javascript files with the canjs framework
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.View.Layouts
 * @since         version 2.13.9
 * @license       http://www.passbolt.com/license
 */
?>

steal(

<?php
// Add required js files.
if(isset($required)) {
	echo "'" . implode("','", $required) . "'";
}
?>

).then(function () {

<?php
	echo $this->fetch('content');
?>

});

<?php
/**
 * CanJs layout.
 * Use to manage dynamic javascript files with the canjs framework
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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

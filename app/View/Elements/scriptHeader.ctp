<?php 
/**
 * Script Header element
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$Role = Common::getModel('Role');
$roles = $Role->find('all', array('conditions' => array('name' => array(Role::ADMIN, Role::USER))));
?>
	<script src="js/lib/modernizr/modernizr-custom.min.js"></script>
	<script type="application/javascript">
	var cakephpConfig = {
		app : {
			name: "<?php echo Configure::read('App.name'); ?>",
			punchline: "<?php echo Configure::read('App.punchline'); ?>",
			copyright: "<?php echo Configure::read('2013 &copy; Passbolt.com'); ?>",
			title: "<?php echo Configure::read('App.title'); ?>",
			version: {
				number: "<?php echo Configure::read('App.version.number'); ?>",
				name: "<?php echo Configure::read('App.version.name'); ?>",
				song: "<?php echo Configure::read('App.version.song'); ?>"
			},
			url: "<?php echo Router::url('/',true); ?>",
			debug: "<?php echo Configure::read('debug'); ?>",
			server_timezone: "<?php echo date_default_timezone_get(); ?>"
		},
		user : {
			id: "<?php echo User::get('id') ?>"
		},
		roles : <?php
				$roles = Hash::combine($roles, '{n}.Role.name', '{n}.Role.id');
				echo json_encode($roles);
		?>,
		image_storage : {
			public_path: "<?php echo Configure::read('ImageStorage.publicPath') ?>"
		}
	};
	</script>
<?php echo $this->fetch('scriptHeader'); ?>


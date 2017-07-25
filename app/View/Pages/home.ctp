<?php
/**
 * Home Page
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
	$this->assign('title', __('Passbolt - The simple password management system'));
	$this->Html->css('main.min', null, array('inline' => false));

	// See. fetch('scriptBottom')
	$this->start('scriptBottom');

    // Load application.
    if(Configure::read('App.js.build') === 'production') :
        echo $this->html->script('/js/lib/steal/steal.production.js', [
            'config' => Router::url('/js/stealconfig.js'),
            'main' => 'app/passbolt',
            'env' => 'production'
        ]);
    else:
        echo $this->html->script('/js/lib/steal/steal.js', [
            'config' => Router::url('/js/stealconfig.js'),
            'main' => 'app/passbolt',
        ]);
    endif;

 	$this->end();

	// See. fetch('scriptHeader')
	$Role = Common::getModel('Role');
	$roles = $Role->find('all', array('conditions' => array('name' => array(Role::ADMIN, Role::USER))));
	$this->start('scriptHeader');
 ?>
<script type="application/javascript">
	var cakephpConfig = {
		app : {
			name: "<?php echo Configure::read('App.name'); ?>",
			description: "<?php echo Configure::read('App.punchline'); ?>",
			title: "<?php echo Configure::read('App.title'); ?>",
			version: {
				number: "<?php echo Configure::read('App.version.number'); ?>",
				name: "<?php echo Configure::read('App.version.name'); ?>"
			},
			url: "<?php echo Router::fullBaseUrl(); ?>/",
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
<?php $this->end(); ?>
<?php echo $this->element('loader'); ?>
<div id="js_app_controller">
</div>

<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

$this->assign('title', $title);
$this->Html->css('main.min', ['block' => 'css']);

// See. fetch('scriptBottom')
$this->start('scriptBottom');
    // Load the javascript application.
    echo $this->html->script('/js/app/steal.production.js');

    // If debug, connect to browserSync service.
    // @see Grunt task appjs-watch
    if($jsBuildMode === 'development') :
        echo $this->html->script('http://localhost:3000/browser-sync/browser-sync-client.js?v=2.18.13', [
            'async' => 'async',
            'id' => '__bs_script__'
        ]);
    endif;
$this->end();
?>
<script type="application/javascript">
    var cakephpConfig = <?php echo json_encode($cakephpConfig); ?>;
</script>
<?php echo $this->element('Loader/splash'); ?>
<div id="js_app_controller"></div>

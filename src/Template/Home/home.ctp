<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
use Cake\Core\Configure;

$this->assign('title', $title);
$this->Html->css('themes/default/api_main.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);

// See. fetch('scriptBottom')
$this->start('scriptBottom');
    // Load the javascript application.
    echo $this->Html->script('/js/app/polyfill.min.js?v=' . Configure::read('passbolt.version'), ['fullBase' => true, 'cache-version' => Configure::read('passbolt.version')]);
    echo $this->Html->script('/js/app/steal.production.js?v=' . Configure::read('passbolt.version'), ['fullBase' => true, 'cache-version' => Configure::read('passbolt.version')]);

    // If debug, connect to browserSync service.
    // @see Grunt task appjs-watch
    if($jsBuildMode === 'development') :
        echo $this->Html->script('http://localhost:3000/browser-sync/browser-sync-client.js?v=2.18.13', [
            'async' => 'async',
            'id' => '__bs_script__'
        ]);
    endif;
$this->end();
?>
<?php echo $this->element('Loader/splash'); ?>
<div id="js_app_controller"></div>

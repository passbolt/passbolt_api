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
use Cake\Core\Configure;
use Cake\Routing\Router;
$this->assign('title', Configure::read('passbolt.meta.description'));
$this->Html->css('main.min', ['block' => 'css']);

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
?>
<?php echo $this->element('Loader/splash'); ?>
<div id="js_app_controller"></div>

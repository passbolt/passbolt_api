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
 * @since         3.11.0
 *
 * @var \App\View\AppView $this
 */
use Cake\Core\Configure;
$title = __('Single-sign on was a success');

$this->assign('title', $title);
$version = Configure::read('passbolt.version');

// See. fetch('scriptBottom')
$this->start('scriptBottom');
// Load the javascript application.
echo $this->Html->script('/js/app/api-vendors.js?v=' . $version, ['fullBase' => true, 'cache-version' => $version]);
echo $this->Html->script('/js/app/api-feedback.js?v=' . $version, ['fullBase' => true, 'cache-version' => $version]);
$this->end();

$this->start('scriptTop');
echo $this->Html->script('/js/app/stylesheet.js?v=' . $version, [
    'id' => 'stylesheet-manager',
    'fullBase' => true,
    'data-file' => 'api_main.min.css',
    'cache-version' => $version
]);
$this->end();
?>
<div id="api-success" class="visually-hidden">
<?php echo $title; ?>
</div>

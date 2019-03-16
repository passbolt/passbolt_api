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
use Cake\Routing\Router;
?>
<?= $this->element('Header/banner'); ?>
    <meta name="description" content="<?= Configure::read('passbolt.meta.description'); ?>">
    <meta name="keywords" content="Passbolt, password manager, online password manager, open source password manager">
    <meta name="robots" content="<?= Configure::read('passbolt.meta.robots'); ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" type="image/png" href="<?= Router::url('/favicon.ico', true); ?>"/>
    <base href="<?= Router::url('/', true); ?>">

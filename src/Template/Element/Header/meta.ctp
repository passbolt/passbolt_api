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
?>
<?= $this->element('Header/banner'); ?>
    <meta name="description" content="<?= Configure::read('passbolt.meta.description'); ?>">
    <meta name="keywords" content="Passbolt, password manager, online password manager, open source password manager">
    <meta name="robots" content="<?= Configure::read('passbolt.meta.robots'); ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" href="<?= Router::url('/favicon.ico'); ?>"/>
    <link rel="icon" type="image/png" href="<?= Router::url('/favicon.png'); ?>"/>
    <link rel="apple-touch-icon" href="<?= Router::url('/apple-touch-icon.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" href="<?= Router::url('/apple-touch-icon-precomposed.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?= Router::url('/apple-touch-icon-57x57-precomposed.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?= Router::url('/apple-touch-icon-72x72-precomposed.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?= Router::url('/apple-touch-icon-114x114-precomposed.png'); ?>"/>

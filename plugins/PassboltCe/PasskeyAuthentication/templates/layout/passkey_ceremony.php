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
 * @since         5.14.0
 * @var \App\View\AppView $this
 *
 * The layout for the top-level passkey ceremony pages. It mirrors the multi factor
 * authentication verify layout so the ceremony popup keeps the passbolt login look, but it lives in
 * this plugin so it does not depend on the multi factor authentication plugin templates being resolved.
 */
use Cake\Core\Configure;

$version = Configure::read('passbolt.version');
?>
<!DOCTYPE html>
<html class="passbolt" lang="en">
<head>
    <?= $this->Html->charset() ?>

    <title><?= Configure::read('passbolt.meta.title'); ?> | <?= $this->fetch('title') ?></title>
    <?= $this->element('Header/meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->Html->script('/js/app/stylesheet.js?v=' . $version, [
        'id' => 'stylesheet-manager',
        'fullBase' => true,
        'data-file' => 'api_authentication.min.css',
        'data-theme' => isset($theme) ? $theme : null,
        'cache-version' => $version]);
    ?>
    <?= $this->fetch('js') ?>

</head>
<body spellcheck="false">
<div id="container" class="container page <?= $this->fetch('pageClass') ?>">
    <div class="content">
        <div class="header">
            <div class="logo"><span class="visually-hidden">Passbolt</span></div>
        </div>
        <?= $this->fetch('content') ?>
    </div>
</div>
</body>
</html>

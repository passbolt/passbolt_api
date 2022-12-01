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
 * @since         2.4.0
 * @var \App\View\AppView $this
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
        'data-file' => 'api_main.min.css',
        'data-theme' => isset($theme) ? $theme : null,
        'cache-version' => $version]);
    ?>
    <?= $this->fetch('js') ?>

</head>
<body spellcheck="false">
<div id="container" class="page <?= $this->fetch('pageClass') ?>">
    <?= $this->fetch('content') ?>
</div>
</body>
</html>

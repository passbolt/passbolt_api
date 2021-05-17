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
?>
<!DOCTYPE html>
<html class="passbolt no-js no-passboltplugin version" lang="en">
<head>
    <?= $this->Html->charset() ?>

    <title><?= Configure::read('passbolt.meta.title'); ?> | <?= $this->fetch('title') ?></title>
    <?= $this->element('Header/meta') ?>
    <?= $this->Html->css('themes/default/api_main.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]); ?>
<?= $this->fetch('css') ?>
</head>
<body>
<div id="container" class="error page <?= $this->fetch('pageClass') ?>">
<?= $this->element('Navigation/default'); ?>
<div id="content">
<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?>
</div>
<?= $this->element('Footer/default'); ?>
</div>
</body>
</html>

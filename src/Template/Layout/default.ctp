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
<!doctype html>
<html class="passbolt no-js version launching no-passboltplugin" lang="en">
<head>
    <?= $this->Html->charset() ?>

    <title><?= Configure::read('passbolt.meta.title'); ?> | <?= $this->fetch('title') ?></title>
    <?= $this->element('Header/meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
<!-- main -->
<div id="container" class="page <?php echo $this->fetch('page_classes') ?>">
<?php echo $this->fetch('content'); ?>
</div>
<?= $this->element('Footer/default'); ?>
<?php echo $this->fetch('scriptBottom'); ?>
</body>
</html>

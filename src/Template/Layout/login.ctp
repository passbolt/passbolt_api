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
    <?php echo $this->Html->charset() ?>

    <title><?php echo Configure::read('passbolt.meta.title'); ?> | <?php echo $this->fetch('title') ?></title>
    <?php echo $this->element('Header/meta') ?>

    <?php echo $this->fetch('css') ?>
</head>
<body>
<div id="container" class="page <?php echo $this->fetch('pageClass') ?>">
<?php echo $this->element('Navigation/default'); ?>
<div id="content">
<?php echo $this->fetch('content') ?>

</div>
<?php echo $this->element('Footer/default'); ?>
</div>
</body>
</html>

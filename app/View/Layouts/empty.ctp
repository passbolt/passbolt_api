<?php
/**
 * Empty layout
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<!doctype html>
<html class="passbolt no-js alpha version no-passboltplugin" lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo sprintf(Configure::read('App.title'), $this->fetch('title')); ?></title>
    <meta name="keywords" content="Passbolt, password manager, online password manager, open source password manager">
    <meta name="viewport" content="width=device-width">
</head>
<body>
<div id="container" class="empty page">
<?php echo $this->fetch('content'); ?>
</div>
</body>
</html>

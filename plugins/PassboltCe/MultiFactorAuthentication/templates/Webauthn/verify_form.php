<?php
/**
 * @var \App\View\AppView $this
 * @var string $redirect
 * @var string|null $theme
 */

use Cake\Core\Configure;
use Cake\I18n\I18n;

$this->assign('title', __('Multi factor authentication verification'));
$version = Configure::read('passbolt.version');

// See. fetch('scriptBottom')
$this->start('scriptBottom');
// Load the javascript application.
echo $this->Html->script('/js/app/api-vendors.js?v=' . $version, ['fullBase' => true, 'cache-version' => $version]);
echo $this->Html->script('/js/app/api-mfa-verify.js?v=' . $version, ['fullBase' => true, 'cache-version' => $version]);
$this->end();

$this->start('scriptTop');

echo $this->Html->script('/js/app/stylesheet.js?v=' . $version, [
    'id' => 'stylesheet-manager',
    'fullBase' => true,
    'data-file' => 'api_authentication.min.css',
    'data-theme' => $theme ?? null,
    'cache-version' => $version]);

$this->end();
?>
<?php // $redirect is already escaped by SanitizeUrlComponent::sanitizeRedirect ?>
<div id="api-mfa-verify"
     data-redirect="<?= $redirect ?>"
     data-locale="<?= h(str_replace('_', '-', I18n::getLocale())) ?>"></div>

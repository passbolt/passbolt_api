<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $currentProvider
 * @var mixed $providers
 */
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
?>
<div class="form-actions">
<?php if ($currentProvider !== MfaSettings::PROVIDER_DUO) :?>
    <button type="submit" class="button primary big full-width" role="button"><?= __('verify'); ?></button>
<?php else :?>
    <button type="submit" class="button primary big full-width" role="button"><?= __('Sign-in with Duo'); ?></button>
<?php endif; ?>
<?php if (isset($providers) && (count($providers) > 1)) :
    $i = array_search($currentProvider, $providers);
    if ($i !== false) :
        if ($i === count($providers) - 1) {
            $i = 0;
        } else {
            $i++;
        } ?>
    <a href="<?= Router::url("/mfa/verify/" . $providers[$i] . "?redirect=" . $redirect, true); ?>">
        <?= __('Or try with another provider'); ?>
    </a>
    <?php endif;
endif; ?>
</div>

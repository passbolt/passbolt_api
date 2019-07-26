<?php
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
?>
<div class="form-actions">
<?php if ($currentProvider !== MfaSettings::PROVIDER_DUO) :?>
    <button type="submit" class="button primary big" role="button"><?= __('verify'); ?></button>
<?php endif; ?>
<?php if (isset($providers) && (count($providers) > 1)) :
    $i = array_search($currentProvider, $providers);
    if ($i !== false) :
        if ($i === (count($providers) -1)) {
            $i = 0;
        } else {
            $i++;
        } ?>
    <a href="<?= Router::url("/mfa/verify/$providers[$i]", true); ?>">
        <?= __('Or try with another provider'); ?>
    </a>
<?php endif; endif; ?>
</div>
<div id="js-install-complete"  class="grid grid-responsive-12 hidden">
    <div class="row">
        <div class="col7">
            <h3><?php echo __('The configuration is complete') ?></h3>
            <div class="message success">
                <strong><i class="fa fa-check-circle"></i> <?php echo __('Success!') ?></strong>
                <?php echo __('You have completed successfully the configuration procedure, congrats!') ?>
                <?php if ($createFirstUser) : ?>
                <?php echo __('You will soon be redirected to passbolt to complete your account setup.') ?>
                <?php else: ?>
                <?php echo __('You will soon be redirected to the login page.') ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col5 last">
        </div>
    </div>
    <div class="row last">
        <div class="input-wrapper">
            <a href="#" class="button primary next big processing"><?php echo __('next') ?></a>
            <a id="js-install-complete-redirect" href="/"><?php echo __('Click here if you can\'t wait') ?></a>
        </div>
    </div>
</div>
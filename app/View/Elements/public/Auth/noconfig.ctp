<div class="col6 push1 information">
    <h2><?php echo __('Almost there, please register!'); ?></h2>
    <div class="plugin-check-wrapper">
        <div class="plugin-check firefox warning">
            <p class="message">
                <?php echo __('Firefox plugin is installed but not configured.'); ?>
                <?php if(Configure::read('Registration.public')) : ?>
                    <a href="register"><?php echo __('Please register!');?></a>
                <?php else : ?>
                    <?php echo __('Please contact your domain administrator to request an invitation.');?>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <p>
        <?php echo __('If you already registered please check your mail inbox (and your spam folder).'); ?>
    </p>
</div>
<div class="col4 push1 last">
    <?php echo $this->element('public/logo'); ?>
    <div class="users login form">
        <div class="feedback">
            <i class="fa huge fa-rocket" ></i>
            <p><?php echo __('You need an account to login.'); ?></p>
        </div>
        <div class="actions-wrapper center">
            <?php if(Configure::read('Registration.public')) : ?>
                <a class="button primary big" href="register"><?php echo __('Please register!');?></a>
            <?php else : ?>
                <?php echo __('Please contact your domain administrator to request an invitation.');?>
            <?php endif; ?>
        </div>
    </div>
</div>
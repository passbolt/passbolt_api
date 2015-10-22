<div class="col6 push1 information">
    <h2><?php echo __('Almost there, please register!'); ?></h2>
    <div class="plugin-check-wrapper">
        <div class="plugin-check firefox warning">
            <p class="message">
                <?php echo __('Firefox plugin is installed but is not configured.'); ?>
                <a href="register"><?php echo __('Please register'); ?></a></p>
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
            <i class="icon huge rocket" ></i>
            <p><?php echo __('You need an account to login.'); ?></p>
        </div>
        <div class="actions-wrapper center">
            <a class="button primary big" href="register"><?php echo __('Please register'); ?></a>
        </div>
    </div>
</div>
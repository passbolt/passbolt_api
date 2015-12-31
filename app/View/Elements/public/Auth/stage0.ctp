<div class="col6 push1 information">
    <h2><?php echo __('Welcome back!'); ?></h2>
    <div class="plugin-check-wrapper">
        <div class="plugin-check firefox success">
            <p class="message">
                <?php echo __('Nice one! Firefox plugin is installed and configured. You are good to go!');?>
            </p>
        </div>
    </div>
    <div class="plugin-check-wrapper">
        <div class="plugin-check gpg notice">
            <p class="message">
                <?php echo __('Server identity check in progress...');?>
                <?php echo __('Checking key:');?>
                <a href="auth/verify" target='_blank' id="serverkey_id">
                    <?php echo Configure::read('GPG.serverKey.id'); ?>
                </a>
            </p>
        </div>
    </div>
</div>
<div class="col4 push1 last">
    <?php echo $this->element('public/logo'); ?>
    <div class="users login form">
        <div class="feedback">
            <i class="fa fa-cog fa-spin huge" ></i>
            <p><?php echo __('Checking server key');?><br> please wait...</p>
        </div>
    </div>
</div>
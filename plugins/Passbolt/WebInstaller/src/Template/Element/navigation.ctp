<?php
$hasAdmin = $this->Session->read('Passbolt.Config.hasExistingAdmin');
if ($hasAdmin === true || $hasAdmin === null) {
    $sections = [
        'system_check'  => __('System check'),
        'license_key'   => __('Subscription key'),
        'database'      => __('Database'),
        'server_keys'   => __('Server keys'),
        'emails'        => __('Emails'),
        'options'       => __('Options'),
        'installation'  => __('Installation'),
        'end'           => __('That\'s it!'),
    ];
}
else {
    $sections = [
        'system_check'  => __('System check'),
        'license_key'   => __('Subscription key'),
        'database'      => __('Database'),
        'server_keys'   => __('Server keys'),
        'emails'        => __('Emails'),
        'options'       => __('Options'),
        'installation'  => __('Installation'),
        'first_user'    => __('First user'),
        'end'           => __('That\'s it!'),
    ];
}
?>
<div class="navigation wizard">
    <ul>
        <?php
        if (!isset($selectedSection)) {
            $selectedSection = 'system_check';
        }
        $disabled = false;
        $className = '';
        $i = 0;
        foreach($sections as $slug => $name) {
            if($slug == $selectedSection)
                $className = 'selected';
            ?>
            <li class="<?= $className ?>">
                <?= $i + 1 ?>. <?= $name ?>
            </li>
            <?php
            if($slug == $selectedSection) {
                $className = 'disabled';
            }
            $i++;
        }
        ?>
    </ul>
</div>
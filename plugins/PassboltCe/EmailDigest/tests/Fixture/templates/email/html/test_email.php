<?php

use App\Test\Factory\UserFactory;
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;

if (!isset($user)) {
    $user = UserFactory::make()->persist();
}

?>
<?= $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => $user['created'],
        'text' => __('This is an email used by tests only!')
    ])
]); ?>

<h1><?= __d('test', 'This is an email in english.'); ?></h1>
<h1><?= __d('test', 'Sending email from: {0}', $from_email); ?></h1>
<h1><?= __d('test', 'Sending email to: {0}', $email); ?></h1>


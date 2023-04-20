<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.11.0
 */
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
<h1><?= __d('test', 'Sending email to: {0}', $email); ?></h1>


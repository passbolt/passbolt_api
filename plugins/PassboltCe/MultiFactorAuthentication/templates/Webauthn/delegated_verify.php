<?php
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
 * @since         5.17.0
 *
 * @var \App\View\AppView $this
 * @var string $state
 * @var string $beginUrl
 * @var string $finishUrl
 * @var string $redirectUri
 */
$this->assign('title', __('Multi factor authentication verification'));
?>
<div id="mfa-webauthn-delegated"
     data-state="<?= h($state) ?>"
     data-begin-url="<?= h($beginUrl) ?>"
     data-finish-url="<?= h($finishUrl) ?>"
     data-redirect-uri="<?= h($redirectUri) ?>">
    <h2><?= __('Verify with passkey') ?></h2>
    <p><?= __('Follow the instructions of your browser to use your passkey.') ?></p>
    <button type="button" id="mfa-webauthn-delegated-return" hidden>
        <?= __('Return to the application') ?>
    </button>
</div>

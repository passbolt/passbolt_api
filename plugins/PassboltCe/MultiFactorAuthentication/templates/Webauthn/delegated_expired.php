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
 * @var string $redirectUri
 */
$this->assign('title', __('Multi factor authentication verification'));
?>
<div id="mfa-webauthn-delegated-expired" data-redirect-uri="<?= h($redirectUri) ?>">
    <h2><?= __('This verification link is no longer valid.') ?></h2>
    <p><?= __('Start the verification again from the application.') ?></p>
    <a href="<?= h($redirectUri) ?>?error=state_invalid"><?= __('Return to the application') ?></a>
</div>

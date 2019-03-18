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
 * @since         2.0.0
 */
use App\Utility\Purifier;
?>
<table id="added_users" style="border:0; margin:5px 0 0 5px;">
    <?php foreach ($groupUsers as $groupUser): ?>
        <tr>
            <td style="width:15px;">&bull;</td>
            <td><?= Purifier::clean($groupUser->user->profile->first_name); ?> <?= Purifier::clean($groupUser->user->profile->last_name); ?> (<?= $groupUser->is_admin ? __('Group manager') : __('Member'); ?>)</td>
        </tr>
    <?php endforeach; ?>
</table>

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
 * @since         2.13.0
 * @var \App\View\AppView $this
 * @var array $report
 */
use App\Utility\Purifier;
use Cake\Http\Exception\InternalErrorException;

if (!isset($report)) {
    throw new InternalErrorException();
}

$users = $report['data']['users'] ?? [];
?>

<div class="row list">
    <div class="col12">
<?php if (empty($users)) : ?>
        <h2><?= __('All users have completed the setup.'); ?></h2>
<?php else : ?>
        <table class="table-info horizontal ">
            <thead>
            <tr>
                <th><?= __('Name'); ?></th>
                <th><?= __('Username'); ?></th>
                <th><?= __('Created since'); ?></th>
                <th><?= __('Setup completed'); ?></th>
                <th><?= __('Role'); ?></th>
            </tr>
            </thead>
            <tbody>
    <?php foreach ($users as $user) :
        $name = Purifier::clean($user['profile']['first_name'] . ' ' . $user['profile']['last_name']);
        $username = Purifier::clean($user['username']);
        $created = $user['created']->nice();
        $role = Purifier::clean($user['role']['name']);
        ?>
            <tr>
                <td><?= $name; ?></td>
                <td><?= $username; ?></td>
                <td><?= $created; ?></td>
                <td><?= __('No'); ?>></td>
                <td><?= $role; ?>></td>
            </tr>
    <?php endforeach; ?>
            </tbody>
        </table>
<?php endif; ?>
    </div>
</div>

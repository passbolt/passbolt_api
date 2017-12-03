<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
?>
<!-- MODULE ROW // TEXT -->
<tr>
    <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
        <!-- CENTERING TABLE // -->
        <!--
            The centering table keeps the content
                tables centered in the emailBody table,
                in case its width is set to 100%.
        -->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
            <tr>
                <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                    <!-- FLEXIBLE CONTAINER // -->
                    <!--
                        The flexible container has a set width
                            that gets overridden by the media query.
                            Most content tables within can then be
                            given 100% widths.
                    -->
                    <table border="0" cellpadding="0" cellspacing="0" width="480" class="flexibleContainer " style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <tr>
                            <td align="center" valign="top" width="480" class="flexibleContainerCell noPaddingTop" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding-top: 0px;padding-right: 20px;padding-left: 20px;">
                                <!-- CONTENT TABLE // -->
                                <!--
                                    The content table is the first element
                                        that's entirely separate from the structural
                                        framework of the email.
                                -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td valign="top" class="textContent">
                                            Name: <?= $group->name ?><br><br>
                                            <?php if (!empty($addedUsers)): ?>
                                                <span style="font-weight:bold;">Added members</span>
                                                <table id="added_users" style="border:0; margin:5px 0 0 5px;">
                                                    <?php foreach ($addedUsers as $addedUser): ?>
                                                        <tr>
                                                            <td style="width:15px;">&bull;</td>
                                                            <td>
                                                                <?= $addedUser->profile->first_name; ?> <?= $addedUser->profile->last_name; ?>
                                                                (<?= $addedUser->group_user->is_admin ? __('Group manager') : __('Member'); ?>)
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                                <br>
                                            <?php endif; ?>
                                            <?php if (!empty($deletedUsers)) : ?>
                                                <span style="font-weight:bold;">Removed members</span>
                                                <table id="deleted_users" style="border:0; margin:5px 0 0 5px;">
                                                    <?php foreach ($deletedUsers as $deletedUser): ?>
                                                        <tr>
                                                            <td style="width:15px;">&bull;</td>
                                                            <td><?= $deletedUser->profile->first_name; ?> <?= $deletedUser->profile->last_name; ?>
                                                                (<?= $deletedUser->group_user->is_admin ? __('Group manager') : __('Member'); ?>)
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                                <br>
                                            <?php endif; ?>
                                            <?php if (!empty($updatedRoles)): ?>
                                                <span style="font-weight:bold;">Updated roles</span>
                                                <table id="updated_roles" style="border:0; margin:5px 0 0 5px;">
                                                    <?php foreach ($updatedRoles as $updatedRole): ?>
                                                        <tr style="margin-left:5px;">
                                                            <td style="width:15px;">&bull;</td>
                                                            <td>
                                                                <?= $updatedRole->profile->first_name; ?> <?= $updatedRole->profile->last_name; ?>
                                                                <?= $updatedRole->group_user->is_admin ? __('is now group manager') : __('is not anymore group manager'); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                                <!-- // CONTENT TABLE -->

                            </td>
                        </tr>
                    </table>
                    <!-- // FLEXIBLE CONTAINER -->
                </td>
            </tr>
        </table>
        <!-- // CENTERING TABLE -->
    </td>
</tr>
<!-- // MODULE ROW -->

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
                                            <?php if (!empty($addedUsers)): ?>
                                                <span style="font-weight:bold;">Added members</span>
                                                <table id="added_users" style="border:0; margin:5px 0 0 5px;">
                                                    <?php foreach ($addedUsers as $addedUser): ?>
                                                        <tr>
                                                            <td style="width:15px;">&bull;</td>
                                                            <td>
                                                                <?= Purifier::clean($addedUser->profile->first_name); ?> <?= Purifier::clean($addedUser->profile->last_name); ?>
                                                                (<?= isset($whoIsAdmin[$addedUser->id]) && $whoIsAdmin[$addedUser->id] ? __('Group manager') : __('Member'); ?>)
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                                <br>
                                            <?php endif; ?>
                                            <?php if (!empty($removedUsers)) : ?>
                                                <span style="font-weight:bold;">Removed members</span>
                                                <table id="deleted_users" style="border:0; margin:5px 0 0 5px;">
                                                    <?php foreach ($removedUsers as $removedUser): ?>
                                                        <tr>
                                                            <td style="width:15px;">&bull;</td>
                                                            <td><?= Purifier::clean($removedUser->profile->first_name); ?> <?= Purifier::clean($removedUser->profile->last_name); ?>
                                                                (<?= isset($whoIsAdmin[$removedUser->id]) && $whoIsAdmin[$removedUser->id] ? __('Group manager') : __('Member'); ?>)
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                                <br>
                                            <?php endif; ?>
                                            <?php if (!empty($updatedUsers)): ?>
                                                <span style="font-weight:bold;">Updated roles</span>
                                                <table id="updated_roles" style="border:0; margin:5px 0 0 5px;">
                                                    <?php foreach ($updatedUsers as $updatedUser): ?>
                                                        <tr style="margin-left:5px;">
                                                            <td style="width:15px;">&bull;</td>
                                                            <td>
                                                                <?= Purifier::clean($updatedUser->profile->first_name); ?> <?= Purifier::clean($updatedUser->profile->last_name); ?>
                                                                <?= isset($whoIsAdmin[$updatedUser->id]) && $whoIsAdmin[$updatedUser->id] ? __('is now group manager') : __('is not anymore group manager'); ?>
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

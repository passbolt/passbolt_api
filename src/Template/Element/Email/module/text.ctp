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
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                    <tr>
                                        <td valign="top" class="textContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #404040;font-family: Helvetica;font-size: 14px;line-height: 125%;text-align: Left;padding-bottom: 20px;">
                                            <?= $text; ?>
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

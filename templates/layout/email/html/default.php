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
use Cake\Routing\Router;
/*
 *  IMPORTANT: do not modify directly!
 *
 *	This is a template for the email that
 *	it is generated from pages/demo/email-notify
 *	with the CSS inlined.
 *
 *  Modify the demo, inline it, then this one!
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width">
    <title><?php echo $title; ?></title>
    <style type="text/css">
        /*////// RESET STYLES //////*/
        body, #bodyTable, #bodyCell {height:100% !important; margin:0; padding:0; width:100% !important;}
        #headerTable, #headerCell, #footerTable, #footerCell { margin:0; padding:0; width:100% !important;}
        table{border-collapse:collapse;}
        img, a img{border:0; outline:none; text-decoration:none;}
        h1, h2, h3, h4, h5, h6{margin:0; padding:0;}
        p{margin: 1em 0;}

        /*////// CLIENT-SPECIFIC STYLES //////*/
        .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail/Outlook.com to display emails at full width. */
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;} /* Force Hotmail/Outlook.com to display line heights normally. */
        table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up. */
        #outlook a{padding:0;} /* Force Outlook 2007 and up to provide a "view in browser" message. */
        img{-ms-interpolation-mode: bicubic;} /* Force IE to smoothly render resized images. */
        body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%;} /* Prevent Windows- and Webkit-based mobile platforms from changing declared text sizes. */

        /*////// FRAMEWORK STYLES //////*/
        .flexibleContainerCell{padding-top:20px; padding-Right:20px; padding-Left:20px;}
        .flexibleContainerCell.noPaddingTop{padding-top:0px;}
        .flexibleImage{height:auto;}
        .bottomShim{padding-bottom:20px;}
        .imageContent, .imageContentLast{padding-bottom:0px;}

        /*////// GENERAL STYLES //////*/
        body, #headerTable, #bodyTable, #footerTable {background-color:#F5F5F5;}
        #headerTable {height:37px !important;}
        #footerTable {height:30px !important;}
        #headerCell{padding-top:40px; padding-bottom:10px;}
        #bodyCell{padding-top:0px; padding-bottom:0px;}
        #footerCell{padding-top:0px; padding-bottom:40px;}
        #emailHeader, #emailFooter {border-collapse:separate;}
        #emailBody{background-color:#FFFFFF; border:1px solid #FFFFFF; border-collapse:separate; border-radius:4px;}
        h1, h2, h3, h4, h5, h6{color:#202020; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left;}
        .textContent, .textContentLast{color:#404040; font-family:Helvetica; font-size:14px; line-height:125%; text-align:Left; padding-bottom:20px;}
        .textContentLast {font-size:12px; text-align:center}
        .textContent a, .textContentLast a{color:#888888; text-decoration:underline;}
        .emailButton{background-color:#2894DF; color:#FFFFFF; order-collapse:separate; border-radius:4px;}
        .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:14px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
        .buttonContent a{color:#FFFFFF; display:block; text-decoration:none;}

        /*////// MOBILE STYLES //////*/
        @media only screen and (max-width: 480px){
            /*////// CLIENT-SPECIFIC STYLES //////*/
            body{width:100% !important; min-width:100% !important;} /* Force iOS Mail to render the email at full width. */

            /*////// FRAMEWORK STYLES //////*/
            /*
                CSS selectors are written in attribute
                selector format to prevent Yahoo Mail
                from rendering media query styles on
                desktop.
            */
            table[id="emailBody"], table[class="flexibleContainer"]{width:100% !important;}

            /*
                The following style rule makes any
                image classed with 'flexibleImage'
                fluid when the query activates.
                Make sure you add an inline max-width
                to those images to prevent them
                from blowing out.
            */
            img[class="flexibleImage"]{height:auto !important; width:100% !important;}

            /*
                Make buttons in the email span the
                full width of their container, allowing
                for left- or right-handed ease of use.
            */
            table[class="emailButton"]{width:100% !important;}
            td[class="buttonContent"]{padding:0 !important;}
            td[class="buttonContent"] a{padding:15px !important;}

            td[class="textContentLast"], td[class="imageContentLast"]{padding-top:20px !important;}

            /*////// GENERAL STYLES //////*/
            td[id="bodyCell"]{padding-top:10px !important; padding-Right:10px !important; padding-Left:10px !important;}
        }
    </style>
    <!--
        Outlook Conditional CSS

            These two style blocks target Outlook 2007 & 2010 specifically, forcing
            columns into a single vertical stack as on mobile clients. This is
            primarily done to avoid the 'page break bug' and is optional.

            More information here:
http://templates.mailchimp.com/development/css/outlook-conditional-css
    -->
    <!--[if mso 12]>
    <style type="text/css">
        .flexibleContainer{display:block !important; width:100% !important;}
    </style>
    <![endif]-->
    <!--[if mso 14]>
    <style type="text/css">
        .flexibleContainer{display:block !important; width:100% !important;}
    </style>
    <![endif]-->
</head>
<body style="margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #F5F5F5;height: 100% !important;width: 100% !important;">
<center>
    <!-- HEADER // -->
    <table border="0" cellpadding="0" cellspacing="0" height="37" width="100%" id="headerTable" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;background-color: #F5F5F5;width: 100% !important;height: 37px !important;">
        <tr>
            <td align="center" valign="top" id="headerCell" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;padding-top: 40px;padding-bottom: 10px;width: 100% !important;">
                <!-- EMAIL CONTAINER // -->
                <!--
                    The table "emailBody" is the email's container.
                        Its width can be set to 100% for a color band
                        that spans the width of the page.
                -->
                <table border="0" cellpadding="0" cellspacing="0" width="480" id="emailHeader" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">

                    <!-- MODULE ROW // -->
                    <tr>
                        <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                            <!-- CENTERING TABLE // -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                <tr>
                                    <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                        <!-- FLEXIBLE CONTAINER // -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="480" class="flexibleContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <tr>
                                                <td valign="top" width="480" class="flexibleContainerCell" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding-top: 20px;padding-right: 20px;padding-left: 20px;">

                                                    <!-- CONTENT TABLE // -->
                                                    <table align="Left" border="0" cellpadding="0" cellspacing="0" width="160" class="flexibleContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                        <tr>
                                                            <td align="Left" valign="top" class="imageContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding-bottom: 0px;">
                                                                <img src="<?php echo Router::url('/img/logo/logo.png',true);?>" width="160" class="flexibleImage" style="max-width: 160px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;height: auto;">
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

                </table>
                <!-- // EMAIL CONTAINER -->
            </td>
        </tr>
    </table>
    <!-- // HEADER -->

    <!-- BODY // -->
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;background-color: #F5F5F5;height: 100% !important;width: 100% !important;">
        <tr>
            <td align="center" valign="top" id="bodyCell" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;padding-top: 0px;padding-bottom: 0px;height: 100% !important;width: 100% !important;">
                <!-- EMAIL CONTAINER // -->
                <!--
                    The table "emailBody" is the email's container.
                        Its width can be set to 100% for a color band
                        that spans the width of the page.
                -->
                <table border="0" cellpadding="0" cellspacing="0" width="480" id="emailBody" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;border: 1px solid #FFFFFF;border-radius: 4px;">
                    <?php echo $this->fetch('content'); ?>

                </table>
                <!-- // EMAIL CONTAINER -->
            </td>
        </tr>
    </table>
    <!-- // BODY -->

    <!-- FOOTER // -->
    <table border="0" cellpadding="0" cellspacing="0" height="30" width="100%" id="footerTable" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;background-color: #F5F5F5;width: 100% !important;height: 30px !important;">
        <tr>
            <td align="center" valign="top" id="footerCell" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;padding-top: 0px;padding-bottom: 40px;width: 100% !important;">
                <!-- EMAIL CONTAINER // -->
                <!--
                    The table "emailBody" is the email's container.
                        Its width can be set to 100% for a color band
                        that spans the width of the page.
                -->
                <table border="0" cellpadding="0" cellspacing="0" width="480" id="emailFooter" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">

                    <!-- MODULE ROW // TITLE AND TEXT -->
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
                                        <table border="0" cellpadding="0" cellspacing="0" width="480" class="flexibleContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <tr>
                                                <td align="center" valign="top" width="480" class="flexibleContainerCell" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding-top: 20px;padding-right: 20px;padding-left: 20px;">

                                                    <!-- CONTENT TABLE // -->
                                                    <!--
                                                        The content table is the first element
                                                            that's entirely separate from the structural
                                                            framework of the email.
                                                    -->
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                        <tr>
                                                            <td valign="top" class="textContentLast" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #404040;font-family: Helvetica;font-size: 12px;line-height: 125%;text-align: center;padding-bottom: 20px;">
                                                                This email is an automatic notification sent by <a href="<?php echo Router::url('/',true) ?>" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #888888;text-decoration: underline;"><?php echo Router::url('/',true) ?></a>.
                                                                You can disable these notifications by requesting an administrator to delete your account.
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

                </table>
                <!-- // EMAIL CONTAINER -->
            </td>
        </tr>
    </table>
    <!-- // FOOTER -->
</center>
</body>
</html>

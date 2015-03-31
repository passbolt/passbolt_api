<?php
/*
 *  IMPORTANT: do not modify directly!
 *
 *	This is a test version for the email that
 *	it is located from pages/demo/email-notify
 *	with the CSS inlined.
*/
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>Modular Template Patterns</title>
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
		#headerTable, #footerTable {height:37px !important;}
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
																<img src="http://www.passbolt.com/img/logo.png" width="160" class="flexibleImage" style="max-width: 160px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;height: auto;">
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

					<!-- MODULE ROW // IMG + TEXT -->
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
													<table align="Left" border="0" cellpadding="0" cellspacing="0" width="60" class="flexibleContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
														<tr>
															<td align="Left" valign="top" class="imageContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding-bottom: 0px;">
																<img src="https://raw.githubusercontent.com/passbolt/passbolt/develop/app/webroot/img/user.png?token=AAEuYs2GqTwX4JeaQPGFPX6DX6ZBIJynks5VI3MuwA%3D%3D" width="50" class="flexibleImage" style="max-width: 50px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;height: auto;">
															</td>
														</tr>
													</table>
													<!-- // CONTENT TABLE -->

													<!-- CONTENT TABLE // -->
													<table align="Right" border="0" cellpadding="0" cellspacing="0" width="360" class="flexibleContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
														<tr>
															<td valign="top" class="textContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #404040;font-family: Helvetica;font-size: 14px;line-height: 125%;text-align: Left;padding-bottom: 20px;">
																<span style="font-weight:bold;">Ismael (ismael@passbolt.com)</span><br>
																shared a password with you<br>
																on Mar 26, 2015 at 18:49<br>
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
																Name: Twitter login<br>
																Username: @passbolt<br>
																URL: http://www.twitter.com/login<br>
																Comment: this is the main twitter account for passbolt.
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

					<!-- MODULE ROW // BUTTON -->
					<tr>
						<td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
							<!-- CENTERING TABLE // -->
							<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
								<tr>
									<td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
										<!-- FLEXIBLE CONTAINER // -->
										<table border="0" cellpadding="0" cellspacing="0" width="480" class="flexibleContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
											<tr>
												<td align="center" valign="top" width="480" class="flexibleContainerCell bottomShim" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;padding-top: 20px;padding-right: 20px;padding-left: 20px;padding-bottom: 20px;">

													<!-- CONTENT TABLE // -->
													<!--
														The emailButton table's width can be changed
															to affect the look of the button. To make the
															button width dependent on the text inside, leave
															the width blank. When a button is placed in a column,
															it's helpful to set the width to 100%.
													-->
													<table border="0" cellpadding="0" cellspacing="0" width="260" class="emailButton" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #2894DF;color: #FFFFFF;order-collapse: separate;border-radius: 4px;">
														<tr>
															<td align="center" valign="middle" class="buttonContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #FFFFFF;font-family: Helvetica;font-size: 14px;font-weight: bold;line-height: 100%;padding: 15px;text-align: center;">
																<a href="#" target="_blank" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #FFFFFF;display: block;text-decoration: none;">view it with passbolt</a>
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
															<td valign="top" class="textContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #404040;font-family: Helvetica;font-size: 14px;line-height: 125%;text-align: Left;padding-bottom: 20px;">
<span style="font-size:11px;font-family: &quot;Courier New&quot;, Courier, monospace;white-space:pre-wrap">
-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22
Comment: Opengpg.js

hQIMA8SHAvApEwAIAQ/9FzeLKJ/nwQxP3MLozEClGYJjEr/7BXwgzjPLEKYgEkjr
/0Yn8cQUXLjWruNUP0Sebb5vomcto6CNoqBCHX3OUz/bsE4IKMniwjgQ/+7nC2aU
aoVb439CpKpYjI4le60I6LW+stfTGPpjxIZbLv0KqMXBojVCcKcXcbMImMwuY05P
NLZPkL0qe1ALmz6sSeFGBNhMRwJS5PywoK0ozfBFb0wunzJ/WmKMWIQoLOO2Upa9
VIwUsM4W9bCnz4RaDk0oLfuW4bVGFBKOjmRSJdx/+cq/NHSss7LxgMyEiesOJZS7
zcaqtlShCfUfQMsd63+jIrN8UUb+J+cz1rvQ9WckTSnbcYsStsBOOL+uGzoK1+3J
h3zeUI2vBoMmqT9PqN3VZZh7fK4CEVBU6X4Fstdw95IxECLvAPrD6WnRwetHr8M2
+KcKUIaCcfliXxJ6zwaB8jY5Gd3Eb3kk4f9OI9TIrnx8BmRSBFqczI+UpmbtW+r8
gP3ZoqiaG7C2UBr+d14I9HP0fuCeC9k5PFDPEC+KsfhRnHFfI+rKip47xUbQ2MCu
Xsj2lEbD7+V0yzt9is1MeSUoqZovAivu3XiQIEx2QkEubuBjjI+Kp2lkM937CBJs
z93twmqLys8GuH46abfGZM93OVf5HGYmLXvih1ij5V7vxgoflcCJC6TLFULYu1vS
RAHSmR3z/ygybNujw/FrKHUWDvpZqZs+ZIYHUqkYpJssJc1UZFOUrY8X2iKT8V+T
ptTZa7zavYFVO1J0Q7TU4ZqN9Ei8
=95oA
-----END PGP MESSAGE-----
</span>
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
	<!-- // BODY -->

	<!-- FOOTER // -->
	<table border="0" cellpadding="0" cellspacing="0" height="37" width="100%" id="footerTable" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;background-color: #F5F5F5;width: 100% !important;height: 37px !important;">
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
																This email was sent by <a href="#" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #888888;text-decoration: underline;">demo.passbolt.com</a>. You can choose which messages you wish to receive, from your profile in the "email notifications: section.
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
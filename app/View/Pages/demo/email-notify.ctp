<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width"/>
	<title>Modular Template Patterns</title>

	<style type="text/css">
		/*////// RESET STYLES //////*/
		body, #bodyTable, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;}
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
		.flexibleContainerCellTop{padding-top:25px; padding-Right:20px; padding-Left:20px;}
		.flexibleContainerCell{padding-top:5px; padding-Right:20px; padding-Left:20px;}
		.flexibleImage{height:auto;}
		.bottomShim{padding-bottom:20px;}
		.imageContent, .imageContentLast{padding-bottom:20px;}
		.nestedContainerCell{padding-top:5px; padding-Right:20px; padding-Left:20px;}

		/*////// GENERAL STYLES //////*/
		body, #bodyTable{background-color:#F2F2F2;}
		#headerCell{padding-top:30px; padding-bottom:20px;}
		#emailHeader{background-color:#F2F2F2; border:0px; border-collapse:separate; border-radius:0px;}
		#emailBody{background-color:#FFFFFF; border:1px solid #FFFFFF; border-collapse:separate; border-radius:4px;}
		h1, h2, h3, h4, h5, h6{color:#202020; font-family:Arial; font-size:20px; line-height:125%; text-align:Left;}
		.textContent, .textContentLast{color:#404040; font-family:Arial; font-size:14px; line-height:125%; text-align:Left; padding-bottom:20px;}
		.textContent a, .textContentLast a{color:#888888; text-decoration:underline;}
		.nestedContainer{background-color:#E5E5E5; border:1px solid #CCCCCC;}
		.emailButton{background-color:#2894DF; border-collapse:separate; border-radius:4px;}
		.buttonContent{color:#FFFFFF; font-family:Arial; font-size:16px; font-weight:bold; line-height:100%; padding:10px; text-align:center;}
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
			table[id="emailheader"], table[id="emailBody"], table[class="flexibleContainer"]{width:100% !important;}

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
<body>
<center>
	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="headerTable">
		<tr>
			<td align="center" valign="top" id="headerCell">
				<!-- EMAIL CONTAINER // -->
				<!--
					The table "emailBody" is the email's container.
						Its width can be set to 100% for a color band
						that spans the width of the page.
				-->
				<table border="0" cellpadding="0" cellspacing="0" width="600" id="emailHeader">
					<!-- MODULE ROW // LOGO IMAGE -->
					<!--
						To move or duplicate any of the design patterns
							in this email, simply move or copy the entire
							MODULE ROW section for each content block.
					-->
					<tr>
						<td align="center" valign="top">
							<!-- CENTERING TABLE // -->
							<!--
								The centering table keeps the content
									tables centered in the emailBody table,
									in case its width is set to 100%.
							-->
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="center" valign="top">
										<!-- FLEXIBLE CONTAINER // -->
										<!--
											The flexible container has a set width
												that gets overridden by the media query.
												Most content tables within can then be
												given 100% widths.
										-->
										<table border="0" cellpadding="0" cellspacing="0" width="530" class="flexibleContainer">
											<tr>
												<td align="left" valign="top" width="530" class="flexibleContainerCell">
													<img src="<?php echo Router::url('/',true); ?>/img/logo.png" width="140" border="0"/>
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
	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
		<tr>
			<td align="center" valign="top" id="bodyCell">
				<!-- EMAIL CONTAINER // -->
				<!--
					The table "emailBody" is the email's container.
						Its width can be set to 100% for a color band
						that spans the width of the page.
				-->
				<table border="0" cellpadding="0" cellspacing="0" width="530" id="emailBody">

					<!-- MODULE ROW // PICTURE AND EVENT DETAILS-->
					<tr>
						<td align="center" valign="top">
							<!-- CENTERING TABLE // -->
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tbody><tr>
									<td align="center" valign="top">
										<!-- FLEXIBLE CONTAINER // -->
										<table class="flexibleContainer" border="0" cellpadding="0" cellspacing="0" width="530">
											<tbody><tr>
												<td class="flexibleContainerCellTop" valign="top" width="530">

													<!-- CONTENT TABLE // -->
													<table class="flexibleContainer" align="Left" border="0" cellpadding="0" cellspacing="0" width="50">
														<tbody><tr>
															<td class="textContent" align="Left" valign="top" style="padding-bottom: 10px;">
																<img src="<?php echo Router::url('/',true);?>/img/user.png" class="flexibleImage" width="50">
															</td>
														</tr>
														</tbody></table>
													<!-- // CONTENT TABLE -->

													<!-- CONTENT TABLE // -->
													<table class="flexibleContainer" align="Right" border="0" cellpadding="0" cellspacing="0" width="400">
														<tbody><tr>
															<td class="textContent" valign="top" style="padding-bottom: 10px;">
																<span style="font-weight:bold;">Ismael (ismael@passbolt.com)</span><br>
																<span style="">shared a password with you</span><br>
																<span style="color:#888888">on Mar 26, 2015 at 18:49</span><br>
															</td>
														</tr>
														</tbody></table>
													<!-- // CONTENT TABLE -->


												</td>
											</tr>
											</tbody></table>
										<!-- // FLEXIBLE CONTAINER -->
									</td>
								</tr>
								</tbody></table>
							<!-- // CENTERING TABLE -->
						</td>
					</tr>
					<!-- // MODULE ROW -->

					<!-- MODULE ROW // PASSWORD DETAILS -->
					<tr>
						<td align="center" valign="top">
							<!-- CENTERING TABLE // -->
							<!--
								The centering table keeps the content
									tables centered in the emailBody table,
									in case its width is set to 100%.
							-->
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tbody><tr>
									<td align="center" valign="top">
										<!-- FLEXIBLE CONTAINER // -->
										<!--
											The flexible container has a set width
												that gets overridden by the media query.
												Most content tables within can then be
												given 100% widths.
										-->
										<table class="flexibleContainer" border="0" cellpadding="0" cellspacing="0" width="530">
											<tbody><tr>
												<td class="flexibleContainerCell" align="center" valign="top" width="530">
													<!-- CONTENT TABLE // -->
													<!--
														The content table is the first element
															that's entirely separate from the structural
															framework of the email.
													-->
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tbody><tr>
															<td class="textContent" valign="top">
																Name: Twitter login<br>
																Username: @passbolt<br>
																URL: http://www.twitter.com/login<br>
																Comment: this is the main twitter account for passbolt.<br><br>
																<table class="emailButton" border="0" cellpadding="0" cellspacing="0" width="230">
																	<tbody><tr>
																		<td class="buttonContent" align="center" valign="middle">
																			<a href="#" target="_blank">view it on passbolt</a>
																		</td>
																	</tr>
																	</tbody></table>
															</td>
														</tr>
														</tbody></table>
													<!-- // CONTENT TABLE -->
												</td>
											</tr>
											</tbody></table>
										<!-- // FLEXIBLE CONTAINER -->
									</td>
								</tr>
								</tbody></table>
							<!-- // CENTERING TABLE -->
						</td>
					</tr>
					<!-- // MODULE ROW -->

					<!-- MODULE ROW // GPG CONTENT -->
					<!--
						To move or duplicate any of the design patterns
							in this email, simply move or copy the entire
							MODULE ROW section for each content block.
					-->
					<tr>
						<td align="center" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="center" valign="top">
										<table border="0" cellpadding="0" cellspacing="0" width="530" class="flexibleContainer">
											<tr>
												<td align="center" valign="top" width="530" class="flexibleContainerCell">
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td valign="top" class="textContent" style="font-family: 'Courier New', Courier, monospace;font-size:12px;" >
																<h3 style="font-size:14px;">Password (encrypted)</h3>
<pre>
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
</pre>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- // MODULE ROW -->


				</table>
				<!-- // EMAIL CONTAINER -->
			</td>
		</tr>
	</table>

	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="footerTable">
		<tr>
			<td align="center" valign="top" id="footerCell">
				<!-- EMAIL CONTAINER // -->
				<!--
					The table "emailBody" is the email's container.
						Its width can be set to 100% for a color band
						that spans the width of the page.
				-->
				<table border="0" cellpadding="0" cellspacing="0" width="600" id="emailFooter">
					<!-- MODULE ROW // LOGO IMAGE -->
					<!--
						To move or duplicate any of the design patterns
							in this email, simply move or copy the entire
							MODULE ROW section for each content block.
					-->
					<tr>
						<td align="center" valign="top">
							<!-- CENTERING TABLE // -->
							<!--
								The centering table keeps the content
									tables centered in the emailBody table,
									in case its width is set to 100%.
							-->
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="center" valign="top">
										<!-- FLEXIBLE CONTAINER // -->
										<!--
											The flexible container has a set width
												that gets overridden by the media query.
												Most content tables within can then be
												given 100% widths.
										-->
										<table border="0" cellpadding="0" cellspacing="0" width="530" class="flexibleContainer">
											<tr>
												<td align="center" valign="top" width="530" class="flexibleContainerCell">
													<!-- CONTENT TABLE // -->
													<!--
														The content table is the first element
															that's entirely separate from the structural
															framework of the email.
													-->
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tbody><tr>
															<td class="textContent" valign="top" style="text-align:center;font-size:12px;padding-top:10px;">
																This email is an automatic notification sent by <a href="demo.passbolt.com">demo.passbolt.com</a>. You can choose which messages you wish to receive from your profile in the "email notifications" section.
															</td>
														</tr>
														</tbody></table>
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
</center>
</body>
</html>
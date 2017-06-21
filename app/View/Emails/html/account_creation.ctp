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
											<img src="<?php echo Router::url('/',true);?><?php echo $sender['Profile']['Avatar']['url']['small'] ?>" width="50" class="flexibleImage" style="max-width: 50px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;height: auto;">
										</td>
									</tr>
								</table>
								<!-- // CONTENT TABLE -->

								<!-- CONTENT TABLE // -->
								<table align="Right" border="0" cellpadding="0" cellspacing="0" width="360" class="flexibleContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
									<tr>
										<td valign="top" class="textContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #404040;font-family: Helvetica;font-size: 14px;line-height: 125%;text-align: Left;padding-bottom: 20px;">
											<span style="font-weight:bold;"><?php echo $sender['Profile']['first_name']; ?> <?php echo $sender['Profile']['last_name']; ?> (<a href="mailto:<?php echo $sender['User']['username']; ?>" style="color:#888;text-decoration: underline;"><?php echo $sender['User']['username']; ?></a>)</span><br>
											<span style="">created and account for you!</span><br>
											<span style="color:#888888">on <?php echo date('M d, Y \a\t H:i', strtotime($account['User']['created'])); ?></span><br>
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

											<h3>Welcome <?php echo $account['Profile']['first_name']; ?>,</h3><br/>
											<?php echo $sender['Profile']['first_name']; ?> just invited you to join <a href="<?php echo Router::url('/',true); ?>"><?php echo Router::url('/',true); ?></a>.
											Passbolt is password manager that is designed to allow sharing credentials with your team without making compromises on security!<br/>
											<br/>
											Let's take the next five minutes to get you started!<br/>
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
											<a href="<?php echo Router::url('/setup/install/' . $account['User']['id'] . '/' . $token, true); ?>" target="_blank" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #FFFFFF;display: block;text-decoration: none;">get started</a>
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

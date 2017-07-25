<!-- MODULE ROW // IMG + TEXT -->
<tr>
	<td align="center" valign="top">
		<!-- CENTERING TABLE // -->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center" valign="top">
					<!-- FLEXIBLE CONTAINER // -->
					<table border="0" cellpadding="0" cellspacing="0" width="480" class="flexibleContainer">
						<tr>
							<td valign="top" width="480" class="flexibleContainerCell">

								<!-- CONTENT TABLE // -->
								<table align="Left" border="0" cellpadding="0" cellspacing="0" width="60" class="flexibleContainer">
									<tr>
										<td align="Left" valign="top" class="imageContent">
											<img src="../../src/img/avatar/user.png" width="50" class="flexibleImage" style="max-width:50px;" />
										</td>
									</tr>
								</table>
								<!-- // CONTENT TABLE -->

								<!-- CONTENT TABLE // -->
								<table align="Right" border="0" cellpadding="0" cellspacing="0" width="360" class="flexibleContainer">
									<tr>
										<td valign="top" class="textContent">
											<span style="font-weight:bold;"><?php echo $sender['Profile']['first_name']; ?> <?php echo $sender['Profile']['last_name']; ?> (<a href="mailto:<?php echo $sender['User']['username']; ?>" style="color:#888;text-decoration: underline;"><?php echo $sender['User']['username']; ?></a>)</span><br>
											has commented on a password<br>
											on <?php echo date('M d, Y \a\t H:i', strtotime($comment['Comment']['created'])); ?><br>
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
					<table border="0" cellpadding="0" cellspacing="0" width="480" class="flexibleContainer ">
						<tr>
							<td align="center" valign="top" width="480" class="flexibleContainerCell noPaddingTop">

								<!-- CONTENT TABLE // -->
								<!--
									The content table is the first element
										that's entirely separate from the structural
										framework of the email.
								-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td valign="top" class="textContent">
											Name: <?php echo $resource['Resource']['name']; ?><br>
											Comment: "<?php echo $comment['Comment']['content']; ?>"
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
	<td align="center" valign="top">
		<!-- CENTERING TABLE // -->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center" valign="top">
					<!-- FLEXIBLE CONTAINER // -->
					<table border="0" cellpadding="0" cellspacing="0" width="480" class="flexibleContainer">
						<tr>
							<td align="center" valign="top" width="480" class="flexibleContainerCell bottomShim">

								<!-- CONTENT TABLE // -->
								<!--
									The emailButton table's width can be changed
										to affect the look of the button. To make the
										button width dependent on the text inside, leave
										the width blank. When a button is placed in a column,
										it's helpful to set the width to 100%.
								-->
								<table border="0" cellpadding="0" cellspacing="0" width="260" class="emailButton">
									<tr>
										<td align="center" valign="middle" class="buttonContent">
											<a href="<?php echo Router::url('/',true); ?>" target="_blank">view it in passbolt</a>
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
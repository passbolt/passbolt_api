					<div class="box information">
						<h2>Information</h2>
						<div class="box-content">
							<?php if($section == 'passwords'): ?>
							<div class="unavailable">Select a category on the left to see the corresponding information.</div>
							<div class="available">
								Created on <span class="date">--</span><br/>
								By <span class="owner">--</span><br/>
								<div class="pwstrength-container">
									Passwords strength : <span class="score-verdict"></span><br/>
									<div class="pwstrength">
										<div class="pwstrength_container">
											<div class="pwstrength_level" style="width:0%;">&nbsp;</div>
										</div>	
										<span class="score">0%</span>
										<hr class="spacer"/>
									</div> 
								</div>
							</div>
							<?php elseif($section == 'people'): ?>
							<div class="unavailable"><?php echo sizeof($users); ?> users.<br/><em>Select a user to see the corresponding information</em></div>
							<div class="available">
								Exists since <span class="date"></span><br/>
								Created by <span class="creator"></span><br/>
								Member of <span class="nbgroups"></span> groups
							</div>
							<?php endif; ?>
						</div> 
					</div>
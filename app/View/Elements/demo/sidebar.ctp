<?php
?>
<div class="resource">
	<h3>Linked In</h3>
	<a href="#" class="dialog-close"><i class="icon close no-text"></i><span>close</span></a>

	<div class="detailed-information">
	<h4>Information</h4>
	<ul>
		<li class="modified">
			<span class="label">Modified</span>
			<span class="value">three weeks ago</span>
		</li>
		<li class="modified-by">
			<span class="label">Modified by</span>
			<span class="value">you</span>
		</li>
		<li class="expire">
			<span class="label">Expire</span>
			<span class="value">in two days</span>
		</li>
		<li class="strength">
			<span class="label">Strength</span>
			<span>
				good
			</span>
			</li>
	</ul>
	</div>

	<div class="description">
		<h4>Description</h4>
		<a href="#editdesc" class="section-action"><i class="icon edit no-text"></i><span>edit</span></a>
		<p>This is the linked in account of the Acme organization, used mainly for recruitment purposes. Use with care.</p>
	</div>

	<div class="tags clearfix">
		<h4>Tags</h4>
		<a href="#edittags" class="section-action"><i class="icon edit no-text"></i><span>edit</span></a>
		<ul class="tags">
			<li>
				<a class="tag link" href="#">
					<span>a</span>
				</a>
			</li>
			<li>
				<a class="tag link" href="#">
					<span>bunch</span>
				</a>
			</li>
			<li>
				<a class="tag link" href="#">
					<span>of</span>
				</a>
			</li>
			<li>
				<a class="tag link" href="#">
					<span>tags</span>
				</a>
			</li>
			<li>
				<a class="tag link" href="#">
					<span>another long one</span>
				</a>
			</li>
			<li>
				<a class="tag link" href="#">
					<span>another</span>
				</a>
			</li>
		</ul>
	</div>

	<div class="comments clearfix">
		<h4>Comments</h4>
		<a href="#createcomment" class="section-action"><i class="icon create no-text"></i><span>create</span></a>
		<ul>
			<li class="comment-wrapper">
				<div class="comment">
					<div class="author profile picture"><a href="#"><img src="img/user.png"/></a></div>
					<p>This is a very short comment, maybe it fits!</p>
					<div class="metadata">
						<span class="author username"><a href="#">you</a></span>
						<span class="modified">2 days ago</span>
					</div>
					<div class="actions">
						<ul>
							<li><a href="#"><i class="icon reply no-text"></i><span>reply</span></a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="comment-wrapper reply">
				<div class="comment">
					<div class="author profile picture"><a href="#"><img src="img/user.png"/></a></div>
					<p>This is a very short comment, maybe it fits on a line or two who knows...</p>
					<div class="metadata">
						<span class="author username"><a href="#">someone else</a></span>
						<span class="modified">12 hours later</span>
					</div>
					<!-- no reply to a reply
					<div class="actions">
						<ul>
							<li><a href="#"><i class="icon reply no-text"></i><span>reply</span></a></li>
						</ul>
					</div> -->
				</div>
			</li>
			<li class="comment-wrapper">
				<div class="comment">
					<div class="author profile picture"><a href="#"><img src="img/user2.png"/></a></div>
					<p>This is a another comment, maybe it fits on a line or two, but it's slightly longer. So it be it, we shall allow very long comments.</p>
					<div class="metadata">
						<span class="author username"><a href="#">Test user with long username</a></span>
						<span class="modified">2 days and one month ago</span>
					</div>
					<div class="actions">
						<ul>
							<li><a href="#"><i class="icon reply no-text"></i><span>reply</span></a></li>
							<li><a href="#"><i class="icon delete no-text"></i><span>delete</span></a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="comment-wrapper">
				<div class="comment add">
					<div class="author profile picture"><a href="#"><img src="img/user.png"/></a></div>
					<div class="input textarea required">
						<label for="Comment">Add a comment</label>
						<input name="data[comment][parent_id]" class="required" maxlength="36" type="hidden" id="CommentParentId"/>
						<input name="data[comment][foreign_id]" class="required" maxlength="36" type="hidden" id="CommentForeignId"/>
						<input name="data[comment][foreign_model]" class="required" maxlength="36" type="hidden" id="CommentForeignId"/>
						<textarea name="data[comment][content]" class="required" maxlength="150" id="CommentContent" placeholder="add a comment"></textarea>
					</div>
					<div class="metadata">
						<span class="author username"><a href="#">You</a></span>
						<span class="modified">right now</span>
					</div>
					<div class="actions">
						<a href="#" class="button"><span>send</span></a>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>

(function($){	
	var instance = null;
	$.passbolt.users = {
		settings : {},
		instance : null,
		group_jstree_selector : "#groups-management.jstree",
		
		selected_user : null,
		user_groups : null,
		_jstree_change_state_on : false,
		
		_initialize : function(settings){
			this.settings = settings;
			this.instance = this;

			var pk = $.passbolt.users.getInstance();
			pk._initGroupTree("#groups-management.jstree");
			$('body').append('<div id="ajax-waiting"><img src="/css/img/loading.gif" /><br/><p class="msg">Saving settings...</p></div>'); // waiting window for ajax transactions
			

			$('ul.users>li').each(function(){
				var id = $(this).attr("id");
				$.passbolt.users.getInstance().uiUserAddEvents(id);
			});
			
			if($('.window-container.account').length){    // My account page
				$.passbolt.users.getInstance().validateUserAccountForm();
				// Manage group button
				$("#changepic").click(function(e){
					$.facebox({ ajax: $(this).attr('href'), success:$.passbolt.users.validateUserPhotoForm }); 		
					return false;
				});
			}
			
			$('a#addUserTopButton').click(function(){
				$.facebox({ ajax: $(this).attr('href'), success:$.passbolt.users.getInstance().initFaceboxUserAddForm }); 
				return false;
			});
			
			// Manage group button
			$("#managegroups").click(function(e){
				//e.stopImmediatePropagation();
				$.facebox({ ajax: /*$(this).attr('href')*/'/groups', 'success':$.passbolt.users.initFaceboxGroupManagementForm }); // load dynamically a url which will display information about the category				
				return false;
			});
			
			$('.userselected').hide();
			
			return this;
		},
		getInstance:function(){
			return this.instance;
		},
		_initGroupTree:function(selector, afterLoaded){
			$(selector).bind("loaded.jstree", function (event, data) {
			    if(afterLoaded)    
			    	afterLoaded();
			})
			.jstree({ 
				"plugins" : [ "themes", "html_data", "ui", "crrm", "types", "checkbox" ],
				"ui" : {
					"selected_parent_close" : "select_parent",
					"disable_selecting_children" : true
				},
				"checkbox" : {
					"two_state" : true
				},
				"types" : {
					"types" : {
						"group" : {
							"icon" : { 
								"image" : "/css/img/icons16x16/group.png" 
							}
						}
					}
				}
			});
			// checkbox "checked" event on the tree
			$(this.group_jstree_selector).bind("change_state.jstree", function (e, data) {
				// STEP 1 : don't let the user select more than one group in the same branch
				var id=data.rslt.attr("id");
				
				var tree = $($.passbolt.users.getInstance().group_jstree_selector);
				var selected_category = $("li#"+id, tree);
				var children = $("li", selected_category);
				var to_disable = Array();
				var i=0;
				children.each(function(elt){
					if($(this).hasClass('jstree-checked')){
						to_disable[i++]=$(this).attr("id");
					} 
				});
				
				var current = selected_category;
				while(current.parent()[0].tagName == "LI" || current.parent()[0].tagName == "UL"){
					if(current.parent()[0].tagName == "LI"){
						if(current.parent().hasClass('jstree-checked')){
							to_disable[i++]=current.parent().attr("id");
						}
					}
					current = current.parent();
				}
				
				for(i in to_disable){
					$("li#"+to_disable[i] ,tree).removeClass('jstree-checked').addClass('jstree-unchecked');
				}
				// END STEP 1
				
				var PBU = $.passbolt.users.getInstance();
				if(PBU._jstree_change_state_on){
					if(data['args'][0] !=  '[object Object]'){ // the value will be [object][Object] if the tree is rolled down (event that we don't want to handle)
						//var checked = jQuery.jstree._reference(PBU.group_jstree_selector+" #"+id).is_checked();
						var selected_li = $(PBU.group_jstree_selector + ' li.jstree-checked');
						var selected = {};
						var i = 0;
						selected_li.each(function(){
							selected[i] = $(this).attr("id");
							i++;
						});
						PBU.ajaxWait("#groups-management.jstree", true, "Saving group settings");
						PBU.userSetGroup($.passbolt.users.getInstance().selected_user, selected);
					}
				}
			});
		},
		_initGroupManagementTree:function(selector){
			$(selector).jstree({ 
				"plugins" : [ "themes", "html_data", "contextmenu", "ui", "crrm", /*"dnd",*/ "types" ],
				"ui" : {
					"selected_parent_close" : "select_parent",
					"disable_selecting_children" : true
				},
				"checkbox" : {
					"two_state" : true
				},
				"types" : {
					"types" : {
						"group" : {
							"icon" : { 
								"image" : "/css/img/icons16x16/group.png" 
							}
						},
						"grouproot" : {
							"icon" : { 
								"image" : "/css/img/icons16x16/grouproot.png" 
							}
						}
					}
				}
			})
			.bind("create.jstree", function (e, data) {
				$.post(
						"/groups/create", 
						{ 
							"operation" : "create_node", 
							"id" : (data.rslt.parent.length ? data.rslt.parent.attr("id").replace("node_","") : 0), 
							"position" : (data.rslt.parent.length ? data.rslt.position : $('#groups-management-window #groups-management.jstree>ul>li').length),
							"title" : data.rslt.name,
							"type" : data.rslt.obj.attr("rel")
						}, 
						function (r) {
							if(r.status) {										// if status is fine
								$(data.rslt.obj).attr("id", r.id);				// insert the id in the new html node
								// Insertion is a success, we refresh the groups widget
								$.passbolt.users.getInstance().refreshGroups();
							}
							else {
								$.jstree.rollback(data.rlbk);
							}
						},
						'json'
					);
				}).bind("before.jstree", function (e, data) {
					if(data.func == "remove") {
						if(!confirm("Are you sure you really want to delete this group ?")){
							e.stopImmediatePropagation();
							return false;
						}
					}
				})
				.bind("remove.jstree", function (e, data) {
					if(data != null){
						data.rslt.obj.each(function () {
							$.ajax({
								async : false,
								type: 'POST',
								url: "/groups/delete",
								data : { 
									"operation" : "remove_node", 
									"id" : this.id
								}, 
								success : function (r) {
									if(!r.status) {
										data.inst.refresh();
									}
									// Deletion is a success, we refresh the groups widget
									$.passbolt.users.getInstance().refreshGroups();
								},
								dataType : 'json'
							});
						});
					}
				})
				.bind("rename.jstree", function (e, data) {
					$.post(
						"/groups/rename", 
						{ 
							"operation" : "rename_node", 
							"id" : data.rslt.obj.attr("id"),
							"title" : data.rslt.new_name
						}, 
						function (r) {
							if(!r.status) {
								$.jstree.rollback(data.rlbk);
							}
							// Renaming is a success, we refresh the groups widget
							$.passbolt.users.getInstance().refreshGroups();
						},
						'json'
					);
				});
				
				// opens the tree by default
				$(selector).jstree("open_all", -1);
		},
		refreshGroups:function(){
			$.get("/groups/getTree", function(data){
				$('.sidebar #groups-management.jstree').html(data);
				$.passbolt.users.getInstance()._initGroupTree('.sidebar #groups-management.jstree');
			});
		},
		treeSelectGroups:function(groups, tree){
			for(i in groups){
				if(groups[i] != ''){
					var parents = tree.jstree("get_path",$("#"+groups[i]),true);
					parents = String(parents).split(",");
					for(var j=0; j<parents.length; j++){
						jQuery.jstree._reference(tree).open_node($("#"+parents[j], tree));
					}
					tree.jstree("check_node", $("#"+groups[i], tree));
				}
			}
			return true;
		},
		selectUser:function(id){
			this.selected_user = id;
			$.passbolt.users.getInstance()._jstree_change_state_on = false; // stop listening event on checkbox (coz we are going to select manually first, so we don't want to trigger a change_state event)
	
			$('ul.users>li.selected').removeClass('selected');
			$($.passbolt.users.getInstance().group_jstree_selector).jstree("uncheck_all");
			$($.passbolt.users.getInstance().group_jstree_selector + " li").removeClass('jstree-checked').addClass('jstree-unchecked');
			
			// if the user is an admin, we load the permission into a selectable tree
			var is_admin = $.passbolt.getInstance().userAdmin;
			if(is_admin){
				$.passbolt.users.getInstance().ajaxWait($.passbolt.users.getInstance().group_jstree_selector, true, "loading groups");
				$.post(
						"/users/listGroups", 
						{ 
							"id" : id
						}, 
						function (r) {
							$('.sidebar .available').show();
							$('.sidebar .unavailable').hide();
							if(r['admin'] == '1'){
								$('.sidebar .available .admin').show();
								$('.sidebar .available .groups').hide();
							}
							else{
								$('.sidebar .available .groups').show();
								$('.sidebar .available .admin').hide();
								var groups = Array();
								for(i in r['user_groups']){
									groups[i] = r['user_groups'][i].id;
								}
								$.passbolt.users.getInstance().treeSelectGroups(groups, $($.passbolt.users.getInstance().group_jstree_selector));	// select the groups in the tree view
								
								$.passbolt.users.getInstance()._jstree_change_state_on = true; // start listening event "checked"	
							}
							$.passbolt.users.getInstance().ajaxWait($.passbolt.users.getInstance().group_jstree_selector, false);
						},
						'json'
					);
			}
			// else, we just display the groups name, no actions will be possible
			else{
				$.post(
						"/users/listGroups", 
						{ 
							"id" : id
						}, 
						function (r) {
							$('.sidebar .available').show();
							$('.sidebar .unavailable').hide();
							if(r['admin'] == '1'){
								$('.sidebar .available .admin').show();
								$('.sidebar .available .groups').hide();
							}
							else{
								$('.sidebar .available .groups').show();
								$('.sidebar .available .admin').hide();
								var groups = r["user_groups"];
								var html = "<ul>";
								for(i in groups){
									if(groups[i] != ''){
										html += "<li>" + groups[i]['name'] + "</li>";
									}
								}
								html += "</ul>";
								$(".box.groups #groups-management").html(html);
							}
						},
						'json'
					);
			}
			
			this.uiLoadUserInfo(id);
			this.uiUserLoadActivity(0);
			
			$('ul.users>li#'+id).addClass('selected');
		},
		userSetGroup:function(user_id, groups_id){
			$.post('/users/setGroup', {"user_id":user_id, "groups_id":groups_id}, function(data){
				$.passbolt.users.getInstance().ajaxWait("#groups-management.jstree", false);
				if(data.status == '0' && data.data["error_code"] == "permission_not_valid"){
					$.jnotify("You don't have the rights to change the groups of a user", "error", 5000);
				}
				return true;
			}, 
			'json');
		},
		userDeactivate:function(id){
			$.post('/users/deactivate', {"id":id}, function(data){
				if(data.result == 1)
					$('.users #'+id).addClass("deactivated");
					$('.users #'+id+' .deactivate').css("display", "none");
					$('.users #'+id+' .activate').css("display", "");
			}, 'json');
		},
		userActivate:function(id){
			$.post('/users/activate', {"id":id}, function(data){
				if(data.result == 1)
					$('.users #'+id).removeClass("deactivated");
					$('.users #'+id+' .deactivate').css("display", "");
					$('.users #'+id+' .activate').css("display", "none");
			}, 'json');
		},
		uiUserAdd:function(user){
			var user_html='<li id="'+ user["id"] +'" class="">'
				+'<div class="image">'
				+'<img src="/img/users/avatar.png">'
				+ (user['admin'] == 1 ? '<em>admin</em>' : '')
				+'</div>'
				+'<ul class="details">'
					+'<li class="name">'+ user['name'] +'</li>'
					+'<li class="email">' + user['email'] + '</li>'
					+'<li class="lastlogin">last login : never</li>'
					+'<li class="edit"><a class="edit" href="">edit</a> | <a class="email" href="">email</a> | <a class="deactivate" href="">deactivate</a><a style="display: none;" class="activate" href="">activate</a> | <a class="remove" href="">remove</a></li>'
				+'</ul>'
			+'</li>';
			
			$('.window-container.people ul.users').append(user_html);
			this.uiUserAddEvents(user["id"]); 			// add the events to the user
		},
		uiUserModify:function(id, user){
			var user_container = $('.window-container.people>users li#'+id);
			$('.details li.name', user_container).html(user["name"]);
			$('.details li.email', user_container).html(user["email"]);
		},
		uiUserAddEvents:function(id){
			var user = $('ul.users>li#'+id);
			user.mouseenter(function(){ $(this).addClass('hover'); })
			.mouseleave(function(){ $(this).removeClass('hover'); })
			.click(function(){ $.passbolt.users.getInstance().selectUser($(this).attr('id')); });
			
			// deactivate fuction
			$('.deactivate', user).click(function(e){
				e.stopImmediatePropagation();
				var id = $(this).parent().parent().parent().attr('id');
				$.passbolt.users.getInstance().userDeactivate(id);
				return false;
			});
			$('.activate', user).click(function(e){
				e.stopImmediatePropagation();
				var id = $(this).parent().parent().parent().attr('id');
				$.passbolt.users.getInstance().userActivate(id);
				return false;
			});
			$('.remove', user).click(function(e){
				e.stopImmediatePropagation();
				var id = $(this).parent().parent().parent().attr('id');
				$.post('/users/delete', {"id":id}, function(data){
						if(data.result == 1)
							$('.users #'+id).remove();
				}, 'json');
				return false;
			});
			$('.edit', user).click(function(e){
				e.stopImmediatePropagation();
				var id = $(this).parent().parent().parent().attr('id');
				$.facebox({ ajax: '/users/edit/'+id, success:$.passbolt.users.getInstance().initFaceboxUserAddForm }); 
				return false;
			});
		},
		uiLoadUserInfo:function(id){
			$.get('/users/getInfo/'+id, function(data){
				$('.box.information .date').html(data["data"]["created"]);
				$('.box.information .creator').html(data["data"]["created_by"]);
				$('.box.information .nbgroups').html(data["data"]["nbgroups"]);
			},
			'json');
		},
		uiUserLoadActivity:function(offset){
			if(!offset) offset = 0;
			var user_id = $.passbolt.users.getInstance().selected_user;
			$.post('/events/retrieveForUser/' + offset, {"user_id":user_id}, function(data){
				if(data){
					$('.box.activity .box-content').html(data);
					setTimeout("$.passbolt.users.getInstance().uiUserLoadActivity(0)", 25000);
				}
			});
		},
		ajaxWait:function(selector, show, msg){
			var offset = $(selector).offset();
			var width = $(selector).width();
			var height = $(selector).height();
			if(msg != null) $("#ajax-waiting .msg").html(msg);
			$("#ajax-waiting").css({"display":(show==true ? "block" : "none"), "top":offset.top, "left":offset.left, "width":width+"px", "height":height+"px"});
		},
		validateUserPhotoForm:function(){
			$('#UserAccountChangePictureForm').validate({
				   submitHandler: function() { 
					   var options = {
								success:function(data){
									$(document).trigger('close.facebox');
									$.jnotify("Your photo has been modified succesfully", 5000);
									$('.user_photo>img').attr('src', '/img/users/big/'+data["data"]["User"]["avatar"]);
								},
								dataType:'json'
							};
						$('#UserAccountChangePictureForm').ajaxSubmit(options);
				   },
					rules: {
					   "data[User][avatar]": {
							required: true,
							accept:"jpg|jpeg|png|gif"
						}
					},
					messages: {
						"data[User][avatar]": {
							required: "You have to select a file",
							accept: "The extensions accepted are .jpg, .jpeg, .png, .gif"
						}
					}
				});
		},		
		validateUserAccountForm:function(){
			$('#UserAccountForm').validate({
			   submitHandler: function() { 
				   var options = {
							success:function(data){
								$(document).trigger('close.facebox');
								$.jnotify("Your account has been modified succesfully", 5000);
							},
							dataType:'json'
						};
					$('#UserAccountForm').ajaxSubmit(options);
			   },
				rules: {
				   "data[User][name]": {
						required: true,
						minlength: 4
					},
					"data[User][email]": {
						required: true,
						email: true,
						minlength: 2
					},
					"data[User][username]": {
						required: true,
						minlength: 5
					},
					"data[User][password]": {
						required: true,
						minlength: 3
					},
					"data[User][passwordRepeat]": {
						required: true,
						minlength: 3,
						equalTo: "#UserPassword"
					}
				},
				messages: {
					"data[User][name]": {
						required: "Please enter a name for the user",
						minlength: "The name must consist of at least 4 characters"
					},
					"data[User][email]": {
						required: "Please enter an email for the user",
						email: "Please enter a proper email",
						minlength: "The email must consist of at least 2 characters"
					},
					"data[User][username]": {
						required: "Please enter a username for the user",
						minlength: "The username must consist of at least 5 characters"
					},
					"data[User][password]": {
						required: "Please enter a password",
						minlength: "The password must contain at least 3 characters"
					},
					"data[User][passwordRepeat]": {
						required: "Please repeat the password",
						minlength: 3,
						equalTo: "The password must be the same as above"
					}
				}
			});
		},
		initFaceboxGroupManagementForm:function(data){
			if($('#groups-management-window').length){ 
				var treeselector = "#groups-management-window .jstree";
				$.passbolt.users.getInstance()._initGroupManagementTree(treeselector);
				$('#groups-management-window #add_group').click(function(){
					$(treeselector).jstree("create","last",{"attr":{"rel":"group"}},"New Group",function(a){ $(a).attr("rel", "group"); }, false); 
					return false;
				});
				return false;
			}
		},
		initFaceboxUserAddForm:function(data){
			if($('#window-user').length){ // if the window which opens is the add user window
				$("#UserName").focus();
				
				var formId = ($('#UserAddForm').length ? '#UserAddForm' : '#UserSaveForm');
				
				$(formId).validate({
					errorPlacement: function(error, element) {
						if(element.attr("id") == "UserGroupsId")
							$(".groups-container").append(error);
						else
							element.parent().append(error);
				   },
				   submitHandler: function() { 
					   var options = {
								success:function(data){
						   			var msg = "The user has been added";
						   			var formId = "#UserAddForm";
						   			if($('#UserSaveForm').length){
						   				msg = "The user has been modified";
						   				formId = "#UserSaveForm";
						   			}
									$(document).trigger('close.facebox');
									if(formId == '#UserAddForm')
										$.passbolt.users.getInstance().uiUserAdd(data["data"]["User"]);
									$.jnotify(msg, 5000);
								},
								dataType:'json'
							};
						$(formId).ajaxSubmit(options);
				   },
					rules: {
					   "data[User][name]": {
							required: true,
							minlength: 4
						},
						"data[User][email]": {
							required: true,
							email: true,
							minlength: 2
						},
						"data[User][username]": {
							required: true,
							minlength: 5
						},
						"data[User][password]": {
							required: true,
							minlength: 3
						},
						"data[User][passwordRepeat]": {
							required: true,
							minlength: 3,
							equalTo: "#UserPassword"
						}
					},
					messages: {
						"data[User][name]": {
							required: "Please enter a name for the user",
							minlength: "The name must consist of at least 4 characters"
						},
						"data[User][email]": {
							required: "Please enter an email for the user",
							email: "Please enter a proper email",
							minlength: "The email must consist of at least 2 characters"
						},
						"data[User][username]": {
							required: "Please enter a username for the user",
							minlength: "The username must consist of at least 5 characters"
						},
						"data[User][password]": {
							required: "Please enter a password",
							minlength: "The password must contain at least 3 characters"
						},
						"data[User][passwordRepeat]": {
							required: "Please repeat the password",
							minlength: 3,
							equalTo: "The password must be the same as above"
						}
					}
				});
				
				var PBU = $.passbolt.users.getInstance();
				PBU._jstree_change_state_on = true;
				// init category system
				PBU._initGroupTree(
					"#window-user .groups", 
					function(){ // function to execute once the tree is loaded : we load the groups in the tree
						// in case of edition, synchronize the groups with the groups view
						var user_groups = $('#window-user #UserGroupsId').val();
						if(user_groups != ''){
							var ug = user_groups.split(",");
							PBU._jstree_change_state_on = false;
							PBU.treeSelectGroups(ug, $("#window-user .groups")); // select the groups in the tree
							PBU._jstree_change_state_on = true;
							//tree.jstree("open_node",$("#"+parents[j]));
					}
				});
				
				$("#window-user .groups").bind("change_state.jstree", function (e, data) {
					if($.passbolt.users.getInstance()._jstree_change_state_on == true){
						// STEP 1 : don't let the user select more than one group in the same branch
						var id=data.rslt.attr("id");
						
						var tree = $("#window-user .groups");
						var selected_category = $("li#"+id, tree);
						var children = $("li", selected_category);
						var to_disable = Array();
						var i=0;
						children.each(function(elt){
							if($(this).hasClass('jstree-checked')){
								to_disable[i++]=$(this).attr("id");
							} 
						});
						
						var current = selected_category;
						while(current.parent()[0].tagName == "LI" || current.parent()[0].tagName == "UL"){
							if(current.parent()[0].tagName == "LI"){
								if(current.parent().hasClass('jstree-checked')){
									to_disable[i++]=current.parent().attr("id");
								}
							}
							current = current.parent();
						}
						
						for(i in to_disable){
							$("li#"+to_disable[i] ,tree).removeClass('jstree-checked').addClass('jstree-unchecked');
						}
						// END STEP 1;
						
						// STEP 2 : fill up the hidden field to mention what groups have been selected
						var selected = "";
						$('.jstree-checked', $("#window-user .groups")).each(function(){ if($(this).attr('id') != ''){ selected += ($(this).attr('id')+","); } });
						$('#UserGroupsId').attr("value", selected);
					}
				});	
			}
		}
	};
})(jQuery);

$(function(){
	//var group_jstree_selector = "#groups-management.jstree";
	var settings = {};
	var PBUsers = $.passbolt.users._initialize(settings);
});	
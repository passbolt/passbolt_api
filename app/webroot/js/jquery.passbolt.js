(function($){	
	var instance = null;
	
	$.passbolt = {
		settings : {},
		instance : null,
		jstree : null,
		aco_id : null,                  // type of aco selected (1=category, 2=password)
		aco_ref_id : null,				// id of the aco selected
		userAdmin : 0,					// is the user an administrator ?
		_users_list:null,
		_users_groups:null,
		_permissions : {},
		_cpermissions : {},				// permissions per category (for the authenticated user)
		_passwords : {},
		_pstrength : 0,
		_clipboard_clip : null,
		_activity_offset : 0,
		_passwordClear : false, 		// tells whether the password id displayed in clear in the passwor creation box
		_copied : null,					// passbolt's clipboard (does not contain the passwords content which uses flash. will contain likely a password or a category)
		
		_initialize : function(settings){
			this.settings = settings;
			this.instance = this;
			
			// initialize the passwords window in case it needs to be
			if($('.window-container .passwords').length){
				// calculates size for the passwords ui
				$.passbolt.ui.passwords.calculateDimensions();
				$(window).resize(function() {
					$.passbolt.ui.passwords.calculateDimensions();
					$.passbolt.ui.passwords.refreshDimensions();
				});
				$('.categories').resizable({ handles: 'e', resize: function (){
					var percent = ( 100 * parseFloat($(this).css("width")) / parseFloat($(this).parent().css("width")) );
					$(this).css("width" , percent + "%");
					$('.passwords').css("width", (100-percent) + "%");
					},
					stop:function(){
						$.passbolt.ui.passwords.calculateDimensions();
						$.passbolt.ui.passwords.refreshDimensions();
					}
				});
			}
			
			this.userAdmin = (parseInt(settings.userAdmin) == 0 ? false : true);
			
			// init category tree FX effect
			this._initCategoryTree();
			
			// setup single ZeroClipboard object for all our elements
			ZeroClipboard.setMoviePath( 'js/ZeroClipboard/ZeroClipboard.swf' );

			// Context Menu
			$('.container').click(function(){
				$.passbolt.ui.menu.remove();
			});
			
			// password adding control
			$('a#addPasswordButton').click(function(){
				$.facebox({ ajax: $(this).attr('href')+'/'+ $.passbolt.getInstance().aco_ref_id }); // load dynamically a url which will display information about the category
				return false;
			});
			
			if($('.activity-filters input.users').length){
				$(".activity-filters input.users").tokenInput("/users/ajaxSearch",{
					minChars: 1,
	                tokenLimit: 1,
	                hintText: "start typing a user name or email",
	                noResultsText: "no user has been found",
	                searchingText: "Searching user..."
				});
				
				$( ".datefrom, .dateto" ).datepicker();
				
			}
			
			// init UI and hide some info in the sidebar
			$('.sidebar .unavailable').show();
			$('.sidebar .available').hide();
			
			$(document).bind('reveal.facebox', function(data){ 
				// remove contextual menu if any
				$.passbolt.ui.menu.remove();
				
				function showStrength(){
					var pwd = ($('#PasswordPassword').val());
					if(pwd != ''){
						$(".pw_score").show();
						var score = $.passbolt.password.getStrength(pwd);
						var colors = $.passbolt.password.getVerdictColors();
						var verdict = $.passbolt.password.getVerdict(score);
						$(".pw_score .score-verdict").html(verdict);
						//alert(colors[verdict]);
						$(".pw_score .score-verdict").css('color', '#'+colors[verdict]);
						$(".pw_score .score").css('width', score + '%');
						$(".pw_score .score").css('backgroundColor', '#'+colors[verdict]);
					}
					else{
						$(".pw_score").hide();
					}
					
				}
				if($('#PasswordAddForm,#PasswordEditForm').length){
					// in case it is the edit form, we need to set the right value in the password field
					if($('#PasswordEditForm').length){
						var masterkey = $.cookie('passbolt[masterkey]');
				   		var decrypted = Aes.Ctr.decrypt($("#PasswordEditForm #PasswordPassword").val(), masterkey, 256);
						$('#PasswordPassword, #PasswordPasswordClear, #PasswordPasswordRepeat, #PasswordPasswordRepeatClear').val(decrypted);
						showStrength();
					}
					else if($('#PasswordAddForm').length){
						$(".pw_score").hide();
					}
					
					var form = $('#PasswordAddForm,#PasswordEditForm');
					$('#PasswordTitle', form).focus();
					// validate signup form on keyup and submit
					
					// calculates password strength
					$('#PasswordPassword').keyup(function(){
						showStrength();
					});
					
					// manage tab order inside the form
					$('#PasswordPassword', form).keydown(function(event){
						if (event.keyCode == '9' && event.shiftKey) {
							event.preventDefault();
						    $("#PasswordUsername").focus();
						} 
						else if (event.keyCode == '9') {
						     event.preventDefault();
						     $("#PasswordPasswordRepeat").focus();
						}	
					});
					$('#PasswordPasswordRepeat', form).keydown(function(event){
						if (event.keyCode == '9' && event.shiftKey) {
						    event.preventDefault();
						    $("#PasswordPassword").focus();
						}
						else if (event.keyCode == '9') {
						    event.preventDefault();
						    $("#PasswordUrl").focus();
						}
					});
					
					$('#PasswordUrl').keydown(function(event){
						if (event.keyCode == '9' && event.shiftKey) {
						    event.preventDefault();
						    $("#PasswordPasswordRepeat").focus();
						}
					});
					
					$("#PasswordAddForm .cancel").click(function(){
						$(document).trigger('close.facebox');
						return false;
					});
					
					$("#PasswordAddForm, #PasswordEditForm").validate({
						errorPlacement: function(error, element) {
							element.parent().append(error);
					   },
					   submitHandler: function() { 
						   var options = {
								beforeSubmit:function(arr, $form, options){
							   		// before submit, we encrypt the passwords that are submitted
							   		var passwordVal = arr[4]["value"];
							   		var masterkey = $.cookie('passbolt[masterkey]');
							   		var encrypted = Aes.Ctr.encrypt(passwordVal, masterkey, 256);
							   		arr[4]["value"] = encrypted;
							   		arr[5]["value"] = encrypted;
								},
								success:function(data){
									if($("#PasswordEditForm").length){
										$.passbolt.getInstance().updatePassword(data["data"]["Password"]["id"], data["data"]["Password"]);
										$.passbolt.getInstance().updatePasswordStrength();
										$.passbolt.getInstance().uiLoadScore();
									}
									else{
										$.passbolt.getInstance().addPasswordLine(data['data']['Password']);
										$.passbolt.getInstance()._passwords[data['data']['Password']['id']] = data['data']['Password'];
										$.passbolt.getInstance().updatePasswordStrength();
										$.passbolt.getInstance().uiLoadScore();
									}
									$(document).trigger('close.facebox');
									//data['data']['Password']['password'] = Aes.Ctr.encrypt(data['data']['Password']['password'], $.cookie('PassBolt[masterkey]'), 256);
									$.jnotify("The password has been saved", 5000);
								},
								dataType:'json'
							};
							$('#PasswordAddForm, #PasswordEditForm').ajaxSubmit(options);
					   },
						rules: {
							"data[Password][title]": {
								required: true,
								minlength: 3
							},
							"data[Password][username]": {
								required: true,
								minlength: 3
							},
							"data[Password][password]": {
								required: true,
								minlength: 4
							},
							"data[Password][passwordRepeat]": {
								required: true,
								minlength: 4,
								equalTo: "#PasswordPassword"
							}
						},
						messages: {
							"data[Password][title]": {
								required: "Please enter a title",
								minlength: "The title must consist of at least 3 characters"
							},
							"data[Password][username]": {
								required: "Please enter a username",
								minlength: "Your username must consist of at least 3 characters"
							},
							"data[Password][password]": {
								required: "Please provide a password",
								minlength: "Your password must be at least 4 characters long"
							},
							"data[Password][passwordRepeat]": {
								required: "Please provide a password",
								minlength: "Your password must be at least 4 characters long",
								equalTo: "Please enter the same password as above"
							}
						}
					});

					
					$('#generate').click(function(){
						$.get("/passwords/generate", function(data){
							$('#PasswordPassword').val(data);
							$('#PasswordPasswordRepeat').val(data);
							showStrength();
						});
					});
	
					// duplicate the password fields
					$('<input type="text" id="PasswordPasswordClear" autocomplete="off" value="'+ $('#PasswordPassword').val() +'">').insertAfter('#PasswordPassword').css('display','none');
					$('<input type="text" id="PasswordPasswordRepeatClear" autocomplete="off" value="'+ $('#PasswordPasswordRepeat').val() +'">').insertAfter('#PasswordPasswordRepeat').css('display','none');
					
					// The 2 instructions below make sure that if there is a manual modification in the clear password field,
					// the changes reflect also in the password-type field (not clear)
					$('#PasswordPasswordClear', form).keyup(function(event){
							$('#PasswordPassword').val($('#PasswordPasswordClear').val());
					});
					
					$('#PasswordPasswordRepeatClear', form).keyup(function(event){
							$('#PasswordPasswordRepeat').val($('#PasswordPasswordRepeatClear').val());
					});
					
					// See button feature handling
					$('#see').click(function(){
						if(!$.passbolt.getInstance().passwordClear){
							$('#PasswordPassword').css('display','none');
							$('#PasswordPasswordClear').val($('#PasswordPassword').val());
							$('#PasswordPasswordClear').css('display','block');
							$('#PasswordPasswordRepeat').css('display','none');
							$('#PasswordPasswordRepeatClear').val($('#PasswordPasswordRepeat').val());
							$('#PasswordPasswordRepeatClear').css('display','block');
							$.passbolt.getInstance().passwordClear = true;
							$(this).html('Hide');
						}
						else{
							$('#PasswordPassword').css('display','block');
							$('#PasswordPasswordClear').val($('#PasswordPassword').val());
							$('#PasswordPasswordClear').css('display','none');
							$('#PasswordPasswordRepeat').css('display','block');
							$('#PasswordPasswordRepeatClear').val($('#PasswordPasswordRepeat').val());
							$('#PasswordPasswordRepeatClear').css('display','none');
							$.passbolt.getInstance().passwordClear = false;
							$(this).html('See');
						}
					});
				}
			});
			$('.formperm').hide();
			$("#addperm").click(function(){
				var p = $('.formperm');
				parwidth = p.parent().width();
				p.css('width',parwidth+'px');
				p.css('left',(p.parent().offset().left) +'px');
				p.animate({"height":"toggle", "opacity":"toggle"});
				$(this).toggleClass('clicked');
				return false;
			});
			
			// Event search form (Activity part)
			if($('.window-container.events').length){
				$('#EventRetrieveForm').ajaxForm({"target" : ".window-container", "success":function(){
					$.passbolt.getInstance().UIPreparePagination('/activity', '#EventRetrieveForm');
				}});
				$.passbolt.getInstance().UIPreparePagination('/activity', '#EventRetrieveForm');
				
				$('#EventRetrieveForm a.reset').click(function(){
					$('#EventRetrieveForm').resetForm();
					if($('#EventRetrieveForm .token-input-delete-token').length){
						$('#EventRetrieveForm .token-input-delete-token').click();
					}
					$.get('/activity', function(data){
						$(".window-container.events").html(data);
					});
					return false;
				});
			}
			
			
			// To be executed at each jquery event :
			$(document).ajaxComplete($.passbolt.onAjaxComplete);
			
			return this;
		},
		UIPreparePagination:function(url, tform){
			$('#activity-nav a').click(function(){
				if($(this).attr('href') != "#"){
					var tforms = null;
					if(tform) tforms = $(tform).formSerialize();
					
					$.post(url + '/' + $(this).attr('href'), tforms,function(data){
						$('.window-container.events').html(data);
						$.passbolt.getInstance().UIPreparePagination(url, tform);
					});
				}
				return false;
			});
		},
		_databaseInitialize:function(){
			$('#database_select select').change(function(){
				var val = $(this).val();
				//document.location.href="/categories/changeDatabase/"+val;
				$.post('/categories/getDatabaseTree', {"id":val}, function(data){
					$("#categories").empty();
					$("#categories").html(data);
					$.passbolt.getInstance()._initCategoryTree();
					// TODO : reinitialize other screens
					$('.box .available').hide();
					$('.box .unavailable').show();
					$.passbolt.ui.passwords.initialize();
				});
			});
			
			$("#newdatabaselink").click(function(){
				$.facebox({ ajax: "/categories/createDatabase" }); // load dynamically a url which will display information about the category
				return false;
			});
			
			$(document).bind('afterReveal.facebox', function(data){ 
				if($('#CategoryCreateDatabaseForm').length){

					$("#CategoryName").focus();
					$('#CategoryCreateDatabaseForm').validate({
						errorPlacement: function(error, element) {
							element.parent().parent().append(error);
					   },
					   submitHandler: function() { 
						   var options = {
									success:function(data){
							   			if(data.status == '1'){
											$(document).trigger('close.facebox');
											$('#database_select select').append('<option value="'+data['data']['id']+'">'+data['data']['name']+'</option>');
											$.jnotify("The database has been created", 5000);
							   			}
							   			else{
							   				$("#window-createdb .createdatabase").append('<label class="error" for="CategoryName">'+ data['data']['error_code'] + '</label>');
							   			}
									},
									dataType:'json'
								};
								$('#CategoryCreateDatabaseForm').ajaxSubmit(options);
					   },
						rules: {
							"data[Category][name]": {
								required: true,
								minlength: 2
							}
						},
						messages: {
							"data[Category][name]": {
								required: "Please enter a name for the category",
								minlength: "The name must consist of at least 2 characters"
							}
						}
					});
				}
			});
		},
		getInstance:function(){
			return this.instance;
		},
		getPermissionByCategory:function(aco_id, category_id){
			return this._cpermissions[aco_id][category_id];
		},
		setPermission:function(aco_id, category_id, permission){
			this._cpermissions[aco_id][category_id] = permission;
		},
		/**
		 * Initialize permissions and render the tree (jstree)
		 * @returns
		 */
		_initCategoryTree:function(){
			// 1) initialize permissions
			var pb = $.passbolt.getInstance();
			pb._cpermissions[1] = new Array();
			$("#categories ul li").each(function(){				// load permission for each category inside $.passbolt.cpermissions array
				var id = $(this).attr('id');
				if($(this).hasClass('r--'))
					pb._cpermissions[1][id] = 'r--';
				else if($(this).hasClass('rw-'))
					pb._cpermissions[1][id] = 'rw-';
				else if($(this).hasClass('rwm'))
					pb._cpermissions[1][id] = 'rwm';
				else
					pb._cpermissions[1][id] = '---';
			});
			
			// 2) initialize UI tree
			this.jstree = $("#categories.jstree").jstree({ 
				"plugins" : [ "themes", "html_data", "contextmenu", "ui", "crrm", "dnd", "types", "cookies" ],
				"types" : {
					"valid_children" : [ "root" ],
					"types" : {
						"root" : {
							"valid_children" : [ "default" ],
							// the declaration below prevents clicking on categories with no permissions
							"select_node" : function(elt){ if( elt.parentNode &&  $.passbolt.getInstance().getPermissionByCategory(1, elt.parentNode.id) == '---') return false; else return true; }
						},
						"norights" : {
							"icon" : { "image" : "/css/img/icons16x16/database.png" },
							"hover_node" : false,
							"select_node" : function(){ return false; }
						},
						"default" : { 
							"valid_children" : [ "default" ],
							"select_node" : function(elt){ if( elt.parentNode &&  $.passbolt.getInstance().getPermissionByCategory(1, elt.parentNode.id) == '---') return false; else return true; }
						}
					}
				},
				"ui" : {
					"select_limit" : 1,
					"select_multiple_modifier" : "alt",
					"selected_parent_close" : "select_parent"
				},
				"contextmenu" : {
					"items" : function(node){
						return $.passbolt.getInstance().contextMenu(node.attr('id'));
					}
				},
				"crrm" : { 
					"move" : {
						"check_move" : function (m) { 
							var id_src = m.o.attr("id");
							var id_dst = m.np.attr("id");
							var src_perm = $.passbolt.getInstance().getPermissionByCategory(1, id_src);
							var dst_perm = $.passbolt.getInstance().getPermissionByCategory(1, id_dst);
							if((src_perm == 'rw-' || src_perm == 'rwm') && (dst_perm == 'rw-' || dst_perm == 'rwm')){
									return true;
							}	
							return false;
						}
					}
				},
				"dnd" : {
						// put here the data for passwords to categories drag n drop
					//"drop_check" : function(){ alert("test"); return false; }
					"drag_target" : '.passwords tr td.title',
					"drag_check" : function(data){
						var id_src = $(data.o).parent().attr('id');
						var id_dst = $(data.r).attr('id');
						var dst_perm = $.passbolt.getInstance().getPermissionByCategory(1, id_dst);
						//$.passbolt.debug(dst_perm);
						if(dst_perm == 'rw-' || dst_perm == 'rwm'){
								return {
									"after":false,
									"before":false,
									"inside":true
								};
						}
						else{
							return false;
						}
					},
					"drag_finish" : function(data){
						var id_src = $(data.o).parent().attr('id');
						var id_dst = $(data.r).attr('id');
						$.passbolt.movePassword(id_src, id_dst);
					}
				}

			})
			.bind("select_node.jstree", function(e, data){
				var id = data.rslt.obj.attr("id");
				$.passbolt.getInstance().onNodeSelect('1', id);
			})
			.bind("create.jstree", function (e, data) {
				$.post(
						"/categories/create", 
						{ 
							"operation" : "create_node", 
							"id" : data.rslt.parent.attr("id").replace("node_",""), 
							"position" : data.rslt.position,
							"title" : data.rslt.name,
							"type" : data.rslt.obj.attr("rel")
						}, 
						function (r) {
							if(r.status) {										// if status is fine
								$(data.rslt.obj).attr("id", r.id);				// insert the id in the new html node
								var parent_id = data.rslt.parent.attr("id");
								// set the same permissions as the parent
								var parent_perm = $.passbolt.getInstance().getPermissionByCategory(1, parent_id);
								$.passbolt.getInstance().setPermission(1, r.id, parent_perm);
								
							}
							else {
								$.jstree.rollback(data.rlbk);
							}
						},
						'json'
					);
				})
				.bind("remove.jstree", function (e, data) {
					data.rslt.obj.each(function () {
						$.ajax({
							async : false,
							type: 'POST',
							url: "/categories/delete",
							data : { 
								"operation" : "remove_node", 
								"id" : this.id
							}, 
							success : function (r) {
								if(!r.status) {
									data.inst.refresh();
								}
							},
							dataType : 'json'
						});
					});
				})
				.bind("rename.jstree", function (e, data) {
					$.post(
						"/categories/rename", 
						{ 
							"operation" : "rename_node", 
							"id" : data.rslt.obj.attr("id"),
							"title" : data.rslt.new_name
						}, 
						function (r) {
							if(!r.status) {
								$.jstree.rollback(data.rlbk);
							}
						},
						'json'
					);
				})
				.bind("move_node.jstree", function (e, data) {
					data.rslt.o.each(function (i) {
						$.ajax({
							async : false,
							type: 'POST',
							url: "/categories/move",
							data : { 
								"operation" : "move_node", 
								"id" : $(this).attr("id"), 
								"ref" : data.rslt.np.attr("id"), 
								"position" : data.rslt.cp + i,
								"title" : data.rslt.name,
								"copy" : data.rslt.cy ? 1 : 0
							},
							success : function (r) {
								//alert(r.status);
								if(!r.status) {
									$.jstree.rollback(data.rlbk);
								}
								else {
									//$(data.rslt.oc).attr("id", "copy_"+r.id);
									for ( var key in r.keys ) {
										//alert("change "+key+" into "+r.keys[key]);
										//$.passbolt.getInstance().debug("key="+r.keys[key]);
										$('#copy_'+key).attr("id", r.keys[key]);
									}
									//if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
										//data.inst.refresh(data.inst._get_parent(data.rslt.oc));
									//}
								}
								//$("#analyze").click();
							},
							dataType: 'json'
						});
					});
				})
				.bind("before.jstree", function (e, data) {
					// this checks that when we drag and drop a node, all the subnodes have the righ to be read
					// if not, cancel the drag and drop and inform the user
					if(data.func == "move_node" && data.args[1] == false && data.plugin == "core"){	
						var sub_noperm = false;
						var node = data["args"]["0"]["o"];
						var id = node.attr("id");
						var res = true;
						$("li", node).each(function(){
							var permission = $.passbolt.getInstance().getPermissionByCategory(1,$(this).attr("id"));
							if(permission == '---' || permission == 'r--'){
								$.jnotify("You cannot move this category because you don't have the permission to modify at least one of its subcategories", "error", 5000);
								return false;
							}
						});
					}
				});
		},
		categoryChangeType:function(category_id, type_id){
			// 3) Update the category in the db
			$.post("/categories/changeType", {"cid":category_id, "tid":type_id}, function(data){
				if(data.status == '1'){
					// 2), check if a style is already applied. If yes, remove it
					var classes = $('#categories #'+category_id).attr("class");
					var match = /t([0-9]{1,2})/i.exec(classes);
					if(match){
						$('#categories #'+category_id).removeClass(match[0]);
					}
					
					// 3) Update the type in the tree (don't update if it is zero. zero=standard. No need to put a className).
					if(type_id != 0) $('#categories #'+category_id).addClass("t"+type_id);
				}
			},
			'json');
		},
		debug:function(str){
			$('.debug').append(str + "<br/>");
		},
		/**
		 * Return one type for the context menu
		 * @param i, the type
		 * @returns a json object
		 */
		contextMenuGetType:function(tid){
			return {
				"separator_before"	: false,
				"separator_after"	: false,
				"label"				: $.passbolt._translate("t"+tid),
				"_class"			: "t"+tid,
				"action"			: function(obj){
					var cid = obj.attr("id");
					$.passbolt.categoryChangeType(cid, tid);
				}
				};
		},
		/**
		 * Create a json object representing all the types available for the application
		 * @returns
		 */
		contextMenuGetTypes:function(){
			var jsonMenu = {};
			var i=0;
			for(i=0; i<69; i++){
				var name="t"+i;
				jsonMenu[name] = this.contextMenuGetType(i);
				
			}
			return jsonMenu;
		},
		contextMenu:function(id){
			var cperm = $.passbolt.getInstance().getPermissionByCategory('1', id);    	// argument "1" is the aco_id (means it's a category, not a password

			var items = null;
			if($('#categories #'+id).attr('rel') == 'root'){
				items =  { 
						"create" : {
							"separator_before"	: false,
							"separator_after"	: true,
							"label"				: "Create",
							"action"			: function (obj) { this.create(obj); }
						}
				};
				return items;
			}					// in case the right click is on the database name, only restricted menu is displayed
			if(cperm == "rw-" || cperm == "rwm"){
				items =  { 
					"create" : {
						"separator_before"	: false,
						"separator_after"	: true,
						"label"				: "Create",
						"action"			: function (obj) { this.create(obj); }
					},
					"rename" : {
						"separator_before"	: false,
						"separator_after"	: false,
						"label"				: "Rename",
						"action"			: function (obj) { this.rename(obj); }
					},
					"remove" : {
						"separator_before"	: false,
						"icon"				: false,
						"separator_after"	: false,
						"label"				: "Delete",
						"action"			: function (obj) { this.remove(obj); }
					},
					"ccp" : {
						"separator_before"	: true,
						"icon"				: false,
						"separator_after"	: false,
						"label"				: "Edit",
						"action"			: false,
						"submenu" : { 
							"cut" : {
								"separator_before"	: false,
								"separator_after"	: false,
								"label"				: "Cut",
								"action"			: function (obj) { this.cut(obj); }
							},
							"copy" : {
								"separator_before"	: false,
								"icon"				: false,
								"separator_after"	: false,
								"label"				: "Copy",
								"action"			: 
									function (obj) { 
										this.copy(obj); 
									}
							},
							"paste" : {
								"separator_before"	: false,
								"icon"				: false,
								"separator_after"	: false,
								"label"				: "Paste",
								"action"			: 
									function (obj) { 
										if($.passbolt.getInstance()._copied != null){ // is used so far when a password is in the clipboard
											var cid = obj.attr("id");
											// paste the password
											$.post('/passwords/move', {"pid":$.passbolt.getInstance()._copied.aco_ref_id, "cid":cid, "copy":"1"}, function(data){
												if(data.status == 1){
													$.jnotify("password succesfully pasted");
												}
												else{
													$.jnotify("A problem occured while pasting");
												}
												$.passbolt.getInstance()._copied = null; // reset the passbolt's clipboard
											},
											'json');
										}
										else{
											this.paste(obj); 
										}
								  	}
								}
							}
						},
						"types" : {
							"separator_before"	: true,
							"icon"				: false,
							"separator_after"	: false,
							"label"				: "Type",
							"action"			: false,
							"_class"			: 'types',
							"submenu" : $.passbolt.getInstance().contextMenuGetTypes()
						}
					};
			}
			return items;
		},
		updatePassword:function(id, pass){
			var title = pass.title;
			var username = pass.username;
			var url = String(pass.url).substring(0, 13) + "...";
			var copyUrl = url; // used for the copy button
			var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

			if(regexp.test(url)){
				url = '<a href="'+ pass.url +'">'+ url +"</a>";
			}
			
			var line = $(".passwords>table tr#"+id);
			$(".passwords>table tr#"+id+" td.title span").html(pass.title);
			$(".passwords>table tr#"+id+" td.url span").html(url);
			$(".passwords>table tr#"+id+" td.username span").html(pass.username);
			this._passwords[id] = pass;
			
			$('.username .copy', line).empty();
			$('.url .copy', line).empty();
			$('.password .copy', line).empty();
			
			/** set copy buttons **/
			$.passbolt.getInstance().addCopyButton($('.username .copy', line), username);
			$.passbolt.getInstance().addCopyButton($('.url .copy', line), copyUrl);
			
			var masterkey = $.cookie('passbolt[masterkey]');
			var ciphertext = Aes.Ctr.decrypt(pass.password, masterkey, 256);
			$.passbolt.getInstance().addCopyButton($('.password .copy', line), ciphertext);
			/** end copy buttons **/
		},
		passwordGetContextMenu:function(id){
			var password = $.passbolt.getInstance()._passwords[id];
			var contextMenu = "<ul>"
									+ "<li><ins>&nbsp;</ins><a href=\"#\" class=\"edit\">edit</a></li>"
									+ "<li class=\"vakata-separator vakata-separator-after\"></li>"
									+ "<li><ins>&nbsp;</ins><a href=\"#\" class=\"copy\">copy</a></li>"
									+ "<li class=\"vakata-separator vakata-separator-after\"></li>"
									+ (password.live == 1 || $.passbolt.getInstance().userAdmin ? "<li><ins>&nbsp;</ins><a href=\"#\" class=\"remove\">remove</a></li>" : "") 
									+ ($.passbolt.getInstance().userAdmin ? "<li><ins>&nbsp;</ins><a href=\"#\" class=\"restore\">restore</a></li>" : "")  
									+ ($.passbolt.getInstance().userAdmin ? "<li><ins>&nbsp;</ins><a href=\"#\" class=\"delete_forever\">delete forever</a></li>" : "") 
									+ "<li class=\"vakata-separator vakata-separator-after\"></li>"
									+ "<li><ins>&nbsp;</ins><a href=\"#\" class=\"history\">changes history</a></li>"
								+ "</ul>";
			
			contextMenu = $(contextMenu);
			
			$('.edit', contextMenu).click(function(){
				$.facebox({ ajax: '/passwords/edit/'+ id}); 
			});
			
			$('.copy', contextMenu).click(function(){
				$.passbolt._copied = {
						"aco_id":2,
						"aco_ref_id":id
				};
				$.passbolt.ui.menu.remove();
				$.jnotify("Copied succesfully. You can now paste it in a category.");
			});
			
			$('.remove', contextMenu).click(function(){
				$.passbolt.ui.menu.remove();
				if(confirm("Are you sure you want to delete this password ?")){
					$.post('/passwords/remove', {"id":id}, function(data){
						if(data["result"] == "1"){
							if(!$.passbolt.getInstance().userAdmin){
								$.passbolt.ui.passwords.remove(id);
							}
							else{
								var line = $('.passwords table tr#'+id);
								line.addClass('deleted');
								$('.restorespan', line).removeClass('invisible');
								$('.removespan', line).addClass('invisible');
							}
						}
					},
					'json');
				}
				return false;
			});
			
			$('.restore', contextMenu).click(function(){
				$.passbolt.ui.menu.remove();
				$.post('/passwords/restore', {"id":id}, function(data){
					if(data["result"] == "1"){
						var line = $('.passwords table tr#'+id);
						line.removeClass('deleted');
						$('.restorespan', line).addClass('invisible');
						$('.removespan', line).removeClass('invisible');
					}
				},
				'json');
				return false;
			});
			
			$('.delete_forever', contextMenu).click(function(){
				$.passbolt.ui.menu.remove();
				if(confirm("Are you sure you want to delete this password forever ?\n Once deleted, there is no way to retrieve it.")){
					if($.passbolt.getInstance().userAdmin){
						$.post('/passwords/deleteForever', {"id":id}, function(data){
							if(data["result"] == "1"){
								var line = $('.passwords table tr#'+id);
								backgroundColor = $('td', line).css('backgroundColor');
								$('td', line).animate({ backgroundColor: "#ffaaaa" }, 'slow', function(){
									$('.passwords>table tr#'+id).remove();
								});
							}
						},
						'json');
					}
				}
				return false;
			});
			
			$('.history', contextMenu).click(function(){
				$.facebox({ajax:"/passwords_history/view/"+id});
			});
			return contextMenu;
		},
		passwordShowContextMenu:function(id, x, y){
			var menu = $.passbolt.getInstance().passwordGetContextMenu(id);
			$('#vakata-contextmenu').css({'visibility':'visible', 'left':x+'px', 'top':y+'px' });
			if($('#vakata-contextmenu ul').length){
				$('#vakata-contextmenu ul').remove();
			}
			$('#vakata-contextmenu').append(menu);
			$('#vakata-contextmenu').addClass('jstree-default-context');
			$('#vakata-contextmenu').addClass('password-context');
		},
		addCopyButton:function(wrapper, text){
			var _clipboard_clip = new ZeroClipboard.Client();
			_clipboard_clip.setHandCursor( true );
			_clipboard_clip.addEventListener( 'onComplete', function(client){
				var password_id = wrapper.parent().parent().parent().attr("id");
				if(wrapper.parent().attr('class')=='password'){
					$.post('/events/register/2', {"id":password_id});
				}
				$.jnotify("item copied in clipboard", 600);
			});
			_clipboard_clip.setText( text );
			var clipHtml = _clipboard_clip.getHTML( 30, 30 );
			wrapper.html(clipHtml);
			return _clipboard_clip;
		},
		addPasswordLine:function(pass, animate){			
			var className = "even";
			className += (pass.live == 0 ? " deleted" : "");
			if($(".passwords>table>tbody").length){ className = ($('.passwords>table>tbody>tr:last').hasClass('even') ? 'odd' : 'even') ; }
			
			var title = pass.title;
			var username = pass.username;
			var url = String(pass.url).substring(0, 13) + "...";
			var copyUrl = pass.url; // used for the copy button
			var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/

			if(regexp.test(url)){
				url = '<a href="'+ pass.url +'" target="_blank">'+ url +"</a>";
			}
	
			var line = $("<tr class=\""+ className +"\" id=\""+ pass.id +"\">" +
						"<td class=\"title\"><div class=\"title\"><span>"+ pass.title +"</span></div></td>" +
						"<td class=\"username\"><div class=\"username\"><span>"+ pass.username +"</span><div class=\"copy\"></div></div></td>" +
					    "<td class=\"password\"><div class=\"password\"><span><em>*</em><em>*</em><em>*</em><em>*</em></span><div class=\"copy\"></div></div></td>" +
					    "<td class=\"url\"><div class=\"url\"><span>"+ url +"</span><div class=\"copy\"></div></div></td><td class=\"actions\"><a href=\"#\" class=\"pmenu\">menu</a></td>" +
					    "</tr>");
			
			if(!$('.passwords>table>tbody').length){
				var line = $("<tbody></tbody>").wrapInner(line);
				$('.passwords>table>thead').after(line);
			}
			else{
				line.appendTo($('.passwords>table>tbody'));
			}
			var line = $('.passwords>table>tbody>tr:last');
			
			/** set copy buttons **/
			$.passbolt.getInstance().addCopyButton($('.username .copy', line), username);
			$.passbolt.getInstance().addCopyButton($('.url .copy', line), copyUrl);
			
			var masterkey = $.cookie('passbolt[masterkey]');
			var ciphertext = Aes.Ctr.decrypt(pass.password, masterkey, 256);
			$.passbolt.getInstance().addCopyButton($('.password .copy', line), ciphertext);
			/** end copy buttons **/
			
			if(animate){
				backgroundColor = $('td', line).css('backgroundColor');
				$('td', line).animate({ backgroundColor: "#ffd154" }, 'slow');
			}
			
			line.mouseenter(function(e){
				$(this).addClass('hover');
				//$.passbolt.debug('line.mouseenter');
				$('div.actions', $(this)).css('visibility','visible'); // show the menu bar
			})
			.mouseleave(function(e){
				$(this).removeClass('hover');
				$('div.actions', $(this)).css('visibility','hidden');
			})
			.bind('contextmenu', function(e){
				e.stopImmediatePropagation();
				var passwordId = $(this).attr("id");
				$.passbolt.getInstance().passwordShowContextMenu(passwordId, e.pageX, e.pageY);
				return false;
			})
			.bind('dblclick', function(e){
				var id = $(this).attr("id");
				$.facebox({ ajax: '/passwords/edit/'+ id}); 
			});
			$('a.pmenu', line).click(function(e){
				e.stopImmediatePropagation();
				var passwordId = $(this).parent().parent().attr("id");
				var x = $(this).offset().left;
				var y = $(this).offset().top;
				$.passbolt.getInstance().passwordShowContextMenu(passwordId, x, y);
				return false;
			});
			
			///////// Set the flash clipboard copy function //////////////
			$('td', line).mouseenter(function(e){
				$(this).addClass('hover');
			})
			.mouseleave( function(e) {
				$(this).removeClass('hover');
			});
		},
		uiLoadPasswords:function(aco_ref_id){
			$.post('/passwords/getPasswords', {"id":this.aco_ref_id}, function(data){ 
				var res = "";
				if(data){
					var pb = $.passbolt.getInstance();
					pb._passwords = Array();
					$('.passwords>table>tbody').remove();
					$.each(data, function(key) {
					    if(!isNaN( parseInt( key ))){ // check if it is an integer (coz in the array sent, one value is not an integer (key : strength)
					    	var password = data[key]["Password"];
					    	pb.getInstance().addPasswordLine(password);
					    	pb._passwords[password.id] = password; // Do not remove. We store in the main component the list of passwords for future reference
					    }
				    });
					
					$.passbolt.ui.passwords.refreshDimensions();
					pb.updatePasswordStrength();
					pb.uiLoadInformation();
				}
				else{
					res = "";
				}
			}
			, 'json');
		},
		updatePasswordStrength:function(){
			var masterkey = $.cookie('passbolt[masterkey]');
			var pb = $.passbolt.getInstance();
			pb._pstrength = 0;
			var score = 0;
			var nb_passwds = 0;
			for(key in pb._passwords){
			    score += $.passbolt.password.getStrength(Aes.Ctr.decrypt(pb._passwords[key].password, masterkey, 256));
			    nb_passwds++;
			}
			if(nb_passwds != 0) pb._pstrength = Math.round(score / nb_passwds);
		},
		uiLoadScore:function(){
			var pb = $.passbolt.getInstance();
			if(pb._passwords.length == 0){
				$('.box-content .pwstrength-container').hide('slow');
				return;
			}
			$('.box-content .pwstrength-container').show('slow');
			var score = pb._pstrength;
			var verdictColors = $.passbolt.password.getVerdictColors();
			var verdict = $.passbolt.password.getVerdict(score);
			
			var i = 1;
			for(v in verdictColors){
				if(v == verdict){
					break;
				}
				i++;
			}
			
			// i is not the index where we have to break
			var animDuration = Math.round(1500 / i);
			var interval = Math.round(score / i);
			//$.passbolt.debug("score="+score+" interval="+interval+ "i="+i); // debug
			var currentSize = interval;
			for(v in verdictColors){
				$(".pwstrength_level").animate({ backgroundColor: '#'+verdictColors[v], width:currentSize+"%" }, animDuration, "linear");
				if(v == verdict){
					break;
				}
				currentSize += interval;
			}
			$(".score").html(score+'%');
			$(".score").css("color", '#'+verdictColors[verdict]);
			$(".score-verdict").html(verdict);
			$(".score-verdict").css("color", '#'+verdictColors[verdict]);
		},
		uiLoadInformation:function(){
			if(this.aco_id == 1){   			// if a category is selected
				$.post('/categories/getInformation', {"id":this.aco_ref_id}, function(data){
					$('.sidebar .information .date').html(data['Category']['created']);
					$('.sidebar .information .owner').html(data['User']['name']);
				}, 'json');
			}
			else{								// if a password is selected
				
			}
			this.uiLoadScore();
		},
		uiRefreshPermissions:function(){
			var permissions = this._permissions;
			var found = false;
			$('ul.groups').html('');
			var localperm = this.getPermissionByCategory(this.aco_id, this.aco_ref_id);
			//alert(localperm);
			
			if(permissions.hasOwnProperty("Group")){
				$.each(permissions["Group"], function(key) {
					var group_name = permissions["Group"][key]["details"]["Group"]["name"];
					var group_id = permissions["Group"][key]["details"]["Group"]["id"];
					//var perm = (permissions["Group"][key]["permissions"]['_read'] == '1' ? 'read, ' : '') + (permissions["Group"][key]["permissions"]['_write'] == '1' ? 'write' : '');
					var perm = $.passbolt.getInstance().getPermissionLabel($.passbolt.getInstance().permissionGetCode(permissions["Group"][key]["permissions"]));
					var inherited = (permissions["Group"][key]["permission_details"]["inherited"] == '1');
					var perm_id = permissions["Group"][key]["permission_details"]["perm_id"];
					var li = $("<li id=\""+ perm_id +"\" class=\"group"+ ( inherited ? ' inherited' : '') +"\" rel=\""+ group_id +"\">" +
								"<div class=\"perm_wrapper\">" +
									"<span class=\"name\">" + group_name + "</span>" +
									"<span class=\"permissions\">" +
										"<span class=\"text\">" + perm + "</span>" +
										"<span class=\"dyn\"></span>" +
									"</span>" +
								"</div>" + 
								( !inherited && localperm=='rwm' ? '<a class="delete" href="/permissions/delete">x</a>' : '') +
								"<hr class=\"spacer\">"+
								"</li>");
	
					if(localperm == 'rwm'){
						// set the permissions
						var select = $('#permissions_select').clone(false);
						select.attr('id', 'perm-list-'+perm_id);
						var code = $.passbolt.getInstance().permissionGetCode(permissions["Group"][key]["permissions"]);
						$('option[value='+ code +']', select).attr('selected', 'selected');
						select.change(function(){
							var perm_id = $(this).parent().parent().parent().attr('id');
							var group_id = $(this).parent().parent().parent().attr('rel');
							var permissions = $(this).val();
							var pb = $.passbolt.getInstance();
							$.post("/permissions/modify", {"aco_id":pb.aco_id, "aco_ref_id":pb.aco_ref_id, "aro_id":1, "aro_ref_id":group_id, "permissions":permissions}, function(data){
								var pb = $.passbolt.getInstance();
								pb.uiLoadPermissions();
							}, 'json');
						});
						$('.permissions .dyn', li).html(select);
					}
					$('.permissions .dyn', li).hide();
				    $('ul.groups').append(li);
				});
				found = true;
			}

			// Insert list of users
			if(permissions.hasOwnProperty("User")){
				$.each(permissions["User"], function(key) {
					var user_id = permissions["User"][key]["details"]["User"]["id"];
					var user_name = permissions["User"][key]["details"]["User"]["name"];
					//var perm = (permissions["User"][key]["permissions"]['_read'] == '1' ? 'read, ' : '') + (permissions["User"][key]["permissions"]['_write'] == '1' ? 'write' : '');
					var perm = $.passbolt.getInstance().getPermissionLabel($.passbolt.getInstance().permissionGetCode(permissions["User"][key]["permissions"]));
					var inherited = (permissions["User"][key]["permission_details"]["inherited"] == '1');
					var perm_id = permissions["User"][key]["permission_details"]["perm_id"];
					var code = $.passbolt.getInstance().permissionGetCode(permissions["User"][key]["permissions"]);
					var li = $("<li id=\""+ perm_id +"\" class=\"user"+ ( inherited ? ' inherited' : '') + (code == '---' ? ' denied' : '') +"\" rel=\""+ user_id +"\">" +
									"<div class=\"perm_wrapper\">" +
										"<span class=\"name\">" + user_name + "</span>" +
										"<span class=\"permissions\">" +
											"<span class=\"text\">" + perm + "</span>" +
											"<span class=\"dyn\"></span>" +
										"</span>" +
									"</div>" + 
									( !inherited && localperm=='rwm' ? '<a class="delete" href="/permissions/delete">x</a>' : '') +
								"<hr class=\"spacer\">"+
								"</li>");
					 
					if(localperm == 'rwm'){
						// set the permissions
						var select = $('#permissions_select').clone(false);
						select.attr('id', 'perm-list-'+perm_id);
						$('option[value='+ code +']', select).attr('selected', 'selected');
						select.change(function(){
							var perm_id = $(this).parent().parent().parent().attr('id');
							var user_id = $(this).parent().parent().parent().attr('rel');
							var permissions = $(this).val();
							var pb = $.passbolt.getInstance();
							$.post("/permissions/modify", {"aco_id":pb.aco_id, "aco_ref_id":pb.aco_ref_id, "aro_id":2, "aro_ref_id":user_id, "permissions":permissions}, function(data){
								var pb = $.passbolt.getInstance();
								pb.uiLoadPermissions();
							}, 'json');
						});
						$('.permissions .dyn', li).html(select);
					}
	
					$('.permissions .dyn', li).hide();
				    $('ul.groups').append(li);
				});
				found = true;
			}
			if(found == false){
				$('ul.groups').append('<li class="noaccess">Admins only</li>');
			}
		    $('ul.groups a.delete').click(function(){
				var perm_id=$(this).parent().attr('id');
				$.post('/permissions/delete', {"id":perm_id}, function(data){
					if(data["result"] == '1'){
						$.passbolt.getInstance().uiLoadPermissions();
					}
				}, 'json');
				return false;
			});
		    
			$('ul.groups').removeClass('loading');
			if(localperm == 'rwm'){
				$('ul.groups>li').mouseenter(function(){
					$(this).addClass('hover');
					$('.dyn', $(this)).show();
					$('.text', $(this)).hide();
				})
				.mouseleave(function(){
					$(this).removeClass('hover');
					$('.dyn', $(this)).hide();
					$('.text', $(this)).show();
				});
			}

			// list of groups
			var tmp_groups_list = Array();
			tmp_groups_list['-1'] = "Groups";
			$.each(permissions["gl"], function(key) {
				tmp_groups_list[key] = permissions['gl'][key];
			});
			this.uiPopulateList('#groups_select', tmp_groups_list);

			this._users_groups = new Array(); 										// will store the users and groups correspondences
			this._users_list = new Array(); 											// will store the full list of users
			this._users_list['-1'] = "Users";

			
			var j=0;
			$.each(permissions["ul"], function(key) {
				pb = $.passbolt.getInstance();
				pb._users_list[key] = pb._permissions['ul'][key]['name'];
				for(g in pb._permissions["ul"][key]["groups"]){
					var group_id = pb._permissions["ul"][key]["groups"][g];
					if(!pb._users_groups.hasOwnProperty(group_id)){
						pb._users_groups[group_id] = new Array();
					}
					pb._users_groups[group_id][j] = pb._permissions["ul"][key]['id'];
					j++;
				}
			});
			this.uiPopulateList('#users_select', this._users_list);
		},
		uiLoadPermissions:function(){
			// Todo : display a waiting screen
			$.post('/permissions/getAllowedUsersGroups', {"aco_id":this.aco_id, "aco_ref_id":this.aco_ref_id}, function(data){ 
				if(data){
					var pb = $.passbolt.getInstance();
					pb._permissions = data;
					pb.uiRefreshPermissions();
				}
				else{
					// TODO : display error
					alert("something wrong occured");
				}
			} , 'json');
		},
		uiLoadActivity:function(offset, auto){
			if(!offset) offset = 0;
			if(offset == 0 && $.passbolt.getInstance()._activity_offset != 0 && auto == true){
				return;
			}
			$.passbolt.getInstance()._activity_offset = offset;
			
			$.post('/events/retrieveForCategory/' + offset, {"aco_id":this.aco_id, "aco_ref_id":this.aco_ref_id}, function(data){
				if(data){
					$('.box.activity .box-content').html(data);
					if($.passbolt.getInstance()._activity_offset == 0){
						setTimeout("$.passbolt.getInstance().uiLoadActivity(0, true)", 25000);
					}
					var buttonPrev = $('.box.activity .box-content .prev');
					var buttonNext = $('.box.activity .box-content .next');
					
					if(buttonPrev.attr('href') == "#") buttonPrev.css("visibility","hidden");
					buttonPrev.click(function(){
						var offset = $(this).attr("href");
						$.passbolt.getInstance().uiLoadActivity(offset);
						return false;
					});
					if(buttonNext.attr('href') == "#") buttonNext.css("visibility","hidden");
					buttonNext.click(function(){
						var offset = $(this).attr("href");
						$.passbolt.getInstance().uiLoadActivity(offset);
						return false;
					});
				}
			});
		},
		// return the code string for one permission, from the array of permissions
		permissionGetCode:function(perms){
			var strres = ((perms['_read'] == '1' ? 'r' : '-') + (perms['_write'] == '1' ? 'w' : '-') + (perms['_manage'] == '1' ? 'm' : '-'));
			return strres;
		},
		permissionGetCodeFromElt:function(elt){
			var type = (elt.hasClass('group') ? 'Group' : 'User');
			var id = elt.attr('rel');
			var perms = permissions[type][id]["permissions"];
			return permissionGetCode(perms);
		},
		getPermissionLabel:function(code){
			return ( code[0] == 'r' ? 'read ' : '') + (code[1] == 'w' ? ',write' : '') + (code[2] == 'm' ? ' and manage' : '');
		},
		uiPopulateList:function(list_selector, alist){
			$(list_selector).html('');
			var html = '';
			for(key in alist){
				html += "<option value=\""+ key +"\">"+ alist[key] +"</option>";
			}
			$(list_selector).html(html);
		},
		movePassword:function(pid, cid){
			$.post('/passwords/move', {"pid":pid, "cid":cid}, function(data){
				if(data.status == 1){
					$.passbolt.ui.passwords.remove(pid);
					var pb = $.passbolt.getInstance();
					delete pb._passwords[pid];
					pb.updatePasswordStrength(); // we update the new strength of the passwords. coz we need to load the new information
					pb.uiLoadInformation();		// refresh information layer
					$.jnotify("Password moved succesfully", 3000);
				}
				else{
					$.jnotify("Oups.. something went wrong :-(", 'error', 3000);
				}
			},
			'json');
		},
		onNodeSelect:function (aco_id, aco_ref_id) {
			this.aco_id = aco_id;
			this.aco_ref_id = aco_ref_id;
			
			// first : check that the key is properly entered. If not entered, we display the popup asking for the master key
			if($.cookie('passbolt[masterkey]') == null){
				$.passbolt.getInstance().uiRequestMasterKey();
				return false;
			}
			this.checkMasterKey(true);
			
			$('.sidebar .available').show();
			$('.sidebar .unavailable').hide();
			
			this.uiLoadPasswords(this.aco_ref_id);					// load the passwords in the ui
			var localperm = this.getPermissionByCategory(aco_id, aco_ref_id);
			if(aco_id == '1' && (localperm == 'rw-' || localperm == 'rwm')){
				$('#addPasswordButton').show();
			}
			else{
				$('#addPasswordButton').hide();
			}
			
			$('ul.groups').html('');
			$('ul.groups').addClass('loading');

			$('#groups_select').change(function(){
				var group_id = $(this).val();
				var pb = $.passbolt.getInstance();
				if(group_id == '-1'){
					pb.uiPopulateList('#users_select', this._users_list);
				}
				else{
					var tmp_users_list = Array();
					tmp_users_list['-1'] = "Users";
					for(i in pb._users_groups[group_id]){
							tmp_users_list[pb._users_groups[group_id][i]] = pb._users_list[pb._users_groups[group_id][i]];
						}
					pb.uiPopulateList('#users_select', tmp_users_list);
				}
			});

			var options = {
				beforeSerialize:function(){
					var pb = $.passbolt.getInstance();
					$('#perm_aco_id').val(pb.aco_id);
					$('#perm_aco_ref_id').val(pb.aco_ref_id);
					$("#addperm").click();
				},
				success:function(data){
					$.passbolt.getInstance().uiLoadPermissions();
					// TODO : display alert
				},
				dataType:'json'
			};
			$('#permissions_management_form').ajaxForm(options);
			this.uiLoadPermissions();
			this.uiLoadActivity();
		},
		/**
		 * This function checks that the master key is set
		 * if not set, display a popup asking for it
		 * @param recursive : whether to perform the check recursively (never stopsm every x intervals)
		 * @returns true if the key is set, false otherwise
		 */
		checkMasterKey:function(recursive){
			var res = false;
			if($.cookie('passbolt[masterkey]') == null){
				if(!$('#window-masterkey').length){ // if the window is not displayed yet
					$.passbolt.getInstance().uiRequestMasterKey();
				}
			}
			else{
				res = true;
			}
			if(recursive){
				setTimeout("$.passbolt.getInstance().checkMasterKey(true)", 500); // a check will occur every 1/2 sec
			}
			//this.debug("check");
			return res;
		},
		uiRequestMasterKey:function(){
			// Show modalbox for a test
			jQuery.fn.modalBox({ 
				directCall : {
					source : '/passwords/setKey'
				},
				callFunctionAfterShow : function(){
					$.passbolt.getInstance().uiMasterKeyWindowInit($('#modalBox'));
				}

			});

		},
		/********************** INIT FACEBOX WINDOWS ********************/
		uiMasterKeyWindowInit:function(w){
			var options = {
					success:function(data){
						if(data["status"] == 1){
							jQuery.fn.modalBox('close');
							$.jnotify("Master key valid. Thanks.", 5000);
							var pb = $.passbolt.getInstance();
							pb.onNodeSelect(pb.aco_id, pb.aco_ref_id);
						}
						else{
							if(!$('#window-masterkey span.errormsg').length){
								$('#window-masterkey div.submit').after('<span class="errormsg">The key entered is not valid.</span>');
								$('#window-masterkey #PasswordMasterkey').addClass('error');
							}
							$.jnotify("The key entered is not valid", 'error', 3000);
						}
					},
					dataType:'json'
				};
				$('#PasswordSetKeyForm', w).ajaxForm(options);
				//alert($('.helpContent').html());
				$('.helpContent', w).hide();
				$('#window-masterkey .help', w).click(function(){
					$('#window-masterkey .helpContent', w).toggle('slow');
					return false;
				});
		},
		onAjaxComplete:function(e, xhr, settings){
			//alert("pass here");
			if(xhr.status == '403'){  // will be 403 if the session has expired
				e.stopImmediatePropagation();  // we stop propagation of the event
				document.location.href='/';
				return false;
			}
			
			try{
				var json = $.parseJSON(xhr.responseText);
				if(json.status == 0 && json.data['error_code']=='user_deactivated'){
					window.location.href = '/users/logout';
				}
			}
			catch(e){
				// don't do anything. just catch exception
			}
		},
		_translate:function(label){
			return _t[label];
		}
	};
	
	$.passbolt.ui = {
			
	};
	
	$.passbolt.ui.menu = {
		remove:function(){
			if($('#vakata-contextmenu.password-context').length){
				$('#vakata-contextmenu.password-context ul').remove();
				$('#vakata-contextmenu').removeClass('password-context');
				$('#vakata-contextmenu').removeClass('jstree-default-context');
				$('#vakata-contextmenu').css({'visibility':'hidden'});
			}
		}
	};
	
	$.passbolt.ui.passwords = {
		username_width : null, 
		title_width : null,
		password_width : null,
		url_width : null,
		flash_width:45, // size of the flash copy component
		
		/**
		 * Calculates the dimensions of the passwords interface to be able to update accordingly later
		 */
		calculateDimensions:function(){
			this.username_width = ($('.passwords thead td.username').width());
			this.password_width = ($('.passwords thead td.password').width());
			this.url_width = ($('.passwords thead td.url').width());
			this.title_width = ($('.passwords thead td.title').width());
		},
		/**
		 * Refreshes the dimensions on the screen according to what has been calculated (calculateDimensions should be called first)
		 */
		refreshDimensions:function(){
			$('.passwords tbody td.username div.username span').css('max-width', ($.passbolt.ui.passwords.username_width - this.flash_width) + 'px');
			$('.passwords tbody td.password div.password span').css('max-width', ($.passbolt.ui.passwords.password_width - this.flash_width) + 'px');
			$('.passwords tbody td.url div.url span').css('max-width', ($.passbolt.ui.passwords.url_width - this.flash_width) + 'px');
			$('.passwords tbody td.title div.title span').css('max-width', ($.passbolt.ui.passwords.title_width) + 'px');
		},
		// adjust the height of the categories box depending on the number of passwords
		adjustHeight:function(){
			var height = $('.passwords').height();
			if(height > 500){
				$('.categories').css('height', height + 'px');
			}
		},
		remove:function(id){
			backgroundColor = $('.passwords>table tr#'+id+' td').css('backgroundColor');
			$('.passwords>table tr#'+id).animate({ backgroundColor: "#ffaaaa" }, 'slow', function(){
				$('.passwords>table tr#'+id).remove();
			});
		},
		initialize:function(){
			$('.window-container .passwords tbody').empty();
			//$("#addPasswordButton").hide();
		}
	};
	
	
})(jQuery);



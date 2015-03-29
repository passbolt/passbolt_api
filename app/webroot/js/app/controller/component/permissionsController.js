steal(
	'app/view/component/permissions.js',
	'app/model/group.js',
	'app/model/user.js',
	'app/model/permission.js',
	'app/model/permissionType.js',

	'app/view/template/form/permission/addForm.ejs',
	'app/view/template/component/permission/permissionListItem.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.PermissionsController
	 * @inherits mad.controller.component.ComponentController
	 * @parent index 
	 * 
	 * @constructor
	 * Creates a new Permissions Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.PermissionsController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.PermissionsController', /** @static */ {

		'defaults': {
			'label': 'Permissions Controller',
			'viewClass': passbolt.view.component.Permissions,
			// the instance to bind the component on
			'acoInstance': null,
			// the autocomplete textbox
			'permAroAutocpltTxtbx': null,
			// the autocomplete list
			'permAroAutocpltList': null
		}

	}, /** @prototype */ {

		'afterStart': function() {
			var self = this;

			// List defined permissions
			this.permList = new mad.controller.component.TreeController($('#js_permissions_list'), {
				'cssClasses': ['permissions'],
				'viewClass': mad.view.component.Tree,
				'itemClass': passbolt.model.Permission,
				'templateUri': 'mad/view/template/component/tree.ejs',
				'itemTemplateUri': 'app/view/template/component/permission/permissionListItem.ejs',
				// The map to use to make jstree working with our category model
				'map': new mad.object.Map({
					'id': 'id',
					'isDirect': {
						'key': 'aro_foreign_key',
						'func': function(aro_foreign_key, map, obj) {
							return obj.isDirect(self.options.acoInstance);
						}
					},
					'aroLabel': {
						'key': 'aro',
						'func': function(aro, map, obj) {
							return aro.toLowerCase();
						}
					},
					'aroAvatarPath': {
						'key': 'id',
						'func': function(user, map, obj) {
							if (obj.aro == 'User') {
								return obj.User.Profile.avatarPath('small');
							} else {
								return 'img/group_default.png';
							}
						}
					},
					'permType': 'PermissionType.serial',
					'permLabel': {
						'key': 'PermissionType',
						'func': function(permType, map, obj) {
							return permType.toString('long');
						}
					},
					'acoLabel': {
						'key': 'aco_foreign_key',
						'func': function(aco_foreign_key, map, obj) {
							switch(obj.aro) {
								case 'Group':
									return obj['Group'].name;
									break;
								case 'User':
									return obj['User']['Profile'].first_name + ' ' + obj['User']['Profile'].last_name;
									break;
							}
						}
					},
					'acoDetails': {
						'key': 'aco_foreign_key',
						'func': function(aco_foreign_key, map, obj) {
							switch(obj.aro) {
								case 'Group':
									return __('group');
									break;
								case 'User':
									return obj['User'].username;
									break;
							}
						}
					}
				})
			});
			this.permList.start();

			// form add permission
			this.addFormController = new mad.form.FormController($('#js_permission_add_form', this.element), {
				'templateBased': true,
				'cssClasses': ['perm-create-form', 'clearfix'],
				'templateUri': 'app/view/template/form/permission/addForm.ejs',
				'validateOnChange': false,
				'callbacks': {
					'submit': function(data) {
						self.addPermission(data);
					}
				}
			});
			this.addFormController.start();
			
			// Form feedback controller
			var permCreateFormFeedback = new mad.form.FeedbackController($('#js_perm_create_form_feedback'), {}).start();

			// Add an hidden element to the form to carry the aro id 
			this.permAroHiddenTxtbx = new mad.form.element.TextboxController($('#js_perm_create_form_aro', this.element), {
				modelReference: 'passbolt.model.Permission.aro_foreign_key'
			}).start();
			this.addFormController.addElement(this.permAroHiddenTxtbx);

			// Add an autocomplete element to the form to search the target aro
			this.options.permAroAutocpltTxtbx = new mad.form.element.AutocompleteController($('#js_perm_create_form_aro_auto_cplt', this.element), {
				modelReference: 'passbolt.model.Permission.aro_foreign_label',
				changeTimeout: 400,
				callbacks: {
					ajax: function(value) {
						return self.autocompleteAro(value);
					}
				}
			}).start();
			this.addFormController.addElement(this.options.permAroAutocpltTxtbx, permCreateFormFeedback);

			// Add a selectbox element to the form to carry permission type
			var availablePermissionTypes = {};
			for (var permType in passbolt.model.PermissionType.PERMISSION_TYPES) {
				availablePermissionTypes[permType] = passbolt.model.PermissionType.toString(permType);
			}
			var permTypeCtl = new mad.form.element.DropdownController($('#js_perm_create_form_type', this.element), {
					emptyValue: false,
					modelReference: 'passbolt.model.Permission.type',
					availableValues: availablePermissionTypes
				}).start();
			this.addFormController.addElement(permTypeCtl, permCreateFormFeedback);		

			// rebind the just created elements, so the controller will be able to listen events which occured on them.
			this.on();
		},

		/**
		 * Show the autcomplete list functions of received users and groups
		 * @param {string} value String to launche the autocomplete with
		 * @return {void}
		 */
		'autocompleteAro': function(value) {
			// get all the users and groups functions of the given string
			// start by getting all the users
			var request = passbolt.model.User.findAll({
				'keywords': value
			}).then(function(users) {
				// Groups are removed from the autocomplete from the time being.
				// Uncomment the lines below to reactivate the feature.
				// See PASSBOLT-742 (https://passbolt.atlassian.net/browse/PASSBOLT-742)

				// get all the groups
				//return passbolt.model.Group.findAll({
				//	'keywords': value
				// }).then(function(groups) {
					// aggregate users & groups in a format that the list will understand
					var returnValue = [];
					// groups.each(function(group, i){
					//	returnValue.push(new mad.model.Model({
					//		id: group.id,
					//		label: group.name,
					//		model: 'passbolt.model.Group'
					//	}));
					// });
					users.each(function(user, i){
						returnValue.push(new mad.model.Model({
							id: user.id,
							label: user.username,
							model: 'passbolt.model.User'
						}));
					});

					return returnValue;
				// });
			});
			
			return request;
		},

		/**
		 * load permission for a given instance
		 * @param {mad.model.Model} obj The target instance
		 * @return {void}
		 */
		'load': function(obj) {
			var self = this;
			this.options.acoInstance = obj;
			// get permissions for the given resource
			passbolt.model.Permission.findAll({
				'aco': this.options.acoInstance.constructor.shortName,
				'aco_foreign_key': this.options.acoInstance.id
			}, function (permissions, response, request) {
				// load the perm list
				self.permList.load(permissions);
				// load the form with the resource data
				self.addFormController.load(self.options.acoInstance);

				// load the edit forms functions of the permissions
				permissions.each(function(permission, i) {
					// form edit permission
					var formId = 'js_perm_edit_form_' + permission.id;
					var formCtl = new mad.form.FormController($('#' + formId, this.element), {
						'templateBased': false,
						'cssClasses': [],
						'validateOnChange': false
					});
					formCtl.start();

					// Add an hidden element to the form to carry the aro id
					var permIdCtl = new mad.form.element.TextboxController($('.js_perm_edit_form_id', formCtl.element), {
						modelReference: 'passbolt.model.Permission.id'
					}).start();
					formCtl.addElement(permIdCtl);

					// Add a selectbox element to the form to carry permission type
					var availablePermissionTypes = {};
					for (var permType in passbolt.model.PermissionType.PERMISSION_TYPES) {
						availablePermissionTypes[permType] = passbolt.model.PermissionType.toString(permType);
					}
					var permTypeCtl = new mad.form.element.DropdownController($('.js_perm_edit_form_type', formCtl.element), {
						emptyValue: false,
						modelReference: 'passbolt.model.Permission.type',
						availableValues: availablePermissionTypes
					}).start();
					formCtl.addElement(permTypeCtl);

					// If the permission is not direct, disable the permission type select list
					if (!permission.isDirect(self.options.acoInstance)) {
						permTypeCtl.setState('disabled');
					}

					// load the form with the resource data
					formCtl.load(permission);
				});
			});
		},

		/**
		 * Refresh
		 * @return {void}
		 */
		'refresh': function() {
			// reset the list in case it has already been populated
			this.permList.reset();
			// reload the component with the updated permissions
			this.load(this.options.acoInstance);
		},

		/**
		 * Listen when the plugin has encrypted the secrets.
		 * @todo #security #architecture refactor, check also resource createFormController.
		 * @todo #dirtycode
		 */
		'{mad.bus} resource_share_secret_encrypted': function(el, ev, armoreds) {
			var self = this;

			// @todo #BUG #JMVC The event is not unbound when the element is destroyed. Check that point when updating to canJS.
			if (!this.element) return;

			var formData = this.addFormController.getData(),
				fieldAttrs = mad.model.Model.getModelAttributes(this.permAroHiddenTxtbx.getModelReference()),
				modelAttr = fieldAttrs[0],
				aco = this.options.acoInstance.constructor.shortName,
				acoForeignKey = this.options.acoInstance.id,
				aro = modelAttr.modelReference.shortName,
				aroForeignKey = formData[modelAttr.modelReference.fullName].id,
				type = formData['passbolt.model.Permission'].type,
				data = {};

			// Add the permissions to the request.
			data.Permissions = [];
			data.Permissions.push({
				'Permission': {
					'aro': 'User',
					'aro_foreign_key': aroForeignKey,
					'type': type
				}
			});
			// Add the secrets to the request.
			data.Secrets = [];
			data.Secrets.push({
				'Secret': {
					'resource_id': acoForeignKey,
					'user_id': aroForeignKey,
					'data': armoreds[aroForeignKey]
				}
			});

			// create a new permission
			passbolt.model.Permission.share(aco, acoForeignKey, data)
				.then(function() {
					//self.load(self.options.acoInstance);
					self.refresh();
				});
		},

		/**
		 * The user want to remove a permission
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Permission} permission The permission to remove
		 * @return {void}
		 */
		'addPermission': function(formData) {
			var self = this;
			var fieldAttrs = mad.model.Model.getModelAttributes(this.permAroHiddenTxtbx.getModelReference());
			var modelAttr = fieldAttrs[0];
			var aro = modelAttr.modelReference.shortName;
			var type = formData['passbolt.model.Permission'].type;

			// gather the data to create a new permission
			var data = {
				'aco': this.options.acoInstance.constructor.shortName,
				'aco_foreign_key': this.options.acoInstance.id,
				'type': type
			};
			data[aro] = formData[modelAttr.modelReference.fullName];

			// ask the plugin to encrypt the secret for the new user.
			// When the secrets are encrypted the addon will send back the event secret_share_secret_encrypted.
			mad.bus.trigger('passbolt.resource_share.encrypt', {
				resourceId: this.options.acoInstance.id,
				userId: data[aro].id
			});

			//// create a new permission
			//new passbolt.model.Permission(data)
			//	.save(function(newPermission){
			//		// refresh the component with the updated permissions
			//		self.refresh()
			//	});
		},

		/* ************************************************************** */
		/* LISTEN TO THE COMPONENT EVENTS */
		/* ************************************************************** */

		/**
		 * The user want to remove a permission
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Permission} permission The permission to remove
		 * @return {void}
		 */
		' request_permission_delete': function (el, ev, permission) {
			var self = this;

			// if the permission is a direct permission, remove it
			if(permission.isDirect(this.options.acoInstance)) {

				var formData = this.addFormController.getData(),
					fieldAttrs = mad.model.Model.getModelAttributes(this.permAroHiddenTxtbx.getModelReference()),
					modelAttr = fieldAttrs[0],
					aco = this.options.acoInstance.constructor.shortName,
					acoForeignKey = this.options.acoInstance.id,
					aro = modelAttr.modelReference.shortName,
					aroForeignKey = formData[modelAttr.modelReference.fullName].id,
					data = {};

				// Add the permissions to the request.
				data.Permissions = [];
				data.Permissions.push({
					'Permission': {
						'id': permission.id,
						'aro': 'User',
						'aro_foreign_key': aroForeignKey,
						'delete': 1
					}
				});

				// create a new permission
				passbolt.model.Permission.share(aco, acoForeignKey, data)
					.then(function() {
						// if removed successfully, remove the permission from the list
						self.permList.removeItem(permission);
						// refresh the component with the update permissions
						self.refresh();
					});

				//permission.destroy(function(){
				//	// if removed successfully, remove the permission from the list
				//	self.permList.removeItem(permission);
				//	// refresh the component with the update permissions
				//	self.refresh()
				//}, function() {
				//	// @todo treat the error properly
				//	alert('Unable to remove the permission');
				//});

			// otherwise write a direct permission to drop the existing right of the given permission
			// @todo check the user has the right to override the permission
			// @todo need to be rechecked, for now we don't do anything on inherited permission.
			} else {
				// gather the data to create a new permission
				var data = {
					'aco': this.options.acoInstance.constructor.shortName,
					'aco_foreign_key': this.options.acoInstance.id,
					'type': 0
				};
				// add the target aro
				data[permission.aro] = permission[permission.aro];
				// create a new permission
				new passbolt.model.Permission(data)
					.save(function(newPermission){
						// refresh the component with the update permissions
						self.refresh()
					});
			}
		},

		/**
		 * The user is typing in the autocomplete box
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {array} data The data typed in the autocomplete box
		 * @return {void}
		 */
		'{permAroAutocpltTxtbx} changed': function(el, ev, data) {
			// reset aro foreign key txtbx
			this.permAroHiddenTxtbx.setValue(null);
		},

		/**
		 * An item has been selected in the autocomplete list
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.User || passbolt.model.Group} permission The permission to remove
		 * @return {void}
		 */
		'{permAroAutocpltList} item_selected': function(el, ev, data) {
			// update the field model reference functions of the given autocomplete result (can be a User or a Group)
			this.permAroHiddenTxtbx.setModelReference(data.model + '.id');
			// set the value of the hidden field aro_foreign_key
			this.permAroHiddenTxtbx.setValue(data.id);
		},

		/**
		 * A permission has been updated.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {string} permissionType The new permission type
		 * @return {void}
		 */
		'.js_perm_edit_form_type changed': function(el, ev, permissionType) {
			var li = el.parents('li'),
				permission = li.data('passbolt.model.Permission');

			permission.attr('type', permissionType['value']);
			permission.update();
		},

		/**
		 * The user request a go to permission.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {string} permissionType The new permission type
		 * @return {void}
		 */
		'.js_perm_goto click': function(el, ev) {
			var li = el.parents('li'),
				permission = li.data('passbolt.model.Permission');

			// Extract the permission.
			switch(permission.aco) {
				case 'Category':
					// Get the full object stored in our local madstore.c
					var i = mad.model.List.indexOf(passbolt.model.Category.madStore, permission.Category.id);
					var category = passbolt.model.Category.madStore[i];
					mad.bus.trigger('request_category_sharing', category);
					break;
			}
		}

	});

});

steal(
	'app/view/component/permissions.js',
	'app/model/group.js',
	'app/model/user.js',
	'app/model/permission.js',
	'app/model/permissionType.js',

	'app/view/template/form/permission/addForm.ejs',
	'app/view/template/component/permission/permissionListElement.ejs'
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
				'itemTemplateUri': 'app/view/template/component/permission/permissionListElement.ejs',
				// The map to use to make jstree working with our category model
				'map': new mad.object.Map({
					'id': 'id',
					'permTarget': {
						'key': 'aco_foreign_key',
						'func': function(aco_foreign_key, map, obj) {
							switch(obj.aro) {
								case 'Group':
									return obj['Group'].name;
								break;
								case 'User':
									return obj['User'].username;
								break;
							}
						}
					},
					'permType': {
						'key': 'aro',
						'func': function(aro, map, obj) {
							return aro.toLowerCase();
						}
					},
					'permLabel': {
						'key': 'PermissionType',
						'func': function(permType, map, obj) {
							return permType.toString('long');
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
			var permTypeCtl = new mad.form.element.DropdownController($('#js_perm_create_form_type', this.element), {
					modelReference: 'passbolt.model.Permission.type',
					availableValues: passbolt.model.PermissionType.PERMISSION_TYPES
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

			// create a new permission
			new passbolt.model.Permission(data)
				.save(function(newPermission){
					// refresh the component with the updated permissions
					self.refresh()
				});
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
		' delete': function (el, ev, permission) {
			var self = this;

			// if the permission is a direct permission, remove it
			if(permission.isDirect(this.options.acoInstance)) {
				permission.destroy(function(){
					// if removed successfully, remove the permission from the list
					self.permList.removeItem(permission);
					// refresh the component with the update permissions
					self.refresh()
				}, function() {
					// @todo treat the error properly
					alert('Unable to remove the permission');
				});

			// otherwise write a direct permission to drop the existing right of the given permission
			// @todo check the user has the right to override the permission
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
		}

	});

});

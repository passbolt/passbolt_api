steal(
	'app/view/component/permissions.js',
	'app/model/group.js',
	'app/model/user.js',
	'app/model/permission.js',
	'app/model/permissionType.js'
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
			// 'templateBased': false
			// the selected resources, you can pass an existing list as parameter of the constructor to share the same list
			//'selectedRs': new can.Model.List()
		}

	}, /** @prototype */ {
		
		'afterStart': function() {
			var self = this;
			
			// List defined permissions
			this.permList = new mad.controller.component.TreeController($('#js_permissions_list'), {
				'viewClass': mad.view.component.Tree,
				'itemClass': passbolt.model.Permission,
				'templateUri': 'mad/view/template/component/tree.ejs',
				'itemTemplateUri': 'app/view/template/component/permission/permissionListElement.ejs',
				// The map to use to make jstree working with our category model
				'map': new mad.object.Map({
					'id': 'id',
					'aro': 'aro',
					'aco': 'aco',
					'permLabel': 'PermissionType.name',
					// {
						// 'key': 'PermissionType',
						// 'func': function(permType, map, obj) {
							// return __('can %s', permType.format('short'));
						// }
					// },
					'label': {
						'key': 'aro',
						'func': function(data, map, obj) {
							switch(data) {
								case 'User':
									return obj.User.username;
								break;
								case 'Group':
									return obj.Group.name;
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
				'templateUri': 'app/view/template/form/permission/addForm.ejs'
			});
			this.addFormController.start();
			
			// Add resource id hidden field
			this.addFormController.addElement(
				new mad.form.element.TextboxController($('#js_permission_aco', this.element), {
					modelReference: 'passbolt.model.Resource.id'
				}).start()
			);
			
			// Add aro selector
			// autocomplete ! 
			// fct of the autocomplete
			// model
			// mapping
			this.textboxPermAro = new mad.form.element.TextboxController($('#js_permission_aro', this.element), {}).start();
			this.addFormController.addElement(
				this.textboxPermAro
			);
			
			this.addFormController.addElement(
				new mad.form.element.TextboxController($('#js_permission_aro_autocomplete', this.element))
					.start()
			);
			
			// Add permission type
			this.addFormController.addElement(
				new mad.form.element.DropdownController($('#js_permission_type', this.element), {
					modelReference: 'passbolt.model.Permission.serial',
					availableValues: {
						0: 'deny',
						1: 'read',
						3: 'create',
						7: 'update',
						15: 'admin'
					}
				}).start()
			);
		},
		
		/**
		 * Show the autcomplete list functions of received users and groups
		 * @param {string} value String to launche the autocomplete with
		 * @return {void}
		 */
		'showAutocomplete': function(value) {
			var self = this;
			// server return
			var users = [],
				groups = [];
			
		    // instanciate the list component
		    // create the DOM entry point for the autocomplete component
			var $el = mad.helper.HtmlHelper.create(
				this.addFormController.element,
				'last',
				'<div id="js_autcomplete" style="position:absolute; border:1px solid red;"></div>'
			);
			var autocompleteList = new mad.controller.component.TreeController($el, {
				'viewClass': mad.view.component.tree.List,
				'itemClass': mad.model.Model,
				'templateUri': 'mad/view/template/component/tree.ejs',
				// The map to use to make jstree working with our category model
				'map': new mad.object.Map({
					'id': 'id',
					'label': 'label',
					'model': 'model'
				})
			});
			autocompleteList.start();
			var rect = $('#js_permission_aro_autocomplete')[0].getBoundingClientRect();
			autocompleteList.element.css({
				'top': (rect.top + rect.height) + 'px',
				'left': rect.left + 'px'
			});
			// does not work
			// autocompleteList.view.position({
				// 'reference': {
					// 'my': 'left top',
					// 'at': 'left bottom',
					// 'of': $('#js_permission_aro_autocomplete')
				// }
			// });
			
			// @todo several options : 1. aggregate ajax request; 2. one controller which manage this operation;
			// get all the users and then get all the groups in the query callback
			passbolt.model.User.findAll({
				'keywords': value
			}, function (dataUsers, response, request) {
				users = dataUsers;
				
				// get all the grousp
				passbolt.model.Group.findAll({
					'keywords': value
				}, function (dataGroups, response, request) {
					groups = dataGroups;
					
					// aggregate users & groups in a format that the list will understand
					// oulala c'est bien de la merde ça ou je rêve, un mapper serait bien 
					var aggregatedData = [];
					groups.each(function(group, i){
						aggregatedData.push(new mad.model.Model({
							id: group.id,
							label: group.name,
							model: 'passbolt.model.Group'
						}));
					});
					users.each(function(user, i){
						aggregatedData.push(new mad.model.Model({
							id: user.id,
							label: user.username,
							model: 'passbolt.model.User'
						}));
					});
					// Load the autocomplete list
					autocompleteList.load(aggregatedData);
				});
			})
			
			// self.addFormController.getElement('js_permission_aro').setValue(value);
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
				'aco_foreign_key': this.options.acoInstance.id,
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
		
		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */
		
		/**
		 * An item has been selected in the autocomplete list
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.User || passbolt.model.Group} permission The permission to remove
		 * @return {void}
		 */
		'#js_autcomplete item_selected': function(el, ev, data) {
			this.textboxPermAro.setModelReference(data.model + '.id');
			this.textboxPermAro.setValue(data.id);
		},
		
		/**
		 * The user want to remove a permission
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Permission} permission The permission to remove
		 * @return {void}
		 */
		'#js_permission_aro_autocomplete changed': function(el, ev, data) {
			// show the autocomplete list functions of the given entered string
			this.showAutocomplete(data.value);
		},

		/**
		 * The user want to remove a permission
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Permission} permission The permission to remove
		 * @return {void}
		 */
		' add_permission': function(el, ev) {
			var self = this;
			var formData = this.addFormController.getData();
			var fieldAttrs = mad.model.Model.getModelAttributes(this.textboxPermAro.getModelReference());
			var modelAttr = fieldAttrs[0];
			var aro = modelAttr.modelReference.shortName;
			var type = formData['passbolt.model.Permission'].serial;

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
					// refresh the component with the update permissions
					self.refresh()
					// self.permLists.removeItem(permission);
					// self.permLists.insertItem(newPermission);
				});
		},

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
						// self.permLists.removeItem(permission);
						// self.permLists.insertItem(newPermission);
					});
			}
		}
		
	});

});
import 'app/view/component/permissions';
import 'app/model/group';
import 'app/model/user';
import 'app/model/permission';
import 'app/model/permission_type';
import 'app/view/template/form/permission/add.ejs!';
import 'app/view/template/component/permission/permission_list_item.ejs!';


/**
 * @inherits mad.Component
 * @parent index
 *
 * @constructor
 * Creates a new Permissions Component
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.Permissions}
 */
var Permissions = passbolt.component.Permissions = mad.Component.extend('passbolt.component.Permissions', /** @static */ {

	defaults: {
		label: 'Permissions Controller',
		viewClass: passbolt.view.component.Permissions,
		// the instance to bind the component on
		acoInstance: null,
		// the autocomplete textbox
		permAroAutocpltTxtbx: null,
		// the autocomplete list
		permAroAutocpltList: null,
		// list of changes.
		changes: []
	}

}, /** @prototype */ {

	afterStart: function() {
		var self = this;

		// List defined permissions
		this.permList = new mad.component.Tree($('#js_permissions_list'), {
			'cssClasses': ['permissions'],
			'viewClass': mad.view.component.Tree,
			'itemClass': passbolt.model.Permission,
			'templateUri': 'mad/view/template/component/tree.ejs',
			'itemTemplateUri': 'app/view/template/component/permission/permission_list_item.ejs',
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
					'key': 'type',
					'func': function(type, map, obj) {
						return passbolt.model.PermissionType.toString(type);
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
		this.addFormController = new mad.form.Form($('#js_permission_add_form', this.element), {
			'templateBased': true,
			'cssClasses': ['perm-create-form', 'clearfix'],
			'templateUri': 'app/view/template/form/permission/addForm.ejs',
			'validateOnChange': false,
			'callbacks': {
				'submit': function(data) {
					self.formAddPermissionSubmit(data);
				}
			}
		});
		this.addFormController.start();

		// Form feedback controller
		var permCreateFormFeedback = new mad.form.Feedback($('#js_perm_create_form_feedback'), {}).start();

		// Add an hidden element to the form to carry the aro id
		this.permAroHiddenTxtbx = new mad.form.element.Textbox($('#js_perm_create_form_aro', this.element), {
			modelReference: 'passbolt.model.Permission.aro_foreign_key'
		}).start();
		this.addFormController.addElement(this.permAroHiddenTxtbx);

		// Add an autocomplete element to the form to search the target aro
		this.options.permAroAutocpltTxtbx = new mad.form.element.Autocomplete($('#js_perm_create_form_aro_auto_cplt', this.element), {
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
		var permTypeCtl = new mad.form.element.Dropdown($('#js_perm_create_form_type', this.element), {
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
	autocompleteAro: function(value) {
		// get all the users and groups functions of the given string
		// start by getting all the users.
		var request = passbolt.model.User.findAll({
			'keywords': value
		}).then(function(users) {
			var returnValue = [];
			var currentUser = passbolt.model.User.getCurrent();
			users.each(function(user, i) {
				// Doesn't include the current user in the list.
				// TODO : doesn't include somebody who already have permissions.
				if (user.id == currentUser.id)
					return;
				// Otherwise, add user in autocomplete.
				returnValue.push(new mad.model.Model({
					id: user.id,
					label: user.username,
					model: 'passbolt.model.User',
					user: user
				}));
			});
			return returnValue;
		});

		return request;
	},

	loadPermission: function(permission) {
		var self = this,
			permSelector = '#js_share_rs_perm_' + permission.id,
			availablePermissionTypes = {};

		for (var permType in passbolt.model.PermissionType.PERMISSION_TYPES) {
			availablePermissionTypes[permType] = passbolt.model.PermissionType.toString(permType);
		}

		// Add the permission to the list of permissions
		this.permList.insertItem(permission);

		// Add a selectbox to display the permission type (and allow to change)
		new mad.form.element.Dropdown($('.js_share_rs_perm_type', permSelector), {
			emptyValue: false,
			modelReference: 'passbolt.model.Permission.type',
			availableValues: availablePermissionTypes
		})
			.start()
			.setValue(permission.type);
	},

	/**
	 * load permission for a given instance
	 * @param {mad.model.Model} obj The target instance
	 * @return {void}
	 */
	load: function(obj) {
		var self = this;
		this.options.acoInstance = obj;
		this.options.changes = {};

		// load the add form with the resource data
		self.addFormController.load(this.options.acoInstance);

		// get permissions for the given resource
		passbolt.model.Permission.findAll({
			'aco': this.options.acoInstance.constructor.shortName,
			'aco_foreign_key': this.options.acoInstance.id
		}, function (permissions, response, request) {
			for (var i=0; i<permissions.length; i++) {
				self.loadPermission(permissions[i]);
			}
		});
	},

	/**
	 * Refresh
	 * @return {void}
	 */
	refresh: function() {
		// reset the list in case it has already been populated
		this.permList.reset();
		// reload the component with the updated permissions
		this.load(this.options.acoInstance);
		// hide the user feedback.
		$('#js_permissions_changes').addClass('hidden');
	},

	/**
	 * Listen when the plugin has encrypted the secrets.
	 * @todo #security #architecture refactor, check also resource createFormController.
	 * @todo #dirtycode
	 */
	'{mad.bus} resource_share_secret_encrypted': function(el, ev, armoreds) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		var self = this;

		// @todo #BUG #JMVC The event is not unbound when the element is destroyed. Check that point when updating to canJS.
		if (!this.element) return;

		// Share the resource with newly created armoreds.
		this.save(armoreds);
	},

	/**
	 * Apply a permission change.
	 * @param {string} id Permission id
	 * @param {array} data New data for the permission
	 */
	permissionChange: function(id, data) {
		// Try to delete a just added permission.
		if (this.options.changes[id] !== undefined && data['delete'] !== undefined && data['delete']) {
			// Remove the changes from the list of changes.
			delete this.options.changes[id];
			// Hide the user feedback if there is no more changes.
			if (!this.options.changes.length) {
				$('#js_permissions_changes').addClass('hidden');
			}
		}
		else {
			this.options.changes[id] = {
				Permission: data
			};
			// Display the user feedback if not shown already.
			if (($permissionChanges = $('#js_permissions_changes.hidden')).length) {
				$permissionChanges.removeClass('hidden');
			}
		}
	},

	/**
	 * The add permission form has been submited.
	 * @param {array} formData the data form.
	 * @return {void}
	 */
	//find . -type f \( -name '*.php' -or -name '*.js' -or -name '*.ctp' -or -name '*.ejs' -or -name '*.html' \) \( -not -path "./lib/Cake/*"  -not -path ".*Vendor*" -not -path "*Plugin*" -not -path "./app/webroot/js/lib/can/*" -not -path "./app/webroot/js/steal/*" -not -path "./app/webroot/js/lib/documentjs/*"  -not -path "./app/webroot/js/lib/funcunit/*"  -not -path "./app/webroot/js/lib/jquerypp/*"  -not -path "./app/webroot/js/lib/jmvc/*" -not -path "./app/webroot/js/build/*"  -not -path "*production*"  -not -path "*.min.js"    -not -path "*node_modules*" \) | xargs wc -l
	formAddPermissionSubmit: function(formData) {
		var self = this,
			fieldAttrs = mad.model.Model.getModelAttributes(this.permAroHiddenTxtbx.getModelReference()),
			modelAttr = fieldAttrs[0];

		// @todo #performance avoid this step.
		var userId = formData[modelAttr.modelReference.fullName].id;
		passbolt.model.User.findOne({
			id: userId,
			async: false
		}).then(function(u) {
			user = u;
		});

		// Add the permission to the list of permission.
		var tmpPermissionId = uuid();
		var permission = new passbolt.model.Permission({
			id: tmpPermissionId,
			isNew: true,
			aco: this.options.acoInstance.constructor.shortName,
			aco_foreign_key: this.options.acoInstance.id,
			aro: modelAttr.modelReference.shortName,
			aro_foreign_key: formData[modelAttr.modelReference.fullName].id,
			type: formData['passbolt.model.Permission'].type,
			User: user
		});
		this.loadPermission(permission);

		// Store the change.
		this.permissionChange(tmpPermissionId, {
			isNew: true,
			aro: modelAttr.modelReference.shortName,
			aro_foreign_key: formData[modelAttr.modelReference.fullName].id,
			type: formData['passbolt.model.Permission'].type
		});

		// reset the add form controller.
		this.addFormController.reset();
	},

	/**
	 * Save the changes.
	 * @param {array} armoreds (optional) the secret armoreds.
	 */
	save: function(armoreds) {
		var self = this,
			data = {},
			aco = this.options.acoInstance.constructor.shortName,
			acoForeignKey = this.options.acoInstance.id;

		// Add the permissions to the request.
		data.Permissions = [];
		for (var i in this.options.changes) {
			data.Permissions.push(this.options.changes[i]);
		}
		// If armoreds secret have been given, add them to the request.
		if (armoreds) {
			data.Secrets = [];
			for (var userId in armoreds) {
				data.Secrets.push({
					'Secret': {
						'resource_id': acoForeignKey,
						'user_id': userId,
						'data': armoreds[userId]
					}
				});
			}
		}

		// create a new permission
		passbolt.model.Permission.share(aco, acoForeignKey, data)
			.then(function() {
				self.refresh();
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
	' request_permission_delete': function (el, ev, permission) {
		this.permissionChange(permission.id, {
			"id" : permission.id,
			"delete" : 1
		});
		this.permList.removeItem(permission);
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
	'{permAroAutocpltTxtbx} item_selected': function(el, ev, data) {
		// update the field model reference functions of the given autocomplete result (can be a User or a Group)
		this.permAroHiddenTxtbx.setModelReference(data.model + '.id');
		// set the value of the hidden field aro_foreign_key
		this.permAroHiddenTxtbx.setValue(data.id);
	},

	/**
	 * A permission has been updated.
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @param {string} permission The permission to edit
	 * @param {string} type The new permission type
	 * @return {void}
	 */
	' request_permission_edit': function (el, ev, permission, type) {
		this.permissionChange(permission.id, {
			id: permission.id,
			type: type
		});
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
	},

	/**
	 * The user request the form to be saved.
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'#js_rs_share_save click': function(el, ev) {
		var aco = this.options.acoInstance.constructor.shortName,
			acoForeignKey = this.options.acoInstance.id,
			usersIds = [];

		// Extract the new permissions users ids.
		for (var i in this.options.changes) {
			if (typeof this.options.changes[i].Permission.isNew != 'undefined'
				&& this.options.changes[i].Permission.isNew) {
				usersIds.push(this.options.changes[i].Permission.aro_foreign_key);
			}
		}

		// If the secret needs to be encrypted for new users.
		if (usersIds.length) {
			// ask the plugin to encrypt the secret for the new users.
			// When the secrets are encrypted the addon will send back the event secret_share_secret_encrypted.
			mad.bus.trigger('passbolt.resource_share.encrypt', {
				resourceId: acoForeignKey,
				usersIds: usersIds
			});
		}
		else {
			this.save();
		}
	}

});

export default Permissions;

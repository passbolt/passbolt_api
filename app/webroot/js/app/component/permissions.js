import 'mad/component/component';
import 'mad/form/form';
import 'mad/form/feedback';
import 'mad/form/element/autocomplete';
import 'mad/form/element/dropdown';
import 'mad/form/element/checkbox';
import 'mad/form/element/textbox';
import 'app/view/component/permissions';
import 'app/model/group';
import 'app/model/user';
import 'app/model/permission';
import 'app/model/permission_type';

import 'app/view/template/component/permissions.ejs!';
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
        // Override the viewClass option.
		viewClass: passbolt.view.component.Permissions,
		// The resource instance to bind the component on.
		acoInstance: null,
		// The list of changes.
		changes: [],
        // The template used to render the permissions component.
		templateUri: 'app/view/template/component/permissions.ejs',
        // Override the silentLoading parameter.
        silentLoading: false,
		// The initial state the component will be initialized on (after start).
		state: 'loading'
	}

}, /** @prototype */ {

	// Constructor like
	init: function (el, opts) {
		this._super(el, opts);
		this.setViewData('canAdmin', this._isAdmin());
	},

	/**
	 * Check that the current user has admin right on the resource.
	 * @return {boolean}
	 */
	_isAdmin: function() {
		return passbolt.model.Permission.isAllowedTo(this.options.acoInstance, passbolt.ADMIN);
	},

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function() {
		var self = this;

		// List defined permissions
		this.permList = new mad.component.Tree($('#js_permissions_list'), {
			cssClasses: ['permissions'],
			viewClass: mad.view.component.Tree,
			itemClass: passbolt.model.Permission,
			templateUri: 'mad/view/template/component/tree.ejs',
			itemTemplateUri: 'app/view/template/component/permission/permission_list_item.ejs',
			// The map to use to make jstree working with our permission model
			map: new mad.Map({
				id: 'id',
				isDirect: {
					key: 'aro_foreign_key',
					func: function(aro_foreign_key, map, obj) {
						return obj.isDirect(self.options.acoInstance);
					}
				},
				aroLabel: {
					key: 'aro',
					func: function(aro, map, obj) {
						return aro.toLowerCase();
					}
				},
				aroAvatarPath: {
					key: 'id',
					func: function(user, map, obj) {
						if (obj.aro == 'User') {
							return obj.User.Profile.avatarPath('small');
						} else {
							return 'img/avatar/group_default.png';
						}
					}
				},
				permType: 'PermissionType.serial',
				permLabel: {
					key: 'type',
					func: function(type, map, obj) {
						return passbolt.model.PermissionType.formatToString(type);
					}
				},
				acoLabel: {
					key: 'aco_foreign_key',
					func: function(aco_foreign_key, map, obj) {
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
				acoDetails: {
					key: 'aco_foreign_key',
					func: function(aco_foreign_key, map, obj) {
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

		if (this._isAdmin()) {
			// Add an hidden element to the form to carry the aro id
			this.permAroHiddenTxtbx = new mad.form.Textbox($('#js_perm_create_form_aro', this.element), {}).start();
			this.permAroHiddenTxtbx.setValue(this.options.acoInstance.id);

			// Notify the plugin that the share dialog is ready to interact with it.
			// The plugin will inject the form to grant new users.
			mad.bus.trigger('passbolt.plugin.resource_share', {
				resourceId: this.options.acoInstance.id,
				armored: this.options.acoInstance.Secret[0].data
			});
		}

		// Load the component for the aco instance given in options.
		this.load(this.options.acoInstance);

		// Add a button to control the final save action
		this.options.saveChangesButton = new mad.component.Button($('#js_rs_share_save'), {
			// By default it is disabled, it will be enabled once the user has changed something.
			state: 'disabled'
		}).start();

		this.on();
	},

	/**
	 * Load a new permission in the list.
	 * @param permission
	 */
	loadPermission: function(permission) {
		var permTypeSelector = '#js_share_rs_perm_' + permission.id,
			permSelector = '#' + permission.id,
            availablePermissionTypes = {},
            permissionTypes = [1, 7, 15]; // Hardcoded for Resource and direct permission.

		// Gather the available permission types
        for (var permType in permissionTypes) {
            availablePermissionTypes[permissionTypes[permType]] = passbolt.model.PermissionType.formatToString(permissionTypes[permType]);
        }

		// Add the permission to the list of permissions
		this.permList.insertItem(permission);

		// Add a selectbox to display the permission type (and allow to change)
		new mad.form.Dropdown($('.js_share_rs_perm_type', permTypeSelector), {
			id: 'js_share_perm_type_' + permission.id,
			emptyValue: false,
			modelReference: 'passbolt.model.Permission.type',
			availableValues: availablePermissionTypes,
			// If the current user has no admin right, disable this action.
			state: this._isAdmin() ? 'ready' : 'disabled'
		})
			.start()
			.setValue(permission.type);

		// Add a button to allow the user to delete the permission
		new mad.component.Button($('.js_perm_delete', permSelector), {
			id: 'js_share_perm_delete_' + permission.id,
			// If the current user has no admin right, disable this action.
			state: this._isAdmin() ? 'ready' : 'disabled'
		}).start();

		// If the permission is temporary and requires a final save action to be applied.
		if(permission.is_new) {
			// Mark the row as updated.
			$(permSelector).addClass('permission-updated');
			// Scroll the permissions list to the last permission.
			this.permList.element.scrollTop(this.permList.element[0].scrollHeight);
		}
	},

	/**
	 * load permission for a given instance
	 * @param {mad.model.Model} obj The target instance
	 */
	load: function(obj) {
		var self = this;
		this.options.acoInstance = obj;
		this.options.changes = {};

		// change the state of the component to loading.
		this.setState('loading');

		// get permissions for the given resource
		return passbolt.model.Permission.findAll({
			aco: this.options.acoInstance.constructor.shortName,
			aco_foreign_key: this.options.acoInstance.id
		}, function (permissions, response, request) {
			for (var i=0; i<permissions.length; i++) {
				self.loadPermission(permissions[i]);
			}
			// Check the permission must have a owner case
			// This check is not necessary if the current user has no admin right, as all actions
			// will be disabled.
			if (self._isAdmin()) {
				self.checkOwner();
			}

			// change the state of the component to loading
			self.setState('ready');
		});
	},

	/**
	 * Refresh
	 */
	refresh: function() {
		var self = this;

		// hide the user feedback.
		$('#js_permissions_changes').addClass('hidden');

		// reset the permissions list.
		this.permList.reset();

		// if the user lost his admin right, hide the add users form.
		if (!this._isAdmin()) {
			$('#js_permissions_create_wrapper', this.element).hide();
		}

		// reload the component with the updated permissions
		this.load(this.options.acoInstance)
			.done(function() {
				// Switch the component in ready state.
				self.setState('ready');
			});
	},

	/**
	 * Show the apply feedback.
	 */
	showApplyFeedback: function() {
		var $permissionChanges = $('#js_permissions_changes');
		$permissionChanges.removeClass('hidden');

		// Enable the save change button
		if (this.options.saveChangesButton.state.is('disabled')) {
			this.options.saveChangesButton.setState('ready');
		}
	},

	/**
	 * Hide the apply feedback.
	 */
	hideApplyFeedback: function() {
		var $permissionChanges = $('#js_permissions_changes');
		$permissionChanges.addClass('hidden');

		// Disable the save change button
		if (this.options.saveChangesButton.state.is('ready')) {
			this.options.saveChangesButton.setState('disabled');
		}
	},

	/**
	 * Owner permission check.
	 * A permission must have at least a owner.
	 * If there is only one owner, the permissions should be locked.
	 */
	checkOwner: function() {
		var self = this,
			ownerPermissions = [];

		// Get all the owner.
		this.permList.options.items.each(function (item) {
			var isOwner = false;
			// Is owner ?
			if (item.type == 15) {
				isOwner = true;
			}
			// A permission has been updated
			if (typeof self.options.changes[item.id] != 'undefined') {
				// got owner right
				if (self.options.changes[item.id].Permission.type == 15) {
					isOwner = true;
				} else {
					isOwner = false;
				}
			}
			// Add the permission to the list of owner permissions
			if (isOwner) {
				ownerPermissions.push(item);
			}
		});

		// If only one owner, make the edition of the owner permission unavailable
		if (ownerPermissions.length == 1) {
			var permTypeDropdownComponentId = 'js_share_perm_type_' + ownerPermissions[0].id,
				permDeleteButtonId = 'js_share_perm_delete_' + ownerPermissions[0].id,
				permTypeDropdown = mad.getControl(permTypeDropdownComponentId, 'mad.form.Dropdown'),
				permDeleteButton = mad.getControl(permDeleteButtonId, 'mad.component.Button');

			// Disable the permission type field and the permission delete button
			permTypeDropdown.setState('disabled');
			permDeleteButton.setState('disabled');
		}
		// If several owners, make the permission type dropdown and permission delete button enabled
		else if (ownerPermissions.length > 1) {
			for (var i in ownerPermissions) {
				var permTypeDropdownComponentId = 'js_share_perm_type_' + ownerPermissions[i].id,
					permDeleteButtonId = 'js_share_perm_delete_' + ownerPermissions[i].id,
					permTypeDropdown = mad.getControl(permTypeDropdownComponentId, 'mad.form.Dropdown'),
					permDeleteButton = mad.getControl(permDeleteButtonId, 'mad.component.Button');

				// Disable the permission type field and the permission delete button
				permTypeDropdown.setState('ready');
				permDeleteButton.setState('ready');
			}
		}
	},

	/**
	 * Add a new permission.
	 * @param data The permission data
	 */
	addPermission: function(data) {
		// Instantiate a new temporary permission.
		data.id = uuid();
		var permission = new passbolt.model.Permission(data);

		// Load this temporary permission in the permissions list component.
		this.loadPermission(permission);

		// Store the change.
		this.options.changes[data.id] = {
			Permission: {
				isNew: true,
				aco: data.aco,
				aco_foreign_key: data.aco_foreign_key,
				aro: data.aro,
				aro_foreign_key: data.aro_foreign_key,
				type: data.type
			}
		};

		// Propagate an event notifying other component regarding the changes.
		this.element.trigger('changed', this.options.changes);
		// Display the change feedback.
		this.showApplyFeedback();
	},

	/**
	 * Update an existing permission
	 * @param id The permission id
	 * @param type The permission type
	 */
	updateTypePermission: function(id, type) {
		// Store the change in the list of permissions changes.
		// If a permission change already exists for the given permission id.
		if (this.options.changes[id]) {
			this.options.changes[id].Permission.type = type;
		}
		// Otherwise add a new update change.
		else {
			this.options.changes[id] = {
				Permission: {
					id: id,
					type: type
				}
			}
		}

		// Propagate an event notifying other component regarding the changes.
		this.element.trigger('changed', this.options.changes);
		// Display the change feedback.
		this.showApplyFeedback();

		// Check the permission must have a owner case
		this.checkOwner();
	},

	/**
	 * Delete a permission
	 * @param permission The permission to update
	 */
	deletePermission: function(permission) {
		// Remove the permission from the list.
		this.permList.removeItem(permission);

		// Store the change in the list of permissions changes.
		// If a permission change already exists for the given permission id.
		if (typeof this.options.changes[permission.id] != 'undefined') {

			// If the changes is relative to a new permission. Remove this new temporary change.
			if (typeof this.options.changes[permission.id].Permission.isNew != 'undefined' &&
				typeof this.options.changes[permission.id].Permission.isNew) {

				// Remove the change.
				delete this.options.changes[permission.id];

				// Notify the plugin, the user can be listed by the autocomplete again.
				mad.bus.trigger('passbolt.share.remove_permission', {
					userId: permission.aro_foreign_key,
					isTemporaryPermission: true
				});
			}
			// Otherwise replace it with a delete change.
			else {
				this.options.changes[permission.id] = {
					Permission: {
						id : permission.id,
						delete : 1
					}
				};
				// Notify the plugin, the user shouldn't be listed by the autocomplete anymore.
				mad.bus.trigger('passbolt.share.remove_permission', {
					userId: permission.aro_foreign_key,
					isTemporaryPermission: false
				});
			}
		}
		// Otherwise add a new delete change.
		else {
			this.options.changes[permission.id] = {
				Permission: {
					id : permission.id,
					delete : 1
				}
			};
			// Notify the plugin, the user shouldn't be listed by the autocomplete anymore.
			mad.bus.trigger('passbolt.share.remove_permission', {
				userId: permission.aro_foreign_key,
				isTemporaryPermission: false
			});
		}

		// Regarding the length of the permissions changes show or hide the apply feedback.
		if ($.isEmptyObject(this.options.changes)) {
			this.hideApplyFeedback();
		}
		else {
			// Propagate an event notifying other component regarding the changes.
			this.element.trigger('changed', this.options.changes);
			// Display the change feedback.
			this.showApplyFeedback();
		}

		// Check the permission must have a owner case
		this.checkOwner();
	},

	/**
	 * Save the permissions changes.
	 * @param {array} armoreds (optional) the secret encrypted for new users.
	 */
	save: function(armoreds) {
		var self = this,
			data = {},
			aco = this.options.acoInstance.constructor.shortName,
			acoForeignKey = this.options.acoInstance.id;

		// Add the changes to the array that will be send to the server.
		data.Permissions = [];
		for (var i in this.options.changes) {
			data.Permissions.push(this.options.changes[i]);
		}

		// If the secret has been encrypted for new users, add the armored
		// secrets.
		if (armoreds) {
			data.Secrets = [];
			for (var userId in armoreds) {
				data.Secrets.push({
					Secret: {
						resource_id: acoForeignKey,
						user_id: userId,
						data: armoreds[userId]
					}
				});
			}
		}

		// Save the permissions changes.
		passbolt.model.Permission.share(aco, acoForeignKey, data)
			.then(function(data) {
				// Notify other components regarding the success of the share action.
				self.element.trigger('saved');
				// Close the dialog.
				self.closest(mad.component.Dialog)
					.remove();
			});
	},

	/* ************************************************************** */
	/* LISTEN TO THE MODEL EVENTS */
	/* ************************************************************** */

	/**
	* Listen to the destroyed event on the edited/shared resource.
	*
	* It can happen when :
	* * the user removes his own permission ;
	* * someone removed remotely the user permission ;
	* * the resource has been destroyed remotely.
	*/
	'{acoInstance} destroyed': function () {
		// For now do nothing, the only case which is managed is: the user removes his own permission.
		// This case is managed in the save function.
	},

	/* ************************************************************** */
	/* LISTEN TO THE PLUGIN EVENTS */
	/* ************************************************************** */

	/**
	 * Once the secret has been encrypted for the new users selected, the plugin
	 * trigger resource_share_encrypted event.
	 * Save the permission changes and the new encrypted secrets.
	 */
	'{mad.bus.element} resource_share_encrypted': function(el, ev, armoreds) {
		// Save the permissions changes including the secret encrypted for the new users.
		this.save(armoreds);
	},

	/**
	 * The encryption has been canceled.
	 */
	'{mad.bus.element} passbolt.plugin.share.canceled': function(el, ev) {
		this.setState('ready');
	},

	/**
	 * Listen when a permission has been added through the plugin.
	 */
	'{mad.bus.element} resource_share_add_permission': function(el, ev, data) {
		this.addPermission(data);
	},

	/* ************************************************************** */
	/* LISTEN TO THE COMPONENT EVENTS */
	/* ************************************************************** */

	/**
	 * The user want to remove a permission
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {passbolt.model.Permission} permission The permission to remove
	 */
	 ' request_permission_delete': function (el, ev, permission) {
		this.deletePermission(permission);
	},

	/**
	 * A permission has been updated.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {string} permission The permission to edit
	 * @param {string} type The new permission type
	 */
	 ' request_permission_edit': function (el, ev, permission, type) {
		this.updateTypePermission(permission.id, type);
	},

	/**
	 * The user request the form to be saved.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{saveChangesButton.element} click': function(el, ev) {
		var usersIds = [];

		// Switch the component in loading state.
		// The ready state will be restored once the component will be refreshed.
		this.setState('loading');

		// Extract the users the secret should be encrypted for by extracting the information from the changes.
		// This information shouldn't be trusted.
		for (var permissionId in this.options.changes) {

			// If the permission is a new permission, add the user id the permission is targeting to the
			// list of users the secret should be encrypted for.
			if (this.options.changes[permissionId].Permission.isNew) {
				usersIds.push(this.options.changes[permissionId].Permission.aro_foreign_key);
			}
		}

		// Request the plugin to encrypt the secret for the new users.
		// Once the plugin has encrypted the secret, it sends back an event resource_share_encrypted.
		mad.bus.trigger('passbolt.share.encrypt');
	},

	/* ************************************************************** */
	/* LISTEN TO ANY STATES CHANGES */
	/* ************************************************************** */

	/**
	 * Listen to any changes relative to the state Loading
	 * Override this function if you want add a specific behavior.
	 *
	 * @param {boolean} go Entering or leaving the state
	 */
	stateLoading: function (go) {
		var saveButton = this.options.saveChangesButton;
		if (go) {
			if (saveButton) {
				saveButton.setState('disabled');
			}
		}
		else {
			if (this.options.changes.length) {
				saveButton.setState('ready');
			}
		}

		this._super(go);
	}

});

export default Permissions;

import 'app/component/sidebar_section';
import 'app/model/permission';
import 'app/view/component/sidebar_section/permissions';
import 'app/view/template/component/sidebar_section/permissions.ejs!';
import 'app/view/template/component/sidebar_section/permission_list_item.ejs!';

/**
 * @inherits mad.component.SidebarSection
 * @parent index
 *
 * @constructor
 * Creates a new Sidebar Section Permissions Component.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.sidebarSection.Permissions}
 */
var Permissions = passbolt.component.sidebarSection.Permissions = mad.Component.extend('passbolt.component.sidebarSection.Permissions', /** @static */ {
	defaults : {
		label : 'Sidebar Section Permissions Component',
		viewClass : passbolt.view.component.sidebarSection.Permissions,
		templateUri : 'app/view/template/component/sidebar_section/permissions.ejs',
		acoInstance : null
	}

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO THE STATE CHANGES                                    */
	/* ************************************************************** */

	/**
	 * After start hook.
	 * Will basically launch a generic tagsController
	 * @see {mad.Component}
	 */
	afterStart: function () {
		var self = this,
			aco_foreign_key = this.options.acoInstance.id;

		// change the state of the component to loading
		this.setState('loading');

		// List defined permissions
		this.permissionsList = new mad.component.Tree($('#js_rs_details_permissions_list'), {
			cssClasses: ['permissions', 'shared-with'],
			viewClass: mad.view.component.Tree,
			itemClass: passbolt.model.Permission,
			templateUri: 'mad/view/template/component/tree.ejs',
			itemTemplateUri: 'app/view/template/component/sidebar_section/permission_list_item.ejs',
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
		this.permissionsList.start();

		// Load permissions.
		this.loadPermissions();

		this.on();
	},

	/**
	 * before render implementation.
	 */
	beforeRender: function () {
		this._super();

		// Tell the view if the user has the admin right for the given resource.
		this.setViewData(
			'administrable',
			passbolt.model.Permission.isAllowedTo(this.options.acoInstance, passbolt.ADMIN)
		);
	},

	/**
	 * Retrieve and load permissions in the list.
	 * @returns {*}
	 */
	loadPermissions: function() {
		var self = this,
			aco_name = this.options.acoInstance.constructor.shortName,
			aco_foreign_key = this.options.acoInstance.id;

		// Set state to loading.
		this.setState('loading');

		// Reset the list of permissions.
		this.permissionsList.reset();

		// Get permissions for the given resource.
		return passbolt.model.Permission.findAll({
			aco: aco_name,
			aco_foreign_key: aco_foreign_key
		}, function (permissions, response, request) {
			self.permissionsList.load(permissions);

			// Change the state of the component to ready.
			self.setState('ready');
		});
	},

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS                                      */
	/* ************************************************************** */

	/**
	 * Observe when the user want to edit the instance's resource description
	 * @param {HTMLElement} el The element
	 * @param {HTMLEvent} ev The event which occurred
	 */
	' request_resource_permissions_edit' : function(el, ev) {
		mad.bus.trigger('request_resource_sharing', this.options.acoInstance);
	}

});

export default Permissions;
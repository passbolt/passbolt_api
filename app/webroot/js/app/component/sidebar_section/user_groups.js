import 'app/component/sidebar_section';
import 'app/model/group';
import 'app/view/template/component/sidebar_section/user_groups.ejs!';
import 'app/view/template/component/sidebar_section/user_group_list_item.ejs!';

/**
 * @inherits mad.component.SidebarSection
 * @parent index
 *
 * @constructor
 * Creates a new Sidebar Section UserGroups Component.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.sidebarSection.UserGroups}
 */
var UserGroups = passbolt.component.sidebarSection.UserGroups = mad.Component.extend('passbolt.component.sidebarSection.UserGroups', /** @static */ {
	defaults : {
		label: 'Sidebar Section User Groups Component',
		templateUri: 'app/view/template/component/sidebar_section/user_groups.ejs',
		acoInstance: null,
		selectedUser: null,
		state: 'loading'
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * Will basically launch a generic tagsController
	 * @see {mad.Component}
	 */
	afterStart: function () {
		var self = this;

		// Group list map.
		var groupListMap = new mad.Map({
			id: 'id',
			name: 'name',
			role: {
				key: 'GroupUser',
				func: function (value, map, item, mappedValues) {
					return value.attr()
						.filter(function(groupUser) {
							return groupUser.user_id == self.options.selectedUser.id;
						}).reduce(function(sum, groupUser) {
							return groupUser.is_admin ? __('Group manager') : __('Member');
						}, '');
				}
			}
		});

		// Initialize the groups list component.
		this.groupList = new mad.component.Tree($('#js_user_groups_list'), {
			cssClasses: ['groups', 'shared-with'],
			viewClass: mad.view.component.Tree,
			itemClass: passbolt.model.Group,
			templateUri: 'mad/view/template/component/tree.ejs',
			itemTemplateUri: 'app/view/template/component/sidebar_section/user_group_list_item.ejs',
			prefixItemId: 'js_user_groups_list_',
			map: groupListMap
		});
		this.groupList.start();

		// Retrieve the groups the user is member of and load the list component.
		this.findUserGroups()
			.then(function(groups) {
				self.groupList.load(groups);
				self.setState('ready');
			});
	},

	/**
	 * Find the groups the users is member of
	 * @return {promise}
	 */
	findUserGroups: function() {
		var findOptions = {
			contain: {user: 1},
			order: ['Group.name ASC'],
			filter: {
				'has-users' : this.options.selectedUser.id
			}
		};
		return passbolt.model.Group.findAll(findOptions);
	},

	/* ************************************************************** */
	/* LISTEN TO THE MODEL EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when a group is created.
	 * Update the component.
	 * @param {mad.Model} group The updated group
	 * @param {object} group The updated group
	 * @param {passbolt.model.Group} group The updated group
	 */
	'{passbolt.model.Group} created': function (model, event, group) {
		this.refresh();
	},

	/**
	 * Observe when a group is updated.
	 * Update the component.
	 * @param {mad.Model} group The updated group
	 * @param {object} group The updated group
	 * @param {passbolt.model.Group} group The updated group
	 */
	'{passbolt.model.Group} updated': function (model, event, group) {
		this.refresh();
	},

	/**
	 * Observe when a group is deleted.
	 * Update the component.
	 * @param {mad.Model} group The updated group
	 * @param {object} group The updated group
	 * @param {passbolt.model.Group} group The updated group
	 */
	'{passbolt.model.Group} deleted': function (model, event, group) {
		this.refresh();
	}

});

export default UserGroups;
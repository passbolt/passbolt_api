import 'mad/view/component/tree';
import 'app/model/group';
import 'app/model/group_user';
import 'app/component/sidebar';
import 'app/view/component/group_sidebar';
import 'app/view/template/component/group_sidebar.ejs!';
import 'app/view/template/component/sidebar_section/group_members_list_item.ejs!';

/*
 * @class passbolt.component.GroupSidebar
 * @inherits mad.component.Sidebar
 * @parent index
 *
 * @constructor
 * Creates a new Group sidebar component
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.GroupSidebar}
 */
var GroupSidebar = passbolt.component.GroupSidebar = passbolt.component.Sidebar.extend('passbolt.component.GroupSidebar', /** @static */ {

	defaults: {
        // Label.
		label: 'Group Details Controller',
        // View class to be used.
		viewClass: passbolt.view.component.GroupSidebar,
        // Template.
		templateUri: 'app/view/template/component/group_sidebar.ejs'
	}

}, /** @prototype */ {

	/**
	 * before start hook.
	 */
	beforeRender: function () {
		this._super();
		var currentUser = passbolt.model.User.getCurrent();
		this.setViewData('editable', this.options.selectedItem.isAllowedToEdit(currentUser));
		this.setViewData('group', this.options.selectedItem);
	},

	/**
	 * Refresh the view only.
	 * @todo Check if interesting to move it into mad component.
	 */
	_refreshView: function() {
		this.element.empty();
		if (this.options.templateBased) {
			this.beforeRender();
			var render = this.view.render();
			render = this.afterRender(render);
			this.view.insertInDom(render);
		}
	},

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		var self = this;
		this._super();
		passbolt.model.Group.findOne({id: this.options.selectedItem.id, contain:{modifier:1}})
			.then(function(group) {
				self.options.selectedItem = group;
				self._refreshView();
				self.initGroupMembersList(group.GroupUser);
				self.on();
			});
	},

	/**
	 * Init the group members section.
	 */
	initGroupMembersList: function(groupUsers) {
		var map = new mad.Map({
			id: 'id',
			userFullName: {
				key: 'User.Profile',
				func: function(profile) {
					return profile.first_name + ' ' + profile.last_name;
				}
			},
			membershipType: {
				key: 'is_admin',
				func: function(isAdmin) {
					return passbolt.model.GroupUser.membershipType[isAdmin ? 1 : 0];
				}
			},
			userAvatarPath: {
				key: 'User.Profile',
				func: function(profile) {
					return profile.avatarPath('small');
				}
			}
		});

		this.options.groupMembersList = new mad.component.Tree($('#js_group_details_group_members_list'), {
			label: 'Group Members List Controller',
			itemClass: passbolt.model.GroupUser,
			itemTemplateUri: 'app/view/template/component/sidebar_section/group_members_list_item.ejs',
			map: map
		});
		this.options.groupMembersList.start();
		this.options.groupMembersList.load(groupUsers);
	},

	/**
	 * Observe when the user want to edit the group members
	 * @param {HTMLElement} el The element
	 * @param {HTMLEvent} ev The event which occurred
	 */
	' request_group_edition' : function(el, ev) {
		mad.bus.trigger('request_group_edition', this.options.selectedItem);
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Listen to the event user_selected
	 * @param {jQuery} element The source element
	 * @param {Event} event The jQuery event
	 * @param {passbolt.model.User} user The selected user
	 */
	'{mad.bus.element} user_selected': function (element, evt, user) {
		if (!this.state.is(null) && !this.state.is('hidden')) {
			this.setState('hidden');
		}
	}

});

export default GroupSidebar;

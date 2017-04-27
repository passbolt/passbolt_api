import 'mad/component/component';
import 'mad/component/button';

import 'app/view/template/component/people_workspace_menu.ejs!';

/**
 * @class passbolt.component.PeopleWorkspaceMenu
 * @inherits mad.controller.component.ComponentController
 * @parent index
 *
 * Our passbolt user workspace menu controller
 *
 * @constructor
 * Creates a new user workspace menu controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.PeopleWorkspaceMenuController}
 */
var PeopleWorkspaceMenu = passbolt.component.PeopleWorkspaceMenu = mad.Component.extend('passbolt.component.PeopleWorkspaceMenu', /** @static */ {

    defaults: {
        label: 'User Workspace Menu Controller',
        tag: 'ul',
        // the selected users, you can pass an existing list as parameter of the constructor to share the same list
        selectedUsers: new can.Model.List(),
        templateUri: 'app/view/template/component/people_workspace_menu.ejs'
    }

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
    afterStart: function () {
        var user = passbolt.model.User.getCurrent(),
        	userRole = user.Role.name;

        if (userRole == 'admin') {

            // Manage edition action
            this.options.editionButton = new mad.component.Button($('#js_user_wk_menu_edition_button'), {
                state: 'disabled'
            }).start();

            // Manage deletion action
            this.options.deletionButton = new mad.component.Button($('#js_user_wk_menu_deletion_button'), {
                state: 'disabled'
            }).start();
        }

        this.on();
    },

    /* ************************************************************** */
    /* LISTEN TO THE APP EVENTS */
    /* ************************************************************** */

    /**
     * Observe when the user wants to edit an instance (Resource, User depending of the active workspace)
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{editionButton.element} click': function (el, ev) {
        mad.bus.trigger('request_user_edition');
    },

    /**
     * Observe when the user wants to delete an instance (Resource, User depending of the active workspace)
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{deletionButton.element} click': function (el, ev) {
        var users = this.options.selectedUsers;
        mad.bus.trigger('request_user_deletion', users);
    },

    /**
     * Observe when a user is selected
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.User} user The selected user
     */
    '{selectedUsers} add': function (el, ev, user) {
        // if no user selected.
        if (this.options.selectedUsers.length == 0) {
            this.setState('ready');
        }
        // else if only 1 user is selected show the details
        else if (this.options.selectedUsers.length == 1) {
            this.setState('selection');
        }
        // else if more than one resource have been selected
        else {
            this.setState('multiSelection');
        }
    },

    /**
     * Observe when a user is unselected
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.User} user The unselected user
     */
    '{selectedUsers} remove': function (el, ev, user) {
        // if more than one resource selected, or no resource selected
        if (this.options.selectedUsers.length == 0) {
            this.setState('ready');

            // else if only 1 resource selected show the details
        } else if (this.options.selectedUsers.length == 1) {
            this.setState('selection');

            // else if more than one resource have been selected
        } else {
            this.setState('multiSelection');
        }
    },

    /* ************************************************************** */
    /* LISTEN TO THE STATE CHANGES */
    /* ************************************************************** */

    /**
     * Listen to the change relative to the state selected
     * @param {boolean} go Enter or leave the state
     */
    stateSelection: function (go) {
        // Is the current user an admin.
        var isAdmin = passbolt.model.User.getCurrent().Role.name == 'admin';

        // If user is an admin, we enable the controls.
        if (isAdmin) {
            if (go) {

                // Is the selected user same as the current user.
                var isSelf = passbolt.model.User.getCurrent().id == this.options.selectedUsers[0].id;

                this.options.editionButton
                    .setValue(this.options.selectedUsers[0])
                    .setState('ready');

                // If the user has not selected himself.
                if (!isSelf) {
                    // Activate the delete button.
                    this.options.deletionButton
                        .setValue(this.options.selectedUsers)
                        .setState('ready');
                }
                // If user has selected himself, delete is not available.
                else {
                    this.options.deletionButton
                        .setValue(null)
                        .setState('disabled');
                }
            } else {
                this.options.editionButton
                    .setValue(null)
                    .setState('disabled');
                this.options.deletionButton
                    .setValue(null)
                    .setState('disabled');
            }
        }
    },

    /**
     * Listen to the change relative to the state multiSelection
     * @param {boolean} go Enter or leave the state
     */
    stateMultiSelection: function (go) {
        if (passbolt.model.User.getCurrent().Role.name == 'admin') {
            if (go) {
                this.options.editionButton
                    .setState('disabled');
                this.options.deletionButton
                    .setValue(this.options.selectedUsers)
                    .setState('ready');
            } else {
                this.options.editionButton
                    .setValue(null)
                    .setState('disabled');
                this.options.deletionButton
                    .setValue(null)
                    .setState('disabled');
            }
        }
    }

});

export default PeopleWorkspaceMenu;
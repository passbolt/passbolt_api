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
        // the selected group, you can pass an existing list as parameter of the constructor to share the same list
        selectedGroups: new can.Model.List(),
        templateUri: 'app/view/template/component/people_workspace_menu.ejs'
    }

}, /** @prototype */ {

    /**
     * after start hook.
     * @return {void}
     */
    afterStart: function () {
        var self = this;

        var user = passbolt.model.User.getCurrent();
        var userRole = user.Role.name;

        if (userRole == 'admin') {
            // Manage creation action
            this.options.creationButton = new mad.component.Button($('#js_user_wk_menu_creation_button'))
                .start();

            // Manage edition action
            this.options.editionButton = new mad.component.Button($('#js_user_wk_menu_edition_button'), {
                state: 'disabled'
            }).start();

            // Manage deletion action
            this.options.deletionButton = new mad.component.Button($('#js_user_wk_menu_deletion_button'), {
                state: 'disabled'
            }).start();

            // Manage more actions.
			// #PASSBOLT-787
            //var moreButtonMenuItems = [
            //    new mad.model.Action({
            //        id: 'js_ppl_wk_remove_user_from_group',
            //        label: __('remove user from group'),
            //        initial_state: 'disabled',
            //        cssClasses: null,
            //        	action: function () {
            //            mad.bus.trigger(
            //                'request_remove_user_from_group',
            //                [self.options.selectedUsers, self.options.selectedGroups]
            //            );
            //        }
            //    })
            //];
            //this.options.moreButton = new mad.component.ButtonDropdown($('#js_user_wk_menu_more_button'), {
            //    state: 'disabled',
            //    items: moreButtonMenuItems
            //}).start();
        }

        // @todo URGENT, buggy, it rebinds 2 times external element event (such as madbus)
        this.on();
    },

    /* ************************************************************** */
    /* LISTEN TO THE APP EVENTS */
    /* ************************************************************** */

    /**
     * Observe when the user wants to create a new user
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{creationButton.element} click': function (el, ev) {
        /*var category = this.options.creationButton.getValue();*/
        mad.bus.trigger('request_user_creation'/*, category*/);
    },

    /**
     * Observe when the user wants to edit an instance (Resource, User depending of the active workspace)
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{editionButton.element} click': function (el, ev) {
        /*var category = this.options.editionButton.getValue();*/
        mad.bus.trigger('request_user_edition'/*, category*/);
    },

    /**
     * Observe when the user wants to delete an instance (Resource, User depending of the active workspace)
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{deletionButton.element} click': function (el, ev) {
        var users = this.options.selectedUsers;
        mad.bus.trigger('request_user_deletion', users);
    },

    /**
     * Observe when a user is selected
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {passbolt.model.User} user The selected user
     * @return {void}
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

        if (passbolt.model.User.getCurrent().Role.name == 'admin') {
            // Enable or disable the "remove user from group" if a group is selected.
            // Active if at least a group is selected.
			// #PASSBOLT-787
            //if (this.options.selectedGroups.length > 0) {
            //    this.options.moreButton.setItemState('js_ppl_wk_remove_user_from_group', 'ready');
            //}
            //// Disabled if no group selected.
            //else {
            //    this.options.moreButton.setItemState('js_ppl_wk_remove_user_from_group', 'disabled');
            //}
        }
    },

    /**
     * Observe when a user is unselected
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {passbolt.model.User} user The unselected user
     * @return {void}
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
     * @return {void}
     */
    stateSelection: function (go) {
        if (passbolt.model.User.getCurrent().Role.name == 'admin') {
            if (go) {
                this.options.editionButton
                    .setValue(this.options.selectedUsers[0])
                    .setState('ready');
                this.options.deletionButton
                    .setValue(this.options.selectedUsers)
                    .setState('ready');
				// #PASSBOLT-787
				//this.options.moreButton
                 //   .setValue(this.options.selectedUsers[0])
                 //   .setState('ready');
            } else {
                this.options.editionButton
                    .setValue(null)
                    .setState('disabled');
                this.options.deletionButton
                    .setValue(null)
                    .setState('disabled');
				// #PASSBOLT-787
                //this.options.moreButton
                //    .setValue(null)
                //    .setState('disabled');
            }
        }
    },

    /**
     * Listen to the change relative to the state multiSelection
     * @param {boolean} go Enter or leave the state
     * @return {void}
     */
    stateMultiSelection: function (go) {
        if (passbolt.model.User.getCurrent().Role.name == 'admin') {
            if (go) {
                this.options.editionButton
                    .setState('disabled');
                this.options.deletionButton
                    .setValue(this.options.selectedUsers)
                    .setState('ready');
				// #PASSBOLT-787
                //this.options.moreButton
                //    .setState('disabled');
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
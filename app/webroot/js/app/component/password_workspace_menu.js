import 'mad/component/component';
import 'mad/component/button';
import 'mad/component/button_dropdown';

import 'app/view/template/component/password_workspace_menu.ejs!';

/**
 * @class passbolt.component.PeopleWorkspaceMenu
 * @inherits mad.ComponentController
 * @parent index
 *
 * Our passbolt password workspace menu controller
 *
 * @constructor
 * Creates a new user workspace menu controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.PeopleWorkspaceMenuController}
 */
var PasswordWorkspaceMenu = passbolt.component.PasswordWorkspaceMenu = mad.Component.extend('passbolt.component.PasswordWorkspaceMenu', /** @static */ {

    defaults: {
        label: 'Workspace Menu Controller',
        tag: 'ul',
        // the selected resources, you can pass an existing list as parameter of the constructor to share the same list
        selectedRs: new can.Model.List(),
        templateUri: 'app/view/template/component/password_workspace_menu.ejs'
    }

}, /** @prototype */ {

    /**
     * after start hook.
     */
    afterStart: function () {
        var self = this;

        // Manage creation action
        this.options.creationButton = new mad.component.Button($('#js_wk_menu_creation_button'))
            .start();

        // Manage edition action
        this.options.editionButton = new mad.component.Button($('#js_wk_menu_edition_button'), {
            'state': 'disabled'
        }).start();

        // Manage deletion action
        this.options.deletionButton = new mad.component.Button($('#js_wk_menu_deletion_button'), {
            'state': 'disabled'
        }).start();

        // Manage sharing action
        this.options.sharingButton = new mad.component.Button($('#js_wk_menu_sharing_button'), {
            'state': 'disabled'
        }).start();

        // Manage more action
        var moreButtonMenuItems = [
            new mad.model.Action({
                'id': uuid(),
                'label': __('copy login to clipboard'),
                'cssClasses': ['todo'],
                'action': function () {
                    var username = self.options.selectedRs[0].username;
                    mad.bus.trigger('passbolt.login.clipboard', username);
                }
            }),
            new mad.model.Action({
                'id': uuid(),
                'label': __('copy password to clipboard'),
                'cssClasses': ['todo'],
                'action': function () {
                    var secret = self.options.selectedRs[0].Secret[0].data;
                    mad.bus.trigger('passbolt.secret.decrypt', secret);
                }
            })
        ];
        this.options.moreButton = new mad.component.ButtonDropdown($('#js_wk_menu_more_button'), {
            'state': 'disabled',
            'items': moreButtonMenuItems
        }).start();

        // @todo URGENT, buggy, it rebinds 2 times external element event (such as madbus)
        this.on();
    },

    /* ************************************************************** */
    /* LISTEN TO THE APP EVENTS */
    /* ************************************************************** */

    /**
     * Observe when the user wants to create a new instance (Resource, User depending of the active workspace)
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{creationButton} click': function (el, ev) {
        var resource = this.options.creationButton.getValue();
        mad.bus.trigger('request_resource_creation', resource);
    },

    /**
     * Observe when the user wants to edit an instance (Resource, User depending of the active workspace)
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{editionButton} click': function (el, ev) {
        var resource = this.options.editionButton.getValue();
        mad.bus.trigger('request_resource_edition', resource);
    },

    /**
     * Observe when the user wants to delete an instance (Resource, User depending of the active workspace)
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{deletionButton} click': function (el, ev) {
        var resources = this.options.deletionButton.getValue();
        mad.bus.trigger('request_resource_deletion', resources);
    },

    /**
     * Observe when the user wants to share an instance (Resource, User depending of the active workspace)
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{sharingButton} click': function (el, ev) {
        var resource = this.options.sharingButton.getValue();
        mad.bus.trigger('request_resource_sharing', resource);
    },

    /**
     * Observe when a resource is selected
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {passbolt.model.Resource} resource The selected resource
     * @return {void}
     */
    '{selectedRs} add': function (el, ev, resource) {
        // if more than one resource selected, or no resource selected
        if (this.options.selectedRs.length == 0) {
            this.setState('ready');

            // else if only 1 resource selected show the details
        } else if (this.options.selectedRs.length == 1) {
            this.setState('selection');

            // else if more than one resource have been selected
        } else {
            this.setState('multiSelection');
        }
    },

    /**
     * Observe when a resource is unselected
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {passbolt.model.Resource} resource The unselected resource
     * @return {void}
     */
    '{selectedRs} remove': function (el, ev, resource) {
        // if more than one resource selected, or no resource selected
        if (this.options.selectedRs.length == 0) {
            this.setState('ready');

            // else if only 1 resource selected show the details
        } else if (this.options.selectedRs.length == 1) {
            this.setState('selection');

            // else if more than one resource have been selected
        } else {
            this.setState('multiSelection');
        }
    },

    /**
     * Observe when a filter is applied on the wsp
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {passbolt.model.Filter} filter The unselected resource
     * @return {void}
     */
    '{mad.bus} filter_resources_browser': function(el, ev, filter) {
        // @todo fixed in future canJs.
        if (!this.element) return;

        var categories = filter.getForeignModels('Category');
        var state = 'ready';

        // If no categories selected, it's allowed to create password.
        if (!categories) {
            // Reset the value carried by the button.
            this.options.creationButton.setValue(new can.List([]));
        } else {
            // The button will now carry the latest selected categories.
            this.options.creationButton.setValue(categories);

            // If the user doesn't have the permission to create into the selected category.
            // Or if multiple categories selected.
            if (categories.length > 1 || (
                categories.length == 1 &&
                !passbolt.model.Permission.isAllowedTo(categories[0], passbolt.CREATE))
            ) {
                state = 'disabled';
            }
        }

        this.options.creationButton.setState(state);
    },

    /* ************************************************************** */
    /* LISTEN TO THE STATE CHANGES */
    /* ************************************************************** */

    /**
     * Listen to the change relative to the state selected
     * @param {boolean} go Enter or leave the state
     * @return {void}
     */
    'stateSelection': function (go) {
        if (go) {
            // Is the resource editable ?
            var updatable = passbolt.model.Permission.isAllowedTo(this.options.selectedRs[0], passbolt.UPDATE);
            // Is the resource administrable ?
            var administrable = passbolt.model.Permission.isAllowedTo(this.options.selectedRs[0], passbolt.ADMIN);

            this.options.editionButton
                .setValue(this.options.selectedRs[0])
                .setState(updatable ? 'ready' : 'disabled');
            this.options.deletionButton
                .setValue(this.options.selectedRs)
                .setState(updatable ? 'ready' : 'disabled');
            this.options.sharingButton
                .setValue(this.options.selectedRs)
                .setState(administrable ? 'ready' : 'disabled');
            this.options.moreButton
                .setValue(this.options.selectedRs[0])
                .setState('ready');
        } else {
            this.options.editionButton
                .setValue(null)
                .setState('disabled');
            this.options.deletionButton
                .setValue(null)
                .setState('disabled');
            this.options.sharingButton
                .setValue(null)
                .setState('disabled');
            this.options.moreButton
                .setValue(null)
                .setState('disabled');
        }
    },

    /**
     * Listen to the change relative to the state multiSelection
     * @param {boolean} go Enter or leave the state
     * @return {void}
     */
    'stateMultiSelection': function (go) {
        if (go) {
            // Is the resource editable ?
            var canUpdate = passbolt.model.Permission.isAllowedTo(this.options.selectedRs, passbolt.UPDATE);
            // Is the resource administrable ?
            var canAdmin = passbolt.model.Permission.isAllowedTo(this.options.selectedRs, passbolt.ADMIN);

            this.options.editionButton
                .setState('disabled');
            this.options.deletionButton
                .setValue(this.options.selectedRs)
                .setState(canUpdate ? 'ready' : 'disabled');
            this.options.sharingButton
                .setValue(this.options.selectedRs)
                .setState(canAdmin ? 'ready' : 'disabled');
            this.options.moreButton
                .setState('disabled');
        } else {
            this.options.editionButton
                .setValue(null)
                .setState('disabled');
            this.options.deletionButton
                .setValue(null)
                .setState('disabled');
            this.options.sharingButton
                .setValue(null)
                .setState('disabled');
        }
    }

});

export default PasswordWorkspaceMenu;
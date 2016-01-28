import 'mad/component/component';
import 'mad/component/button';
import 'mad/component/button_dropdown';
import 'app/model/permission';

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
	 * After start hook.
	 * @see {mad.Component}
	 */
    afterStart: function () {
        var self = this;

		// Manage edition action
		this.options.secretCopyButton = new mad.component.Button($('#js_wk_menu_secretcopy_button'), {
			state: 'disabled'
		}).start();

		// Manage edition action
		this.options.editButton = new mad.component.Button($('#js_wk_menu_edition_button'), {
			state: 'disabled'
		}).start();

        // Manage sharing action
        this.options.shareButton = new mad.component.Button($('#js_wk_menu_sharing_button'), {
            state: 'disabled'
        }).start();

        // Manage more action
        var moreButtonMenuItems = [
            new mad.model.Action({
                id: uuid(),
                label: __('copy login to clipboard'),
                cssClasses: [],
                action: function () {
                    var username = self.options.selectedRs[0].username;
                    mad.bus.trigger('passbolt.clipboard', {
						name: 'username',
						data: username
					});
                }
            }),
			new mad.model.Action({
				id: uuid(),
				label: __('copy password to clipboard'),
				cssClasses: [],
				action: function () {
					var secret = self.options.selectedRs[0].Secret[0].data;
					mad.bus.trigger('passbolt.secret.decrypt', secret);
				}
			}),
			new mad.model.Action({
				id: 'js_wk_menu_delete_action',
				label: __('delete'),
				cssClasses: [],
				action: function () {
					self.element.trigger('delete_action_clicked');
				}
			})
        ];
        this.options.moreButton = new mad.component.ButtonDropdown($('#js_wk_menu_more_button'), {

            state: 'disabled',
            items: moreButtonMenuItems
        }).start();

        // @todo URGENT, buggy, it rebinds 2 times external element event (such as madbus)
        this.on();
    },

    /* ************************************************************** */
    /* LISTEN TO THE APP EVENTS */
    /* ************************************************************** */

	/**
	 * Observe when the user wants to copy the secret a resource.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{secretCopyButton.element} click': function (el, ev) {
		var resource = this.options.editButton.getValue();
		var secret = resource.Secret[0].data;
		mad.bus.trigger('passbolt.secret.decrypt', secret);
	},

    /**
     * Observe when the user wants to edit a resource.
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{editButton.element} click': function (el, ev) {
        var resource = this.options.editButton.getValue();
        mad.bus.trigger('request_resource_edition', resource);
    },

    /**
     * Observe when the user wants to delete a resource.
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    ' delete_action_clicked': function (el, ev) {
        var resources = this.options.selectedRs;
        mad.bus.trigger('request_resource_deletion', resources);
    },

    /**
     * Observe when the user wants to share a resrouce.
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{shareButton.element} click': function (el, ev) {
        var resource = this.options.shareButton.getValue();
        mad.bus.trigger('request_resource_sharing', resource);
    },

    /**
     * Observe when a resource is selected
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.Resource} resource The selected resource
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
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.Resource} resource The unselected resource
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

    /* ************************************************************** */
    /* LISTEN TO THE STATE CHANGES */
    /* ************************************************************** */

    /**
     * Listen to the change relative to the state selected
     * @param {boolean} go Enter or leave the state
     */
    stateSelection: function (go) {
        if (go) {
            // Is the resource editable ?
            var updatable = passbolt.model.Permission.isAllowedTo(this.options.selectedRs[0], passbolt.UPDATE);
            // Is the resource administrable ?
            var administrable = passbolt.model.Permission.isAllowedTo(this.options.selectedRs[0], passbolt.ADMIN);

			this.options.secretCopyButton
				.setValue(this.options.selectedRs[0])
				.setState('ready');
            this.options.editButton
                .setValue(this.options.selectedRs[0])
                .setState(updatable ? 'ready' : 'disabled');
            this.options.shareButton
                .setValue(this.options.selectedRs)
                .setState(administrable ? 'ready' : 'disabled');
            this.options.moreButton
                .setValue(this.options.selectedRs[0])
                .setState('ready');
			this.options.moreButton.setItemState('js_wk_menu_delete_action', updatable ? 'ready' : 'disabled')
        } else {
			this.options.secretCopyButton
				.setValue(null)
				.setState('disabled');
            this.options.editButton
                .setValue(null)
                .setState('disabled');
            this.options.shareButton
                .setValue(null)
                .setState('disabled');
            this.options.moreButton
                .setValue(null)
                .setState('disabled');
			this.options.moreButton.setItemState('js_wk_menu_delete_action', 'disabled');
        }
    },

    /**
     * Listen to the change relative to the state multiSelection
     * @param {boolean} go Enter or leave the state
     */
    stateMultiSelection: function (go) {
        if (go) {
            // Is the resource editable ?
            var canUpdate = passbolt.model.Permission.isAllowedTo(this.options.selectedRs, passbolt.UPDATE);
            // Is the resource administrable ?
            var canAdmin = passbolt.model.Permission.isAllowedTo(this.options.selectedRs, passbolt.ADMIN);

			this.options.secretCopyButton
				.setValue(null)
				.setState('disabled');
            this.options.editButton
                .setState('disabled');
            this.options.shareButton
                .setValue(this.options.selectedRs)
                .setState(canAdmin ? 'ready' : 'disabled');
            this.options.moreButton
                .setState('disabled');
        } else {
			this.options.secretCopyButton
				.setValue(null)
				.setState('disabled');
            this.options.editButton
                .setValue(null)
                .setState('disabled');
            this.options.shareButton
                .setValue(null)
                .setState('disabled');
        }
    }

});

export default PasswordWorkspaceMenu;
import 'mad/component/component';
import 'mad/component/dialog';
import 'app/component/people_workspace_menu';
import 'app/component/workspace_secondary_menu';
import 'app/component/people_breadcrumb';
//import 'app/component/group_chooser'; // @roadmap
import 'app/component/user_browser';
import 'app/component/user_shortcuts';
import 'app/component/user_details';
import 'app/form/user/create';
//import 'app/form/group/create'; // @roadmap
import 'app/model/user';
//import 'app/model/group'; // @roadmap
import 'app/model/filter';

import 'app/view/template/people_workspace.ejs!';

/**
 * @inherits {mad.Component}
 * @parent index
 *
 * @constructor
 * Creates a new People Workspace Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.PeopleWorkspace}
 */
var PeopleWorkspace = passbolt.component.PeopleWorkspace = mad.Component.extend('passbolt.component.PeopleWorkspace', /** @static */ {

    defaults: {
        label: 'People',
        templateUri: 'app/view/template/people_workspace.ejs',
        selectedUsers: new can.Model.List(),
        selectedGroups: new can.Model.List(),
        filter: new passbolt.model.Filter(),
		// Override the silentLoading parameter.
		silentLoading: false
    }

}, /** @prototype */ {

    /**
     * Called right after the start function
     * @return {void}
     * @see {mad.controller.ComponentController}
     */
    afterStart: function() {
        // Instantiate the primary workspace menu controller outside of the workspace container, destroy it when the workspace is destroyed
        var primWkMenu = mad.helper.Component.create(
            $('#js_wsp_primary_menu_wrapper'),
            'last',
            passbolt.component.PeopleWorkspaceMenu, {
                selectedUsers: this.options.selectedUsers,
                selectedGroups: this.options.selectedGroups
            }
        );
        primWkMenu.start();

        // Instantiate the secondary workspace menu controller outside of the workspace container, destroy it when the workspace is destroyed
        var secWkMenu = mad.helper.Component.create(
            $('#js_wsp_secondary_menu_wrapper'),
            'last',
            passbolt.component.WorkspaceSecondaryMenu,
            {}
        );
        secWkMenu.start();

        // Instantiate the password workspace breadcrumb controller
        this.breadcrumCtl = new passbolt.component.PeopleBreadcrumb($('#js_wsp_users_breadcrumb'), {});
        this.breadcrumCtl.start();

        // Instanciate the users filter controller.
        var userShortcut = new passbolt.component.UserShortcuts('#js_wsp_users_filter_shortcuts', {});
        userShortcut.start();

        // Removed group choosed for #PASSBOLT-787
        //// Instanciate the group chooser controller.
        //this.grpChooser = new passbolt.component.GroupChooserController('#js_wsp_users_group_chooser', {
        //'selectedGroups': this.options.selectedGroups
        //});
        //this.grpChooser.start();

        // Instanciate the passwords browser controller.
        var userBrowserController = new passbolt.component.UserBrowser('#js_wsp_users_browser', {
            selectedUsers: this.options.selectedUsers
        });
        userBrowserController.start();

        // Instanciate the resource details controller
        var userDetails = new passbolt.component.UserDetails($('.js_wsp_users_sidebar_second', this.element), {
            id: 'js_user_details',
            selectedUsers: this.options.selectedUsers
        });

        // Filter the workspace.
        var filter = null;
        // A filter has been given in options.
        if (this.options.filter) {
            filter = this.options.filter;
        } else {
            filter = new passbolt.model.Filter({
                label: __('All users'),
                type: passbolt.model.Filter.SHORTCUT
            });
        }
        mad.bus.trigger('filter_users_browser', filter);
    },

    /**
     * Destroy the workspace.
     */
    destroy: function() {
        // Be sure that the primary & secondary workspace menus controllers will be destroyed also.
        $('#js_wsp_primary_menu_wrapper').empty();
        $('#js_wsp_secondary_menu_wrapper').empty();

        this._super();
    },

    /* ************************************************************** */
    /* LISTEN TO THE APP EVENTS */
    /* ************************************************************** */

    /**
     * Observe when group is selected
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {passbolt.model.Group} group The selected group
     * @return {void}
     */
    '{mad.bus.element} group_selected': function (el, ev, group) {
        // @todo fixed in future canJs.
        if (!this.element) return;

        console.log('group selected');
        // reset the selected resources
        this.options.selectedUsers.splice(0, this.options.selectedUsers.length);
        // Set the new filter
        this.options.filter.attr({
            foreignModels: {
                Group: new can.List([group])
            },
            type: passbolt.model.Filter.FOREIGN_MODEL
        });
        // propagate a special event on bus
        mad.bus.trigger('filter_users_browser', this.options.filter);

        // Add the group to the list of selected groups.
        this.options.selectedGroups.splice(0, this.options.selectedGroups.length);
        this.options.selectedGroups.push(group);
    },

    /**
     * Event filter_users_browser.
     * When a new filter is applied.
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {passbolt.model.Filter} filter, the filter being applied.
     * @return {void}
     */
    '{mad.bus.element} filter_users_browser': function (el, ev, filter) {
        // @todo fixed in future canJs.
        if (!this.element) return;

        // If the filter applied is "all groups", then empty the list of selected groups.
        if (typeof filter.name != 'undefined') {
            if(filter.name == 'all') {
                this.options.selectedGroups.splice(0, this.options.selectedGroups.length);
            }
        }
        this.options.selectedUsers.splice(0, this.options.selectedUsers.length);

        // Update the breadcrumb with the new filter.
        this.breadcrumCtl.load(filter);
    },


    /**
     * Observe when the user requests a category creation
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{mad.bus.element} request_group_creation': function (el, ev, data) {
        // @todo fixed in future canJs.
        if (!this.element) return;

        var group = new passbolt.model.Group();

        // Get the dialog
        var dialog = new mad.component.Dialog(null, {label: __('Create a new Group')})
            .start();

        // Attach the component to the dialog.
        var form = dialog.add(passbolt.form.group.Create, {
            data: group,
            callbacks : {
                submit: function (data) {
                    var instance = new passbolt.model.Group(
                        data['passbolt.model.Group']
                    )
                        .save();
                    dialog.remove();
                }
            }
        });
        form.load(group);
    },

    /**
     * Observe when the user requests a group edition
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{mad.bus.element} request_group_edition': function (el, ev, group) {
        // @todo fixed in future canJs.
        if (!this.element) return;

        // get the dialog
        var dialog = new mad.component.Dialog(null, {label: __('Edit a Group')})
            .start();

        // attach the component to the dialog
        var form = dialog.add(passbolt.form.group.Create, {
            data: group,
            callbacks : {
                submit: function (data) {
                    group.attr(data['passbolt.model.Group'])
                        .save();
                    dialog.remove();
                }
            }
        });

        form.load(group);
    },

    /**
     * Observe when the user requests a group deletion
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{mad.bus.element} request_group_deletion': function (el, ev, group) {
        // @todo fixed in future canJs.
        if (!this.element) return;

        group.destroy();
    },

    /**
     * Observe when the user requests a category creation
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{mad.bus.element} request_user_creation': function (el, ev, data) {
        // @todo fixed in future canJs.
        if (!this.element) return;

        // create the resource which will be used by the form builder to populate the fields
        var user = new passbolt.model.User({active:1});

        // get the dialog
        var dialog = new mad.component.Dialog(null, {label: __('Add User')})
            .start();

        // attach the component to the dialog
        var form = dialog.add(passbolt.form.user.Create, {
            data: user,
            action: 'create',
            callbacks : {
                submit: function (data) {
                    var user = new passbolt.model.User(data['passbolt.model.User']);
                    user.save(
                        // success
                        function() {
                            dialog.remove();
                        },
                        function(v) {
                            form.showErrors(JSON.parse(v.responseText)['body']);
                        }
                    );

                }
            }
        });
        form.load(user);
    },

    /**
     * Observe when the user requests a user edition
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {passbolt.model.User} resource The target user to edit
     * @return {void}
     */
    '{mad.bus.element} request_user_edition': function (el, ev, user) {
        // @todo fixed in future canJs.
        if (!this.element) return;

        var self = this;
        // Retrieve the selected user
        user = this.options.selectedUsers[0];

        // get the dialog
        var dialog = new mad.component.Dialog(null, {label: __('Edit User')})
            .start();

        // attach the component to the dialog
        var form = dialog.add(passbolt.form.user.Create, {
            data: user,
            action: 'edit',
            callbacks : {
                submit: function (data) {
                    user.attr(data['passbolt.model.User']).save(
                        // Success.
                        function() {
                            dialog.remove();
                        },
                        // Error.
                        function(v) {
                            form.showErrors(JSON.parse(v.responseText)['body']);
                        }
                    );
                }
            }
        });
        form.load(user);
    },

    /**
     * Observe when the user requests a user deletion
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {passbolt.model.User} user1 A target user to delete
     * @param {passbolt.model.User} [user2 ...] Other users to delete
     * @return {void}
     */
    '{mad.bus.element} request_user_deletion': function (el, ev) {
		var args = arguments;
		var confirm = new mad.component.Confirm(
			null,
			{
				label: __('Do you really want to delete user ?'),
				content: __('Please confirm you really want to delete the user. After clicking ok, it will be deleted permanently.'),
				action: function() {
					for (var i=2; i < args.length; i++) {
						var user = args[i];
						if (!(user instanceof passbolt.model.User)) {
							throw new mad.error.Exception('The parameter ' + i + ' should be an instance of passbolt.model.User');
						}
						user.destroy();
					}
				}
			}
		).start();
    },

    /**
     * Observe when a user requests to remove a user from a group.
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    '{mad.bus.element} request_remove_user_from_group': function (el, ev, selectedUsers, selectedGroups) {
        // @todo fixed in future canJs.
        if (!this.element) return;

        // Check Params.
        if(selectedGroups.attr("length") == 0) {
            return;
        }
        if(selectedUsers.attr("length") == 0) {
            return;
        }

        // Process and delete user from group.
        var groupId = selectedGroups[0]['id'];
        for (i in selectedUsers) {
            for (j in selectedUsers[i]['GroupUser']) {
                if (selectedUsers[i]['GroupUser'][j]['group_id'] == groupId) {
                    // Delete userGroup.
                    selectedUsers[i]['GroupUser'][j].destroy();
                }
            }
        }
    }

});

export default PeopleWorkspace;

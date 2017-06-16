import 'mad/component/component';
import 'mad/component/dialog';
import 'mad/component/confirm';
import 'app/component/people_workspace_menu';
import 'app/component/workspace_secondary_menu';
import 'app/component/people_breadcrumb';
import 'app/component/groups';
//import 'app/component/group_chooser'; // @roadmap
import 'app/component/user_browser';
import 'app/component/user_shortcuts';
import 'app/component/user_sidebar';
import 'app/component/group_edit';
import 'app/component/group_sidebar';
import 'app/form/user/create';
import 'app/model/user';
import 'app/model/filter';

import 'app/view/template/people_workspace.ejs!';
import 'app/view/template/component/create_button.ejs!';
import 'app/view/template/component/create_button_dropdown.ejs!';

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
		// The current selected users
        selectedUsers: new can.Model.List(),
		// The current selected groups
        selectedGroups: new can.Model.List(),
		// The current filter
        filter: null,
		// Override the silentLoading parameter.
		silentLoading: false,
		// Filter the workspace with this filter settings.
		filterSettings: null
    },

	/**
	 * Return the default filter used to filter the workspace
	 * @return {passbolt.model.Filter}
	 */
	getDefaultFilterSettings: function() {
		return new passbolt.model.Filter({
            id: 'default',
			label: __('All users'),
            order: ['Profile.last_name ASC']
		});
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
    afterStart: function() {
		var role = passbolt.model.User.getCurrent().Role.name;

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
            passbolt.component.WorkspaceSecondaryMenu, {
                selectedItems: this.options.selectedUsers
            }
        );
        secWkMenu.start();

		// Create user / group capability is only available to admin user.
		if (role == 'admin') {
            var self = this;
            var createButtonMenuItems = [
                new mad.model.Action({
                    id: uuid(),
                    label: __('New user'),
                    cssClasses: ['create-user'],
                    action: function () {
                        self.options.createButton.view.close();
                        mad.bus.trigger('request_user_creation');
                    }
                }),
                new mad.model.Action({
                    id: uuid(),
                    label: __('New group'),
                    cssClasses: ['create-group'],
                    action: function () {
                        self.options.createButton.view.close();
                        mad.bus.trigger('request_group_creation');
                    }
                })
            ];

            // Instantiate the create button component.
            this.options.createButton = mad.helper.Component.create(
            	$('.main-action-wrapper'),
            	'last',
                mad.component.ButtonDropdown, {
                    id: 'js_wsp_create_button',
                    templateBased: true,
                    templateUri: 'app/view/template/component/create_button_dropdown.ejs',
                    tag: 'a',
                    cssClasses: ['button', 'primary'],
                    items: createButtonMenuItems
                }
            ).start();

		}

        // Instantiate the password workspace breadcrumb controller
        this.breadcrumCtl = new passbolt.component.PeopleBreadcrumb($('#js_wsp_users_breadcrumb'), {});
        this.breadcrumCtl.start();

        // Instanciate the users filter controller.
        this.userShortcut = new passbolt.component.UserShortcuts('#js_wsp_users_filter_shortcuts', {});
        this.userShortcut.start();

        // Instanciate the users groups controller.
        var groups = new passbolt.component.Groups('#js_wsp_users_groups', {
            selectedGroups: this.options.selectedGroups
        });
        groups.start();

        // Instantiate the passwords browser controller.
        var userBrowserController = new passbolt.component.UserBrowser('#js_wsp_users_browser', {
            selectedUsers: this.options.selectedUsers
        });
        userBrowserController.start();

        // Instantiate the user details controller
        new passbolt.component.UserSidebar($('.js_wsp_users_sidebar_second', this.element), {
            id: 'js_user_details',
            selectedItems: this.options.selectedUsers
        });
        $('.js_wsp_users_sidebar_second', this.element).hide();

        //// Instantiate the gtroup details controller
        new passbolt.component.GroupSidebar($('.js_wsp_groups_sidebar_second', this.element), {
            id: 'js_group_details',
            selectedItems: this.options.selectedGroups
        });
        $('.js_wsp_groups_sidebar_second', this.element).hide();

        // A filter has been given in options.
        // If not given, set one by default.
		var filter = null;
		if (this.options.filterSettings == undefined) {
			filter = this.constructor.getDefaultFilterSettings();
		} else {
			filter = this.options.filterSettings;
		}

		// Filter the workspace
		mad.bus.trigger('filter_workspace', filter);

        this.on();
    },

    /**
     * Destroy the workspace.
     */
    destroy: function() {
        // Be sure that the primary & secondary workspace menus controllers will be destroyed also.
        $('#js_wsp_primary_menu_wrapper').empty();
        $('#js_wsp_secondary_menu_wrapper').empty();
		$('.main-action-wrapper').empty();

        // Destroy Selected users.
        this.options.selectedUsers.splice(0, this.options.selectedUsers.length);

        // Call parent.
        this._super();
    },

    /**
     * Open EditGroupDialog.
     * @param group
     */
    openEditGroupDialog: function(group) {
        // get the dialog
        var dialog = new mad.component.Dialog(null, {
            label: group.id == undefined || group.id == '' ? __('Create group') : __('Edit group'),
            cssClasses: ['edit-group-dialog','dialog-wrapper']
        }).start();

        // Attach the component to the dialog.
        dialog.add(passbolt.component.GroupEdit, {
            id: 'js_edit_group',
            data: {
                Group: group
            }
        });
    },

    /**
     * Delete a group.
     * @param group
     */
    deleteGroup: function(group) {
        // First do a dry run to determine whether the group can be deleted.
        group.deleteDryRun(group.id)
            .then(
                function success(response) {
                    var contentH2 = __('You are about to delete the group "%s"!', group.name);
                    var contentText = '';
                    if (response.length == 0) {
                        contentText = __('This group is not associated with any password. You are good to go!');
                    }
                    else if(response.length > 0) {
                        contentText = __('This group is associated with %s passwords. All users in this group will lose access to these passwords.', response.length);
                    }

                    var confirm = new mad.component.Confirm(
                        null,
                        {
                            label: __('Are you sure ?'),
                            subtitle: contentH2,
                            content: contentText,
                            submitButton: {
                                label: __('delete group'),
                                cssClasses: [
                                    'warning'
                                ]
                            },
                            action: function() {
                                // Destroy group.
                                group.destroy();
                            }
                        }).start();
                },
                function error(errorResponse) {
                    var response = errorResponse.responseJSON.body;
                    // Build a list of resources for displaying.
                    var listResources = '';
                    response.forEach(function(elt, index) {
                        listResources += (index > 0 ? ', ' + elt.Resource.name : elt.Resource.name);
                    });
                    // Subtitle and content.
                    var contentH2 = __('You are trying to delete the group "%s"!', group.name);
                    var contentText = __(
                        'This group is the sole owner of %s %s: %s. You need to transfer the ownership to other users before you can proceed.',
                        response.length,
                        response.length > 1 ? __('passwords') : __('password'),
                        listResources
                    );

                    // Display confirm dialog.
                    new mad.component.Confirm(
                        null,
                        {
                            label: __('You cannot delete this group!'),
                            subtitle: contentH2,
                            content: contentText,
                            submitButton: {
                                label: __('Got it!'),
                                cssClasses: []
                            },
                            action: function() {
                                mad.component.Confirm.closeLatest();
                            }
                        }).start();
                }
            );
    },

    /**
     * Request the user to confirm the user delete.
     *
     * @param {passbolt.model.User} user The user to delete.
     */
    requestUserDeleteConfirmation: function(user) {
        new mad.component.Confirm(null, {
            label: __('Do you really want to delete user ?'),
            content:  __('Please confirm you really want to delete the user. After clicking ok, the user will be <strong>deleted permanently</strong>.'),
            submitButton: {
                label: __('delete user'),
                cssClasses: ['warning']
            },
            action: function() {
                user.destroy();
            }
        }).start();
    },

    /**
     * Notify the user regarding the delete failure.
     *
     * @param {passbolt.model.User} user The user to delete.
     * @param {passbolt.model.Resource} resources The resources the user is the sole owner.
     */
    displayDeleteUserSoleOwnerErrorDialog: function(user, resources) {
        new mad.component.Confirm(null, {
            label: __('You cannot delete this user!'),
            subtitle: __('You are trying to delete the user "%s"!', user.Profile.fullName()),
            content:  __('This user is the sole owner of %s %s: %s. You need to transfer the ownership to other users before you can proceed.',
                resources.length,
                resources.length > 1 ? __('passwords') : __('password'),
                resources.reduce(function(result, resource) {
                    return result + (result != '' ? ', ' : '') + resource.Resource.name;
                }, '')
            ),
            submitButton: {
                label: __('Got it!'),
                cssClasses: []
            },
            action: function() {
                mad.component.Confirm.closeLatest();
            }
        }).start();
    },

    /**
     * Delete a user.
     *
     * Request a dry-run delete on the API.
     * - If the dry-run is a success, ask the user to confirm the deletion;
     * - If the dry-run failed, notify the user about the reasons.
     *
     * @param {passbolt.model.User} user The user to delete.
     */
    deleteUser: function(user) {
        var self = this;

        user.deleteDryRun(user.id)
            // In case of success.
            .then(function() {
                // Display the delete confirmation dialog.
                self.requestUserDeleteConfirmation(user);
            })
            // In case of error.
            .then(null, function(response) {
                // Display the error dialog.
                if (response.responseJSON.body) {
                    var resources = response.responseJSON.body;
                    self.displayDeleteUserSoleOwnerErrorDialog(user, resources);
                }
            });
    },

    /* ************************************************************** */
    /* LISTEN TO THE APP EVENTS */
    /* ************************************************************** */

    /**
     * When a new filter is applied to the workspace.
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.Filter} filter, the filter being applied.
     */
    '{mad.bus.element} filter_workspace': function (el, ev, filter) {
        // If the filter applied is "all users", then empty the list of selected groups.
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
     * Observe when the user requests a group creation
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{mad.bus.element} request_group_creation': function (el, ev, data) {
        var group = new passbolt.model.Group();
        this.openEditGroupDialog(group);
    },

    /**
     * Observe when the user requests a group edition
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{mad.bus.element} request_group_edition': function (el, ev, group) {
        this.openEditGroupDialog(group);
    },

    /**
     * Observe when the user requests a group deletion
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{mad.bus.element} request_group_deletion': function (el, ev, group) {
        this.deleteGroup(group);
    },

    /**
     * Observe when the user requests a user creation
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{mad.bus.element} request_user_creation': function (el, ev, data) {
        // create the resource which will be used by the form builder to populate the fields
        var user = new passbolt.model.User({active:1});

        // get the dialog
        var dialog = new mad.component.Dialog(null, {
            label: __('Add User'),
            cssClasses : ['create-user-dialog','dialog-wrapper']
        }).start();

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
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.User} resource The target user to edit
     */
    '{mad.bus.element} request_user_edition': function (el, ev, user) {
        var self = this;
        // Retrieve the selected user
        user = this.options.selectedUsers[0];

        // get the dialog
        var dialog = new mad.component.Dialog(null, {
            label: __('Edit User'),
            cssClasses : ['edit-user-dialog','dialog-wrapper']
        }).start();

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
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.User} user A target user to delete
     */
    '{mad.bus.element} request_user_deletion': function (el, ev, user) {
        this.deleteUser(user);
    },

    /**
     * Observe when a user requests to remove a user from a group.
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{mad.bus.element} request_remove_user_from_group': function (el, ev, selectedUsers, selectedGroups) {
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
    },

    /**
     * Reset the filters.
     *
     * Unselect the group if it was selected, and select All users instead.
     *
     * @param el
     * @param ev
     * @param data
     */
    '{mad.bus.element} reset_filters': function(el, ev) {
        var filter = passbolt.model.Filter.model({name: 'all'});
        mad.bus.trigger('filter_workspace', filter);
        this.userShortcut.selectItem(this.userShortcut.options.items[0]);
    }

});

export default PeopleWorkspace;

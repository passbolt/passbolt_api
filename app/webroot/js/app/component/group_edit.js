import 'mad/component/component';
import 'mad/form/form';
import 'app/form/group/create';
import 'app/model/group_user';
import 'app/view/component/group_edit';
import 'app/view/template/component/group_edit.ejs!';
import 'app/view/template/component/group/group_user_list_item.ejs!';

/**
 * @inherits mad.Component
 * @parent index
 *
 * @constructor
 * Creates new GroupEdit component
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.GroupEdit}
 */
var GroupEdit = passbolt.component.GroupEdit = mad.Component.extend('passbolt.component.GroupEdit', /** @static */ {

    defaults: {
        label: null,
        resource: null,
        cssClasses: ['share-tab', 'edit-group-dialog'],
        viewClass: passbolt.view.component.GroupEdit,
        templateUri: 'app/view/template/component/group_edit.ejs',
        // The list of changes.
        GroupUserChanges: [],
        // The initial state the component will be initialized on (after start).
        state: 'ready'
    }

}, /** @prototype */ {

    /**
     * After start hook.
     * @see {mad.Component}
     */
    afterStart: function() {
        this.group = new passbolt.model.Group();

        this.formGroup = new passbolt.form.group.Create($('#js_group_edit_form'), {
            data: this.group,
            callbacks : {}
        });
        this.formGroup.load(this.group);
        this.formGroup.start();

        // List defined permissions
        this.groupUserList = new mad.component.Tree($('#js_permissions_list'), {
            cssClasses: ['group_user'],
            viewClass: mad.view.component.Tree,
            itemClass: passbolt.model.GroupUser,
            templateUri: 'mad/view/template/component/tree.ejs',
            itemTemplateUri: 'app/view/template/component/group/group_user_list_item.ejs',
            // The map to use to make jstree working with our permission model
            map: new mad.Map({
                id: 'id',
                userAvatarPath: {
                    key: 'User',
                    func: function(user, map, obj) {
                        return user.Profile.avatarPath('small');
                    }
                },
                userLabel: {
                    key: 'User',
                    func: function(user, map, obj) {
                        return user.Profile.first_name + ' ' + user.Profile.last_name;
                    }
                },
                userEmail: {
                    key: 'User',
                    func: function(user, map, obj) {
                        return user.User.username;
                    }
                },
                userFingerprint: {
                    key: 'User',
                    func: function(user, map, obj) {
                        return user.Gpgkey.fingerprint;
                    }
                },
                isAdmin: {
                    key: 'is_admin',
                    func: function(is_admin, map, obj) {
                        return is_admin;
                    }
                }
            })
        });
        this.groupUserList.start();

        // Trigger plugin to load plugin.group_edit components.
        mad.bus.trigger('passbolt.plugin.group_edit', {
            //resourceId: this.options.acoInstance.id,
            //armored: this.options.acoInstance.Secret[0].data
        });

        // Add a button to control the final save action
        this.options.saveChangesButton = new mad.component.Button($('#js_group_save'), {
            // By default it is disabled, it will be enabled once the user has entered
            // a name and added a group admin.
            state: 'disabled'
        }).start();

        // By default, there are no user groups shown. We set the empty class.
        $('.group_members').addClass('empty');

        // TODO: this should be done once the plugin has loaded its components, and after the data are loaded.
        // Don't forget to set the initial state of the plugin to loading in the defaults.
        this.setState('ready');

        this.on();
    },

    loadGroupUser: function(groupUser) {
        var groupUserTypeSelector = '#js_group_user_is_admin_select_' + groupUser.id,
            groupUserSelector = '#' + groupUser.id,
            availableTypes = passbolt.model.GroupUser.membershipType;

        // Insert GroupUser in the list.
        this.groupUserList.insertItem(groupUser);

        // Add a selectbox to display the permission type (and allow to change)
        new mad.form.Dropdown($('.js_group_user_is_admin', groupUserTypeSelector), {
            id: 'js_group_user_is_admin_' + groupUser.id,
            emptyValue: false,
            modelReference: 'passbolt.model.GroupUser.is_admin',
            availableValues: availableTypes,
            // If the current user has no admin right, disable this action.
            state: 'ready'
        })
            .start()
            .setValue(groupUser.is_admin);

        // Add a button to allow the user to delete the userGroup.
        new mad.component.Button($('.js_group_user_delete', $('.actions')), {
            id: 'js_group_user_delete_' + groupUser.id,
            state: 'ready'
        }).start();
    },

    /**
     * Add a new user to the group.
     * @param data The permission data
     */
    addGroupUser: function(data) {
        // Instantiate a new temporary permission.
        data.id = uuid();
        var groupUser = new passbolt.model.GroupUser(data);
        // Map user data into a user object.
        groupUser.User = new passbolt.model.User(groupUser.User);

        // Load this temporary groupUser in the group users list component.
        this.loadGroupUser(groupUser);

        $('.group_members').removeClass('empty');

        // Check manager.
        this.checkManager();
    },

    /**
     * Delete a group user
     * @param groupUSer The groupUser to delete
     */
    deleteGroupUser: function(groupUser) {
        // Remove the permission from the list.
        this.groupUserList.removeItem(groupUser);

        // Show empty permission warning message if the list is empty.
        if (this.groupUserList.options.items.length == 0) {
            $('.group_members').addClass('empty');
        }

        // Notify the plugin, the user shouldn't be listed by the autocomplete anymore.
        mad.bus.trigger('passbolt.group.edit.remove_group_user', {
            groupUser: groupUser
        });

        this.checkManager();
    },

    /**
     * Edit a group user
     * @param groupUser The groupUser to edit
     */
    editGroupUser: function(groupUser, value) {
        groupUser.is_admin = value;
        // Notify the plugin, the user can be listed by the autocomplete again.
        mad.bus.trigger('passbolt.group.edit.edit_group_user', {
            groupUser: groupUser
        });

        this.checkManager();
    },

    checkManager: function() {
        var self = this,
            admins = [],
            hasAdmins = false;

        this.groupUserList.options.items.each(function (item) {
            var isAdmin = false;
            // Is admin ?
            if (item.is_admin == 1) {
                isAdmin = true;
            }

            if(isAdmin == true) {
                admins.push(item);
            }
        });

        // Populate hasAdmins variable.
        hasAdmins = admins.length >= 1 ? true : false;

        // Set state on select field and delete button.
        // If only one owner, make the edition of the is_admin field unavailable.
        if (admins.length == 1) {
            var permTypeDropdownComponentId = 'js_group_user_is_admin_' + admins[0].id,
                permDeleteButtonId = 'js_group_user_delete_' + admins[0].id,
                permTypeDropdown = mad.getControl(permTypeDropdownComponentId, 'mad.form.Dropdown'),
                permDeleteButton = mad.getControl(permDeleteButtonId, 'mad.component.Button');

            // Disable the permission type field and the permission delete button
            permTypeDropdown.setState('disabled');
            permDeleteButton.setState('disabled');
        }
        // If several admins, make the is_admin dropdown and delete button enabled.
        else if (admins.length > 1) {
            for (var i in admins) {
                var permTypeDropdownComponentId = 'js_group_user_is_admin_' + admins[0].id,
                    permDeleteButtonId = 'js_group_user_delete_' + admins[0].id,
                    permTypeDropdown = mad.getControl(permTypeDropdownComponentId, 'mad.form.Dropdown'),
                    permDeleteButton = mad.getControl(permDeleteButtonId, 'mad.component.Button');

                // Disable the permission type field and the permission delete button
                permTypeDropdown.setState('ready');
                permDeleteButton.setState('ready');
            }
        }

        // If at least one admin is set, the form can be saved.
        if (hasAdmins == true) {
            this.options.saveChangesButton.setState('ready');
        }
        else {
            this.options.saveChangesButton.setState('disabled');
        }

        return hasAdmins;
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * The user request the form to be saved.
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    '{saveChangesButton.element} click': function(el, ev) {
        if (this.state.is('ready')) {
            // Validate form.
            var validate = this.formGroup.validate();

            var hasAdmins = this.checkManager();
            if (validate == true && hasAdmins == true) {
                var formData = this.formGroup.getData()['passbolt.model.Group'];
                var groupJson = {name: formData['name']};

                mad.bus.trigger('passbolt_loading');
                // Inform plugin that group should be saved.
                mad.bus.trigger('passbolt.group.edit.save', {
                    group: groupJson
                });

                // Switch the component in loading state.
                // The ready state will be restored once the component will be refreshed.
                this.setState('loading');

                // Button goes in processing state.
                this.options.saveChangesButton.setState('processing');
            }
        }
    },

    /* ************************************************************** */
    /* LISTEN TO THE PLUGIN EVENTS */
    /* ************************************************************** */

    /**
     * Listen when a permission has been added through the plugin.
     */
    '{mad.bus.element} group_edit_add_user': function(el, ev, data) {
        this.addGroupUser(data);
    },

    /**
     * Listen when a group has been added through the plugin.
     */
    '{mad.bus.element} group_edit_save_success': function(el, ev, data) {
        // Force triggering event of group created.
        var group = passbolt.model.Group.model(data);
        can.trigger(passbolt.model.Group, 'created', group);

        // Complete loading bar.
        mad.bus.trigger('passbolt_loading_complete');

        mad.bus.trigger('passbolt_notify', {
            status: 'success',
            title: 'app_groups_add_success',
            data: data
        });

        // The ready state will be restored once the component will be refreshed.
        this.setState('ready');

        // Button goes in ready state.
        this.options.saveChangesButton.setState('ready');

        // Close the dialog.
        this.closest(mad.component.Dialog)
            .remove();
    },

    /**
     * Listen when a group could not be saved by the plugin.
     * @param el
     * @param ev
     * @param json errorResponse the response sent by the server in json format.
     */
    '{mad.bus.element} group_edit_save_error': function(el, ev, errorResponse) {
        if (errorResponse.header.status_code == 400) {
            // If it' an error with the group name, display validation error.
            if (errorResponse.body.Group['name'] != undefined) {
                this.formGroup.showErrors(errorResponse.body);
            }
            else {
                // If error with another field, log it in console.
                console.error('Validation error while saving group', errorResponse);
            }
        }
        else {
            // If error with something else, log it in console.
            console.error('Unknown error while saving group', errorResponse);
        }

        // Complete loading bar.
        mad.bus.trigger('passbolt_loading_complete');
        mad.bus.trigger('passbolt_notify', {
            status: 'error',
            title: 'app_groups_add_error',
            data: errorResponse
        });

        // The ready state is restored.
        this.setState('ready');

        // Button goes back in processing state.
        this.options.saveChangesButton.setState('ready');
    },


    /* ************************************************************** */
    /* LISTEN TO THE COMPONENT EVENTS */
    /* ************************************************************** */

    /**
     * The user want to remove a groupUser
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.groupUser} groupUser The groupUser to remove
     */
    ' request_group_user_delete': function (el, ev, groupUser) {
        this.deleteGroupUser(groupUser);
    },

    /**
     * The user wants to edit a groupUser
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.groupUser} groupUser The groupUser to edit
     */
    ' request_group_user_edit': function (el, ev, groupUser, value) {
        this.editGroupUser(groupUser, value);
    }

});

export default GroupEdit;
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
        cssClasses: ['share-tab'],
        viewClass: passbolt.view.component.GroupEdit,
        templateUri: 'app/view/template/component/group_edit.ejs',
        // The list of changes.
        GroupUserChanges: [],
        // The initial state the component will be initialized on (after start).
        state: 'loading',
        // Should be provided at the call.
        data: {
            Group: {}
        }
    }

}, /** @prototype */ {

    /**
     * After start hook.
     * @see {mad.Component}
     */
    afterStart: function() {
        this.options.data.Group = this.options.data.Group || {};
        this.group = this.options.data.Group;
        this.formState = (this.group.id == undefined ? 'create' : 'edit');
        this.changeList = [];
        // Is the user an admin.
        this.isAdmin = passbolt.model.User.getCurrent().Role.name == 'admin' ? true : false;
        this.isGroupManager = this.formState == 'edit' ? this.group.isGroupManager(passbolt.model.User.getCurrent()) : false;

        this.formGroup = new passbolt.form.group.Create($('#js_group_edit_form'), {
            data: this.group,
            canUpdateName: this.isGroupManager && !this.isAdmin ? false: true,
            callbacks : {}
        });
        this.formGroup.start();
        this.formGroup.load(this.group);

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
                        return user.username;
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
                },
                isNew: {
                    key: 'isNew',
                    func: function(isNew, map, obj) {
                        return isNew;
                    }
                }
            })
        });
        this.groupUserList.start();

        // Inform the plugin about the operation.
        mad.bus.trigger('passbolt.plugin.group_edit', {
            groupId: this.group.id != undefined ? this.group.id : '',
            canAddGroupUsers: this.formState == 'create' || this.isGroupManager // Will define if the plugin loads the autocomplete field or not.
        });

        // Add a button to control the final save action.
        this.options.saveChangesButton = new mad.component.Button($('#js_group_save'), {
            // By default it is disabled, it will be enabled once the user has entered
            // a name and added a group admin.
            state: 'disabled'
        }).start();

        // If group creation, there are no user groups shown. We set the empty class.
        if (this.formState == 'create') {
            $('.group_members').addClass('empty');
        }

        // The component is not marked as ready when editing a group, as the group members list
        // is retrieved through the plugin. See passbolt.plugin.group.edit.group_loaded
        if (this.formState == 'create') {
            this.options.state = 'ready';
        }

        this.showFeedback();

        this.on();
    },

    /**
     * Show a visual feedback as per the form status.
     * Feedback can be:
     * - The group is empty, please add a group manager.
     * - You need to click save for the changes to take place.
     * - Only the group manager can add new people to a group
     */
    showFeedback: function() {
        var feedback = [];
        if (this.formState == 'create' && this.groupUserList.options.items.length == 0) {
            feedback.push(__('The group is empty, please add a group manager.'));
        }
        if (this.formState == 'edit' && !this.isGroupManager) {
            feedback.push(__('Only the group manager can add new people to a group.'));
        }
        // Check if any changes is there.
        if (this.changeList.length) {
            feedback.push(__('You need to click save for the changes to take place.'));
        }

        $('.message.feedback').empty();
        if (feedback.length) {
            feedback.forEach(function(fb) {
                $('.message.feedback').append('<span>' + fb + '</span>');
            });
            $('.message.feedback').removeClass('hidden');
        }
        else {
            $('.message.feedback').addClass('hidden');
        }
    },

    /**
     * Load a groupUser in the list.
     * @param groupUser
     */
    loadGroupUser: function(groupUser) {
        var groupUserId = groupUser.id,
            groupUserTypeSelector = '#js_group_user_is_admin_select_' + groupUserId,
            groupUserSelector = '#' + groupUserId,
            availableTypes = passbolt.model.GroupUser.membershipType;

        // Insert GroupUser in the list.
        this.groupUserList.insertItem(groupUser);

        // Add a selectbox to display the permission type (and allow to change)
        new mad.form.Dropdown($('.js_group_user_is_admin', groupUserTypeSelector), {
            id: 'js_group_user_is_admin_' + groupUserId,
            emptyValue: false,
            modelReference: 'passbolt.model.GroupUser.is_admin',
            availableValues: availableTypes,
            // If the current user has no admin right, disable this action.
            state: 'ready'
        })
            .start()
            .setValue(groupUser.is_admin ? 1 : 0);

        // Add a button to allow the user to delete the userGroup.
        new mad.component.Button($('.js_group_user_delete', $('.actions', groupUserSelector)), {
            id: 'js_group_user_delete_' + groupUserId,
            state: 'ready'
        }).start();
    },

    /**
     * Add a new user to the group.
     * @param groupUser The groupUser data, either in json, or in object.
     */
    addGroupUser: function(groupUser) {
        // Instantiate a new temporary permission.
        if (groupUser.id == undefined) {
            groupUser.id = uuid();
            groupUser.isNew = true;
        }
        else {
            groupUser.isNew = false;
        }

        // Make sure to convert the data into an object.
        var groupUser = passbolt.model.GroupUser.model(groupUser);

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
            groupUser: {
                id: groupUser.attr('id'),
                user_id: groupUser.attr('user_id'),
                group_id: groupUser.attr('group_id'),
                is_admin: groupUser.attr('is_admin'),
                isNew: groupUser.attr('isNew')
            }
        });

        this.checkManager();
    },

    /**
     * Edit a group user
     * @param groupUser The groupUser to edit
     */
    editGroupUser: function(groupUser, value) {
        // Force value on groupUser.
        groupUser.attr('is_admin', (value == 1 || value == true ? 1 : 0));

        // Notify the plugin, the user can be listed by the autocomplete again.
        mad.bus.trigger('passbolt.group.edit.edit_group_user', {
            groupUser: {
                id: groupUser.attr('id'),
                user_id: groupUser.attr('user_id'),
                group_id: groupUser.attr('group_id'),
                is_admin: groupUser.attr('is_admin'),
                isNew: groupUser.attr('isNew')
            }
        });

        this.checkManager();
    },

    /**
     * Check whether there are enough group managers, and lock the neccessary is_admin fields if necessary.
     * @returns {boolean}
     */
    checkManager: function() {
        var self = this,
            admins = [],
            hasAdmins = false;

        this.groupUserList.options.items.each(function (item) {
            var isAdmin = false;
            // Is admin ?
            if (item.is_admin == 1 || item.is_admin == true) {
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
                var permTypeDropdownComponentId = 'js_group_user_is_admin_' + admins[i].id,
                    permDeleteButtonId = 'js_group_user_delete_' + admins[i].id,
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

    /**
     * Set a state to a groupUser element : "created", "updated", "unchanged"
     *
     * Will reflect the change in the UI.
     *
     * @param uuid groupUserId
     *   groupUser id
     * @param string state
     *   can be "created", "updated", or null.
     */
    setGroupUserItemState : function(groupUserId, state) {
        var $li = this.groupUserList.view.getItemElement({id:groupUserId});
        if (state == null) {
            $li.removeClass('permission-updated');
            $('.permission_changes span', $li).text(__('Unchanged'));
        }
        else {
            $li.addClass('permission-updated');
            var text = state == 'created' ? __('Will be added') : __('Will be updated');
            $('.permission_changes span', $li).text(text);
        }
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
     * Listen when a group has been loaded by the plugin.
     */
    '{mad.bus.element} passbolt.plugin.group.edit.group_loaded': function(el, ev, group) {
        group = passbolt.model.Group.model(group);
        this.formGroup.load(group);

        var self = this;

        // Load groupUsers.
        group.GroupUser.each(function(groupUser) {
            // Load groupUser.
            self.addGroupUser(groupUser);
        });

        // Mark the component as ready.
        // If the component rendering is slower than the time the plugin makes to retrieve the group.
        this.options.state = 'ready';
        this.setState('ready');
    },

    /**
     * Listen when a permission has been added through the plugin.
     */
    '{mad.bus.element} passbolt.group.edit.add_user': function(el, ev, data) {
        this.addGroupUser(data);
    },

    /**
     * Listen when a change to group users has been reported by the plugin.
     */
    '{mad.bus.element} passbolt.plugin.group.edit.group_users_updated': function(el, ev, data) {
        var self = this;
        self.changeList = data.changeList;
        setTimeout(function() {
            self.groupUserList.options.items.each(function(item) {
                var userId = item.user_id,
                    groupUserId = item.id,
                    correspondingChange = _.findWhere(data.changeList, { user_id: userId });

                if (correspondingChange != undefined) {
                    // We act only for created and updated. Deleted simply disappear from the list.
                    if (correspondingChange.status == 'created' || correspondingChange.status == 'updated') {
                        self.setGroupUserItemState(groupUserId, correspondingChange.status);
                    }
                }
                else {
                    // Reset groupUser state.
                    self.setGroupUserItemState(groupUserId, null);
                }
            });
            self.showFeedback();
        }, 0);
    },

    /**
     * Listen when a group has been added / updated through the plugin.
     */
    '{mad.bus.element} group_edit_save_success': function(el, ev, data) {
        // Force triggering event of group created.
        var group = passbolt.model.Group.model(data);
        can.trigger(passbolt.model.Group, this.formState == 'create' ? 'created' : 'updated', group);

        // Complete loading bar.
        mad.bus.trigger('passbolt_loading_complete');

        mad.bus.trigger('passbolt_notify', {
            status: 'success',
            title: 'app_groups_' + (this.formState == 'create' ? 'add' : 'edit') + '_success',
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
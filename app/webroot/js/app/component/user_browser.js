import moment from 'moment';
import 'mad/component/grid';
import 'mad/form/element/checkbox';
import 'app/model/user';
import 'app/model/group';
import 'app/model/profile';
import 'app/view/component/user_browser';

/**
 * @class passbolt.component.UserBrowserController
 * @inherits {mad.controller.component.GridController}
 * @parent index
 *
 * Our user grid controller
 *
 * @constructor
 * Creates a new User Browser Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.UserBrowser}
 */
var UserBrowser = passbolt.component.UserBrowser = mad.component.Grid.extend('passbolt.component.UserBrowser', /** @static */ {

    defaults: {
        // the type of the item rendered by the grid
        itemClass: passbolt.model.User,
        // Specific view for userBrowser. To handle specific behaviours like drag n drop.
        viewClass: passbolt.view.component.UserBrowser,
        // the list of displayed groups
        groups: [],
        // the selected resources, you can pass an existing list as parameter of the constructor to share the same list
        selectedUsers: new can.Model.List(),
        // Prefix each row id with user_
        prefixItemId: 'user_',
        // Override the silentLoading parameter.
        silentLoading: false
    }

}, /** @prototype */ {

    /**
     * The filter used to filter the browser.
     * @type {passbolt.model.Filter}
     */
    filterSettings: null,

    // Constructor like
    init: function (el, options) {

        // The map to use to make our grid working with our resource model
        options.map = new mad.Map({
            id: 'id',
            name: {
                key: 'Profile',
                func: function(profile) {
                    return profile.first_name + ' ' + profile.last_name;
                }
            },
            username: 'username',
            modified: 'modified',
            last_logged_in: 'last_logged_in',
            Group: 'Group',
            Profile: 'Profile'
        });

        // the columns model
        options.columnModel = [new mad.model.GridColumn({
            name: 'multipleSelect',
            index: 'multipleSelect',
            css: ['selections s-cell'],
            label: '<div class="input checkbox"> \
                    <input type="checkbox" name="select all" value="checkbox-select-all" id="checkbox-select-all-people" disabled="disabled"> \
                    <label for="checkbox-select-all-people">select all</label> \
                </div>',
            cellAdapter: function (cellElement, cellValue, mappedItem, item, columnModel) {
                var availableValues = [];
                availableValues[item.id] = '';
                var checkbox = mad.helper.Component.create(
                    cellElement,
                    'inside_replace',
                    mad.form.Checkbox, {
                        id: 'multiple_select_checkbox_' + item.id,
                        name: 'test',
                        cssClasses: ['js_checkbox_multiple_select'],
                        availableValues: availableValues
                    }
                );
                checkbox.start();
            }
        }), new mad.model.GridColumn({
            name: 'avatar',
            index: 'Profile',
            css: ['s-cell'],
            label: '',
            titleAdapter: function (value, mappedItem, item, columnModel) {
                return 'avatar';
            },
            valueAdapter: function (value, mappedItem, item, columnModel) {
                return '<img src="' + item.Profile.avatarPath('small') + '" alt="' + __('Picture of: ') + mappedItem.Profile.first_name + ' ' + mappedItem.Profile.last_name + '" width="30" height="30">';
            }
        }), new mad.model.GridColumn({
            name: 'name',
            index: 'Profile',
            css: ['m-cell'],
            label: __('User'),
            sortable: true
        }), new mad.model.GridColumn({
            name: 'username',
            index: 'username',
            css: ['m-cell'],
            label: __('Username'),
            sortable: true
        }), new mad.model.GridColumn({
            name: 'modified',
            index: 'modified',
            css: ['m-cell'],
            label: __('Modified'),
            sortable: true,
            valueAdapter: function (value, mappedItem, item, columnModel) {
	            return passbolt.Common.datetimeGetTimeAgo(value);
            }
        }), new mad.model.GridColumn({
            name: 'last_logged_in',
            index: 'last_logged_in',
            css: ['m-cell'],
            label: __('Last logged in'),
            sortable: true,
            valueAdapter: function (value, mappedItem, item, columnModel) {
                var last_logged_in = __('never');
                if (value != undefined) {
                    last_logged_in = passbolt.Common.datetimeGetTimeAgo(value);
                }
                return last_logged_in;
            }
        })];

        this._super(el, options);
    },

    /**
     * Show the contextual menu
     * @param {passbolt.model.User} item The item to show the contextual menu for
     * @param {string} x The x position where the menu will be rendered
     * @param {string} y The y position where the menu will be rendered
     * @param {HTMLElement} eventTarget The element the event occurred on
     */
    showContextualMenu: function (item, x, y, eventTarget) {
        // Get the offset position of the clicked item.
        var $item = $('#' + this.options.prefixItemId + item.id);
        var item_offset = $item.offset();

        // Is the user an admin.
        var isAdmin = passbolt.model.User.getCurrent().Role.name == 'admin';

        // Is the selected user same as the current user.
        var isSelf = passbolt.model.User.getCurrent().id == this.options.selectedUsers[0].id;

        // Instantiate the contextual menu menu.
        var contextualMenu = new mad.component.ContextualMenu(null, {
            state: 'hidden',
            source: eventTarget,
            coordinates: {
                x: x,
                y: item_offset.top
            }
        });
        contextualMenu.start();

        // Add Edit action.
        var action = new mad.model.Action({
            id: 'js_user_browser_menu_copy_key',
            label: 'Copy public key',
            action: function (menu) {
                var data = {
                    name: 'public key',
                    data: item.Gpgkey.key
                };
                mad.bus.trigger('passbolt.clipboard', data);
                menu.remove();
            }
        });
        contextualMenu.insertItem(action);

        // Add Edit action.
        var action = new mad.model.Action({
            id: 'js_user_browser_menu_copy_email',
            label: 'Copy email address',
            cssClasses: (isAdmin ? ['separator-after'] : []),
            action: function (menu) {
                var data = {
                    name: 'email',
                    data: item.username
                };
                mad.bus.trigger('passbolt.clipboard', data);
                menu.remove();
            }
        });
        contextualMenu.insertItem(action);

        // Actions if the user is an admin
        if (isAdmin) {
            // Add Edit action.
            var action = new mad.model.Action({
                id: 'js_user_browser_menu_edit',
                label: 'Edit',
                action: function (menu) {
                    mad.bus.trigger('request_user_edition', item);
                    menu.remove();
                }
            });
            contextualMenu.insertItem(action);

            // Delete is available only if the current user has not selected himself.
            if (!isSelf) {
                // Add Delete action.
                var action = new mad.model.Action({
                    id: 'js_user_browser_menu_delete',
                    label: 'Delete',
                    action: function (menu) {
                        mad.bus.trigger('request_user_deletion', item);
                        menu.remove();
                    }
                });
                contextualMenu.insertItem(action);
            }
        }

        // Display the menu.
        contextualMenu.setState('ready');
    },

    /**
     * Refresh an item in the grid.
     * We override this function, so we can keep the selected state after the refresh.
     * @param item
     */
    refreshItem: function (item) {
        // If the item doesn't exist
        if (!this.itemExists(item)) {
            return;
        }

        this._super(item);
        if (this.options.selectedUsers.length > 0) {
            this.select(this.options.selectedUsers[0]);
        }
    },

    /**
     * Remove an item to the grid
     * @param {mad.model.Model} item The item to remove
     */
    removeItem: function (item) {
        // remove the item to the grid
        this._super(item);
    },

    /**
     * Reset the grid
     */
    reset: function () {
        this.filtered = false;
        this.filterSettings = null;
        var sortedColumnModel = this.getColumnModel('name');
        this.view.markColumnAsSorted(sortedColumnModel, true);
        this._super();
    },

    /**
     * Before selecting an item
     * @param {mad.model.Model} item The item to select
     */
    beforeSelect: function (item) {
        var returnValue = true;

        if (this.state.is('selection')) {
            // if an item has already been selected
            // if the item is already selected, unselect it
            if (this.options.selectedUsers.length > 0 && this.options.selectedUsers[0].id == item.id) {
                this.unselect(item);
                this.setState('ready');
                returnValue = false;
            } else {
                for (var i = this.options.selectedUsers.length - 1; i > -1; i--) {
                    this.unselect(this.options.selectedUsers[i]);
                }
            }
        }

        return returnValue;
    },

    /**
     * Select an item
     * @param {mad.model.Model} item The item to select
     * @param {boolean} silent Do not propagate any event (default:false)
     */
    select: function (item) {
        // If the item doesn't exist
        if (!this.itemExists(item)) {
            return;
        }

        // Unselect the previously selected user, if not in multipleSelection.
        if (!this.state.is('multipleSelection') &&
			this.options.selectedUsers.length > 0) {
            this.unselect(this.options.selectedUsers[0]);
        }

        // Add the user to the list of selected items.
        this.options.selectedUsers.push(item);

        // Check the checkbox (if it is not already done).
        var checkbox = mad.getControl('multiple_select_checkbox_' + item.id, 'mad.form.Checkbox');
        checkbox.setValue([item.id]);

        // Make the item selected in the view.
        this.view.selectItem(item);

        // Notify the application about this selection.
        mad.bus.trigger('user_selected', item);
    },

    /**
     * Before unselecting an item
     * @param {mad.model.Model} item The item to unselect
     */
    beforeUnselect: function (item) {
        var returnValue = true;
        return returnValue;
    },

    /**
     * Unselect an item
     * @param {mad.model.Model} item The item to unselect
     * @param {boolean} silent Do not propagate any event (default:false)
     */
    unselect: function (item, silent) {
        silent = typeof silent == 'undefined' ? false : silent;

        // If the item doesn't exist
        if (!this.itemExists(item)) {
            return;
        }

        // Uncheck the associated checkbox (if it is not already done).
		var controlId = 'multiple_select_checkbox_' + item.id,
        	checkbox = mad.getControl(controlId, 'mad.form.Checkbox');

		// Uncheck the checkbox by reseting it. Brutal.
		checkbox.reset();

        // Unselect the item in grid.
        this.view.unselectItem(item);

        // Remove the resource from the previously selected resources.
        mad.model.List.remove(this.options.selectedUsers, item);

        // Notify the app about the just unselected resource.
        if (!silent) {
            mad.bus.trigger('user_unselected', item);
        }
    },

    /**
     * Filter the browser using a filter settings object
     * @param {passbolt.model.Filter} filter The filter to
     */
    filterBySettings: function(filter) {
        var self = this,
        // The deferred used for the users find all request.
            def = null;

        // If new filter or the filter changed, request the API.
        if (!this.filterSettings || this.filterSettings.id !== filter.id) {
            this.setState('loading');
            this.reset();

            // Request the API.
            var findOptions = {
                silentLoading: false,
                filter: filter.getRules(['keywords']), // All rules except keywords that is filtered on the browser.
                order: filter.getOrders()
            };
            def = passbolt.model.User.findAll(findOptions).then(function (users, response, request){
                // If the browser has been destroyed before the request completed.
                if (self.element == null) return;

                // Load the resources in the browser.
                self.load(users);
                self.setState('ready');
                if (!users.length) {
                    self.state.addState('empty');
                }

                // If the results is ordered by the server, mark the relative column.
                if (filter.order) {
                    var sortedColumnModel = self.getColumnModel(filter.order);
                    if (sortedColumnModel) {
                        self.view.markColumnAsSorted(sortedColumnModel, true);
                    }
                }
            });
        }
        this.filterSettings = filter;

        // Once the call API done, if any, filter locally the result by keywords if any.
        $.when(def).done(function() {
            // Mark the ordered column if any.
            var orders = filter.getOrders();
            if (orders && orders[0]) {
                var matches = /((\w*)\.)?(\w*)\s*(asc|desc|ASC|DESC)?/i.exec(orders[0]),
                    modelName = matches[2],
                    fieldName = matches[3],
                    sortWay = matches[4] ? matches[4].toLowerCase() : 'asc';

                if (fieldName) {
                    if (fieldName === 'last_name' || fieldName === "first_name") {
                        fieldName = 'name';
                    }

                    var sortedColumnModel = self.getColumnModel(fieldName);
                    if (sortedColumnModel) {
                        self.view.markColumnAsSorted(sortedColumnModel, sortWay === 'asc');
                    }
                }
            }

            // Filter by keywords if any filter defined.
            var keywords = filter.getRule('keywords');
            if (keywords && keywords != '') {
                self.filterByKeywords(keywords, {
                    searchInFields: ['username', 'Role.name', 'Profile.first_name', 'Profile.last_name']
                });
            }
            // Otherwise reset the local filtering.
            else if (self.isFiltered()){
                self.resetFilter();
            }
        });
    },

    /* ************************************************************** */
    /* LISTEN TO THE MODEL EVENTS */
    /* ************************************************************** */

    /**
     * Observe when a user is created.
     * @param {mad.model.Model} model The model reference
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.Resource} resource The created resource
     */
    '{passbolt.model.User} created': function (model, ev, user) {
        var self = this;
        self.insertItem(user, null, 'first');
        return false;
    },

    /**
     * Observe when a user is updated.
     * If the user is displayed by he grid, refresh it.
     * note : We listen the model directly, listening on changes on
     * a list seems too much here (one event for each updated attribute)
     * @param {mad.model.Model} model The model reference
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.User} user The updated user
     */
    '{passbolt.model.User} updated': function (model, ev, user) {
        if (this.options.items.indexOf(user) != -1) {
            this.refreshItem(user);
        }
    },

    /**
     * Observe when users are removed from the list of displayed users and
     * remove it from the grid
     * @param {mad.model.Model} model The model reference
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.User} users The removed user
     */
    '{users} remove': function (model, ev, users) {
        var self = this;
        can.each(users, function (user, i) {
            self.removeItem(user);
        });
    },

    /**
     * Observe when a user group relation is destroyed
     * @param {mad.model.Model} model The model reference
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.GroupUser} groupUser The removed associated
     */
    '{passbolt.model.GroupUser} destroyed': function (model, ev, groupUser) {
        // Remove user from the list of users in the grid.
        for (i in this.options.items) {
            if (this.options.items[i].id == groupUser.user_id) {
                break;
            }
        }
        this.options.items.splice(i, 1);

        // Remove user from the list of selected users.
        for (i in this.options.selectedUsers) {
            if (this.options.selectedUsers[i].id == groupUser.user_id) {
                this.options.selectedUsers.splice(i, 1);
            }
        }
    },

    /**
     * Listen when a group model has been updated.
     *
     * And update the list of users in the grid
     * only in case the corresponding group filter
     * was selected.
     *
     * @param el
     * @param ev
     * @param data
     */
    '{passbolt.model.Group} updated': function(el, ev, group) {
        var filterId = 'workspace_filter_group_' + group.id;
        var regExp = new RegExp('^' + filterId);
        if (this.filterSettings != null && this.filterSettings.id.match(regExp)) {
            // Reload user browser.
            this.reset();
            // Propagate the filter by group component.
            var filter = new passbolt.model.Filter({
                id: filterId,
                label: group.name + __(' (group)'),
                rules: {
                    'has-groups': group.id
                },
                order: ['Profile.last_name ASC']
            });
            mad.bus.trigger('filter_workspace', filter);
        }

    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * Observe when an item is selected in the grid.
     * This event comes from the grid view
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {mixed} item The selected item instance or its id
     * @param {HTMLEvent} ev The source event which occurred
     */
    ' item_selected': function (el, ev, item, srcEvent) {
        // switch to select state
        this.setState('selection');

        if (this.beforeSelect(item)) {
            this.select(item);
        }
    },

    /**
     * An item has been right selected
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.User} item The right selected item instance or its id
     * @param {HTMLEvent} srcEvent The source event which occurred
     */
    ' item_right_selected': function (el, ev, item, srcEvent) {
        // Select item.
        this.select(item);
        // Show contextual menu.
        this.showContextualMenu(item, srcEvent.pageX, srcEvent.pageY, srcEvent.target);
    },

    /**
     * Listen to the check event on any checkbox form element components.
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {mixed} rsId The id of the resource which has been checked
     */
    '.js_checkbox_multiple_select checked': function (el, ev, userId) {
        // if the grid is in initial state, switch it to selected
        if (this.state.is('ready')) {
            this.setState('selection');
        }

        // find the resource to select functions of its id
        var i = mad.model.List.indexOf(this.options.items, userId);
        var user = this.options.items[i];

        if (this.beforeSelect(user)) {
            this.select(user);
        }
    },

    /**
     * Listen to the uncheck event on any checkbox form element components.
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {mixed} userId The id of the user which has been unchecked
     */
    '.js_checkbox_multiple_select unchecked': function (el, ev, userId) {
        var self = this;

        // find the resource to select functions of its id
        var i = mad.model.List.indexOf(this.options.items, userId);
        var user = this.options.items[i];

        if (this.beforeUnselect()) {
            self.unselect(user);
        }

        // if there is no more selected resources, switch the grid to its initial state
        if (!this.options.selectedUsers.length) {
            this.setState('ready');

            // else if only one resource is selected
        } else if (this.options.selectedUsers.length == 1) {
            this.setState('selection');
        }
    },

    /* ************************************************************** */
    /* LISTEN TO THE APP EVENTS */
    /* ************************************************************** */

    /**
     * Listen to the browser filter
     * @param {jQuery} element The source element
     * @param {Event} event The jQuery event
     * @param {passbolt.model.Filter} filter The filter to apply
     */
    '{mad.bus.element} filter_workspace': function (element, evt, filter) {
        this.filterBySettings(filter);
    },

    /**
     * Observe when an item is unselected
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     * @param {passbolt.model.Resource|array} items The unselected items
     */
    '{selectedUsers} remove': function (el, ev, items) {
        for (var i in items) {
            this.unselect(items[i]);
        }
    },

    /* ************************************************************** */
    /* LISTEN TO THE STATE CHANGES */
    /* ************************************************************** */

    /**
     * Listen to the change relative to the state Ready.
     * The ready state is fired automatically after the Component is rendered
     * @param {boolean} go Enter or leave the state
     */
    stateReady: function (go) {
        // nothing to do
    },

    /**
     * Listen to the change relative to the state selected
     * @param {boolean} go Enter or leave the state
     */
    stateSelection: function (go) {
        // nothing to do
    },

    /**
     * Listen to the change relative to the state multipleSelected
     * @param {boolean} go Enter or leave the state
     */
    stateMultipleSelection: function (go) {
        // nothing to do
    }

});

export default UserBrowser;

import 'mad/component/tree';
import 'app/model/group';
import 'app/view/component/groups_list';
import 'app/view/template/component/group_item.ejs!';

/**
 * @class passbolt.component.GroupsList
 * @inherits mad.component.Tree
 * @parent index
 *
 * UserGroupsList component.
 * It contains a groups list.
 *
 * @constructor
 * Creates a new Groups List Controller.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.controller.UserGroupsList}
 */
var GroupsList = passbolt.component.GroupsList = mad.component.Tree.extend('passbolt.component.GroupsList', /** @static */ {

    defaults: {
        itemClass: passbolt.model.Group,
        templateUri: 'mad/view/template/component/tree.ejs',
        itemTemplateUri: 'app/view/template/component/group_item.ejs',
        prefixItemId: 'group_',
        selectedGroups: can.Model.List(),
        selectedFilter: null,
        // the view class to use. Overriden so we can put our own logic.
        viewClass: passbolt.view.component.GroupsList,
        map: new mad.Map({
            id: 'id',
            label: 'name',
            canEdit: {
                key: 'GroupUser',
                func: function(GroupUser, map, obj) {
                    var currentUser = passbolt.model.User.getCurrent();
                    return obj.isAllowedToEdit(currentUser);
                }
            }
        }),
        state: 'loading',
        silentLoading: false,
        defaultGroupFilter: {},
        // Wether we want a menu associated to the group or not.
        // If set to true, the view will render a menu icon  at the end of the line, that can be clicked.
        // on click, it will trigger a item_menu_clicked event.
        // see password_categories.js for a practical implementation sample.
        withMenu: false,
        // After load hook.
        // If not null, should be a function with a group parameter.
        afterLoad: function(groups){}
    }

}, /** @prototype */ {

    /**
     * AfterStart hook.
     */
    afterStart: function () {
        this.setViewData('withMenu', this.options.withMenu);
        this.loadGroups(this.options.defaultGroupFilter);
        this._super();
    },

    /**
     * Load groups after retrieving them from API according to a given filter.
     * @param json filter
     *   The filter required. Provide {} if no filter
     *   Example: {"has-users":"xxx-xxx-xxx-xxx-xxx"}
     */
    loadGroups: function(filter) {
        var self = this;

        // Load the groups.
        var findOptions = {
            contain: {user: 1},
            order: ['Group.name ASC'],
            filter: filter
        };

        passbolt.model.Group.findAll(findOptions)
          .then(function (groups) {
              // Load the tree component with the groups.
              self.load(groups);
              if (self.options.afterLoad != null) {
                  self.options.afterLoad(groups);
              }
              self.setState('ready');
          });
    },

    /**
     * Insert a group in the list following an alphabetical order.
     * @param {passbolt.model.Group} item The group to insert
     * @param item
     */
    insertAlphabetically : function(item) {
        var self = this,
            inserted = false;

        this.options.items.each(function(elt) {
            if (item.name.localeCompare(elt.name) == -1) {
                self.insertItem(item, elt, 'before');
                inserted = true;
                return false;
            }
        });

        if (inserted == false) {
            self.insertItem(item, null, 'last');
        }
    },

	/**
	 * Select a group.
	 * @param {passbolt.model.Group} group The group to filter the workspace with
	 */
    select: function (group) {
        this.view.selectItem(group);

        // Reset the list of selected groups and add the new selected one.
        this.options.selectedGroups.splice(0, this.options.selectedGroups.length);
        this.options.selectedGroups.push(group);

        // Propagate the filter by group component.
        this.selectedFilter = new passbolt.model.Filter({
            id: 'workspace_filter_group_' + group.id,
            label: group.name + __(' (group)'),
            rules: {
            'has-groups': group.id
            },
            order: ['Profile.last_name ASC']
        });
        mad.bus.trigger('filter_workspace', this.selectedFilter);
	},

	/* ************************************************************** */
	/* LISTEN TO THE MODEL EVENTS */
	/* ************************************************************** */

	/**
     * Listen when a group has been created and insert it in the list.
     * @param el
     * @param ev
     * @param data
     */
    '{passbolt.model.Group} created': function(el, ev, data) {
        this.insertAlphabetically(data);
    },

    /**
     * Listen when a group model has been updated.
     *
     * And update it in the list.
     *
     * @param el
     * @param ev
     * @param data
     */
    '{passbolt.model.Group} updated': function(el, ev, data) {
        this.refreshItem(data);
        if(this.options.selectedGroups.attr('length') == 0) {
            return;
        }
        if (this.options.selectedGroups[0] != null && this.options.selectedGroups[0].id == data.id) {
            this.select(data);
        }
    },

    /**
     * Listen when a group model has been destroyed.
     *
     * And update the component accordingly by removing it from the list, and unselecting all groups.
     *
     * @param el
     * @param ev
     * @param data
     */
    '{passbolt.model.Group} destroyed': function(el, ev, group) {
        this.unselectAll();
        this.removeItem(group);
        mad.bus.trigger('reset_filters');
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
        if (!filter.id.match(/^workspace_filter_group_/)) {
            this.options.selectedGroups.splice(0, this.options.selectedGroups.length);
            this.unselectAll();
        }
    },

    /**
    * Observe when an item is selected.
    *
    * @param {HTMLElement} el The element the event occurred on
    * @param {HTMLEvent} ev The event which occurred
    * @param {mixed} item The selected item instance or its id
    * @param {HTMLEvent} ev The source event which occurred
    */
    ' item_selected': function (el, ev, item, srcEvent) {
        this.select(item);
    }
});

export default GroupsList;
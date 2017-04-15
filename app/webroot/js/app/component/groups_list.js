import 'mad/component/tree';
import 'app/model/group';
import 'app/view/template/component/group_item.ejs!';

/*
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
        selfLoad:false,
        itemClass: passbolt.model.Group,
        templateUri: 'mad/view/template/component/tree.ejs',
        itemTemplateUri: 'js/app/view/template/component/group_item.ejs',
        prefixItemId: 'group_',
        selectedFilter: null
    }

}, /** @prototype */ {

    /**
     * Init callback.
     * @param el
     * @param opts
     */
    init: function (el, opts) {
        this._super(el, opts);
        var self = this;
        // Load the groups.
        passbolt.model.Group.findAll({
            order: ['Group.name ASC']
        }, function (groups, response, request) {
            // Load the tree component with the groups.
            self.load(groups);
        });
    },

    /**
     * Insert a group in the list following an alphabetical order.
     * @param {passbolt.model.Group} item The group to insert
     * @param item
     */
    insertAlphabetically : function(item) {
        var self = this;

        var inserted = false;

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
	 * Filter by group.
	 * @param {passbolt.model.Group} group The group to filter the workspace with
	 */
	filter: function (group) {
		var filter = new passbolt.model.Filter({
            id: 'workspace_filter_group_' + group.id,
			label: group.name + __(' (group)'),
			rules: {
				'has-groups': group.id
			},
            order: ['Profile.last_name ASC']
		});
        this.selectedFilter = filter;
		mad.bus.trigger('filter_workspace', filter);
	},

	/* ************************************************************** */
	/* LISTEN TO THE MODEL EVENTS */
	/* ************************************************************** */

	/**
     * Listen when a group model has been created.
     *
     * And insert it in the list.
     *
     * @param el
     * @param ev
     * @param data
     */
    '{passbolt.model.Group} created': function(el, ev, data) {
        this.insertAlphabetically(data);
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
        if (this.selectedFilter && this.selectedFilter.id != filter.id) {
            this.unselectAll();
        }
    },

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when an item is selected.
	 *
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {mixed} item The selected item instance or its id
	 * @param {HTMLEvent} ev The source event which occurred
	 */
	' item_selected': function (el, ev, item, srcEvent) {
		this.view.selectItem(item);
		this.filter(item);
	}
});

export default GroupsList;
import "mad/component/menu";

/*
 * @class passbolt.controller.UserShortcutsController
 * @inherits mad.controller.component.TreeController
 * @parent index
 *
 * Our users shortcuts component.
 * It will allow the user to filter the users browser.
 *
 * @constructor
 * Creates a new User Shortcuts Controller.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.controller.UserShortcutsController}
 */
var UserShortcuts = passbolt.component.UserShortcuts = mad.component.Menu.extend('passbolt.component.UserShortcuts', /** @static */ {

    defaults: {}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
    afterStart: function() {
        var menuItems = [
            new mad.model.Action({
                id: 'js_users_wsp_filter_all',
                label: __('All users'),
                filter: passbolt.component.PeopleWorkspace.getDefaultFilterSettings()
            }), new mad.model.Action({
                id: 'js_users_wsp_filter_recently_modified',
                label: __('Recently modified'),
                filter: new passbolt.model.Filter({
                    id: 'workspace_filter_modified',
                    label: __('Recently modified'),
                    order: ['User.modified DESC']
                })
            })
        ];
        this.load(menuItems);
        // Select first item.
        this.selectItem(menuItems[0]);
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * An item has been selected
     * @parent mad.component.Menu.view_events
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {string} item The selected item
     * @return {void}
     */
    ' item_selected': function (el, ev, item) {
        this._super(el, ev, item);

        // If this item is not disabled, try to execute the item action.
        if (!item.state.is('disabled')) {
            mad.bus.trigger('filter_workspace', item.filter);
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
        var self = this;

        this.unselectAll();

        // If the filter is relative to a filter registered in this component, select it.
        this.options.items.each(function(item) {
            if (item.filter.id == filter.id) {
                self.selectItem(item);
            }
        });
    }
});

export default UserShortcuts;

import 'mad/component/component';
import 'mad/component/menu';
import 'app/model/filter';

import 'app/view/template/component/breadcrumb/breadcrumb.ejs!';
import 'app/view/template/component/breadcrumb/breadcrumb_item.ejs!';

/**
 * @inherits {mad.Component}
 * @parent index
 *
 * @constructor
 * Creates a new People Breadcrumb Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.PeopleBreadcrumb}
 */
var PeopleBreadcrumb = passbolt.component.PeopleBreadcrumb = mad.Component.extend('passbolt.component.PeopleBreadcrumb', /** @static */ {

    defaults: {
        // Template
        templateUri: 'app/view/template/component/breadcrumb/breadcrumb.ejs',
        // Hidden by default
        status: 'hidden'
    }

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
    afterStart: function () {
        // Create and render menu in the created container.
        var menuSelector = '#' + this.getId() + ' ul';
        this.options.menu = new mad.component.Menu(menuSelector, {
            itemTemplateUri: 'app/view/template/component/breadcrumb/breadcrumb_item.ejs'
        });
        this.options.menu.start();
    },

    /**
     * Parse the current filter
     * @param {passbolt.model.Filter} filter The filter to load
     * @return {array}
     */
    parseFilter: function (filter) {
        var menuItems = [],
            keywords = filter.getRule('keywords');

        // Add default filter as root action.
        var menuItem = new mad.model.Action({
            id: uuid(),
            label: __('All users'),
            filter: passbolt.component.PeopleWorkspace.getDefaultFilterSettings()
        });
        menuItems.push(menuItem);

        // If filtered by keywords, add a breadcrumb relative to the searched keywords
        if (keywords && keywords != '') {
            var menuItem = new mad.model.Action({
                id: uuid(),
                label: __('Search : %s', keywords)
            });
            menuItems.push(menuItem);
        }
        // For any other filters than the default one, add a breadcrumb entry.
        else if (filter.id != 'default') {
            var menuItem = new mad.model.Action({
                id: uuid(),
                label: filter.label
            });
            menuItems.push(menuItem);
        }

        return menuItems;
    },

    /**
     * Load the current filter
     * @param {passbolt.model.Filter} filter The filter to load
     */
    load: function (filter) {
        var menuItems = this.parseFilter(filter);

        this.options.menu.reset();
        this.options.menu.load(menuItems);
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
        if (item.filter) {
            mad.bus.trigger('filter_workspace', item.filter);
        }
    },

    /* ************************************************************** */
    /* LISTEN TO APP EVENTS */
    /* ************************************************************** */

    /**
     * Listen to the browser filter
     * @param {jQuery} element The source element
     * @param {Event} event The jQuery event
     * @param {passbolt.model.Filter} filter The filter to apply
     */
    '{mad.bus.element} filter_workspace': function (element, evt, filter) {
        this.options.menu.reset();
        var menuItems = this.parseFilter(filter);
        this.options.menu.load(menuItems);
    }

});

export default PeopleBreadcrumb;

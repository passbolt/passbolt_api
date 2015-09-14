import 'mad/component/component';
import 'mad/component/menu';
import 'app/model/filter';
import 'app/model/category';

import 'app/view/template/component/breadcrumb/breadcrumb.ejs!';
import 'app/view/template/component/breadcrumb/breadcrumb_item.ejs!';

/**
 * @inherits {mad.component.Component}
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
var PeopleBreadcrumb = passbolt.component.PeopleBreadcrumb = mad.component.Component.extend('passbolt.component.PeopleBreadcrumb', /** @static */ {

    defaults: {
        categories: passbolt.model.Category.List,
        // Template
        templateUri: 'app/view/template/component/breadcrumb/breadcrumb.ejs',
        // Hidden by default
        status: 'hidden',
        // The filter to display
        filter: null
    }

}, /** @prototype */ {

    /**
     * Called right after the start function
     *
     * @see {mad.controller.ComponentController}
     */
    afterStart: function () {
        // Create and render menu in the created container.
        var menuSelector = '#' + this.getId() + ' ul';
        this.options.menu = new mad.component.Menu(menuSelector, {
            itemTemplateUri: 'app/view/template/component/breadcrumb/breadcrumbItem.ejs'
        });
        this.options.menu.start();
    },

    /**
     * Parse the current filter
     * @param {passbolt.model.Filter} filter The filter to load
     * @return {array}
     */
    parseFilter: function (filter) {
        var menuItems = [];

        // Add a link to filter on all items as first item.
        var menuItem = new mad.model.Action({
            id: uuid(),
            label: __('All users'),
            action: function () {
                var filter = new passbolt.model.Filter({
                    label: __('All users'),
                    type: passbolt.model.Filter.SHORTCUT
                });
                mad.bus.trigger('filter_users_browser', filter);
            }
        });
        menuItems.push(menuItem);

        // If we want to filter on a Category.
        if (typeof filter.foreignModels.Group != 'undefined') {
            // The breadcrumb can react for a unique Category.
            if (filter.foreignModels.Group.length == 1) {
                var group = filter.foreignModels.Group[0];

                // Add the current category to the breadcrumb.
                var menuItem = new mad.model.Action({
                    id: uuid(),
                    label: group.name,
                    action: function () {
                        mad.bus.trigger('group_selected', category);
                    }
                });
                menuItems.push(menuItem);
            }
        }
        // If we want to filter on keywords.
        else if (typeof filter.keywords != 'undefined' && filter.keywords != '') {
            // Add the current category to the breadcrumb.
            var menuItem = new mad.model.Action({
                id: uuid(),
                label: __('Search : %s', filter.keywords)
            });
            menuItems.push(menuItem);
        }
        // Case filter
        else {
            if (typeof filter.label != 'undefined'
                && filter.label != __('All users')) {
                var menuItem = new mad.model.Action({
                    id: uuid(),
                    label: filter.label
                });
                menuItems.push(menuItem);
            }
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
    }

});

export default PeopleBreadcrumb;

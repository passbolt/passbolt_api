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
                case: 'all_items',
                label: __('All users'),
                action: function () {
                    var filter = new passbolt.model.Filter({
                        label: __('All users'),
                        case: 'all_items',
                        type: passbolt.model.Filter.SHORTCUT
                    });
                    mad.bus.trigger('filter_workspace', filter);
                }
            }), new mad.model.Action({
                id: 'js_users_wsp_filter_recently_modified',
                case : 'recently_modified',
                label: __('Recently modified'),
                action: function () {
                    var filter = new passbolt.model.Filter({
                        label: __('Recently modified'),
                        case : 'recently_modified',
                        order: 'modified',
                        type: passbolt.model.Filter.SHORTCUT
                    });
                    mad.bus.trigger('filter_workspace', filter);
                }
            })
        ];
        this.load(menuItems);
        // Select first item.
        this.selectItem(menuItems[0]);
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

        if (filter.type != passbolt.model.Filter.SHORTCUT) {
            this.unselectAll();
        } else {
            this.options.items.each(function(item, i) {
                if (item.case == filter.case) {
                    self.selectItem(item);
                    return;
                }
            });
        }
    }
});

export default UserShortcuts;

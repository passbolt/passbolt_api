

/**
 * @inherits jQuery.View
 *
 * @constructor
 *
 * @return {mad.view.component.Tab}
 */
var Tab = mad.view.component.Tab = mad.View.extend('mad.view.component.Tab', /** @static */ { }, /** @prototype */ {

    /**
     * Select a tab
     * @param {string} tabId The target tab id
     * @return {void}
     */
    selectTab: function(tabId) {
        // add the selected class to the tab
        this.getController().getComponent(tabId)
            .view
            .addClass('selected');
        // add the selected class to the menu entry
        $('#js_tab_nav_' + tabId, this.element)
            .find('a')
            .addClass('selected');
    },

    /**
     * Unselect a tab
     * @param {string} tabId The target tab id
     * @return {void}
     */
    unselectTab: function(tabId) {
        // remove the selected class to the tab
        this.getController().getComponent(tabId)
            .view
            .removeClass('selected');
        // remove the selected class to the menu entry
        $('#js_tab_nav_' + tabId, this.element)
            .find('a')
            .removeClass('selected');
    }

});

export default Tab;
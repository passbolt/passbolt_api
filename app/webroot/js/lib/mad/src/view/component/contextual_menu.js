import 'mad/view/component/dropdown_menu';

/**
 * @inherits mad.view.component.DropdownMenu
 *
 * Our representation of the contexual menu
 *
 * @constructor
 * Instantiate a new Contextual Menu view
 * @return {mad.view.component.ContextualMenu}
 */
var ContextualMenu = mad.view.component.ContextualMenu = mad.view.component.DropdownMenu.extend('mad.view.component.ContextualMenu', /* @static */ {}, /** @prototype */ {

    /**
     * Intercept global mousedown event.
     *
     * Intercept global mousedown event and close menu if open.
     *
     * @param el
     * @param ev
     */
    '{document} mousedown': function (el, ev) {
        if (!this.element.is(el) && !$(this.getController().options.source).is(ev.target)) {
            this.element.remove();
        }
    },

    /**
     * Intercept contextmenu event.
     *
     * A bit of history : this event was introduced here because we were having issues on some systems (like windows)
     * after triggering the contextual menu on mousedown, the contextual menu was appearing and was covering the source element,
     * resulting in contextual menu component catching the contextmenu event.
     * We don't want that to happen, so we simply preventDefault here.
     *
     * @param el
     * @param ev
     */
    ' contextmenu': function(el, ev) {
        ev.preventDefault();
    }
});

export default ContextualMenu;

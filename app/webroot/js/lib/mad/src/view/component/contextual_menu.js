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
     * Intercept global click event.
     *
     * Intercept global click event and close menu if open.
     *
     * @param el
     * @param ev
     */
    '{document} click': function (el, ev) {
        if (!this.element.is(el) && !$(this.getController().options.source).is(ev.target)) {
            this.element.remove();
        }
    }

});

export default ContextualMenu;

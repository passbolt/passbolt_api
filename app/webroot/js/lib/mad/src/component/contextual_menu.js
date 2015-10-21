import 'mad/component/dropdown_menu';
import 'mad/view/component/contextual_menu';

/**
 * @parent Mad.components_api
 * @inherits mad.Component
 *
 * The ContextualMenu is an implementation of a simple contextual menu that can
 * be displayed anywhere on the screen.
 * Usually it is displayed on the right click event at the mouse position.
 *
 * ## Example
 * @demo demo.html#contextual_menu/contextual_menu
 *
 * @constructor
 * Create a new ContextualMenu Component.
 * @signature `new mad.Component.ContextualMenu( element, options )`
 * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
 * @param {Object} [options] option values for the component.  These get added to
 * this.options and merged with defaults static variable
 *   * source : element which requests the contextual menu.
 *   * coordinates [ x, y ] : coordinates where you want to display the contextual menu.
 * @return {mad.component.ContextualMenu}
 */
var ContextualMenu = mad.component.ContextualMenu = mad.component.DropdownMenu.extend('mad.component.ContextualMenu', {

    'defaults': {
        'viewClass': mad.view.component.ContextualMenu,
        'cssClasses': ['contextual-menu'],
        // The element which requests the contextual menu.
        'source': null,
        // The coordinates you want to display the contextual menu.
        'coordinates' : {
            'x': null,
            'y': null
        }
    }

}, /** @prototype */ {

    /**
     * Constructor.
     *
     * @signature `new mad.Component.ContextualMenu( element, options )`
     * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
     * @param {Object} [options] option values for the component.  These get added to
     * this.options and merged with defaults static variable
     *   * source : element which requests the contextual menu.
     *   * coordinates [ x, y ] : coordinates where you want to display the contextual menu.
     * @return {mad.component.ContextualMenu}
     */
    init: function(el, options) {
        // If no element given, create a temporary one.
        if(el == null || !el.length) {
            // Remove any other contextual menu.
            if($('#js_contextual_menu').length != 0) {
                $('#js_contextual_menu').remove();
            }

            // Create the DOM entry point for the contextual menu.
            var $el = mad.helper.Html.create(
                mad.config.rootElement,
                'first',
                '<ul id="js_contextual_menu" />'
            );

            // Changing the element force us to recall setup which is called before all init functions
            // and make the magic things happen (bind events ...)
            this.setup($el);
        }

        this._super($el, options);
    },

    /**
     * After start hook.
     * Position the contextual menu functions of the given position
     */
    afterStart: function () {
        this._super();
        this.view.position({
            'coordinates': this.options.coordinates
        });
    },

    /**
     * Destructor.
     * Remove the contextual menu from the dom.
     */
    destroy: function() {
        this._super();
        $('#js_contextual_menu', $(mad.config.rootElement)).remove();
    }
});

export default ContextualMenu;
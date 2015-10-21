import 'mad/component/button';
import 'mad/view/template/component/button/button.ejs!';

/**
 * @parent Mad.components_api
 * @inherits mad.component.Button
 * @group mad.component.ToggleButton.view_events 0 View Events
 * @group mad.component.ToggleButton.state_changes 1 State Changes
 *
 * The Toggle Button class Controller is our implementation of the UI component toggle button.
 *
 * ## Example
 * @demo demo.html#toggle_button/toggle_button
 *
 * @constructor
 * Create a new Button Component.
 * @signature `new mad.Component.ToggleButton( element, options )`
 * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
 * @param {Object} [options] option values for the component.  These get added to
 * this.options and merged with defaults static variable
 * @return {mad.component.ToggleButton}
 */
var ToggleButton = mad.component.ToggleButton = mad.component.Button.extend('mad.component.ToggleButton', /** @static */ {

    'defaults': {
    }

}, /** @prototype */ {

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * Listen to the event click on the DOM toggle button element
     * @function mad.component.ToggleButton.click
     * @parent mad.component.ToggleButton.view_events
     * @return {void}
     */
    'click': function (el, ev) {
        this._super(el, ev);
        if(!this.state.is('selected')) {
            this.setState('selected');
        } else {
            this.setState('ready');
        }
    },

    /* ************************************************************** */
    /* LISTEN TO THE STATE CHANGES */
    /* ************************************************************** */

    /**
     * Listen to the change relative to the state Disabled
     * @function mad.component.ToggleButton.stateSelected
     * @parent mad.component.ToggleButton.state_changes
     * @param {boolean} go Enter or leave the state
     * @return {void}
     */
    'stateSelected': function (go) {
        if (go) {
            this.element.addClass('selected');
        } else {
            this.element.removeClass('selected');
        }
    }
});

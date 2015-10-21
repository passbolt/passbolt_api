import 'mad/component/component';
import 'mad/component/free_composite';
import 'mad/view/component/dialog';
import 'mad/view/template/component/dialog/dialog.ejs!';

/**
 * @parent Mad.components_api
 * @inherits {mad.component.FreeComposite}
 *
 * A dialog box implementation.
 *
 * ## Example
 * @demo demo.html#dialog/dialog
 *
 * @constructor
 * Instantiate a new Dialog Component.
 * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
 * @param {Object} [options] option values for the component.  These get added to
 * this.options and merged with defaults static variable
 * @return {mad.component.Dialog}
 */
var Dialog = mad.component.Dialog = mad.component.FreeComposite.extend('mad.component.Dialog', /** @static */ {

    defaults: {
        label: 'Dialog Controller',
        viewClass: mad.view.component.Dialog,
        templateUri: 'mad/view/template/component/dialog/dialog.ejs',
        cssClasses: ['dialog-wrapper'],
        tag: 'div'
    },

    /**
     * Close the latest dialog.
     */
    closeLatest: function() {
        $('.dialog-wrapper:last').remove();
    }

}, /** @prototype */ {

    /**
     * Constructor.
     * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
     * @param {Object} [options] option values for the component.  These get added to
     * this.options and merged with defaults static variable
     * @return {mad.component.Dialog}
     */
    init: function(el, options) {
        // Create the DOM entry point for the dialog
        var refElt =  mad.config.rootElement,
            position = 'first';

        // If a dialog already exist, position the new one right after.
        var $existingDialog = $('.dialog-wrapper:last');
        if ($existingDialog.length) {
            refElt = $existingDialog;
            position = "after";
        }

        // Insert the element in the page DOM.
        var $el = mad.helper.Html.create(refElt, position, '<div />');

        // Changing the element force us to recall the setup which is called before all init functions
        // and make the magic things (bind event ...)
        this.setup($el, options);
        this._super($el, options);
    },

    /**
     * Add a component to the dialog container
     * @param {mad.controller.ComponentController} Class The class of the component to add, or the html to
     *   display.
     * @param {Object} options Option of the component
     */
    add: function(Class, options) {
        if (typeof options == 'undefined' || options == null) {
            options = {};
        }

        var component = this.addComponent(Class, options, 'js_dialog_content');
        component.start();

        return component;
    },

    /**
     * Set the title
     * @param {string} title The new title
     */
    setTitle: function(title) {
        this.view.setTitle(title);
    }
});

export default Dialog;

import 'mad/view/form/element';

/**
 * @inherits mad.view.form.Element
 */
var Dropdown = mad.view.form.Dropdown = mad.view.form.Element.extend('mad.view.form.Dropdown', /* @static */ {}, /** @prototype */ {

    /**
     * Get the value of the dropdown form element
     * @return {mixed} The value of the component
     */
    getValue: function (value) {
        return this.element.val();
    },

    /**
     * Set the value of the dropdown form element
     * @param {mixed} value The value to set
     */
    setValue: function (value) {
        this.element.val(value);
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * Listen to the view event change
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     */
    change: function (el, event) {
        el.trigger('changed', {
            value: this.getValue()
        });
    }
});

export default Dropdown;

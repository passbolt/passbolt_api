import 'mad/view/form/element';

/**
 * @inherits mad.view.form.Element
 */
var Textbox = mad.view.form.Textbox = mad.view.form.Element.extend('mad.view.form.Textbox', /* @static */ {

}, /** @prototype */ {

    /**
     * Store here the last reference of a setTimeout call.
     * see: changed event handler.
     */
    _changeTimeout: null,

    /**
     * Get the value from the associated HTML Element.
     *
     * @return {mixed} The value of the component
     */
    getValue: function () {
        return this.element.val();
    },

    /**
     * Set the value of the associated HTML Element.
     *
     * @param {mixed} value The value to set
     */
    setValue: function (value) {
        this.element.val(value);
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * The value of the HTML Element changed.
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     */
    ' input': function (el, ev) {
        var self = this;

        // Extract the value from the HTML Element.
        var newValue = this.getValue();

        // Is there a limit of characters before firing the changed event ?
        if (newValue.length >= this.getController().options.onChangeAfterLength) {

            // If a firing of the changed event has already been planed.
            // Remove it, and plan a new firing.
            if (this._changeTimeout != null) {
                clearTimeout(this._changeTimeout);
            }

            // Plan a new firing of the changed event.
            this._changeTimeout = setTimeout(function () {
                self.element.trigger('changed', {
                    value: self.getValue()
                });
            }, this.getController().options.onChangeTimeout);
        }
    }

});

export default Textbox;

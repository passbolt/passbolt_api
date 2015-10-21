import 'mad/view/view';

// Initialize the view form namespaces.
mad.view.form = mad.view.form || {};

/**
 * @inherits mad.View
 */
var Element = mad.view.form.Element = mad.View.extend('mad.view.form.Element', /** @static */ {}, /** @prototype */ {

    /**
     * Get the name of the form element
     * @return {string}
     */
    getName: function () {
        return this.element.attr('name');
    },

    /**
     * Set the value of the form element
     * @param {mixed} value The value to set
     */
    setValue: function (value) {
        steal.dev.warn('The setValue function has not been implemented for the class ' + this.getClass().fullName);
    },

    /**
     * Reset the form element view
     */
    reset: function () {
        steal.dev.warn('The reset function has not been implemented for the class ' + this.getClass().fullName);
    }

});

export default Element;

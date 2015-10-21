import 'mad/component/component';
import 'mad/view/form/element';

// Initialize the form namespaces.
mad.form = mad.form || {};

/**
 * @parent Mad.form_api
 * @inherits mad.Component
 * @group mad.form.Element.view_events 0 View Events
 * @group mad.form.Element.states_changes 0 State changes
 *
 * The Form Element Component as for aim to manage form element.
 * @todo TBD
 */
var Element = mad.form.Element = mad.Component.extend('mad.form.Element', /* @static */ {

    defaults: {
        // Override the label option.
        label: 'Form Element Component',
        // Override the cssClasses option.
        cssClasses: ['form-element'],
        // Override the tag option.
        tag: null,
        // Override the templateUri option.
        templateUri: null,
        // Override the templateBased option.
        templateBased: false,
        // Override the viewClass option.
        viewClass: mad.view.form.Element,

        // The model the form element represents.
        modelReference: null,
        // The form element requires a validation.
        validate: true,
        // The form element should be validated with this function.
        // Priority max.
        validateFunction: null,
        // The form element has a default value.
        defaultValue: null,
        // The current form element.
        value: null,
        // The callbacks the component offers to the dev to bind their code.
        callbacks: {
            // The value of the form element changed.
            changed: function (el, ev, value) {}
        }
    }

}, /** @prototype */ {

    // Constructor like.
    init: function(el, options) {
        this.defaultValue = options.value;
        this.value = options.value;
        this._super(el, options);
    },

    /**
     * Get the associated model reference.
     *
     * @return {string}
     */
    getModelReference: function () {
        return this.options.modelReference;
    },

    /**
     * Get the associated validate function
     *
     * @return {func}
     */
    getValidateFunction: function () {
        return this.options.validateFunction;
    },

    /**
     * Check if the element has to be validated.
     *
     * @return {bool}
     */
    requireValidation: function () {
        return this.options.validate;
    },

    /**
     * Set the associated model.attribute
     *
     * @param {string} modelReference
     */
    setModelReference: function (modelReference) {
        this.options.modelReference = modelReference;
    },

    /**
     * Get the value of the form element
     *
     * @return {mixed} value The value of the form element
     */
    getValue: function () {
        return this.value;
    },

    /**
     * Switch the component to its initial state
     */
    reset: function () {
        this.setState('reset');
        this.setValue(this.options.value);
        this.setState('ready');
    },

    /**
     * Set the value of the form element
     *
     * @param {mixed} value The value to set
     *
     * @return {mad.form.FormElement}
     */
    setValue: function (value) {
        this.value = value;
        this.view.setValue(this.value);
        return this;
    },

    /**
     * Implements afterStart hook().
     */
    afterStart: function() {
        // set the value after the component
        this.setValue(this.options.value);
    },

    /* ************************************************************** */
    /* LISTEN TO THE STATE CHANGES */
    /* ************************************************************** */

    /**
     * @function mad.form.Element.stateReset
     * @parent mad.form.Element.states_changes
     *
     * Listen to the change relative to the state Reset
     * @param {boolean} go Enter or leave the state
     */
    stateReset: function (go) {
        this.setState('ready');
    },

    /**
     * @function mad.form.Element.stateReady
     * @parent mad.form.Element.states_changes
     *
     * Listen to the change relative to the state Ready
     * @param {boolean} go Enter or leave the state
     */
    stateReady: function (go) {
        // override the function to catch the state switch to ready
    },

    /**
     * @function mad.form.Element.stateError
     * @parent mad.form.Element.states_changes
     *
     * Listen to the change relative to the state Error
     * @param {boolean} go Enter or leave the state
     */
    stateError: function (go) {
        // override the function to catch the state switch to error
    },

    /**
     * @function mad.form.Element.stateDisabled
     * @parent mad.form.Element.states_changes
     *
     * Listen to the change relative to the state Disabled
     * @param {boolean} go Enter or leave the state
     */
    stateDisabled: function (go) {
        if (go) {
            this.element.attr('disabled', 'disabled')
                .addClass('disabled');
        } else {
            this.element.removeAttr('disabled')
                .removeClass('disabled');
        }
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * @function mad.form.Element.__changed
     * @parent mad.form.Element.view_events
     * 
     * Listen to the view event changed
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     * @param {mixed} data The new data
     */
    ' changed': function (el, ev, data) {
        this.value = data.value;
        if (this.options.callbacks.changed) {
            this.options.callbacks.changed(this.value);
        }
    }

});

export default Element;

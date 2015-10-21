import 'mad/component/component';

// Initialize the form namespaces.
mad.form = mad.form || {};

/**
 * @parent Mad.form_api
 * @inherits mad.Component
 * @group mad.form.Feedback.states_changes 0 State changes
 *
 * The Form Feedback component as for aim to manage forms element validation feedback.
 * @todo TBD
 */
var Feedback = mad.form.Feedback = mad.Component.extend('mad.form.Feedback', /* @static */ {

    defaults: {
        // Override the label option.
        label: 'Form Feedback Component',
        // Override the templateBased option.
        templateBased: false,

        // The message the feedback should display.
        message: null
    }

}, /** @prototype */ {

    /**
     * Set the feedback component controller message
     *
     * @param {string} message The message to display
     *
     * @return {mad.form.Feedback}
     */
    setMessage: function (message) {
        this.message = message;
        return this;
    },

    /* ************************************************************** */
    /* LISTEN TO THE STATE CHANGES */
    /* ************************************************************** */

    /**
     * @function mad.form.Feedback.stateSuccess
     * @parent mad.form.Feedback.states_changes
     *
     * Listen to the change relative to the state Success
     *
     * @param {boolean} go Enter or leave the state
     * @todo clean what should be moved in view
     */
    stateSuccess: function (go) {
        if (go) {
            this.element.html(this.message);
            this.element.removeClass('error');
        }
    },

    /**
     * @function mad.form.Feedback.stateError
     * @parent mad.form.Feedback.states_changes
     *
     * Listen to the change relative to the state Error
     *
     * @param {boolean} go Enter or leave the state
     * @todo clean what should be moved in view
     */
    stateError: function (go) {
        if (go) {
            this.element.html(this.message);
            this.element.addClass('error');
        }
    }

});

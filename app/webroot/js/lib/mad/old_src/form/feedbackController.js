steal(
	'mad/form/formElement.js'
//	'mad/view/template/component/feedback.ejs'
).then(function () {

	/*
	 * @class mad.form.FeedbackController
	 * @inherits mad.form.FormElement
	 * @parent mad.form
	 * 
	 * The Feedback Component Controller
	 * 
	 * @constructor
	 * Creates a new Feedback Component Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.FeedbackController}
	 */
	mad.controller.ComponentController.extend('mad.form.FeedbackController', /** @static */ {

		'defaults': {
			'label': 'Feedback Component Controller',
			'message': null,
			'templateBased': false
		}

	}, /** @prototype */ {

		/**
		 * Set the feedback component controller message
		 * @param {string} message The message to display
		 * @return {mad.form.element.FeedbackController}
		 */
		'setMessage': function (message) {
			this.message = message;
			return this;
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Success
		 * @param {boolean} go Enter or leave the state
		 * @todo clean what should be moved in view
		 * @return {void}
		 */
		'stateSuccess': function (go) {
			if (go) {
				this.element.html(this.message);
				this.element.removeClass('error');
			}
		},

		/**
		 * Listen to the change relative to the state Error
		 * @param {boolean} go Enter or leave the state
		 * @todo clean what should be moved in view
		 * @return {void}
		 */
		'stateError': function (go) {
			if (go) {
				this.element.html(this.message);
				this.element.addClass('error');
			}
		}

	});

});
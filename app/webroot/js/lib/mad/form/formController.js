/*
 * @page mad.form Form
 * @tag mad.form
 * @parent index
 * 
 * 
 * <p>
 *	<h2>Example</h2>
 *	@demo /js/mad/demo/form/formcontroller.html
 * </p>
 *	
 */

steal(
	MAD_ROOT + '/controller',
	MAD_ROOT + '/form/formElement.js'
).then(function ($) {

	/*
	 * @class mad.form.FormController
	 * @inherits mad.controller.Controller
	 * @parent mad.form
	 * 
	 * The mad form controller
	 * 
	 * @constructor
	 * Creates a new Form Controller
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.controller.FormController}
	 */
	mad.controller.Controller.extend('mad.form.FormController', /** @static */ {

		'defaults': {
			'templateBased': false,
			'callbacks': {
				'error': function (data) {},
				'success': function () {}
			}
		}

	}, /** @prototype */ {

		/**
		 * The form elements
		 * @type mad.form.element.FormElement[]
		 * @private
		 */
		'elements': {},

		/**
		 * The feedbacks elements associated to the form elements
		 * @type mad.form.element.FeedbackInterface[]
		 * @private
		 */
		'feedbackElements': {},

		/**
		 * The form data
		 * @type mad.model.Model[]
		 * @private
		 */
		'data': {},

		// constructor of the Class
		'init': function (el, options) {
			this._super(el, options);
		},

		/**
		 * Add an element to the form
		 * @param {mad.form.FormElement} element The element to add to the form
		 * @param {mad.form.element.FeddbackController} feedback The form feedback element to associate to the form element
		 * @return {void}
		 */
		'addElement': function (element, feedback) {
			if (!element instanceof mad.form.FormElement) {
				throw new mad.error.WrongParameters('The function addElement is expecting a mad.form.FormElement object as first parameter, ' + (typeof mad.form.FormElement) + ' given');
			}
			var elementName = element.getName();
			this.elements[elementName] = element;
			if (typeof feedback != 'undefined') {
				this.feedbackElements[elementName] = feedback;
			}
		},

		/**
		 * Remove an element from the form
		 * @param {mad.form.FormElement} element The element to remove from the form
		 * @return {void}
		 */
		'removeElement': function (element) {
			if (!element instanceof mad.form.FormElement) {
				throw new mad.error.WrongParameters('The function removeElement is expecting a mad.form.FormElement object as first parameter, ' + (typeof mad.form.FormElement) + ' given');
			}
			var elementName = element.getName();
			delete this.elements[elementName];
			delete this.feedbackElements[elementName];
		},

		/**
		 * Extract the form data
		 * @return {array}
		 */
		'extractData': function () {
			var returnValue = {},
				formData = {};

			// Get the form elements value
			for (var elementId in this.elements) {
				var element = this.elements[elementId];
				var elementName = element.getName();
				// get the element model name
				var split = elementName.split('.');
				var elementModelName = split.slice(0, split.length - 1).join('.');
				if(typeof returnValue[elementModelName] == 'undefined') {
					returnValue[elementModelName] = [];
				}
				// get the element attribute name
				var elementAttributeName = split[split.length - 1];
				returnValue[elementModelName][elementAttributeName] = element.getValue();
			}
			
			return returnValue;
		},

		/**
		 * Validate the form
		 * @return {boolean}
		 */
		'validate': function () {
			var returnValue = true;

			// validate the data
			for(var modelName in this.data) {
				// get the model functions of the model name
				var model = $.String.getObject(modelName);
				for(var attributeName in this.data[modelName]) {
					// validate the attribute value
					var isValidAttribute = model.validateAttribute(attributeName, this.data[modelName][attributeName], this.data[modelName]);
					if(isValidAttribute !== true) {
						var elementName = modelName + '.' + attributeName;
						this.elements[elementName]
							.setState('error');
						this.feedbackElements[elementName]
							.setValue(isValidAttribute)
							.setState('error');
						returnValue = false;
					}
				}
			}

			return returnValue;
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		'submit': function (el, ev) {
			ev.preventDefault();
			this.data = this.extractData();
			if (this.validate()) {
				// convert form data in model data
				var modelData = {};
				for (var modelName in this.data) {
					var ModelClass = $.String.getObject(modelName);
					modelData[modelName] = new ModelClass(this.data[modelName]);
				}
				this.options.callbacks.submit(modelData);
			} else {
				this.options.callbacks.error();
			}
		}

	});
});
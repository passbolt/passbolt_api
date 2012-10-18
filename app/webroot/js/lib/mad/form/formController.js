steal(
	MAD_ROOT + '/controller',
	MAD_ROOT + '/form/formElement.js'
).then(function ($) {

	/*
	 * @class mad.form.FormController
	 * @inherits mad.controller.ComponentController
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
	mad.controller.ComponentController.extend('mad.form.FormController', /** @static */ {

		'defaults': {
//			'templateBased': false,
			'callbacks': {
				'error': function () { },
				'submit': function (data) { }
			},
			'tag': 'form',
			'validateOnChange': true
		},

		'listensTo': [
			'changed'
		]

	}, /** @prototype */ {

		/**
		 * The form elements
		 * @type mad.form.element.FormElement[]
		 */
		'elements': {},

		/**
		 * The feedbacks elements associated to the form elements
		 * @type mad.form.element.FeedbackInterface[]
		 */
		'feedbackElements': {},

		/**
		 * The form data
		 * @type mad.model.Model[]
		 */
		'data': {},

		/**
		 * Add an element to the form and optionaly its associated feedback controller.
		 * See the validate function to know more about the feedback controller.
		 * @param {mad.form.FormElement} element The element to add to the form
		 * @param {mad.form.element.FeddbackController} feedback The form feedback element to associate to the form element
		 * @exception mad.error.WrongParametersException
		 * @return {void}
		 */
		'addElement': function (element, feedback) {
			if (!element instanceof mad.form.FormElement) {
				throw new mad.error.WrongParametersException('The function addElement is expecting a mad.form.FormElement object as first parameter, ' + (typeof mad.form.FormElement) + ' given');
			}
			var elementName = element.getModelReference();
			this.elements[elementName] = element;
			if (typeof feedback != 'undefined') {
				this.feedbackElements[elementName] = feedback;
			}
		},

		/**
		 * Remove an element from the form
		 * @param {mad.form.FormElement} element The element to remove from the form
		 * @exception mad.error.WrongParametersException
		 * @return {void}
		 */
		'removeElement': function (element) {
			if (!element instanceof mad.form.FormElement) {
				throw new mad.error.WrongParametersException('The function removeElement is expecting a mad.form.FormElement object as first parameter, ' + (typeof mad.form.FormElement) + ' given');
			}
			var elementName = element.getModelReference();
			delete this.elements[elementName];
			delete this.feedbackElements[elementName];
		},

		/**
		 * Extract the form data. The data will be formated functions of the form element
		 * name.
		 * <br/>
		 * By exemple for the following form
		 * @codestart
&lt;input type="text" id="field_id" name="mad.model.MyModel.id" />
&lt;input type="text" id="field_id" name="mad.model.MyModel.label" />
&lt;input type="text" id="field_id" name="mad.model.MyModel2.label" />
		 * @codeend
		 * 
		 * The extract data function will return
		 * @codestart
{
	mad.model.MyModel : {
		id: STRING,
		label: STRING
	},
	mad.model.MyModel2 : {
		label: STRING
	}
}
		 * @codeend
		 * 
		 * @return {array}
		 */
		'extractData': function () {
			var returnValue = {};

			// Get the form elements value
			for (var elementId in this.elements) {
				var element = this.elements[elementId],
					model = element.getModel(),
					modelName = model.fullName,
					modelShortName = model.shortName;

				// 
				if (typeof returnValue[modelName] == 'undefined') {
					returnValue[modelName] = {};
					returnValue[modelName][modelShortName] = {};
				}

				// get the element attribute name
				var eltAttrName = element.getModelAttributeName();

				// if the attribute name is a reference to another model attribute
				if (eltAttrName.indexOf('.') != -1) {
					var cursor = returnValue[modelName],
						eltAttrNames = eltAttrName.split('.'),
						subModelName = eltAttrNames[0],
						subModelAttrName = eltAttrNames[1];
					
					// the submodel is an array
					var xregexp = new XRegExp("models$");
					if (xregexp.test(model.attributes[subModelName])) {
						returnValue[modelName][subModelName] = [];
						var values = element.getValue();
						if (!$.isArray(values)) values = [values];

						for (var i in values) {
							var subModelData = {};
							subModelData = {};
							subModelData[subModelAttrName] = values[i];
							returnValue[modelName][subModelName].push(subModelData);
						}
					} else {
						returnValue[modelName][subModelName] = {};
						returnValue[modelName][subModelName][subModelAttrName] = element.getValue();
					}

//					for (var i in eltAttrNames) {
//						if(i == eltAttrNames.length - 1) { // if we reach the lead
//							cursor[eltAttrNames[i]] = element.getValue();
//						} else { // move the cursor
//							cursor[eltAttrNames[i]] = {};
//							cursor = cursor[eltAttrNames[i]];
//						}
//					}
				} else {
					returnValue[modelName][modelShortName][eltAttrName] = element.getValue();
				}
			}
			return returnValue;
		},

		/**
		 * Convert data to model representation
		 * @return {array}
		 */
		'dataToModel': function () {
			var returnValue = {};
			for (var modelName in this.data) {
				var ModelClass = $.String.getObject(modelName);
				returnValue[modelName] = new ModelClass(this.data[modelName]);
			}
			return returnValue;
		},

		/**
		 * validate an element functions of its associated model. If the element is invalid :
		 * <ul>
		 *	<li>switch the state of the element to error</li>
		 *	<li>switch the state of the associated feedback element to error and display 
		 *	the mad.model.Model.validateAttribute message</li>
		 *	<li>return false</li>
		 * </ul>
		 * 
		 * @see mad.model.Model
		 * @return {boolean}
		 */
		'validateElement': function (element) {
			var returnValue = true,
				model = element.getModel(),
				modelName = model.fullName,
				modelShortName = model.shortName;
				attributeName = element.getModelAttributeName(),
				value = element.getValue(),
				elementName = element.getModelReference(),
				validationResult = true;

			// validate the attribute value
			if (model.validateAttribute) {
				validationResult = model.validateAttribute(attributeName, value, this.data[modelName]);
			}

			if(validationResult !== true) {
				// switch the state of the element to error
				this.elements[elementName]
					.setState('error');
				// set the feedback message, and switch the feedback element state to error
				this.feedbackElements[elementName]
					.setMessage(validationResult)
					.setState('error');

				returnValue = false;
			} else {
				this.elements[elementName]
					.setState('success');
				// set the feedback message, and switch the feedback element state to success
				if (this.feedbackElements[elementName]){
					this.feedbackElements[elementName]
						.setMessage('OK')
						.setState('success');
				}
			}
			
			return returnValue;
		},

		/**
		 * Validate the form
		 * 
		 * @see mad.form.FormController.prototype.validateElement
		 * @return {boolean}
		 */
		'validate': function () {
			var returnValue = true;
			for (var i in this.elements) {
				returnValue &= this.validateElement (this.elements[i]);
			}
			return returnValue;
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to any submit events on the associated HTML element
		 * 
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'submit': function (el, ev) {
			ev.preventDefault();
			this.data = this.extractData();
			// Form data are valid
			if (this.validate()) {
				// convert form data in model data
				var modelData = this.dataToModel();
				// if a submit callback is given, call it
				if (this.options.callbacks.submit) {
					this.options.callbacks.submit(modelData);
				}
			} else {
				// Data are not valid
				// if an error callback is given, call it
				if (this.options.callbacks.error) {
					this.options.callbacks.submit(modelData);
				}
			}
		},
		
		/**
		 * Listen to any changed event which occured on the form elements contained by
		 * the form controller. If the validateOnChange option is set to true, validate
		 * the target form element.
		 * 
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'changed': function (el, ev, data) {
			if (this.options.validateOnChange) {
				this.data = this.extractData();
				var controllers = $(ev.target).controllers();
				for (var i in controllers) {
					// get the form element controller
					if (controllers[i] instanceof mad.form.FormElement) {
						var element = controllers[i];
						this.validateElement(element);
					}
				}
			}
		}

	});
});
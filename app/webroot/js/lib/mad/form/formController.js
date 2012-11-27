steal(
	'mad/controller',
	'mad/form/formElement.js'
).then(function () {

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
			'callbacks': {
				'error': function () { },
				'submit': function (data) { }
			},
			'tag': 'form',
			'validateOnChange': true
		}

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
		 * @type {mad.model.Model}
		 */
		'data': {},

		/**
		 * Load the form with the instance' data
		 * @param {mad.model.Mode} instance The instance to use to populate the form
		 * @return {void}
		 */
		'load': function (instance) {
			if (!(instance instanceof mad.model.Model)) {
				throw new mad.error.WrongParametersException('instance', mad.model.Model.fullName);
			}

			for (var eltId in this.elements) {
				var models = mad.model.Model.getModelAttributes(this.elements[eltId].getModelReference());
				var attrPath = '';
				var attrName = models[models.length - 1].name
				for (var i = 1; i<models.length-1; i++) {
					attrPath += attrPath.length ? '.' + models[i].name : models[i].name;
				}
				var modelAttr = can.getObject(attrPath, instance);
				var leafValue = null;
				// if multiple association
				if (modelAttr.length) {
					leafValue = [];
					can.each(modelAttr, function(attr, i) {
						leafValue.push(attr[attrName]);
					});
				} else {
					leafValue = modelAttr[attrName];
				}
				this.elements[eltId].setValue(leafValue);
			}
		},

		/**
		 * Add an element to the form and optionaly its associated feedback controller.
		 * See the validate function to know more about the feedback controller.
		 * @param {mad.form.FormElement} element The element to add to the form
		 * @param {mad.form.element.FeddbackController} feedback The form feedback element to associate to the form element
		 * @exception mad.error.WrongParametersException
		 * @return {void}
		 */
		'addElement': function (element, feedback) {
			if (!(element instanceof mad.form.FormElement)) {
				throw new mad.error.WrongParametersException('element', 'mad.form.FormElement');
			}
			// store the element with its associated model reference
			var eltId = element.getId();
			this.elements[eltId] = element;
			if (typeof feedback != 'undefined') {
				this.feedbackElements[eltId] = feedback;
			}
		},

		/**
		 * Remove an element from the form
		 * @param {mad.form.FormElement} element The element to remove from the form
		 * @exception mad.error.WrongParametersException
		 * @return {void}
		 */
		'removeElement': function (element) {
			if (!(element instanceof mad.form.FormElement) || element == null) {
				throw new mad.error.WrongParametersException('element', 'mad.form.FormElement');
			}
			var eltId = element.getId();
			if (typeof this.elements[eltId] == 'undefined') {
				throw new mad.error.Exception('The target element to remove does not exist');
			}
			delete this.elements[eltId];
			delete this.feedbackElements[eltId];
		},

		/**
		 * Extract the form data. The data will be formated functions of the form element
		 * name.
		 * <br/>
		 * By exemple for the following form
		 * @codestart
&lt;input type="text" id="field_id" name="mad.model.MyModel.id" />
&lt;input type="text" id="field_id" name="mad.model.MyModel.attr1" />
&lt;input type="text" id="field_id" name="mad.model.MyModel2.attr2" />
		 * @codeend
		 * 
		 * The extract data function will return
		 * @codestart
{
	mad.model.MyModel : {
		id: STRING,
		attr1: STRING
	},
	mad.model.MyModel2 : {
		attr2: STRING
	}
}
		 * @codeend
		 * 
		 * @return {array}
		 */
		'getData': function () {
			var returnValue = {};

			// Get the form elements value
			for (var eltId in this.elements) {
				// Get the model references of the current element
				var fieldAttrs = mad.model.Model.getModelAttributes(this.elements[eltId].getModelReference()),
					// the elt value
					eltValue = this.elements[eltId].getValue(),
					// the attr name
					attrName = fieldAttrs[fieldAttrs.length - 1].name;

				// if not exists, create the top model reference 
				if (!returnValue[fieldAttrs[0].name]) {
					returnValue[fieldAttrs[0].name] = {};
				}

				// if sub models
				if (fieldAttrs.length>2) {
					var leafSubModelRef = fieldAttrs[fieldAttrs.length - 2], // the last one is the field
						leafValue = null;

					// format the element value following the leaf model multiplicity
					// * muliplicity
					if (leafSubModelRef.multiple) {
						eltValue = can.isArray(eltValue) ? eltValue : [eltValue];
						leafValue = [];
						can.each(eltValue, function (val, i) {
							var obj = {};
							obj[attrName] = val;
							leafValue.push(obj);
						});
					} else {
						// single multiplicity
						leafValue = {};
						leafValue[attrName] = eltValue;
					}

					// extract the sub models path
					var subModelPath = '';
					for (var i=1; i<(fieldAttrs.length-1); i++) {
						subModelPath += subModelPath.length ? '.' + fieldAttrs[i].name : fieldAttrs[i].name;
					}
					// construct the return format functon of the sub models references
					can.getObject(subModelPath, returnValue[fieldAttrs[0].name], true, leafValue);
				} else {
					returnValue[fieldAttrs[0].name][attrName] = eltValue;
				}
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
				// the model references of the element
				fieldAttrs = mad.model.Model.getModelAttributes(element.getModelReference()),
				// the leaf model reference
				model = fieldAttrs[fieldAttrs.length-2].modelReference,
				// the attribute name
				attrName = fieldAttrs[fieldAttrs.length-1].name,
				// the validation result
				validationResult = true,
				// the element's id
				eltId = element.getId();

			// validate the attribute value
			if (model.validateAttribute) {
				var value = element.getValue();
				validationResult = model.validateAttribute(attrName, element.getValue());
			}

			if(validationResult !== true) {
				// switch the state of the element to error
				this.elements[eltId]
					.setState('error');
				// set the feedback message, and switch the feedback element state to error
				if (this.feedbackElements[eltId]){
					this.feedbackElements[eltId]
						.setMessage(validationResult)
						.setState('error');
				}
				returnValue = false;
			} else {
				this.elements[eltId]
					.setState('success');
				// set the feedback message, and switch the feedback element state to success
				if (this.feedbackElements[eltId]){
					this.feedbackElements[eltId]
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
			// Form data are valid
			if (this.validate()) {
				// if a submit callback is given, call it
				if (this.options.callbacks.submit) {
					this.options.callbacks.submit(this.getData());
				}
			} else {
				// Data are not valid
				// if an error callback is given, call it
				if (this.options.callbacks.error) {
					this.options.callbacks.error();
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
		 * @param {mixed} data The new value of the component
		 * @return {void}
		 */
		' changed': function (el, ev, data) {
			if (this.options.validateOnChange) {
				var formEltCtls = $(ev.target).controllers(mad.form.FormElement);
				if (formEltCtls.length) {
					this.validateElement(formEltCtls[0]);
				} else {
					throw new mad.error.Error();
				}
			}
		}

	});
});
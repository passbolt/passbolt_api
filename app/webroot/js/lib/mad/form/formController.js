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
			for (var eltModelRef in this.elements) {
				var models = mad.model.Model.getModelReferences(eltModelRef);
				var attrPath = '';
				var attrName = models[models.length - 1].label
				for (var i = 1; i<models.length-1; i++) {
					attrPath += attrPath.length ? '.' + models[i].label : models[i].label;
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
				this.elements[eltModelRef].setValue(leafValue);
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
			if (!element instanceof mad.form.FormElement) {
				throw new mad.error.WrongParametersException('The function addElement is expecting a mad.form.FormElement object as first parameter, ' + (typeof mad.form.FormElement) + ' given');
			}
			// store the element with its associated model reference
			var eltModelRef = element.getModelReference();
			this.elements[eltModelRef] = element;
			if (typeof feedback != 'undefined') {
				this.feedbackElements[eltModelRef] = feedback;
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
			var eltModelRef = element.getModelReference();
			delete this.elements[eltModelRef];
			delete this.feedbackElements[eltModelRef];
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
		'getData': function () {
			var returnValue = {};

			// Get the form elements value
			for (var elementId in this.elements) {
				// Get the model references of the current element
				var modelRefs = mad.model.Model.getModelReferences(this.elements[elementId].getModelReference()),
					// the elt value
					eltValue = this.elements[elementId].getValue(),
					// the attr name
					attrName = modelRefs[modelRefs.length - 1].label;

				// if not exists, create the top model reference 
				if (!returnValue[modelRefs[0].label]) {
					returnValue[modelRefs[0].label] = {};
				}

				// if sub models
				if (modelRefs.length>2) {
					var leafSubModelRef = modelRefs[modelRefs.length - 2], // the last one is the field
						leafValue = null;

					// format the element value following the leaf model multiplicity
					// * muliplicity
					if (leafSubModelRef.multiple) {
						eltValue = can.isArray(eltValue) ? eltValue : [eltValue];
						leafValue = [];
						can.each(eltValue, function (val, i) {
//							console.log('val', val);
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
					for (var i=1; i<(modelRefs.length-1); i++) {
						subModelPath += subModelPath.length ? '.' + modelRefs[i].label : modelRefs[i].label;
					}
					// construct the return format functon of the sub models references
					can.getObject(subModelPath, returnValue[modelRefs[0].label], true, leafValue);
				} else {
					returnValue[modelRefs[0].label][attrName] = eltValue;
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
				modelRefs = mad.model.Model.getModelReferences(element.getModelReference()),
				// the root model reference
				rootModel = modelRefs[0].model,
				// the leaf model reference
				model = modelRefs[modelRefs.length-2].model,
				// the attribute name
				attrName = modelRefs[modelRefs.length-1].label,
				// the validation result
				validationResult = true,
				// the associated model reference
				eltModelRef = element.getModelReference();

			// validate the attribute value
			if (model.validateAttribute) {
				var value = element.getValue();
				
				validationResult = model.validateAttribute(attrName, element.getValue());
			}

			if(validationResult !== true) {
				// switch the state of the element to error
				this.elements[eltModelRef]
					.setState('error');
				// set the feedback message, and switch the feedback element state to error
				this.feedbackElements[eltModelRef]
					.setMessage(validationResult)
					.setState('error');

				returnValue = false;
			} else {
				this.elements[eltModelRef]
					.setState('success');
				// set the feedback message, and switch the feedback element state to success
				if (this.feedbackElements[eltModelRef]){
					this.feedbackElements[eltModelRef]
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
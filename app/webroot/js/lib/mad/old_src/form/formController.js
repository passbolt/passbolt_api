steal(
	'mad/controller',
	'mad/view/form/formView.js',
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
			/**
			 * Callbacks 'error' and 'submit'.
			 */
			'callbacks': {
				'error': function () { },
				'submit': function (data) { }
			},

			/**
			 * Tag.
			 */
			'tag': 'form',

			/**
			 * Default action.
			 */
            'action': null,

			/**
			 * validateOnChange : whether the form should validate after a change in one of its field.
			 * 3 possible values : true, false, or 'afterFirstValidation'
			 */
			'validateOnChange': 'afterFirstValidation',

			/**
			 * Whether the form is template based.
			 */
			'templateBased': false,

			/**
			 * Class for the view.
			 */
			'viewClass': mad.view.form.FormView
		}

	}, /** @prototype */ {

		// constructor like
		'init': function(el, options) {
			/**
			 * The form elements
			 * @type mad.form.element.FormElement[]
			 */
			this.elements = {};
			/**
			 * The feedbacks elements associated to the form elements
			 * @type mad.form.element.FeedbackInterface[]
			 */
			this.feedbackElements = {};
			/**
			 * The form data
			 * @type {mad.model.Model}
			 */
			this.data = {};

			/**
			 * Total number of validations for this form.
			 * @type {number}
			 */
			this.validations = 0;
			
			this._super(el, options);
		},

		/**
		 * Implements beforeRender hook.
		 */
		'beforeRender': function() {
			this.setViewData('action', this.options.action);
		},

		/**
		 * Reset the form.
		 * @return {void}
		 */
		'reset': function () {
			// Reset all the form elements value.
			for (var eltId in this.elements) {
				this.elements[eltId].setValue(this.options.defaultValue);
			}
		},

		/**
		 * Load the form with the given data
		 * @param {mixed} data The data to load the form element with
		 * @return {void}
		 */
		'load': function (data) {
			// load each element with the given data
			for (var eltId in this.elements) {
				/* 
				 * the form element is driven by an associated model reference :
				 * - The validation of the field will be automatically operated through the model reference
				 *   validation rule
				 * - The form will return its data formated following the associated model reference, by instance
				 *   for the associated model mad.model.MyModel.MyAssociatedModel.myFieldName, the form will return
				 *   {
				 * 	   mad.model.MyModel: {
				 * 	     MyAssociatedModel: {
				 * 	       myFieldName: MIXED // VALUE OF THE FORM ELEMENT
				 *       }
				 *     }
				 *   }
				 */
				var eltModelRef = this.elements[eltId].getModelReference(),
					value = null;
				
				// if a model reference has been associated to the form element
				if(eltModelRef != null) {
					// data has to be a model instance
					if (!(data instanceof mad.model.Model)) {
						throw new mad.error.WrongParametersException('data', mad.model.Model.fullName);
					}
					value = mad.model.Model.getModelAttributeValue(eltModelRef, data);
				} else {
					value = data[eltId];
				}
				
				// set the element value
				this.elements[eltId].setValue(value);
			}
		},

		/**
		 * Get an element
		 * @param {string} eltId The element Id
		 * @return the form element
		 */
		'getElement': function(eltId) {
			return this.elements[eltId];
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

			// Get validation rules for this model if this is the first element using a model of this type.
			// Check if element model is already present in the form.
			var modelPresentInForm = false;
			var modelAttr = mad.model.Model.getModelAttributes(element.getModelReference());
			// Get model name.
			var modelName = modelAttr[modelAttr.length - 2].name;
			// Loop on the already added form elements.
			for (var eltId in this.elements) {
				// Get model name for current element.
				var eltModelAttr = mad.model.Model.getModelAttributes(this.elements[eltId].getModelReference());
				var eltModelName = modelAttr[eltModelAttr.length - 2].name;
				// If we don't find in the form elements the same model as in the new element, then we note it.
				if (modelName == eltModelName) {
					modelPresentInForm = true;
					break;
				}
			}
			// If model is not already present in form.
			// (means we don't know the validation rules yet).
			if (!modelPresentInForm) {
				// We get the validation rules.
				// First, get the model.
				var model = can.getObject(modelName);
				if (model !== undefined) {
					// Get the validation rules.
					model.getValidationRules(this.options.action);
				}
			}

			// store the element with its associated model reference
			var eltId = element.getId();
			this.elements[eltId] = element;
			if (typeof feedback != 'undefined') {
				this.feedbackElements[eltId] = feedback;
			}
			return element;
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
				var eltModelRef = this.elements[eltId].getModelReference(),
					// the element value
					eltValue = this.elements[eltId].getValue();

				// If a model reference is associated to the current form element
				if (eltModelRef != null) {
					var fieldAttrs = mad.model.Model.getModelAttributes(eltModelRef),
						// the field attribute name
						attrName = fieldAttrs[fieldAttrs.length - 1].name;
					
						// if not exists, create the top model reference 
					if (!returnValue[fieldAttrs[0].name]) {
						returnValue[fieldAttrs[0].name] = {};
					}
		
					// if there is only 2 field attributes 
					// and the last one is a scalar attribute
					if (fieldAttrs.length<=2 && fieldAttrs[fieldAttrs.length-1].modelReference == null) {
						returnValue[fieldAttrs[0].name][attrName] = eltValue;
					}
					// if sub models
					else {
						var leafValue = null,
							subModelPath = '';

						// if the last field attribute is a reference to a model
						// no extra transformation, the leaf value is equal to the elt value
						// the developper as to take care about what the form element is
						// returning
						if (fieldAttrs[fieldAttrs.length-1].modelReference!=null) {
							leafValue = eltValue;
							// extract the sub models path
							subModelPath = can.map(fieldAttrs, function (val, prop) { return val.name; })
								.slice(1, fieldAttrs.length)
								.join('.');
							// construct the return format functions of the sub models references
							can.getObject(subModelPath, returnValue[fieldAttrs[0].name], true, leafValue);
		
						// else the last field attribute is a scalar attribute
						} else {
							var leafSubModelRef = fieldAttrs[fieldAttrs.length-2];
							// extract the sub models path
							var subModelPath = can.map(fieldAttrs, function (val, prop) { return val.name; })
								.slice(1, fieldAttrs.length-1)
								.join('.');

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
								// construct the return format function of the sub models references
								can.getObject(subModelPath, returnValue[fieldAttrs[0].name], true, leafValue);
							} else {
								// construct the return format function of the sub models references
								can.getObject(subModelPath + '.' + attrName , returnValue[fieldAttrs[0].name], true, eltValue);
							}
						}
					}
				}
					// else if the element is not associated to a model attribute
				else {
					returnValue[eltId] = eltValue;
				}
			}

			return returnValue;
		},

		/**
		 * Read and process server errors.
		 * @param errors
		 */
		'showErrors':function(errors) {
			console.log(errors);
			for (var i in this.elements) {
				var element = this.elements[i];
				//console.log(element);
				var eltModelRef = element.getModelReference();
				if (eltModelRef) {
					var fieldAttrs = mad.model.Model.getModelAttributes(eltModelRef),
						// model name
						modelFullName = fieldAttrs[fieldAttrs.length-2].name,
						// the attribute name
						attrName = fieldAttrs[fieldAttrs.length-1].name,
						// model name
						modelName = modelFullName.substr(modelFullName.lastIndexOf('.') + 1),
						// element id
						eltId = element.getId();

					for (var j in errors) {
						if (errors[j][modelName] != undefined && errors[j][modelName][attrName] != undefined) {
							var error = errors[j][modelName][attrName][0];

							var eltStates = ['error'];
							if (element.state.is('hidden')) {
								eltStates.push('hidden');
							}
							// switch the state of the element to error
							element.setState(eltStates);
							// set the feedback message, and switch the feedback element state to error
							if (this.feedbackElements[eltId]) {
								this.feedbackElements[eltId]
									.setMessage(error)
									.setState('error');
							}
							// Update the view.
							this.view.setElementState(this.elements[eltId], 'error');
						}
					}

				}
			}

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
				// the element value is driven by an associated model
				eltModelRef = element.getModelReference(),
				// the validation result
				validationResult = true,
				// the element's id
				eltId = element.getId();

			// The element requires a validation.
			if (element.requireValidation()) {
				// Get the element value.
				var value = element.getValue();

				// if a validation function is given directly in the element declaration, use this.
				if (typeof element.options.validateFunction != 'undefined' && element.options.validateFunction != null) {
					validationResult = element.options.validateFunction(value, {});
				}
				// if the element is associated to a model reference
				else if (eltModelRef != null) {
					// the model references of the element
					var fieldAttrs = mad.model.Model.getModelAttributes(eltModelRef),
					// the leaf model reference
						model = fieldAttrs[fieldAttrs.length-2].modelReference,
					// the attribute name
						attrName = fieldAttrs[fieldAttrs.length-1].name;

					// validate the attribute value
					if (model.validateAttribute) {
						validationResult = model.validateAttribute(attrName, value, {}, this.options.action);
					}
				}

				// the validation of the element failed
				if(validationResult !== true) {
					var eltStates = ['error'];
					if (this.elements[eltId].state.is('hidden')) {
						eltStates.push('hidden');
					}
					// switch the state of the element to error
					this.elements[eltId].setState(eltStates);
					// set the feedback message, and switch the feedback element state to error
					if (this.feedbackElements[eltId]){
						this.feedbackElements[eltId]
							.setMessage(validationResult)
							.setState('error');
					}
					// Update the view.
					this.view.setElementState(this.elements[eltId], 'error');
					returnValue = false;

					// otherwise the validation is successful
				} else {
					var eltStates = ['success'];
					if (this.elements[eltId].state.is('hidden')) {
						eltStates.push('hidden');
					}
					this.elements[eltId].setState(eltStates);
					// set the feedback message, and switch the feedback element state to success
					if (this.feedbackElements[eltId]){
						this.feedbackElements[eltId]
							.setMessage('')
							.setState('success');
					}
					// Update the view.
					this.view.setElementState(this.elements[eltId], 'success');
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
			// Increment number of validations.
			this.validations ++;
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
		' submit': function (el, ev) {
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
			var validateOnChange = this.options.validateOnChange === true
				|| (this.options.validateOnChange === 'afterFirstValidation' && this.validations > 0);
			if (validateOnChange) {
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
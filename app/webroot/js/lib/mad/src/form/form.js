import 'mad/component/component';
import 'mad/form/element';
import 'mad/form/feedback';
import 'mad/view/form/form';

// Initialize the form namespaces.
mad.form = mad.form || {};

/**
 * @parent Mad.form_api
 * @inherits mad.Component
 * @group mad.Form.view_events 0 View Events
 *
 * The Form Component as for aim to manage forms.
 * @todo TBD
 *
 * Tracks :
 * If the form element is driven by a model reference :
 * - The validation of the field will be automatically operated through the model reference
 *   validation rule
 * - The form will return its data formatted following the associated model reference. See
 * the function [mad.Form::getData() getData()] function.
 */
var Form = mad.Form = mad.Component.extend('mad.Form', /* @static */ {

    defaults: {
        // Override the label option.
        label: 'Form Component',
        // Override the cssClasses option.
        cssClasses: ['form'],
        // Override the tag option.
        tag: 'form',
        // Override the templateUri option.
        templateUri: null,
        // Override the templateBased option.
        templateBased: false,
        // Override the viewClass option.
        viewClass: mad.view.Form,

        // The callbacks the component offers to the dev to bind their code.
        callbacks: {
            // An error occurred on submit.
            // @todo is it fired on auto-validate ?
            error: null,
            // The form has been submitted
            submit: null
        },
        // Default action.
        action: null,
        // Whether the form should validate after a change in one of its field.
        // Available values : true, false, or afterFirstValidation
        validateOnChange: 'afterFirstValidation'
    }

}, /** @prototype */ {

    // constructor like
    init: function (el, options) {
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
         * @type {mad.Model}
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
    beforeRender: function () {
        this.setViewData('action', this.options.action);
    },

    /**
     * Reset the form.
     * @return {void}
     */
    reset: function () {
        // Reset all the form elements value.
        for (var eltId in this.elements) {
            this.elements[eltId].setValue(this.options.defaultValue);
        }
    },

    /**
     * Load the form with the given data.
     *
     * @param {mixed} data The instance to use to initialize the form elements values with.
     */
    load: function (data) {
        // Initialize the form elements value with the value given in data.
        for (var eltId in this.elements) {
            var element = this.getElement(eltId),
                eltModelRef = element.getModelReference(),
                value = null;

            // If a model reference has been associated to the form element
            if (eltModelRef != null) {
                // Data has to be a model instance
                if (!(data instanceof mad.Model)) {
                    throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'data');
                }
                value = mad.Model.getModelAttributeValue(eltModelRef, data);
            } else {
                value = data[eltId];
            }

            // set the element value
            element.setValue(value);
        }
    },

    /**
     * Get a form element that has been added to the form based on its id.
     *
     * @param {string} eltId The form element Id
     *
     * @return {mad.form.Element} The form element.
     */
    getElement: function (eltId) {
        return this.elements[eltId];
    },

    /**
     * Add a form element to the form.
     * You can optionally associate a feedback element to this form element.
     * See the validate function to know more about the feedback controller.
     *
     * @param {mad.form.Element} element The form element to add.
     * @param {mad.form.Feedback} feedback The form feedback element to associate to the form element.
     */
    addElement: function (element, feedback) {
        // If the given form element is not inherited from the Class mad.form.Element.
        if (!(element instanceof mad.form.Element)) {
            throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'element');
        }

        // If the element has been associated to a model reference.
        // Try to define which model reference.
        // If this is the first time the form is treating this model, get the validation rules associated to it.
        var modelReference = element.getModelReference();

        if (modelReference != null) {
            // Check if an element of the form is already associated to this model.
            var modelPresentInForm = false,
            // The chain of models and models attributes representing this reference.
                modelAttr = mad.Model.getModelAttributes(modelReference),
            // The model name.
                modelName = modelAttr[modelAttr.length - 2].name;

            // Loop on the already added form elements.
            for (var eltId in this.elements) {
                // Get model name for current element.
                var eltModelRef = this.elements[eltId].getModelReference();

                if (eltModelRef != null) {
                    var eltModelAttr = mad.Model.getModelAttributes(eltModelRef),
                        eltModelName = modelAttr[eltModelAttr.length - 2].name;

                    // If we don't find in the form elements the same model as in the new element, then we note it.
                    if (modelName == eltModelName) {
                        modelPresentInForm = true;
                        break;
                    }
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
     * Remove an element from the form.
     * The remove takes break the association between the form and the element, to remove the element
     * from the DOM use the $.remove() function.
     *
     * @param {mad.form.Element} element The element to remove from the form
     */
    removeElement: function (element) {
        // The given element has to be inherited from the class mad.form.FormElement and not be null.
        if (!(element instanceof mad.form.Element) || element == null) {
            throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'element');
        }
        // Check if the element has been added to the form.
        var eltId = element.getId(),
            element = this.getElement(eltId);
        if (element == undefined) {
            throw mad.Exception.get(mad.error.ELEMENT_NOT_FOUND, eltId);
        }
        // Remove the association of the element with the form.
        delete this.elements[eltId];
        // Remove the feedback association with the form.
        delete this.feedbackElements[eltId];
    },

    /**
     * Extract the data gathered by the form's elements.
     *
     * For the given form :
     *
     * ```
     <form id="form" class="mad_form form mad_view ready">
     <input id="textbox" type="text">
     <div id="checkbox"></div>
     </form>
     * ```
     *
     * ```
     var form = new mad.Form($('#form'), {});
     form.start();

     // Add a textbox to the form.
     var $textbox = $('<input id="textbox" type="text"/>').appendTo($form);
     var textbox = new mad.form.Textbox($textbox, {
    modelReference: 'mad.test.model.TestModel.testModelAttribute'
});
     form.addElement(textbox.start());

     // Add a checkbox to the form.
     var $checkbox = $('<div id="checkbox></div>').appendTo($form);
     var checkbox = new mad.form.Checkbox($checkbox, {
    availableValues: {
        'option_1': 'Option 1',
        'option_2': 'Option 2',
        'option_3': 'Option 3'
    },
    modelReference: 'mad.test.model.TestModel.TestModel1s.testModel1Attribute'
});
     form.addElement(checkbox.start());
     * ```
     *
     * We expect get data to return values like :
     * ```
     {
         'mad.test.model.TestModel' : {
             'testModelAttribute': VALUE,
             'TestModel1s': [
                 {
                     'testModel1Attribute': VALUE
                 }, ...
             ]
         }
     }
     * ```
     *
     * @return {array}
     */
    getData: function () {
        var returnValue = {};

        // Foreach form elements.
        for (var eltId in this.elements) {
            // The current form element.
            var element = this.getElement(eltId),
            // The current form element associated model reference.
                eltModelRef = element.getModelReference(),
            // The current form element value.
                eltValue = element.getValue();

            // If no model reference associated to the current form element.
            if (eltModelRef == null || eltModelRef == undefined) {
                returnValue[eltId] = eltValue;
            }
            // A model reference is associated to the current form element.
            else {
                // Get the form element model reference details.
                var fieldAttrs = mad.Model.getModelAttributes(eltModelRef),
                // The pointer where to store the form element value in the return value variable.
                    pointer = returnValue;

                // Loop on the field attributes.
                for (var i = 0; i < fieldAttrs.length; i++) {
                    var eltSubModelRef = fieldAttrs[i].getModelReference();

                    // If the attribute reference a scalar (meaning: it is not a model reference).
                    // Insert the value in the return value and go to the next form element treatment.
                    if (eltSubModelRef == null || eltModelRef == undefined) {
                        // If the parent attribute of the current one is a model with a cardinality multiple.
                        // And the value is not null.
                        if (fieldAttrs[i - 1].isMultiple()) {
                            // If the element value is not null.
                            if (eltValue != null) {
                                // Add each value to the return value following the expected representation.
                                can.each(eltValue, function (val, prop) {
                                    var obj = {};
                                    obj[fieldAttrs[i].getName()] = val;
                                    pointer.push(obj);
                                });
                            }
                        }
                        // If the parent attribute is not multiple.
                        else {
                            pointer[fieldAttrs[i].getName()] = eltValue;
                        }
                        // Break the attributes loop, the scalar attribute should be the one which carries the element value.
                        break;
                    } else {
                        // Move the pointer forward.
                        if (pointer[fieldAttrs[i].getName()] == undefined) {
                            pointer[fieldAttrs[i].getName()] = [];
                        }
                        pointer = pointer[fieldAttrs[i].getName()];
                    }
                }
            }
        }

        return returnValue;
    },

    /**
     * Read and process server errors.
     * @param errors
     */
    showErrors: function (errors) {
        for (var i in this.elements) {
            var element = this.elements[i];
            //console.log(element);
            var eltModelRef = element.getModelReference();
            if (eltModelRef) {
                var fieldAttrs = mad.Model.getModelAttributes(eltModelRef),
                // model full name
                    modelFullName = fieldAttrs[fieldAttrs.length - 2].name,
                // the attribute name
                    attrName = fieldAttrs[fieldAttrs.length - 1].name,
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
     * Validate a form element.
     *
     * If the form element has been associated to a model, validate the value of the form element
     * with model attribute rule.
     *
     * If the form element has been associated to a custom validation function, use this function
     * to validate the value of the form element. This case is executed in priority.
     *
     * If the element is invalid :
     * * Switch the state of the element to error ;
     * * Switch the state of the associated feedback element to error ;
     * * Display an error message see [mad.Model.validateAttribute]
     *
     * @return {boolean}
     */
    validateElement: function (element) {
		var returnValue = true,
		// The form element is driven by an associated model.
			eltModelRef = element.getModelReference(),
		// By default the result value is true, if no rule found to validate the form element, the validation is a success.
			validationResult = [],
		// The form element id.
			eltId = element.getId();

		// The element requires a validation.
		if (element.requireValidation()) {
			// Get the element value.
			var value = element.getValue(),
			// The direct validate function associated to the form element.
				validateFunction = element.getValidateFunction();

			// A direct validate function is defined.
			if (validateFunction != null) {
				var validateFuncResult = validateFunction(value, {});
				if (validateFuncResult !== true) {
					validationResult.push(validateFuncResult);
				}
			}
			// If the element is referenced by a model reference.
			else if (eltModelRef != null) {
				// Get the models & attribtues that define this model reference.
				var fieldAttrs = mad.Model.getModelAttributes(eltModelRef),
				// The model that own the attribute that represents the form element.
					model = fieldAttrs[fieldAttrs.length - 2].getModelReference(),
				// The attribute name
					attrName = _.last(fieldAttrs).getName();

				// Validate the attribute with the model attribute rule.
				if (model.validateAttribute) {
					validationResult = model.validateAttribute(attrName, value, {}, this.options.action);
				}
			}

			// The validation of the element failed.
			if (validationResult.length > 0) {
				var eltStates = ['error'];
				if (this.elements[eltId].state.is('hidden')) {
					eltStates.push('hidden');
				}
				// switch the state of the element to error
				this.elements[eltId].setState(eltStates);
				// set the feedback message, and switch the feedback element state to error
				if (this.feedbackElements[eltId]) {
					this.feedbackElements[eltId]
						.setMessage(validationResult[0])
						.setState([])
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
				if (this.feedbackElements[eltId]) {
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
     * Validate the form.
     *
     * @return {boolean}
     */
    validate: function () {
        var returnValue = true;

        for (var i in this.elements) {
            returnValue &= this.validateElement(this.elements[i]);
        }

        // Increment number of validations.
        this.validations++;

        return returnValue;
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * @function mad.Form.__submit
     * @parent mad.Form.view_events
     *
     * Listen to any submit events on the associated HTML element
     *
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @return {void}
     */
    ' submit': function (el, ev) {
        ev.preventDefault();

        // Validate the form.
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
     * @function mad.Form.__changed
     * @parent mad.Form.view_events
     *
     * Listen to any changed event which occured on the form elements contained by
     * the form controller. If the validateOnChange option is set to true, validate
     * the target form element.
     *
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event that occurred
     * @param {mixed} data The new data
     */
    ' changed': function (el, ev, data) {
        // Should the form element be validated.
        var validateOnChange = this.options.validateOnChange === true
            || (this.options.validateOnChange === 'afterFirstValidation' && this.validations > 0);

        // If the form should validate on change.
        // Validate the element which has changed.
        if (validateOnChange) {
            var formElement = this.getElement(ev.target.id);
            if (formElement) {
                this.validateElement(formElement);
            }
            else {
                throw mad.Exception.get('No form element found.');
            }
        }
    }

});

export default Form;

steal( 
	'jquery/controller',
	 MAD_ROOT+'/controller/componentController.js',
	 MAD_ROOT+'/view/template/component/input.ejs'
)
.then( function ($) {

	/*
	 * @class mad.controller.component.InputController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 * @todo to finish
	 * 
	 * The Input class Controller is our implementation of the UI component input
	 * 
	 * @constructor
	 * Creates a new Input Controller Component
	 * @param {array} options Optional parameters
	 * @return {mad.controller.component.InputController}
	 */
	mad.controller.ComponentController.extend('mad.controller.component.InputController',
	/** @static */
	{
		'defaults': {
			'label': 'Button Component',
			'templateUri': '//' + MAD_ROOT + '/view/template/component/button.ejs',
			'value': null,
			'event': {
				'click': null
			}
		}
	},
	/** @prototype */
	{

		/**
		 * Value of the button. This value will be released when events occured
		 * @type {string}
		 */
		'value': null,

		// Construcor
		'init': function (el, options) {
			this._super();
			this.value = options.value;
		},

		/**
		 * Get the value of the button
		 * @return {mixed} value The value of the button
		 */
		'getValue': function () {
			return this.value;
		},

		/**
		 * Set the value of the button
		 * @param {mixed} value The value to set
		 * @return {void}
		 */
		'setValue': function (value) {
			this.value = value;
		}


		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

	});

});
steal('jquery/class')
.then( function ($) {

	/*
	 * @class mad.net.Header
	 * 
	 * @inherits {mad.core.Class}
	 * @parent index
	 * 
	 * The ajax response header model
	 * 
	 * @constructor
	 * Creates a new reponse header
	 * @return {mad.net.Header}
	 */
	$.Model('mad.net.Header',

	/** @static */
	{
		'STATUS_ERROR': 'error',
		'STATUS_NOTICE': 'notice',
		'STATUS_SUCCESS': 'success',
		'STATUS_WARNING': 'warning',
		'RESPONSE_ID_UNDEFINED': 'undefined',
		'RESPONSE_CONTROLLER_UNDEFINED': 'undefined',
		'RESPONSE_ACTION_UNDEFINED': 'undefined'
	},

	/** @prototype */
	{
		/** Id of the transaction
		 * @type {string} */
		'id': null,

		/** Status of the transaction (success, notice, warning, error)
		 * @type {string} */
		'status': null,

		/** Label of the transaction
		 * @type {string} */
		'title': null,

		/** Message of the transaction
		 * @type {string} */
		'message': null,

		/** Controller called
		 * @type {string} */
		'controller': null,

		/** Action called
		 * @type {string} */
		'action': null,

		/**
		 * Check if the response is valid. All fields should be filled
		 * @return {boolean}
		 */
		'isValid': function () {
			var returnValue = true;

			if (this.id == null || this.status == null || this.title == null || this.message == null || this.controller == null || this.action == null) returnValue = false;

			return returnValue;
		}
	});

});
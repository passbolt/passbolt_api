steal( 
	'jquery/class',
	MAD_ROOT+'/net/header.js'
)
.then( function ($) {

	/*
	 * @class mad.net.Response
	 * @inherits mad.core.Class
	 * @parent mad.net
	 * 
	 * Our ajax response model
	 * 
	 * @constructor
	 * Creates a new ajax response
	 * @return {mad.net.Response}
	 */
	$.Model('mad.net.Response',

	/** @static */
	{
		attributes: {
			'header': 'mad.net.Header'
		}
	},

	/** @prototype */
	{
		/** Passbolt response Header
		 * @type {mad.net.Header} */
		'header': null,

		/** Passbolt response Body. Data sent by the server. The type can be
		 * any JMVC model or json data.
		 * @type {mixed} */
		'body': null,

		/**
		 * Check if the response is valid. All fields not null, except data which can be null.
		 * @return {boolean}
		 */
		'isValid': function () {
			return this.header.isValid(); // && this.body.isValid();
		}
	});

});
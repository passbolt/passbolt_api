steal('jquery/model').then(function ($) {

	/*
	 * @class mad.model.Model
	 * @inherits jQuery.Model
	 * @parent mad.core
	 * 
	 * The core class Model is an extension of the JavascriptMVC Model. This
	 * class allow us to easily hook common behavior, such as :
	 * <ul>
	 *	<li>
	 *		validating model attribute
	 *	</li>
	 * </ul>
	 * 
	 * @constructor
	 * creates a new model
	 * @return {mad.model.Model}
	 */
	$.Model('mad.model.Model', /** @static */ {

		/**
		 *
		 */
		'validateAttribute': function (attrName, value, modelValues) {
			return 'wrong parameter';
		}

	}, /** @prototype */ {

		// Class constructor
		'init': function (el, options) {

		},

		/**
		 * 
		 * @return {void}
		 */
		'destroy': function () {}

	});

});
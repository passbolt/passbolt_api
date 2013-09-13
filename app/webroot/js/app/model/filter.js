steal(
	'mad/model'
).then(function () {

	/*
	 * @class passbolt.model.Filter
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The Filter model
	 * 
	 * @constructor
	 * Creates a filter
	 * @param {array} options
	 * @return {passbolt.model.Filter}
	 */
	mad.model.Model('passbolt.model.Filter', /** @static */	{

/* ************************************************************** */
/* MODEL DEFINITION */
/* ************************************************************** */

		'validateRules': { },

		'attributes': {
			'keywords': 'string',
			'tags': 'passbolt.model.Category.models'
		}

	}, /** @prototype */ {

		/**
		 * Get filter keywords
		 * @return {array}
		 */
		'getKeywords': function() {
			if(typeof this.keywords != 'undefined') {
				return this.keywords;
			}
			return '';
		},

		/**
		 * Get filter tags
		 * @return {array}
		 */
		'getTags': function() {
			if(typeof this.tags != 'undefined') {
				return this.tags;
			}
			return [];
		}

	});

});
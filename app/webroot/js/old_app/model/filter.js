steal(
	'mad/model'
).then(function () {

	/*
	 * @class passbolt.model.Filter
	 * @inherits {mad.model.Model}
	 * @parent index
	 *
	 * The Filter model used for the search
	 *
	 * target : Resource / User
	 * keywords : string
	 * conditions : Category = *, Group = *
	 * order : conditions of order,
	 * script:
	 *
	 * @constructor
	 * Creates a filter
	 * @param {array} options
	 * @return {passbolt.model.Filter}
	 */
	mad.model.Model('passbolt.model.Filter', /** @static */	{

		'attributes': {
			// Label of the filter
			'label': 'string',
			// Filter type
			'type': 'string',
			// Filter by keywords
			'keywords': 'string',
			// Filter on a specific case (modified ... )
			'case': 'string',
			// Filter functions of a foreign model
			'foreignModels': 'array',
			// Order by
			'order': 'array',
			// Prefix the filter param by
			'requestPrefix': 'string'
		},

		// Available shortcut types
		'SHORTCUT': 1,
		'FOREIGN_MODEL': 2,
		'KEYWORD': 3,
		'TAG': 4

	}, /** @prototype */ {

		'init': function(attrs) {
			if(typeof attrs == 'undefined' || typeof attrs['requestPrefix'] == 'undefined') {
				this.requestPrefix = 'fltr_';
			}
			if(typeof attrs == 'undefined' || typeof attrs['foreignModels'] == 'undefined') {
				this.foreignModels = new can.List([]);
			}
		},

		/**
		 * Format the filter to be passed in an ajax request
		 */
		'toRequest': function() {
			var returnValue = {};

			if(this.keywords != null) {
				returnValue[this.requestPrefix + 'keywords'] = this.keywords;
			}
			if(this['case'] != null) {
				returnValue[this.requestPrefix + 'case'] = this['case'];
			}
			if(this.order != null) {
				returnValue[this.requestPrefix + 'order'] = this.order;
			}
			var foreignModels = this.foreignModels.attr();
			for(var foreignModel in foreignModels) {
				returnValue[this.requestPrefix + 'model_' + [foreignModel.toLowerCase()]] = can.map(this.foreignModels[foreignModel], function (instance, i) { return instance.id; }).join(',');
			}

			return returnValue;
		},

		/**
		 * Get the foreign model which will apply to the filter
		 * @param {string} name The condition name
		 * @return {string}
		 */
		'getForeignModels': function(name) {
			var returnValue = [];
			if(typeof this.foreignModels != 'undefined' && typeof this.foreignModels[name] != 'undefined') {
				returnValue = this.foreignModels[name];
			}
			return returnValue;
		}

	});

});
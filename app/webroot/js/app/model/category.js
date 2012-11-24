steal(
	'mad/model'
).then(function () {

	/*
	 * @class passbolt.model.Category
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The Category model
	 * 
	 * @constructor
	 * Creates a category
	 * @param {array} options
	 * @return {passbolt.model.Category}
	 */
	mad.model.Model('passbolt.model.Category', /** @static */	{

/* ************************************************************** */
/* MODEL DEFINITION */
/* ************************************************************** */

		'validateRules': { },

		'attributes': {
			'id': 'string',
			'parent_id': 'string',
			'lft': 'string',
			'rght': 'string',
			'name': 'string',
			'category_type_id': 'string',
			'children': 'passbolt.model.Category.models'
		},

/* ************************************************************** */
/* CRUD FUNCTION */
/* ************************************************************** */

		/**
		 * Create a new category
		 * @params {array} attrs Attributes of the new category
		 * @return {jQuery.Deferred)
		 */
		'create' : function (attrs, success, error) {
			var self = this;
			var params = this.toCakePHP(attrs);
			return mad.net.Ajax.request({
				url: APP_URL + '/categories',
				type: 'POST',
				params: params,
				success: success,
				error: error
			}).pipe(function (data, textStatus, jqXHR) {
				var def = $.Deferred();
				def.resolveWith(this, [self.toCan(data.body), new mad.net.Response(data)]);
				return def;
			});
		},

		/**
		 * Destroy a category following the given parameter
		 * @params {string} id the id of the instance to remove
		 * @return {jQuery.Deferred)
		 */
		'destroy' : function (id, success, error) {
			var params = {id:id};
			return mad.net.Ajax.request({
				url: APP_URL + 'categories/{id}',
				type: 'DELETE',
				params: params,
				success: success,
				error: error
			});
		},

		/**
		 * Find a category following the given parameter
		 * @params {array} params Optional parameters
		 * @return {jQuery.Deferred)
		 */
		'findOne': function (params, success, error) {
			params.children = params.children || false;
			return mad.net.Ajax.request({
				url: APP_URL + '/categories/{id}/{children}.json',
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		},

		/**
		 * Find a bunch of categories following the given parameters
		 * @params {array} params Optional parameters
		 * @return {jQuery.Deferred)
		 */
		'findAll': function (params, success, error) {
			return mad.net.Ajax.request({
				url: APP_URL + '/categories/index/{children}.json',
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		}


	}, /** @prototype */ {

		/**
		 * Get children categories
		 * @return {passbolt.model.Category.List}
		 */
		'getSubCategories': function () {
			return mad.model.Model.nestedToList(this, 'children');
		}

	});

});
steal('jquery/model').then(function () {

	/*
	 * @class passbolt.model.Category
	 * @inherits {$.Model}
	 * @parent index
	 * 
	 * The Category model
	 * 
	 * @constructor
	 * Creates a category
	 * @param {array} options
	 * @return {passbolt.model.Category}
	 */
	$.Model('passbolt.model.Category', /** @static */	{

		attributes: {
			'id': 'string',
			'parent_id': 'string',
			'lft': 'string',
			'rght': 'string',
			'name': 'string',
			'category_type_id': 'string',
			'children': 'passbolt.model.Category.models'
		},

		/**
		 * Get a category
		 */
		'get': function (params, success, error) {
			params.children = typeof (params.children) != 'undefined' ? params.children : false;
			var url = APP_URL + '/categories/get/{id}/{children}';
			url = $.String.sub(url, params, true);

			return mad.net.Ajax.singleton().request({
				url: url,
				type: 'get',
				dataType: 'passbolt.model.Category.models',
				data: null,
				success: success,
				error: error
			});
		},

		/**
		 * Get the roots category
		 */
		'getRoots': function (params, success, error) {
			params.children = false;
			var url = APP_URL + '/categories/index/1.json';
			url = $.String.sub(url, params, true);

			return mad.net.Ajax.singleton().request({
				url: url,
				type: 'get',
				dataType: 'passbolt.model.Category.models',
				data: null,
				success: success,
				error: error
			});
		}

	}, /** @prototype */ {

	});
});
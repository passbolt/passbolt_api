steal(
	MAD_ROOT + '/model'
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

		'validateRules': {
			
		},

		attributes: {
			// Cannot manage attributes like this, our representation is fully compatible with the cakephp model structure
//			'id': 'string',
//			'parent_id': 'string',
//			'lft': 'string',
//			'rght': 'string',
//			'name': 'string',
//			'category_type_id': 'string',
			'children': 'passbolt.model.Category.models'
		},

		add : function (resource, success, error) {
			var data = resource.serialize();
			var url = APP_URL + 'categories';
			return mad.net.Ajax.request({
				url: url,
				type: 'post',
				data: data,
				success: success,
				error: error,
				dataType: 'passbolt.model.Category.model'
			});
		},

		/**
		 * Get a category
		 */
		'get': function (params, success, error) {
			params.children = params.children || false;
			var url = APP_URL + '/categories/{id}/{children}';
			url = $.String.sub(url, params, true);
			return mad.net.Ajax.request({
				url: url,
				type: 'get',
				dataType: 'passbolt.model.Category.model',
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
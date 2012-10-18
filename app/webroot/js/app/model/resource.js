steal(
	'jquery/model',
	'app/model/category.js'
).then(function () {

	/*
	 * @class passbolt.model.Resource
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The resource model
	 * 
	 * @constructor
	 * Creates a resource
	 * @param {array} data 
	 * @return {passbolt.model.Resource}
	 */
	mad.model.Model('passbolt.model.Resource', /** @static */ {

		'validateRules': {
		},

		attributes: {
//			'id': 'string',
//			'name': 'string',
//			'username': 'string',
//			'expiry_date': 'string',
//			'uri': 'string',
//			'description': 'string',
//			'deleted': 'string',
//			'created': 'string',
//			'modified': 'string'
			'Category': 'passbolt.model.Category.models'
		},

		'add' : function (resource, success, error) {
			var data = resource.serialize();
			var url = APP_URL + '/resources/add';
			return mad.net.Ajax.singleton().request({
				url: url,
				type: 'post',
				data: data,
				success: success,
				error: error,
				dataType: 'passbolt.model.Resource.model'
			});
		},

		'delete' : function (params, success, error) {
			var url = APP_URL + '/resources/delete/{id}';
			url = $.String.sub(url, $.extend(true, {}, params), true);
			return mad.net.Ajax.singleton().request({
				url: url,
				type: 'delete',
				data: null,
				success: success,
				error: error
			});
		},

		/**
		 * Get resources for a given category
		 */
		'getByCategory': function (params, success, error) {
			var urlTpl = APP_URL + '/resources/viewByCategory/{category_id}/{recursive}',
				url = $.String.sub(urlTpl, $.extend(true, {}, params), true);

			return mad.net.Ajax.singleton().request({
				url: url,
				type: 'get',
				dataType: 'passbolt.model.Resource.models',
				data: {
					category_id: params.category_id,
					recursive: params.recursive
				},
				success: success,
				error: error
			});
		},

		/**
		 * Get a resource
		 */
		'get': function (params, success, error) {
			var url = APP_URL + '/resources/view/{id}';
			url = $.String.sub(url, $.extend(true, {}, params), true);
			return mad.net.Ajax.singleton().request({
				url: url,
				type: 'get',
				dataType: 'passbolt.model.Resource.model',
				data: {
					id: params.id
				},
				success: success,
				error: error
			});
		}

	}, /** @prototype */ {

		'Resource': {
			'id': 'string',
			'name': 'string',
			'username': 'string',
			'expiry_date': 'string',
			'uri': 'string',
			'description': 'string',
			'deleted': 'string',
			'created': 'string',
			'modified': 'string'
		}

	});
});

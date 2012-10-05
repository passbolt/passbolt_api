steal('jquery/model').then(function () {
	/*
	 * @class passbolt.model.Resource
	 * @inherits {$.Model}
	 * @parent index
	 * 
	 * The resource model
	 * 
	 * @constructor
	 * Creates a resource
	 * @param {array} options
	 * @return {passbolt.model.Resource}
	 */
	$.Model('passbolt.model.Resource', /** @static */ {

		attributes: {
			'Resource.id': 'string',
			'Resource.name': 'string',
			'Resource.username': 'string',
			'Resource.expiry_date': 'string',
			'Resource.uri': 'string',
			'Resource.description': 'string',
			'Resource.deleted': 'string',
			'Resource.created': 'string',
			'Resource.modified': 'string'
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

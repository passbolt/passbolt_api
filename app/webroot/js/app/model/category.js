steal(MAD_ROOT + '/model').then(function () {

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

		attributes: {
			'id': 'string',
			'parent_id': 'string',
			'lft': 'string',
			'rght': 'string',
			'name': 'string',
			'category_type_id': 'string',
			'children': 'passbolt.model.Category.models'
		},

		create : function (attrs, success, error) {
			var extractedData = {};
			for (var name in attrs) {
				if (typeof attrs[name] == undefined || typeof attrs[name] != 'string') continue;
				extractedData[name] = attrs[name];
			}
			var data = {
				Category: extractedData
			};
			$.post(APP_URL + 'categories', data, success, "json");
		},

//		'save': function (params, success, error) {
//			console.log(this);
//			var url = APP_URL + '/categories/add.json';
//			return mad.net.Ajax.singleton().request({
//				url: url,
//				type: 'post',
//				data: this,
//				success: success,
//				error: error
//			});
//		},

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
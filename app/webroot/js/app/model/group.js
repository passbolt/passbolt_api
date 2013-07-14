steal(
	'jquery/model',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

	/*
	 * @class passbolt.model.Group
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The group model
	 * 
	 * @constructor
	 * Creates a group
	 * 
	 * @param {array} data 
	 * @return {passbolt.model.Group}
	 */
	mad.model.Model('passbolt.model.Group', /** @static */ {

		'validateRules': {
			'name': ['alphanum', 'required']
		},

		attributes: {
			'id': 'string',
			'name': 'string'
		},

		'findAll': function (params, success, error) {
			return mad.net.Ajax.request({
				url: APP_URL + '/groups',
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		}

	}, /** @prototype */ { });
});

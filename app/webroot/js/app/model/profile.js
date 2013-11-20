steal(
	'jquery/model',
	// 'app/model/group.js',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

		/*
		 * @class passbolt.model.Profile
		 * @inherits {mad.model.Model}
		 * @parent index
		 *
		 * The profile model
		 *
		 * @constructor
		 * Creates a profile
		 *
		 * @param {array} data
		 * @return {passbolt.model.Profile}
		 */
		mad.model.Model('passbolt.model.Profile', /** @static */ {

			'validateRules': {
			},

			attributes: {
				'id': 'string',
				'first_name': 'string',
				'last_name': 'string'
			},

			'findAll': function (params, success, error) {
				return mad.net.Ajax.request({
					url: APP_URL + '/profiles',
					type: 'GET',
					params: params,
					success: success,
					error: error
				});
			}

		}, /** @prototype */ { });
	});

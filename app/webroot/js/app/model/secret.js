steal(
	'jquery/model',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

	/*
	 * @class passbolt.model.Secret
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The secret model
	 * 
	 * @constructor
	 * Creates a secret
	 * @param {array} data 
	 * @return {passbolt.model.Resource}
	 */
	mad.model.Model('passbolt.model.Secret', /** @static */ {

		attributes: {
			'id': 'string',
			'data': 'string'
		}

	}, /** @prototype */ { });
});

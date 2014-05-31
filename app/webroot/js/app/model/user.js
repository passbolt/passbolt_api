steal(
	'jquery/model',
	'app/model/profile.js',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

	/*
	 * @class passbolt.model.User
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The user model
	 * 
	 * @constructor
	 * Creates a user
	 * 
	 * @param {array} data 
	 * @return {passbolt.model.User}
	 */
	mad.model.Model('passbolt.model.User', /** @static */ {

		'validateRules': {
			'username': ['email', 'required']
		},

		attributes: {
			'id': 'string',
			'username': 'string',
			'email': 'string',
			'active' : 'string',
			'Profile': 'passbolt.model.Profile.model'
		},

		'create' : function (attrs, success, error) {
			var self = this;
			var params = mad.model.serializer.CakeSerializer.to(attrs, this);
			return mad.net.Ajax.request({
				url: APP_URL + 'users',
				type: 'POST',
				params: params,
				success: success,
				error: error
			}).pipe(function (data, textStatus, jqXHR) {
					// pipe the result to convert cakephp response format into can format
					// else the new attribute are not well placed
					var def = $.Deferred();
					def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);
					return def;
				});
		},

		'destroy' : function (id, success, error) {
			var params = {id:id};
			return mad.net.Ajax.request({
				url: APP_URL + '/users/{id}',
				type: 'DELETE',
				params: params,
				success: success,
				error: error
			});
		},

		'findAll': function (params, success, error) {
			// a filter is provided, format it as GET request parameter
			if(typeof params.filter != 'undefined') {
				var filter = params.filter;
				delete params.filter;
				// add the filter to the request param
				var formattedFilter = filter.toRequest();
				$.extend(params, formattedFilter);
			}

			return mad.net.Ajax.request({
				url: APP_URL + '/users',
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		},

		'update' : function(id, attrs, success, error) {
			var self = this;
			// remove not desired attributes
			delete attrs.created;
			delete attrs.modified;
			// format data as expected by cakePHP
			var params = mad.model.serializer.CakeSerializer.to(attrs, this);
			// add the root of the params, it will be used in the url template
			params.id = id;
			return mad.net.Ajax.request({
				url: APP_URL + 'users/{id}',
				type: 'PUT',
				params: params,
				success: success,
				error: error
			}).pipe(function (data, textStatus, jqXHR) {
				//pipe the result to convert cakephp response format into can format
				var def = $.Deferred();
				def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);
				return def;
			});
		}

	}, /** @prototype */ { });
});

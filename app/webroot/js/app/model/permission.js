steal(
	'jquery/model',
	'app/model/category.js',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

	/*
	 * @class passbolt.model.Permission
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The permission model
	 * 
	 * @constructor
	 * Creates a resource
	 * @param {array} data 
	 * @return {passbolt.model.Permission}
	 */
	mad.model.Model('passbolt.model.Permission', /** @static */ {

		'validateRules': {
			
		},

		attributes: {
			'id': 'string',
			'type': 'string',
			'aco': 'string',
			'aco_foreign_key': 'string',
			'aro': 'string',
			'aro_foreign_key': 'string',
			'PermissionType': 'passbolt.model.PermissionType.models'
		},

		'create' : function (attrs, success, error) {
			var self = this;
			var params = mad.model.serializer.CakeSerializer.to(attrs, this);
			return mad.net.Ajax.request({
				url: APP_URL + '/permissions',
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
				url: APP_URL + '/permissions/{id}',
				type: 'DELETE',
				params: params,
				success: success,
				error: error
			});
		},

		'findAll': function (params, success, error) {
			return mad.net.Ajax.request({
				url: APP_URL + '/permissions',
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		},

		'findOne': function (params, success, error) {
			return mad.net.Ajax.request({
				url: APP_URL + '/permissions/{id}',
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
				url: APP_URL + '/permissions/{id}',
				type: 'PUT',
				params: params,
				success: success,
				error: error
			}).pipe(function (data, textStatus, jqXHR) {
				// pipe the result to convert cakephp response format into can format
				var def = $.Deferred();
				def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);
				return def;
			});
		}

	}, /** @prototype */ {
		
		
		'destroy': function () {
			// @todo unbind the passbolt.model.Permission destroyed event
		}
	});
});

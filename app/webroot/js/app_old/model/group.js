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

		/**
		 * Create a new group
		 * @param {array} attrs Attributes of the new category
		 * @return {jQuery.Deferred)
		 */
		'create' : function (attrs, success, error) {
			var self = this;
			var params = mad.model.serializer.CakeSerializer.to(attrs, this);
			return mad.net.Ajax.request({
				url: APP_URL + 'groups.json',
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

		/**
		 * Find all Groups.
		 * @param params
		 * @param success
		 * @param error
		 * @returns {deferred|*|request|request|request|request}
		 */
		'findAll': function (params, success, error) {
			return mad.net.Ajax.request({
				url: APP_URL + '/groups',
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
				url: APP_URL + 'groups/{id}.json',
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
		},

		/**
		 * Destroy a group following the given parameter
		 * @params {string} id the id of the instance to remove
		 * @return {jQuery.Deferred)
		 */
		'destroy' : function (id, success, error) {
			var params = {id:id};
			return mad.net.Ajax.request({
				url: APP_URL + 'groups/{id}',
				type: 'DELETE',
				params: params,
				success: success,
				error: error
			});
		}

	}, /** @prototype */ { });
});

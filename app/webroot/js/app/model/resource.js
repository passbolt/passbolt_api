import 'app/model/model';
import 'app/model/secret';
import 'mad/model/serializer/cake_serializer';

/**
 * @inherits {passbolt.Model}
 * @parent index
 *
 * The resource model
 *
 * @constructor
 * Creates a resource
 * @param {array} data
 * @return {passbolt.model.Resource}
 */
var Resource = passbolt.model.Resource = passbolt.Model.extend('passbolt.model.Resource', /** @static */ {
	/**
	 * The rules to validate this model are available on the server.
	 */
	checkServerRules: true,

	attributes: {
		id: 'string',
		name: 'string',
		username: 'string',
		uri: 'string',
		created: 'string',
		modified: 'string',
		description: 'string',
		Secret: 'passbolt.model.Secret.models',
		Favorite: 'passbolt.model.Favorite.model',
		Creator: 'passbolt.model.User.model',
		Modifier: 'passbolt.model.User.model',
		UserResourcePermission: 'passbolt.model.UserResourcePermission.model',
		GroupResourcePermission: 'passbolt.model.GroupResourcePermission.model'
	},

	create : function (attrs, success, error) {
		var self = this;
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		return mad.net.Ajax.request({
			url: APP_URL + '/resources',
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

	destroy : function (id, success, error) {
		var params = {id:id};
		return mad.net.Ajax.request({
			url: APP_URL + 'resources/{id}.json',
			type: 'DELETE',
			params: params,
			success: success,
			error: error
		});
	},

	findAll: function (params, success, error) {
		// a filter is provided, format it as GET request parameter
		if(typeof params.filter != 'undefined') {
			var filer = params.filter;
			delete params.filter;
			// add the filter to the request param
			var formatedFilter = filer.toRequest();
			$.extend(params, formatedFilter);
		}

		return mad.net.Ajax.request({
			url: APP_URL + 'resources.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	findOne: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + 'resources/{id}.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	update: function(id, attrs) {
		var self = this,
		// The request parameters.
			params = {};

		// Filter the attributes that need to be send by the request.
		var data = this.filterAttributes(attrs);

		// Format the data as expected by cakePHP
		params = mad.model.serializer.CakeSerializer.to(data, this);

		// Add the id, required by the templated url.
		params.id = id;

		// Send the request to the server.
		return mad.net.Ajax.request({
			url: APP_URL + 'resources/{id}.json',
			type: 'PUT',
			params: params
		}).pipe(function (data, textStatus, jqXHR) {
			// pipe the result to convert cakephp response format into can format
			var def = $.Deferred();
			def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);
			return def;
		});
	},

	/**
	 * @inherited-doc
	 */
	getFilteredFields: function(filteredCase) {
		var filteredFields = false;

		switch(filteredCase) {
			case 'edit':
				filteredFields = [
					'name',
					'username',
					'expiry_date',
					'uri',
					'description'
				];
				break;
			case 'edit_with_secrets':
				filteredFields = [
					'name',
					'username',
					'expiry_date',
					'uri',
					'description',
					'Secret'
				];
				break;
			case 'edit_description':
				filteredFields = [
					'description'
				];
				break;
		}

		return filteredFields;
	}

}, /** @prototype */ {

	/**
	 * Is favorite
	 * @return {boolean}
	 */
	isFavorite: function () {
		if (this.Favorite && this.Favorite.id) {
			return true;
		} else {
			return false;
		}
	},

	destroy: function () {
		// @todo unbind the passbolt.model.Category destroyed event, if it does not done automatically
		this._super();
	}
});

export default Resource;

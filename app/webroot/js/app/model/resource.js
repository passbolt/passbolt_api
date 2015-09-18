import 'mad/model/model';
import 'app/model/category';
import 'app/model/secret';
import 'app/model/item_tag';
import 'mad/model/serializer/cake_serializer';

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The resource model
 *
 * @constructor
 * Creates a resource
 * @param {array} data
 * @return {passbolt.model.Resource}
 */
var Resource = passbolt.model.Resource = mad.Model.extend('passbolt.model.Resource', /** @static */ {
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
		Category: 'passbolt.model.Category.models',
		Secret: 'passbolt.model.Secret.models',
		Favorite: 'passbolt.model.Favorite.model',
		ItemTag: 'passbolt.model.ItemTag.models',
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

	update : function(id, attrs, success, error) {
		var self = this;
		// remove not desired attributes
		delete attrs.created;
		delete attrs.modified;
		// format data as expected by cakePHP
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		// add the root of the params, it will be used in the url template
		params.id = id;
		return mad.net.Ajax.request({
			url: APP_URL + 'resources/{id}.json',
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

	/**
	 * Override the constructor function
	 * Listen change on Category, and update the model when a category has been destroyed
	 */
	init: function () {
		var self = this;

		// Listen when a category is destroyed
		passbolt.model.Category.bind('destroyed', function(ev, category) {
			var destroyedCategories = mad.Model.nestedToList(category, 'children', 'id');
			var toUpdate = false;
			can.each(self.Category, function(resourceCategory, i) {
				if(destroyedCategories.indexOf(resourceCategory.id) != -1) {
					self.Category.splice(i, 1);
					toUpdate = true;
				}
			});
			if (toUpdate) {
				can.trigger(passbolt.model.Resource, 'updated', self);
			}
		});
	},

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

import 'mad/model/model';
import 'mad/model/serializer/cake_serializer';


/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The Category model
 *
 * @constructor
 * Creates a category
 * @param {array} options
 * @return {passbolt.model.Category}
 */
var Category = passbolt.model.Category = mad.Model.extend('passbolt.model.Category', /** @static */ {

/* ************************************************************** */
/* MODEL DEFINITION */
/* ************************************************************** */

	// Store all the instance of this model in the local store.
	forceStore: true,

	validateRules: { },

	attributes: {
		id: 'string',
		parent_id: 'string',
		lft: 'string',
		rght: 'string',
		name: 'string',
		children: 'passbolt.model.Category.models',
		UserCategoryPermission: 'passbolt.model.UserCategoryPermission.model',
		GroupCategoryPermission: 'passbolt.model.GroupCategoryPermission.model'
	},

/* ************************************************************** */
/* CRUD FUNCTION */
/* ************************************************************** */

	/**
	 * Create a new category
	 * @param {array} attrs Attributes of the new category
	 * @return {jQuery.Deferred)
	 */
	create : function (attrs, success, error) {
		var self = this;
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		return mad.net.Ajax.request({
			url: APP_URL + 'categories.json',
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
	 * Destroy a category following the given parameter
	 * @params {string} id the id of the instance to remove
	 * @return {jQuery.Deferred)
	 */
	destroy : function (id, success, error) {
		var params = {id:id};
		return mad.net.Ajax.request({
			url: APP_URL + 'categories/{id}',
			type: 'DELETE',
			params: params,
			success: success,
			error: error
		});
	},

	/**
	 * Find a category following the given parameter
	 * @param {array} params Optional parameters
	 * @return {jQuery.Deferred)
	 */
	findOne: function (params, success, error) {
		params.children = params.children || false;
		return mad.net.Ajax.request({
			url: APP_URL + 'categories/{id}.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	/**
	 * Find a bunch of categories following the given parameters
	 * @param {array} params Optional parameters
	 * @return {jQuery.Deferred)
	 */
	findAll: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + 'categories.json',
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
			url: APP_URL + 'categories/{id}.json',
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
	 * Get children categories
	 * @return {passbolt.model.Category.List}
	 */
	getSubCategories: function () {
		return mad.Model.nestedToList(this, 'children');
	},

	/**
	* Get parent categories
	*
	*/
	getParentCategories: function() {
		var data = [];

		// If a parent category has been found.
		var i = mad.model.List.indexOf(passbolt.model.Category.madStore, this.parent_id);
		if (i != -1) {
			var parentCategory = passbolt.model.Category.madStore[i];

			// Find the parent category of the parent category.
			if (parentCategory.parent_id != null) {
				data = parentCategory.getParentCategories();
			}

			data.push(parentCategory);
		}

		return data;
	}

});

export default Category;
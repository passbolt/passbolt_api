import 'mad/model/model';
import 'mad/model/serializer/cake_serializer';


/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The GroupUser model
 *
 * @constructor
 * Creates a groupUser
 * @param {array} options
 * @return {passbolt.model.GroupUser}
 */
var GroupUser = passbolt.model.GroupUser = mad.Model.extend('passbolt.model.GroupUser', /** @static */ {

	/* ************************************************************** */
	/* MODEL DEFINITION */
	/* ************************************************************** */

	validateRules: { },

	attributes: {
		id: 'string',
		group_id: 'string',
		user_id: 'string',
		User: 'passbolt.model.User.model',
		Group: 'passbolt.model.Group.model'
	},

	membershipType: {
		0 : __('Member'),
		1 : __('Group manager')
	},

	/* ************************************************************** */
	/* CRUD FUNCTION */
	/* ************************************************************** */

	/**
	 * Create a new groupUser
	 * @param {array} attrs Attributes of the new groupUser
	 * @return {jQuery.Deferred)
 	 */
	create : function (attrs, success, error) {
		var self = this;
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		return mad.net.Ajax.request({
			url: APP_URL + 'groupsUsers',
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
	 * Destroy a groupUser following the given parameter
	 * @params {string} id the id of the instance to remove
	 * @return {jQuery.Deferred)
	 */
	destroy : function (id, success, error) {
		var params = {id:id};
		return mad.net.Ajax.request({
			url: APP_URL + 'groupsUsers/{id}',
			type: 'DELETE',
			params: params,
			success: success,
			error: error
		});
	},

	/**
	 * Find a groupUser following the given parameter
	 * @param {array} params Optional parameters
	 * @return {jQuery.Deferred)
 	 */
	findOne: function (params, success, error) {
		params.children = params.children || false;
		return mad.net.Ajax.request({
			url: APP_URL + 'groupsUsers/{id}.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	/**
	 * Find a bunch of groupsUsers following the given parameters
	 * @param {array} params Optional parameters
	 * @return {jQuery.Deferred)
 	 */
	findAll: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + 'groupsUsers.json',
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
			url: APP_URL + 'groupsUsers/{id}',
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

});

export default GroupUser;

import 'mad/model/model';
import 'app/model/profile';
import 'app/model/image_storage';
import 'mad/model/serializer/cake_serializer';

/**
 * @inherits {mad.Model}
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
var User = passbolt.model.User = mad.Model.extend('passbolt.model.User', /** @static */ {

	/**
	 * Stores the current user. (the one logged in).
	 */
	current : null,

    /**
     * Get validation rules from server.
     */
    checkServerRules: true,

	/**
	 * Attributes.
	 */
	attributes: {
		id: 'string',
		username: 'string',
		email: 'string',
		role_id: 'string',
		active: 'string',
        last_logged_in: 'string',
		Profile: 'passbolt.model.Profile.model',
		GroupUser: 'passbolt.model.GroupUser.models'
	},

	getCurrent : function() {
		return passbolt.model.User.current;
	},

	setCurrent : function(user) {
		passbolt.model.User.current = user;
	},

	create : function (attrs, success, error) {
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

	destroy : function (id, success, error) {
		var params = {id:id};
		return mad.net.Ajax.request({
			url: APP_URL + '/users/{id}',
			type: 'DELETE',
			params: params,
			success: success,
			error: error
		});
	},

	findAll: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + '/users.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	findOne: function (params, success, error) {
		var async = true;
		if (typeof params['async'] != 'undefined') {
			async = params['async'];
		}
		return mad.net.Ajax.request({
			url: APP_URL + 'users/{id}.json',
			type: 'GET',
			params: params,
			success: success,
			error: error,
			async: async
		});
	},

	update : function(id, attrs, success, error) {
		var self = this;

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
	},

	updateAvatar : function(attrs, success, error) {
		var self = this;

		// Build the params.
		var params = new FormData();
		params.append('file-0', attrs.newAvatar);
		params.id = attrs['id'];

		return mad.net.Ajax.request({
			url: APP_URL + 'users/avatar/{id}',
			type: 'POST',
			cache: false,
			contentType: false,
			processData: false,
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
	 * Save a new user avatar.
	 * @param file The new avatar file.
	 * @return {can.Deferred}
	 */
	saveAvatar: function(file) {
		// Custom update.
		// Use the makeRequest operation of Can to support the local object update
		// feature and its events system which is awesome.
		this.attr('newAvatar', file);
		var def = can.Model._makeRequest(this, 'updateAvatar', null, null, 'updated');
		this.attr('newAvatar', null);
		return def;
	},

	/**
	 * Attempt a dry run of delete.
	 *
	 * @param id
	 * @param attrs
	 * @param success
	 * @param error
	 * @returns {*|jQuery.deferred}
	 */
	deleteDryRun : function(id, attrs, success, error) {
		var params = {id:id};
		return mad.net.Ajax.request({
			url: APP_URL + 'users/{id}/dry-run.json',
			type: 'DELETE',
			params: params,
			success: success,
			error: error,
			silentNotify: true
		});
	}

});

export default User;

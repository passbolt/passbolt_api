import 'mad/model/model';
import 'app/model/permission_type';
import 'app/model/group_resource_permission';
import 'app/model/user_resource_permission';
import 'mad/model/serializer/cake_serializer';

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The permission model
 *
 * @constructor
 * Creates a resource
 * @param {array} data
 * @return {passbolt.model.Permission}
 */
var Permission = passbolt.model.Permission = mad.Model.extend('passbolt.model.Permission', /** @static */ {

	validateRules: {
		// 'aco_foreign_key': ['required', 'uid'],
		aro_foreign_key: ['required', 'uid'],
		aro_foreign_label: ['required'],
		type: [
			'required',
			{
				rule: 'foreignRule',
				options: {
					model: passbolt.model.PermissionType,
					attribute: 'serial'
				}
			}
		]
	},

	attributes: {
		id: 'string',
		type: 'string',
		aco: 'string',
		aco_foreign_key: 'string',
		aro: 'string',
		aro_foreign_key: 'string',
		aro_foreign_label: 'string',
		PermissionType: 'passbolt.model.PermissionType.model',
		Resource: 'passbolt.model.Resource.model',
		User: 'passbolt.model.User.model',
		Group: 'passbolt.model.Group.model'
	},

	create : function (attrs, success, error) {
		var self = this;
		// build the uri functions of the aco instance
		var uri = 'permissions/' + attrs['aco'].toLowerCase() + '/' + attrs['aco_foreign_key'];
		delete attrs['aco'], attrs['aco_foreign_key'];
		// format the data to send to be understable by cake
		var params = mad.model.serializer.CakeSerializer.to(attrs, this);
		// call the server
		return mad.net.Ajax.request({
			url: APP_URL + uri,
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
			url: APP_URL + 'permissions/{id}.json',
			type: 'DELETE',
			params: params,
			success: success,
			error: error
		});
	},

	findAll: function (params, success, error) {
		var uri = 'permissions';
		if(typeof params.aco != 'undefined' && typeof params.aco_foreign_key != 'undefined') {
			uri += '/' + params.aco.toLowerCase() + '/' + params.aco_foreign_key;
		}
		return mad.net.Ajax.request({
			url: APP_URL + uri,
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	findOne: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + '/permissions/{id}',
			type: 'GET',
			params: params,
			success: success,
			error: error
		});
	},

	update: function(id, attrs, success, error) {
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
	},

	share: function(aco, acoForeignKey, attrs, success, error) {
		var self = this;

		return mad.net.Ajax.request({
			url: APP_URL + 'share/' + aco + '/' + acoForeignKey + '.json',
			type: 'PUT',
			params: attrs,
			success: success,
			error: error
		}).pipe(function (data, textStatus, jqXHR) {
			// Pipe the result to convert CakePHP response format into can format.
			var def = $.Deferred();

			// If the user still have permission to the resource, the updated resource is sent
			// by the server while updating the permissions.
			if (data.acoInstance != null) {
				// Make a passbolt.model.Resource with the resource sent by the server.
				var resource = passbolt.model.Resource.model(mad.model.serializer.CakeSerializer.from(data.acoInstance, passbolt.model.Resource));
				// Trigger a change on the retrieved resource, to notify all components that something changed on the resource.
				resource.updated();
			}
			// If the resource is not there, that means the user doesn't have access to this resource
			// anymore. Delete it locally, all components should be notified by this change and update themselves.
			else {
				var storedResource = passbolt.model.Resource.store[acoForeignKey];
				// Mark it as destroyed should trigger the removed event.
				storedResource.destroyed();
			}

			// Resolve the deferred.
			def.resolveWith(this, [mad.model.serializer.CakeSerializer.from(data, self)]);

			return def;
		});
	},

	/**
	 * Search the users who can receive a direct permission for a target permissionnable
	 * instance.
	 *
	 * As per the requirements a user can receive only one direct permission by permissionnable
	 * instance.
	 *
	 * @param params
	 * @param success
	 * @param error
	 * @returns {*}
	 */
	searchUsers: function (params, success, error) {
		return mad.net.Ajax.request({
			url: APP_URL + '/share/search-users/{model}/{id}.json',
			type: 'GET',
			params: params,
			success: success,
			error: error
		}).pipe(function (data, textStatus, jqXHR) {
			// pipe the result to convert cakephp response format into can format
			var def = $.Deferred();
			def.resolveWith(this, [passbolt.model.User.models(data)]);
			return def;
		});
	},

	/**
	 * Am I authorized to perform the given operation.
	 * @param {passbolt.model.PermissionType} type
	 */
	isAllowedTo: function(objs, requestedPermission) {
		var permission = null;
		var returnValue = null;

		if (!(objs instanceof can.Model.List)) {
			objs = new can.List([objs])
		}

		objs.each(function(obj, i) {
			// The asked permission has to be lower than all the defined permissions
			// on the target objects.
			if (returnValue == false) {
				return;
			}

			// Extract the permission.
			switch(obj.constructor.shortName) {
				case 'Resource':
					if (typeof obj.UserResourcePermission != 'undefined') {
						permission = obj.UserResourcePermission;
					} else if (typeof obj.GroupResourcePermission != 'undefined') {
						permission = obj.GroupResourcePermission;
					}
					break;
			}

			if (permission.permission_type >= requestedPermission) {
				returnValue = true;
			} else {
				returnValue = false;
			}
		});

		return returnValue != null ? returnValue : false;
	}

}, /** @prototype */ {

	/**
	 * Check if the permission is a direct permission for the given aco and aro instances.
	 * @param {mad.Model} obj The target instance to test if the permission is direct for it. The instance
	 * can be of whatever type (ex: Resource)
	 * @return {boolean}
	 */
	isDirect: function(acoInstance) {
		var permAcoModel = can.getObject('passbolt.model.' + this.aco);
		if(acoInstance instanceof permAcoModel && acoInstance.id === this.aco_foreign_key) {
			return true;
		}
		return false;
	}
});

export default Permission;


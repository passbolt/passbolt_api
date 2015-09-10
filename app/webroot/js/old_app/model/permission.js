steal(
	'jquery/model',
	'app/model/category.js',
	'app/model/permissionType.js',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

	/*
	 * @class passbolt.model.UserCategoryPermission
	 * @inherits {mad.model.Model}
	 * @parent index
	 *
	 * User category permission model
	 *
	 * @constructor
	 * Creates a resource
	 * @param {array} data
	 * @return {passbolt.model.UserCategoryPermission}
	 */
	mad.model.Model('passbolt.model.UserCategoryPermission', /** @static */ {
		attributes: {
			'permission_id': 'string',
			'permission_type': 'number'
		}
	}, {});

	/*
	 * @class passbolt.model.GroupCategoryPermission
	 * @inherits {mad.model.Model}
	 * @parent index
	 *
	 * Group category permission model
	 *
	 * @constructor
	 * Creates a resource
	 * @param {array} data
	 * @return {passbolt.model.GroupCategoryPermission}
	 */
	mad.model.Model('passbolt.model.GroupCategoryPermission', /** @static */ {
		attributes: {
			'permission_id': 'string',
			'permission_type': 'number'
		}
	}, {});

	/*
	 * @class passbolt.model.UserResourcePermission
	 * @inherits {mad.model.Model}
	 * @parent index
	 *
	 * User resource permission model
	 *
	 * @constructor
	 * Creates a resource
	 * @param {array} data
	 * @return {passbolt.model.UserResourcePermission}
	 */
	mad.model.Model('passbolt.model.UserResourcePermission', /** @static */ {
		attributes: {
			'permission_id': 'string',
			'permission_type': 'number'
		}
	}, {});

	/*
	 * @class passbolt.model.GroupResourcePermission
	 * @inherits {mad.model.Model}
	 * @parent index
	 *
	 * Group resource permission model
	 *
	 * @constructor
	 * Creates a resource
	 * @param {array} data
	 * @return {passbolt.model.GroupResourcePermission}
	 */
	mad.model.Model('passbolt.model.GroupResourcePermission', /** @static */ {
		attributes: {
			'permission_id': 'string',
			'permission_type': 'number'
		}
	}, {});

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
			// 'aco_foreign_key': ['required', 'uid'],
			'aro_foreign_key': ['required', 'uid'],
			'aro_foreign_label': ['required'],
			'type': [
				'required', 
				{
					'rule': 'foreignRule',
					'options': {
						'model': passbolt.model.PermissionType,
						'attribute': 'serial'
					}
				}
			]
		},

		attributes: {
			'id': 'string',
			'type': 'string',
			'aco': 'string',
			'aco_foreign_key': 'string',
			'aro': 'string',
			'aro_foreign_key': 'string',
			'aro_foreign_label': 'string',
			'PermissionType': 'passbolt.model.PermissionType.model',
			'Resource': 'passbolt.model.Resource.model',
			'Category': 'passbolt.model.Category.model',
			'User': 'passbolt.model.User.model',
			'Group': 'passbolt.model.Group.model'
		},

		'create' : function (attrs, success, error) {
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

		'destroy' : function (id, success, error) {
			var params = {id:id};
			return mad.net.Ajax.request({
				url: APP_URL + 'permissions/{id}.json',
				type: 'DELETE',
				params: params,
				success: success,
				error: error
			});
		},

		'findAll': function (params, success, error) {
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

		'findOne': function (params, success, error) {
			return mad.net.Ajax.request({
				url: APP_URL + '/permissions/{id}',
				type: 'GET',
				params: params,
				success: success,
				error: error
			});
		},

		'update': function(id, attrs, success, error) {
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

		'share': function(aco, acoForeignKey, attrs, success, error) {
			var self = this;
			// format data as expected by cakePHP
			//var params = mad.model.serializer.CakeSerializer.to(attrs, this);
			// add the root of the params, it will be used in the url template
			//params.id = id;
			return mad.net.Ajax.request({
				url: APP_URL + 'share/' + aco + '/' + acoForeignKey + '.json',
				type: 'PUT',
				params: attrs,
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
		 * Am I authorized to perform the given operation.
		 * @param {passbolt.model.PermissionType} type
		 */
		'isAllowedTo': function(objs, requestedPermission) {
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
					case 'Category':
						if (typeof obj.UserCategoryPermission != 'undefined') {
							permission = obj.UserCategoryPermission;
						} else if (typeof obj.GroupCategoryPermission != 'undefined') {
							permission = obj.GroupCategoryPermission;
						}
						break;
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
		 * @param {mad.model.Model} obj The target instance to test if the permission is direct for it. The instance
		 * can be of whatever type (Resource, Category ...)
		 * @return {boolean}
		 */
		'isDirect': function(acoInstance) {
			var permAcoModel = can.getObject('passbolt.model.' + this.aco);
			if(acoInstance instanceof permAcoModel && acoInstance.id === this.aco_foreign_key) {
				return true;
			}
			return false;
		}
	});
});

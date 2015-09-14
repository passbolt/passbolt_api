import 'mad/model/model';
import 'app/model/category';
import 'mad/model/serializer/cake_serializer';


/*
 * Passbolt permission constants.
 */
passbolt.DENY 	= 0;
passbolt.READ 	= 1;
passbolt.CREATE = 3;
passbolt.UPDATE = 7;
passbolt.ADMIN 	= 15;

/**
 * @inherits {mad.model.Model}
 * @parent index
 *
 * The permission model
 *
 * @constructor
 * Creates a resource
 * @param {array} data
 * @return {passbolt.model.PermissionType}
 */
var PermissionType = passbolt.model.PermissionType = mad.model.Model.extend('passbolt.model.PermissionType', /** @static */ {

	validateRules: {
		serial: [
			{
				rule: 'choice',
				options: {
					callback: function() {
						// return the available serials (array_keys in js style)
						return $.map(passbolt.model.PermissionType.PERMISSION_TYPES, function(element,index) {return index});
					}
				}
			}
		]
	},

	attributes: {
		serial: 'string',
		name: 'string',
		binary: 'string',
		_admin: 'boolean',
		_update: 'boolean',
		_create: 'boolean',
		_read: 'boolean',
		description: 'string'
	},

	PERMISSION_TYPES: {
		0: __('deny'),
		1: __('read'),
		3: __('create'),
		7: __('update'),
		15: __('owner')
	},

	/**
	 * Get permission type formated.
	 * @return {string}
	 */
	toString: function(permId) {
		var returnValue = '';
		switch (permId) {
			case passbolt.DENY
				.toString():
				returnValue = this.PERMISSION_TYPES[permId];
				break;
			case passbolt.ADMIN
				.toString():
				returnValue = __('is %s', this.PERMISSION_TYPES[permId]);
				break;
			default:
				returnValue = __('can %s', this.PERMISSION_TYPES[permId]);
				break;
		}
		return returnValue;
	},

	/**
	 * Get the list of permission type.
	 * @param {string} foreignModel (optional) Filter permission types by foreign model.
	 * @return {array}
	 */
	getPermissionTypes: function(foreignModel) {
		var returnValue = [];

		// @todo [low] Make something generic and configurable.
		var allowedPermissions = {
			'Group': [0,1,3,7,15],
			'User': [0,1,7,15]
		};

		if (typeof foreignModel != 'undefined') {
			for (var permType in allowedPermissions[foreignModel]) {
				returnValue[permType] = passbolt.model.PermissionType.PERMISSION_TYPES[permType];
			}
		} else {
			returnValue = passbolt.model.PermissionType.PERMISSION_TYPES;
		}

		return returnValue;
	}

}, /** @prototype */ {

	toString: function(format) {
		var returnValue;
		switch(format) {
			case 'long':
				returnValue = passbolt.model.PermissionType.PERMISSION_TYPES[this.serial];
				if(this.serial !== passbolt.DENY.toString() && this.serial != passbolt.ADMIN.toString()) {
					returnValue = __('can %s', returnValue);
				}
				break;

			case 'short':
			default:
				returnValue = passbolt.model.PermissionType.PERMISSION_TYPES[this.serial];
		}
		return returnValue;
	},

	destroy: function () {
		// @todo unbind the passbolt.model.Category destroyed event
	}
});

export default PermissionType;

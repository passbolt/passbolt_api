import 'mad/model/model';
import 'mad/model/serializer/cake_serializer';

/*
 * Passbolt permission types.
 */
passbolt.READ 	= 1;
passbolt.UPDATE = 7;
passbolt.ADMIN 	= 15;

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The permission model
 *
 * @constructor
 * Creates a resource
 * @param {array} data
 * @return {passbolt.model.PermissionType}
 */
var PermissionType = passbolt.model.PermissionType = mad.Model.extend('passbolt.model.PermissionType', /** @static */ {

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
		description: 'string'
	},

	PERMISSION_TYPES: {
		1: __('read'),
		7: __('update'),
		15: __('owner')
	},

	/**
	 * Get permission type formated.
	 * @return {string}
	 */
	formatToString: function(permId) {
		var returnValue = '';
		if (permId == undefined) {
			console.error('Warning, PermissionType.toString called without permId');
			return 'can read';
		}
		switch (permId.toString()) {
			case passbolt.ADMIN.toString():
				returnValue = __('is %s', this.PERMISSION_TYPES[permId]);
				break;
			default:
				returnValue = __('can %s', this.PERMISSION_TYPES[permId]);
				break;
		}
		return returnValue;
	}

}, /** @prototype */ {

	toString: function(format) {
		var returnValue;
		switch(format) {
			case 'long':
				returnValue = passbolt.model.PermissionType.PERMISSION_TYPES[this.serial];
				if(this.serial != passbolt.ADMIN.toString()) {
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
		// @todo unbind the passbolt.model.PermissionType destroyed event
	}
});

export default PermissionType;

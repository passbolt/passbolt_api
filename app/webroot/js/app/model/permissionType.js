steal(
	'jquery/model',
	'app/model/category.js',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

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
	mad.model.Model('passbolt.model.PermissionType', /** @static */ {

		'validateRules': {
			
		},

		attributes: {
			'serial': 'string',
			'name': 'string',
			'binary': 'string',
			'_admin': 'boolean',
			'_update': 'boolean',
			'_create': 'boolean',
			'_read': 'boolean',
			'description': 'string'
		},

		PERMISSION_TYPES: {
			0: __('deny'),
			1: __('read'),
			3: __('create'),
			7: __('update'),
			15: __('admin')
		}

	}, /** @prototype */ {

		'toString': function(format) {
			var returnValue;
			switch(format) {
				case 'long':
					returnValue = passbolt.model.PermissionType.PERMISSION_TYPES[this.serial];
					if(this.serial !== '0') {
						returnValue = __('can %s', returnValue);
					}
					break;

				case 'short':
				default:
					returnValue = passbolt.model.PermissionType.PERMISSION_TYPES[this.serial];
			}
			return returnValue;
		},

		'destroy': function () {
			// @todo unbind the passbolt.model.Category destroyed event
		}
	});
});

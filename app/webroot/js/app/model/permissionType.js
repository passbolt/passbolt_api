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
			'serial': 'integer',
			'name': 'string',
			'binary': 'string',
			'_admin': 'boolean',
			'_update': 'boolean',
			'_create': 'boolean',
			'_read': 'boolean',
			'description': 'string'
		},
	}, /** @prototype */ {
		
		'format': function(format) {
			switch(format) {
				case 'short':
					console.log(this);
					return 'can edit';
				break;
			}
		},
		
		'destroy': function () {
			// @todo unbind the passbolt.model.Category destroyed event
		}
	});
});

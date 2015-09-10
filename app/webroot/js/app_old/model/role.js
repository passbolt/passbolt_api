steal(
	'jquery/model',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

		/*
		 * @class passbolt.model.Role
		 * @inherits {mad.model.Model}
		 * @parent index
		 *
		 * The role model
		 *
		 * @constructor
		 * Creates a role
		 * @param {array} data
		 * @return {passbolt.model.Role}
		 */
		mad.model.Model('passbolt.model.Role', /** @static */ {

			attributes: {
				'id': 'string',
				'name': 'string'
			},

			ROLE_TYPES: {
				'user' : '',
				'admin' : '',
				'root' : ''
			},

			/**
			 * Get role name from id.
			 * @return {string}
			 */
			'toString': function(roleId) {
				var returnValue = '';
				for (i in cakephpConfig.roles) {
					if (roleId == cakephpConfig.roles[i]) {
						return i;
					}
				}
				return returnValue;
			},

			/**
			 * Get role id from name.
			 * @param roleName
			 */
			'toId': function(roleName) {
				var returnValue = '';
				if (cakephpConfig.roles[roleName] != undefined) {
					return cakephpConfig.roles[roleName];
				}
				return returnValue;
			}

		}, /** @prototype */ {

		});
	});

steal(
	'jquery/model',
	'app/model/imageStorage.js',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

		/*
		 * @class passbolt.model.Profile
		 * @inherits {mad.model.Model}
		 * @parent index
		 *
		 * The profile model
		 *
		 * @constructor
		 * Creates a profile
		 *
		 * @param {array} data
		 * @return {passbolt.model.Profile}
		 */
		mad.model.Model('passbolt.model.Profile', /** @static */ {

			'validateRules': {
			},

			attributes: {
				'id': 'string',
				'first_name': 'string',
				'last_name': 'string',
				'Avatar': 'passbolt.model.ImageStorage.model'
			},

			'findAll': function (params, success, error) {
				return mad.net.Ajax.request({
					url: APP_URL + '/profiles',
					type: 'GET',
					params: params,
					success: success,
					error: error
				});
			}

		}, /** @prototype */ {

			/**
			 * Get the avatar image path
			 * @param {string} version (optional) The version to get
			 * @return {string} The image path
			 */
			'avatarPath': function(version) {
				if (typeof this.Avatar != 'undefined' && this.Avatar.url != undefined) {
					return this.Avatar.imagePath(version);
				} else {
					return "img/avatar/user.png";
				}
			},

			/**
			 * Return the user full name.
			 * @returns {string}
			 */
			'fullName': function() {
				return this.first_name + ' ' + this.last_name;
			}
		});
	});

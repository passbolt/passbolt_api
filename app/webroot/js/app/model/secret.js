steal(
	'jquery/model',
	'mad/model/serializer/cakeSerializer.js',
	'app/model/secretStrength.js'
).then(function () {

	/*
	 * @class passbolt.model.Secret
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The secret model
	 * 
	 * @constructor
	 * Creates a secret
	 * @param {array} data 
	 * @return {passbolt.model.Secret}
	 */
	mad.model.Model('passbolt.model.Secret', /** @static */ {

		attributes: {
			'id': 'string',
			'data': 'string'
		},

		/**
		 * Get the entropy level functions of the entropy bits
		 * @param {int} entropy The entropy bits
		 * @return {string} The entropy level VERY_WEAK, WEAK, MODERATE, STRONG, VERY_STRONG
		 */
		entropyLevel: function(entropy) {
			var returnValue = null;
			for(var level in passbolt.model.Secret.ENTROPY) {
				if(entropy >= passbolt.model.Secret.ENTROPY[level].start) {
					returnValue = level;
				}
			}
			return returnValue;
		},

		/**
		 * Mesure the entropy of a password, following the mathematical rule Entropy = Pwd Length 
		 * @param {srtring} pwd The password to test the entropy
		 * @return {int}
		 */
		entropy: function(pwd) {
			var pwdLen = pwd.length;
			var pwdMasksSize = 0;
			for (var i in passbolt.model.Secret._masks) {
				if(pwd.match(passbolt.model.Secret._masks[i].pattern)) {
					pwdMasksSize += passbolt.model.Secret._masks[i].size;
				}
			}
			var entropy = pwdLen * (Math.log(pwdMasksSize) / Math.log(2));
			return entropy;
		}

	}, /** @prototype */ { });
});

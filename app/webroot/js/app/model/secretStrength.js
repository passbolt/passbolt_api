steal(
	'jquery/model'
).then(function () {

	/**
	 * Secret entropy levels
	 */
	var STRENGTH = {
		VERY_WEAK: {
			start: 0,
			id: 'very_weak',
			label: __('very weak')
		},
		WEAK: {
			start: 60,
			id: 'weak',
			label: __('weak')
		},
		FAIR: {
			start: 80,
			id: 'fair',
			label: __('fair')
		},
		STRONG: {
			start: 112,
			id: 'strong',
			label: __('strong')
		},
		VERY_STRONG: {
			start: 128,
			id: 'very_strong',
			label: __('very strong')
		},
	};

	/*
	 * @class passbolt.model.SecretStrength
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The SecretStrength model
	 */
	mad.model.Model('passbolt.model.SecretStrength', /** @static */ {

		attributes: {
			'id': 'string',
			'label': 'string',
			'start': 'int'
		},

		/**
		 * Get the entropy level functions of the entropy bits
		 * @param {string} pwd The secret to get the strenght for
		 * @return {string} The entropy level VERY_WEAK, WEAK, MODERATE, STRONG, VERY_STRONG
		 */
		getSecretStrength: function(pwd) {
			var returnValue = null;
			
			if(!pwd) {
				return returnValue;
			}
			
			// Mesure the entropy of the password
			var entropy = passbolt.model.SecretStrength.getEntropy(pwd);

			// Functions of the entropy return secret strength information
			for(var level in STRENGTH) {
				if(entropy >= STRENGTH[level].start) {
					returnValue = new passbolt.model.SecretStrength(STRENGTH[level]);
				} else {
					break;
				}
			}
			return returnValue;
		},

		/**
		 * Mesure the entropy of a password, following the mathematical rule Entropy = Pwd Length 
		 * @param {srtring} pwd The password to test the entropy
		 * @return {int}
		 */
		getEntropy: function(pwd) {
			var pwdLen = pwd.length;
			var pwdMasksSize = 0;
			for (var i in passbolt.model.Secret.MASKS) {
				if(pwd.match(passbolt.model.Secret.MASKS[i].pattern)) {
					pwdMasksSize += passbolt.model.Secret.MASKS[i].size;
				}
			}	
			var entropy = pwdLen * (Math.log(pwdMasksSize) / Math.log(2));
			return entropy;
		}

	}, /** @prototype */ { });
});

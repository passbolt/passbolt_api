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


	/**
	 * The different masks used to mesure the entropy
	 */
	var ENTROPY_MASK = {
		'alpha': {
			size:26,
			data: 'abcdefghijklmnopqrstuvwxyz',
			pattern: /[a-z]/
		},
		'uppercase': {
			size:26, 
			data: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			pattern: /[A-Z]/
		},
		'digit': {
			size:10, 
			data: '0123456789',
			pattern: /[0-9]/
		},
		'special': {
			size:32,
			// ASCII Code = 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 58, 59, 60, 61, 62, 63, 64, 91, 92, 93, 94, 95, 96, 123, 124, 125, 126
			data: '!"#$%&\'()*+,-./:;<=>?@[\]^_`{|}~',
			pattern: /[!"#$%&\'\(\)*+,\-./:;<=>?@\[\\\]^_`{|}~]/
		}
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
			for (var i in ENTROPY_MASK) {
				if(pwd.match(ENTROPY_MASK[i].pattern)) {
					pwdMasksSize += ENTROPY_MASK[i].size;
				}
			}
			var entropy = pwdLen * (Math.log(pwdMasksSize) / Math.log(2));
			return entropy;
		}

	}, /** @prototype */ { });
});

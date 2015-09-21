import 'mad/model/model';


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
	}
};

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The SecretStrength model
 */
var SecretStrength = passbolt.model.SecretStrength = mad.Model.extend('passbolt.model.SecretStrength', /** @static */ {

	attributes: {
		id: 'string',
		label: 'string',
		start: 'int'
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
		var entropy = passbolt.model.SecretStrength.mesurePwdEntropy(pwd);

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
	 * Calculate the entropy functions of the given primitive
	 * @param {int} length The number of characters
	 * @param {int} maskSize The number of possibility for each character
	 * @return {int}
	 */
	mesureEntropy: function(length, maskSize) {
		return length * (Math.log(maskSize) / Math.log(2));
	},

	/**
	 * Mesure the entropy of a password
	 * @param {srtring} pwd The password to test the entropy
	 * @return {int}
	 */
	mesurePwdEntropy: function(pwd) {
		var maskSize = 0;
		for (var i in passbolt.model.Secret.MASKS) {
			if(pwd.match(passbolt.model.Secret.MASKS[i].pattern)) {
				maskSize += passbolt.model.Secret.MASKS[i].size;
			}
		}
		return passbolt.model.SecretStrength.mesureEntropy(pwd.length, maskSize);
	}

}, /** @prototype */ { });

export default SecretStrength;

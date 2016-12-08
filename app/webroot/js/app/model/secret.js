import 'mad/model/model';
import 'mad/model/serializer/cake_serializer';
import 'app/model/secret_strength';


/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The secret model
 *
 * @constructor
 * Creates a secret
 * @param {array} data
 * @return {passbolt.model.Secret}
 */
var Secret = passbolt.model.Secret = mad.Model.extend('passbolt.model.Secret', /** @static */ {

	attributes: {
		'id': 'string',
		'data': 'string'
	},

	// The masks used to generate a password
	MASKS: {
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
			data: '!"#$%&\'()*+,-./:;<=>?@[:]^_`{|}~',
			pattern: /[!"#$%&\'\(\)*+,\-./:;<=>?@\[\]^_`{|}~]/
		}
	},

	/**
	 * Generate a password following the system configuration
	 * @return {string}
	 */
	generate: function() {
		var secret = '',
			secretMasks = mad.Config.read('secret.generator.masks'),
			secretLength = mad.Config.read('secret.generator.length'),
			mask = [],
			expectedEntropy;

		// build the mask to use to generate a pwd
		for (var i in secretMasks) {
			mask = $.merge(mask, passbolt.model.Secret.MASKS[secretMasks[i]].data);
		}

		// Calculate the expected entropy
		// expectedEntropy = passbolt.model.SecretStrength.mesureEntropy(secretLength, mask.length)
		// generate a pwd
		var j = 0;
		do {
			secret = '';
			expectedEntropy = passbolt.model.SecretStrength.mesureEntropy(secretLength, mask.length)
			for (var i=0; i<secretLength; i++) {
				secret += mask[Math.randomRange(0, mask.length-1)];
			}
		} while (expectedEntropy != passbolt.model.SecretStrength.mesurePwdEntropy(secret) && j<10	);

		return secret;
	}

}, /** @prototype */ { });

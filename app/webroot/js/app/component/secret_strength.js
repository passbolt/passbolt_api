import 'mad/component/component';
import 'app/model/secret';
import 'app/model/secret_strength';

import 'app/view/template/component/secret/secret_strength.ejs!';

/*
 * @class passbolt.controller.SecretStrengthController
 * @inherits mad.controller.component.ComponentController
 * @parent index
 *
 * @constructor
 * Creates a new Secret Strength Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.controller.SecretStrengthController}
 */
var SecretStrength = passbolt.component.SecretStrength = mad.Component.extend('passbolt.component.SecretStrength', /** @static */ {

	defaults: {
		label: 'Secret Strength Controller',
		templateBased: true,
		templateUri: 'app/view/template/component/secret/secret_strength.ejs',
		secretStrength: null
	}

}, /** @prototype */ {

	beforeRender: function () {
		this._super();

		var strengthLabel = '',
			strengthId = '';

		if (this.options.secretStrength) {
			strengthLabel = this.options.secretStrength.label;
			strengthId = this.options.secretStrength.id;
		}

		this.setViewData('strengthLabel', strengthLabel);
		this.setViewData('strengthId', strengthId);
	},

	/**
	 * Load a new secret strength
	 * @param {Object} secretStrength
	 */
	load: function (secretStrength) {
		this.options.secretStrength = secretStrength;
		this.refresh();
	}

});

export default SecretStrength;

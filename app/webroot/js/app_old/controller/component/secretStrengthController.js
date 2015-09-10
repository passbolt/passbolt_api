steal(
	'app/model/secret.js',
	'app/model/secretStrength.js',
	'app/view/template/component/secret/secretStrength.ejs'
).then(function () {

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
	mad.controller.ComponentController.extend('passbolt.controller.component.SecretStrengthController', /** @static */ {

		'defaults': {
			'label': 'Secret Strength Controller',
			'templateBased': true,
			'templateUri': 'app/view/template/component/secret/secretStrength.ejs',
			'secretStrength': null
		}

	}, /** @prototype */ {
		
		'beforeRender': function() {
			this._super();
			
			var strengthLabel = '',
				strengthId = '';

			if(this.options.secretStrength) {
				strengthLabel = this.options.secretStrength.label;
				strengthId = this.options.secretStrength.id;
			}

			this.setViewData('strengthLabel', strengthLabel);
			this.setViewData('strengthId', strengthId);
		},

		/**
		 * Load a new secret strength 
 		 * @param {Object} secretStrength
 		 * @return {void}
		 */
		'load': function(secretStrength) {
			this.options.secretStrength = secretStrength;
			this.refresh();
		}
		
	});

});
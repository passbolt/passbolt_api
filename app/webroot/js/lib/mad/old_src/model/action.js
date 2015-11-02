steal(
	'mad/model',
	'mad/model/state.js'
).then(function () {

	/*
	 * @class mad.model.Action
	 * @inherits mad.model.Model
	 * @parent mad.controller.component
	 * 
	 * The Action model will carry actions used by other component
	 * 
	 * @constructor
	 * Creates a new Action
	 * @param {array} options
	 * @return {mad.model.Action}
	 */
	mad.model.Model('mad.model.Action', /** @static */ {

		/**
		 * Define attributes of the model
		 * @type {Object}
		 */
		attributes: {
			'id': 'string',
			'label': 'string',
			'name': 'string',
			'icon': 'string',
			'action': 'function',
			'cssClasses': 'array',
			'initial_state': 'string',
			'state': mad.model.State.model,
			'active': 'boolean'
		}

	}, /** @prototype */ {

		// Constructor like.
		'init': function() {
			// Initialize the associated state instance. Byt default use the stateName defined in
			// the options.state
			if (typeof this.initial_state == 'undefined') {
				this.initial_state = 'ready';
			}
			this.state = new mad.model.State();
			this.state.setState(this.initial_state);

			// Instantiate the css classes variables if it has not been defined.
			// As the action is massively used by the
			if (typeof this.cssClasses == 'undefined' || this.cssClasses == null) {
				this.cssClasses = [];
			}
		},

		/**
		 * Get the associated action
		 * @return {function}
		 */
		'getAction': function () {
			return (typeof this.action != 'undefined') ? this.action : null;
		}

	});

});

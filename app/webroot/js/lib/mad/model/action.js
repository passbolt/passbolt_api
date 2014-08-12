steal(
	'mad/model'
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
			'action': 'string',
			'active': 'boolean'
		}

	}, /** @prototype */ {
		
		/**
		 * Get the associated action
		 * @return {function}
		 */
		'getAction': function () {
			return (typeof this.action != 'undefined') ? this.action : null;
		}
		
	});

});

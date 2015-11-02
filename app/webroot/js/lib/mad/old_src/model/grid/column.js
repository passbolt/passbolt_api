steal(
	'mad/model'
).then(function () {

	/*
	 * @class mad.model.grid.Column
	 * @inherits mad.model.Model
	 * @parent mad.controller.component
	 * 
	 * The Grid Column model will carry data relative to a grid column 
	 * actions used by other component
	 * 
	 * @constructor
	 * Creates a new Grid Column
	 * @param {array} options
	 * @return {mad.model.grid.Column}
	 */
	mad.model.Model('mad.model.grid.Column', /** @static */ {

		/**
		 * Define attributes of the model
		 * @type {Object}
		 */
		attributes: {
			'id': 'string',
			'label': 'string',
			'icon': 'string',
			'action': 'string'
		}

	}, /** @prototype */ {});

});

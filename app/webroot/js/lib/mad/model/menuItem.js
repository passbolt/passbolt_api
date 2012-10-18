steal(
	'jquery/model'
).then(function () {

	/*
	 * @class mad.model.MenuItem
	 * @inherits jQuery.Model
	 * @parent mad.controller.component
	 * 
	 * The Menu Item model will carry the menu item used by the menu controller
	 * 
	 * @constructor
	 * Creates a new Menu Item
	 * @param {array} options
	 * @return {mad.model.MenuItem}
	 */
	$.Model('mad.model.MenuItem', /** @static */ {

		/**
		 * Define attributes of the model
		 * @type {Object}
		 */
		attributes: {
			'id': null,
			'label': null,
			'action': null
		}

	}, /** @prototype */ {});

});
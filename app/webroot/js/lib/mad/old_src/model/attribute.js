steal(
	'mad/model/model.js'
).then(function () {

	/*
	 * @class mad.model.Attribute
	 * @inherits mad.model.Model
	 * @parent mad.model
	 * 
	 * The Attribute model has been created to carry attribute description data.
	 * It is used by the Model Class to return models' attributes information.
	 * 
	 * @constructor
	 * Creates a new Attribute description instance
	 * @param {array} options
	 * @return {mad.model.Attribute}
	 */
	mad.model.Model.extend('mad.model.Attribute', /** @static */ {

		/**
		 * Define attributes of the model
		 * @type {Object}
		 */
		attributes: {
			'name': 'string',
			'type': 'string',
			'modelReference': 'string',
			'multiple': 'string'
		}

	}, /** @prototype */ {

		/**
		 * Get the associated model reference
		 * @
		 */
		'getModelReference': function () {
			return this.modelReference;
		}

	});

});
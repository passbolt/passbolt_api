steal(
	MAD_ROOT + '/view/component/tree.js'
).then(function ($) {

	/*
	 * @class passbolt.controller.CategoryChooserController
	 * @inherits mad.controller.component.TreeController
	 * @parent index 
	 * 
	 * @constructor
	 * Creates a new Category Chooser Controller.
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.CategoryChooserController}
	 */
	mad.controller.component.TreeController.extend('passbolt.controller.component.CategoryChooserController', /** @static */ {

		'defaults': {
			'label': 'Category Chooser'
		},
		'listensTo': ['item_selected']

	}, /** @prototype */ {

		'init': function (el, options) {
			// The map to use to make jstree working with our category model
			options.map = new mad.object.Map({
				'attr.id': {
					'key': 'Category.id',
					'func': function (value, map) {
						return value;
					}
				},
				'data': 'Category.name',
				'children': {
					'key': 'children',
					'func': mad.object.Map.mapObjects
				}
			});
			this._super(el, options);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been selected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} itemId The item identifier
		 * @return {void}
		 */
		'item_selected': function (element, event, itemId) {
			passbolt.eventBus.trigger('category_selected', {
				'id': itemId
			});
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the application is ready and load the tree with the roots
		 * categories
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'{mad.eventBus} app_ready': function (ui, event) {
			var self = this;
			//load categories function of the selected database
			this.setState('loading');
			passbolt.model.Category.getRoots({
				children: false
			}, function (request, response, categories) {
				// load the tree with the categories
				self.load(categories);
				self.setState('ready');
			});
		}

	});

});
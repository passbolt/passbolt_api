steal(
	'mad/controller/component/dropDownMenuController.js',
	'mad/view/component/menu/contextualMenu.js'
).then(function () {

	/**
	 * @class mad.controller.component.ContextualMenuController
	 * @inherits {mad.controller.component.DropDownMenuController}
	 * @parent mad.controller.component
	 * 
	 * @constructor
	 * Instanciate a Contextual Menu Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.ContextualMenuController}
	 */
	mad.controller.component.DropDownMenuController.extend('mad.controller.component.ContextualMenuController', /** @static */ {

		'defaults': {
			'viewClass': mad.view.component.menu.ContextualMenu,
			'cssClasses': ['contextual-menu'],
			// The element which requests the contextual menu.
			'source': null,
			// The coordinates you want to display the contextual menu.
			'coordinates' : {
				'x': null,
				'y': null
			}
		}

	}, /** @prototype */ {

		// constructor like
		'init': function(el, options) {
			// If no element given, create a temporary one.
			if(el == null || !el.length) {
				// Remove any other contextual menu.
				if($('#js_contextual_menu').length != 0) {
					$('#js_contextual_menu').remove();
				}

				// Create the DOM entry point for the contextual menu.
				var $el = mad.helper.HtmlHelper.create(
					mad.app.element,
					'first',
					'<ul id="js_contextual_menu" />'
				);

				// Changing the element force us to recall setup which is called before all init functions
				// and make the magic things happen (bind events ...)
				this.setup($el);
			}

			this._super($el, options); 
		},

		/**
		 * After start hook.
		 * Position the contextual menu functions of the given position
		 */
		'afterStart': function () {
			this._super();
			this.view.position({
				'coordinates': this.options.coordinates
			});
		}

	});
});
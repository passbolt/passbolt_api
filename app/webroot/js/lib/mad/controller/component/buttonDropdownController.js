steal(
	'mad/controller/component/buttonController.js',
	'mad/view/component/buttonDropdown.js',
	'mad/view/template/component/buttonDropdown.ejs'
).then(function () {

		/*
		 * @class mad.controller.component.ButtonDropdownController
		 * @inherits mad.controller.ButtonController
		 * @parent mad.controller.component
		 *
		 * The Button Dropdown class Controller is our implementation of the UI component Drop down button.
		 *
		 * @constructor
		 * Creates a new Button Drop down Controller Component
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {mad.controller.component.ButtonDropdownController}
		 */
		mad.controller.component.ButtonController.extend('mad.controller.component.ButtonDropdownController', /** @static */ {

			'defaults': {
				'label': 'Button Dropdown Component',
				'templateUri': 'mad/view/template/component/buttonDropdown.ejs',
				'templateBased': false,
				'viewClass': mad.view.component.ButtonDropdown,
				'value': null,
				'events': {
					'click': null
				},
				'tag': 'buttonMenu'
			}

		}, /** @prototype */ {


		});

	});
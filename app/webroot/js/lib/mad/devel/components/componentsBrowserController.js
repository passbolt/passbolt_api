steal(
	'mad/controller/component/gridController.js'
).then(function () {
	/*
	 * @class mad.devel.ComponentsBrowserController
	 * @inherits {mad.controller.component.GridController}
	 * @parent index
	 *
	 * Our debug components browser controller.
	 *
	 * @constructor
	 * Creates a new Components Browser Controller
	 *
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.devel.ComponentsBrowserController}
	 */
	mad.controller.component.GridController.extend('mad.devel.ComponentsBrowserController', /** @static */ {

		'defaults': {
		}

	}, /** @prototype */ {

		// Constructor like
		'init': function (el, options) {
			options.map = new mad.object.Map({
				'id': {
					'key': 'options.id',
					'func': function(key, map, item) {
						return 'devel-components-browser-row-' + item.options.id;
					}
				},
				'template': 'options.templateUri'
			});

			// the columns model
			options.columnModel = [{
				'name': 'id',
				'index': 'id',
				'header': {
					'css': [],
					'label': __('Identifier')
				},
				'valueAdapter': function (value, mappedItem, item, columnModel) {
					return item.getId();
				}
			},{
				'name': 'status',
				'index': 'status',
				'header': {
					'css': [],
					'label': __('Status')
				},
				'valueAdapter': function (value, mappedItem, item, columnModel) {
					return item.state.toString(', ');
				}
			}, {
				'name': 'controller',
				'index': 'controller',
				'header': {
					'css': [],
					'label': __('Controller')
				},
				'valueAdapter': function (value, mappedItem, item, columnModel) {
					return item.getClass().shortName;
				},
				'titleAdapter': function(value, mappedItem, item, columnModel) {
					return item.getClass().fullName;
				}
			}, {
				'name': 'inherit',
				'index': 'inherit',
				'header': {
					'css': [],
					'label': __('Inherit')
				},
				'valueAdapter': function (value, mappedItem, item, columnModel) {
					return item.getClass().getParentClass().shortName;
				},
				'titleAdapter': function(value, mappedItem, item, columnModel) {
					return item.getClass().getParentClass().fullName;
				}
			}, {
				'name': 'template',
				'index': 'template',
				'header': {
					'css': [],
					'label': __('Template')
				},
				'valueAdapter': function (value, mappedItem, item, columnModel) {
					var returnValue = [];
					if (item.options.templateBased) {
						if (item.view) {
							returnValue.push(item.view.getTemplate());
						}
					}
					if (item.options.itemTemplateUri) {
						returnValue.push(item.options.itemTemplateUri);
					}

					if (returnValue.length) {
						return returnValue.join('<br/>');
					}
					return '-';
				}
			}];

			this._super(el, options);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when an item is selected in the grid.
		 * This event comes from the grid view
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The selected item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_selected': function (el, ev, item, srcEvent) {
			var componentId = item.replace('devel-components-browser-row-', '');
			// Display the component in the console.
			console.log(mad.app.getComponent(componentId));
		}
	});

});
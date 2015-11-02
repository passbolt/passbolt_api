steal(
	'jquery/controller',
	'mad/controller/component/treeController.js',
	'mad/view/component/tree/dynamicTree.js',
	'mad/object/map.js',
	'mad/view/template/component/tree.ejs',
	'mad/view/template/component/tree/dynamicTree.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.DynamicTreeController
	 * @inherits mad.controllerTreeController
	 * @parent mad.controller.component
	 * @see mad.view.component.DynamicTree
	 * 
	 * The Dynamic Tree class Controller is our implementation of the dynamic tree
	 * component ( such as jstree )
	 * 
	 * @constructor
	 * Creates a new Dynamic Tree Controller Component
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @param {mad.object.Map} options.map The mapping object used to map data from the JMVC
	 * model to the understood format.
	 * @return {mad.controller.component.DynamicTreeController}
	 */
	mad.controller.component.TreeController.extend('mad.controller.component.DynamicTreeController', /** @static */	{

		'defaults': {
			'label': 'Dynamix Tree Component',
			'viewClass': mad.view.component.tree.DynamicTree,
			'templateUri': 'mad/view/template/component/tree.ejs',
			'itemTemplateUri': 'mad/view/template/component/tree/dynamicTree.ejs',
			'map': null,
			'callbacks': {
				'item_selected': null,
				'item_right_selected': null,
				'item_hovered': null
			}
		}

	}, /** @prototype */ {

		/**
		 * Open an item
		 * @param {mad.model.Model} item The target item to open
		 * @return {void}
		 */
		'open': function (item) {
			this.view.open(item);
		},

		/**
		 * Close an item
		 * @param {mad.model.Model} item The target item to close
		 * @return {void}
		 */
		'close': function (item) {
			this.view.close(item);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * An item has been uncollapsed
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mad.model.Model} item The target item
		 * @return {void}
		 */
		' item_opened': function (el, ev, item) {
			this.open(item);
		},

		/**
		 * An item has been collapsed
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mad.model.Model} item The target item
		 * @return {void}
		 */
		' item_closed': function (el, ev, item) {
			this.close(item);
		}

	});

});
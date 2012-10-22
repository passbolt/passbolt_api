steal(
	'lib/jstree/jquery.jstree.js',
	MAD_ROOT + '/view/component/tree.js'
).then(function ($) {

	/*
	 * @class mad.view.component.menu.DropDownMenu
	 * @inherits mad.view.component.Tree
	 * 
	 * Our representation of the drop down menu
	 * 
	 * @constructor
	 * Instanciate a new Drop Down Menu view
	 * @return {mad.view.component.tree.Jstree}
	 */
	mad.view.component.Tree.extend('mad.view.component.menu.DropDownMenu', /** @static */ {

	}, /** @prototype */ {

		// Constructor like
		'init': function (controller, options) {
			this._super(controller, options);
		},

		/**
		 * Open an item
		 * @param {string} itemId The target item to open
		 * @return {void}
		 */
		'open': function (itemId) {
			var li = $('#' + itemId, this.element);
			li.removeClass('closed')
				.addClass('opened');
			var control = $('.control:first', li);
			control.removeClass('open')
				.addClass('close');
		},

		/**
		 * Close an item
		 * @param {string} itemId The target item to close
		 * @return {void}
		 */
		'close': function (itemId) {
			var li = $('#' + itemId, this.element);
			li.removeClass('opened')
				.addClass('closed');
			var control = $('.control:first', li);
			control.removeClass('close')
				.addClass('open');
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Uncollapse an item
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'a mouseover': function (element, event) {
			event.stopPropagation();
			event.preventDefault();
			var li = element.parents('li');
			var itemId = li[0].id;
			this.element.trigger('item_opened', itemId);
		},

		/**
		 * Uncollapse an item
		 * @param {HTMLElement} element The element the event occured on
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'li mouseleave': function (element, event) {
			var itemId = element[0].id;
			this.element.trigger('item_closed', itemId);
		}

	});
});
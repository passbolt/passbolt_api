steal(
	'mad/view/component/tree.js'
).then(function () {

	/*
	 * @class mad.view.component.tree.Jstree
	 * @inherits mad.view.component.Tree
	 * 
	 * Our representation of the dynamic tree view (such as the jquery plugin jstree)
	 * 
	 * @constructor
	 * Instanciate a new Dynamic Tree view
	 * @return {mad.view.component.tree.Jstree}
	 */
	mad.view.component.Tree.extend('mad.view.component.tree.DynamicTree', /** @static */ {

	}, /** @prototype */ {

		// Constructor like
		'init': function (controller, options) {
			this._super(controller, options);
		},

		/**
		 * Open an item
		 * @param {mad.model.Model} item The target item to open
		 * @return {void}
		 */
		'open': function (item) {
			var li = $('#' + item.id, this.element);
			li.removeClass('close')
				.addClass('open');
		},

		/**
		 * Close an item
		 * @param {mad.model.Model} item The target item to close
		 * @return {void}
		 */
		'close': function (item) {
			var li = $('#' + item.id, this.element);
			li.removeClass('open')
				.addClass('close');
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Collapse / Uncollapse an item
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'.node-ctrl a click': function (el, ev) {
			ev.stopPropagation();
			ev.preventDefault();
			var data = null,
				li = el.parents('li');

			if (this.getController().getItemClass()) {
				data = li.data(this.getController().getItemClass().fullName);
			} else {
				data = li[0].id;
			}

			// if the element is closed, open it
			if(li.hasClass('close')) {
				this.element.trigger('item_opened', data);
			}
			// otherwise close it
			else {
				this.element.trigger('item_closed', data);
			}
		},

		/**
		 * Contextual menu
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'.more-ctrl a click': function (el, ev) {
			ev.stopPropagation();
			ev.preventDefault();
			var data = null,
				li = el.parents('li');

			if (this.getController().getItemClass()) {
				data = li.data(this.getController().getItemClass().fullName);
			} else {
				data = li[0].id;
			}

			this.element.trigger('item_right_selected', [data, ev]);
		}

	});
});
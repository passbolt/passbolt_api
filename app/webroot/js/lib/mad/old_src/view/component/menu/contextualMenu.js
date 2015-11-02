steal(
	'mad/view/component/menu/dropDownMenu.js'
).then(function () {

	/*
	 * @class mad.view.component.menu.ContextualMenu
	 * @inherits mad.view.component.menu.DropDownMenu
	 * 
	 * Our representation of the contexual menu
	 * 
	 * @constructor
	 * Instanciate a new Contextual Menu view
	 * @return {mad.view.component.menu.ContextualMenu}
	 */
	mad.view.component.menu.DropDownMenu.extend('mad.view.component.menu.ContextualMenu', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Intercept global click event.
		 *
		 * Intercept global click event and close menu if open.
		 *
		 * @param el
		 * @param ev
		 */
		'{document} click': function (el, ev) {
			if (!this.element.is(el) && !$(this.getController().options.source).is(ev.target)) {
				this.element.remove();
			}
		}

	});
});
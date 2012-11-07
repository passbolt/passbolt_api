steal(
	'mad/view'
).then(function () {

	/*
	 * @class mad.view.component.Tab
	 * @inherits jQuery.View
	 * @hide
	 * 
	 * @constructor
	 * 
	 * @return {mad.view.component.Tab}
	 */
	mad.view.View.extend('mad.view.component.Tab', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Add a component to the container. Add its dom element
		 * @param {Array} componentOptions The options of the component to add
		 * @return {jQuery} the just added jQuery element
		 */
		'addComponent': function (componentOptions) {
			// add a tag for the component to add
			var $component = $('<div id="' + componentOptions.id + '"></div>').appendTo(this.element);
			// Add the tab with the jquery tabs API
			//this.element.tabs('add', '#' + componentOptions.id, componentOptions.label);
			return $component;
		}
	});

});
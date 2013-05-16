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
		'add': function (Class, options) {
			// get the tag to use
			var tag = 'div';
			if(typeof options.tag != 'undefined') tag = options.tag;
			else if(typeof Class.defaults.tag) tag = Class.defaults.tag;
			// render the component tag
			var html = '<' + tag + ' id="' + options.id + '"></' + tag + '>';
			// add a tag for the component to add
			return mad.helper.HtmlHelper.create(this.element, 'last', html);
		}
	});

});
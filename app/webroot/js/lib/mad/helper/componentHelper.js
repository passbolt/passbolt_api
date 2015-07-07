steal(
	'can/construct'
).then(function () {

	/*
	 * @class mad.helper.ComponentHelper
	 * @inherits {mad.Class}
	 * @parent utilities
	 */
	can.Construct('mad.helper.ComponentHelper', /** @static */ {

		/**
		 * Create a new component controller function of the given parameters
		 * @param {HTMLElement} refElement The element reference
		 * @param {string} position The position about the reference element
		 * @param {mad.controller.ComponentController} Clazz The component controller class reference
		 * @return {mad.controller.ComponentController}
		 */
		'create': function (refElement, position, Clazz, options) {
			var returnValue = null,
				// our DOM component
				$component = null,
				// the rendered html
				html = '',
				// the component id
				id = (options.id || '');

			// class attributes options.
			var classAttributes = {};
			if (typeof Clazz.defaults.attributes != 'undefined') {
				classAttributes = Clazz.defaults.attributes;
			}
			// attributes to add to the tag
			var attributes = $.extend({}, classAttributes, options.attributes);

			if (refElement.length == 0) {
				throw new mad.error.WrongParametersException('refElement');
			}

			// build the tag
			html = '<' + Clazz.defaults.tag + ' id="' + (options.id || '') + '"';
			
			// add attributes
			for(var attrName in attributes) {
				html += ' ' + attrName + '="' + attributes[attrName] + '"';
			}
			
			// close our tag
			html += '/>';


			// insert the component in the DOM
			$component = mad.helper.HtmlHelper.create(refElement, position, html);

			// Instanciate the component
			if (typeof Clazz.singleton != 'undefined') {
				returnValue = Clazz.singleton($component, options);
			} else {
				returnValue = new Clazz($component, options);
			}

			return returnValue;
		}

	}, /** @prototype */ {

	});

});
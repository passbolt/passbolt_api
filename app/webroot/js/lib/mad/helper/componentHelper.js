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
				componentHtml = '<' + Clazz.defaults.tag + ' id="' + (options.id || '') + '"/>',
				$component = null;

			if (refElement.length == 0) {
				throw new mad.error.WrongParametersException('refElement');
			}

			// insert the component in the DOM
			$component = mad.helper.HtmlHelper.create(refElement, position, componentHtml);

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
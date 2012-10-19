steal(
	'jquery/class'
).then(function ($) {

	/**
	 * The controller class helper offers to the developper tools arround controllers
	 */
	$.Class('mad.helper.ComponentHelper', /** @static */ {


		/**
		 * Create a new component controller function of the given parameters
		 * @param {HTMLElement} refElement The element reference
		 * @param {string} position The position about the reference element
		 * @param {mad.controller.ComponentController} Clazz The component controller class reference
		 * @return {mad.controller.ComponentController}
		 */
		'create': function (refElement, position, Clazz, options) {
			refElement = typeof refElement == 'string' ? $(refElement) : refElement;
			var returnValue = null,
				component = '<' + Clazz.defaults.tag + ' id="' + (options.id || '') + '"/>',
				$component = null;

			if (refElement.length == 0) {
				throw new mad.error.WrongParametersException('refElement');
			}

			$component = mad.helper.HtmlHelper.create(refElement, position, component);

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
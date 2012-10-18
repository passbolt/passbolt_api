steal(
	'jquery/class'
).then(function ($) {

	$.Class('mad.helper.HtmlHelper', /** @static */ {

		/**
		 * Insert an html content functions of a target element
		 * @param {HTMLElement} refElement The element reference
		 * @param {string} position The position about the reference element
		 * @param {string} content The content to insert. Has to be an html string
		 * @return {HTMLElement} The inserted element
		 */
		'create': function (refElement, position, content) {
			refElement = typeof refElement == 'string' ? $(refElement) : refElement;
			var returnValue = $(content);

			if (refElement.length == 0) {
				throw new mad.error.WrongParametersException('refElement');
			}

			// insert the component functions of the reference element and the given position
			switch (position) {
			case 'inside_replace':
				refElement.empty();
				returnValue = returnValue.prependTo(refElement);
				break;

			case 'first':
				returnValue = returnValue.prependTo(refElement);
				break;

			case 'last':
				returnValue = returnValue.appendTo(refElement);
				break;

			case 'before':
				returnValue = returnValue.insertBefore(refElement);
				break;

			case 'after':
				returnValue = returnValue.insertAfter(refElement);
				break;

			default:
				throw new mad.error.WrongParametersException('position');
			}

			return returnValue;
		}

	}, /** @prototype */ { });

});
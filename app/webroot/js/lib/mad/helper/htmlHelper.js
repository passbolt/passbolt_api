steal(
	'can/construct'
).then(function () {

	/*
	 * @class mad.helper.HtmlHelper
	 * @inherits {mad.Class}
	 * @parent utilities
	 */
	can.Construct('mad.helper.HtmlHelper', /** @static */ {

		/**
		 * Insert an html content functions of a given position and a reference element
		 * @param {HTMLElement} refElement The reference element.
		 * @param {string} position The position about the reference element. The available
		 * positions are : inside_replace, replace_with, first, last, before, after.
		 * @param {string} content The content to insert. The content parameter has to
		 * be a valid html string.
		 * @return {HTMLElement} The inserted element
		 */
		'create': function (refElement, position, content) {
			var returnValue = $(content);

			if (!(refElement instanceof jQuery)) {
				throw new mad.error.WrongParametersException('refElement', 'jQuery');
			}
			if (refElement.length == 0) {
				throw new mad.error.WrongParametersException('refElement', 'jQuery');
			}

			// insert the component functions of the reference element and the given position
			switch (position) {
			case 'inside_replace':
				refElement.empty();
				returnValue = returnValue.prependTo(refElement);
				break;
			case 'replace_with':
				refElement.replaceWith(returnValue);
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
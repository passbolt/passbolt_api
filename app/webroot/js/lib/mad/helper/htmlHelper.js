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
		 * Position an element in absolute
		 * @param {HTMLElement} el The element to position
		 * @param {array} options Array of options
		 * @param {array} options.mouse (optional) Position the element functions of a mouse position
		 * @param {string} options.mouse.x (At x pixels from the left first relative element found
		 * @param {string} options.mouse.y At y pixels from the top first relative element found
		 * @param {array} options.reference (optional) Position the element functions of a reference element
		 * @param {HTMLElement} options.reference.element The reference element
		 * @param {array} options.reference.my As per Jquery position plugin, the target corner of my element ("top left" by instance)
		 * @param {array} option.reference.at As per Jquery position plugin, the target corner of the reference element ("bottom left" by instance)
		 * @return {void}
		 */
		'position': function(el, options) {
			if (typeof options.mouse != 'undefined') {
				el.css({
					'position': 'absolute',
					'left': options.mouse.x + 'px',
					'top': options.mouse.y + 'px'
				});
			} else if(typeof options.reference != 'undefined') {
				console.log(el);
				console.log(options.reference.of);
				el.position({
					'my': options.reference.my,
					'at': options.reference.at,
					'of': options.reference.of
				});
			}
		},

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
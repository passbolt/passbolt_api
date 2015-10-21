import mad from 'mad/util/util';

// Initialize the helper namespaces.
mad.helper = mad.helper || {};

/**
 * @parent Mad.core_helper_api
 * @inherits can.Construct
 *
 * A set of tools to help developer with Html.
 */
var HtmlHelper = mad.helper.Html = can.Construct.extend('mad.helper.Html', /** @static */ {

	/**
	 * Position an element
	 *
	 * @param {HTMLElement} el The element to position
	 * @param {array} options Array of options
	 *
	 * @body
	 * ## Options
	 *
	 * ### coordinates {object} (optional)
	 * Position the element regarding the coordinates
	 * * x {integer} : The position of the element on x axis
	 * * y {integer} : The position of the element on y axis
	 *
	 * ### reference {object} (optional)
	 * Position the element functions of a reference element
	 * * element {HTMLElement} : The reference element
	 * * my {string} : As per Jquery position plugin, the target corner of my element ("top left" by instance)
	 * * at {string} : As per Jquery position plugin, the target corner of the reference element ("bottom left" by instance)
	 */
	position: function(el, options) {
		if (typeof options.coordinates != 'undefined') {
			el.css({
				position: 'absolute',
				left: options.coordinates.x + 'px',
				top: options.coordinates.y + 'px'
			});
		} else if(typeof options.reference != 'undefined') {
			el.position({
				my: options.reference.my,
				at: options.reference.at,
				of: options.reference.of
			});
		}
	},

	/**
	 * Insert an html content and position it.

	 * @param {jQuery} refElement The reference element to position the content with.
	 * @param {string} position (optional) The position of the content to insert, regarding the reference element.
	 *
	 * Available values : inside_replace, replace_with, before, after, first, last.
	 * @param {string} content The content to insert. The content parameter has to be a valid html string.
	 * @return {HTMLElement} The inserted element
	 */
	create: function (refElement, position, content) {
		var returnValue = $(content);

		if (!(refElement instanceof jQuery)) {
			throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'refElement');
		}
		if (refElement.length == 0) {
            throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'refElement');
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
                throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'position');
				//throw new mad.Exception('The parameter position is not valid');
		}

		return returnValue;
	}

}, /* @prototype */ { });
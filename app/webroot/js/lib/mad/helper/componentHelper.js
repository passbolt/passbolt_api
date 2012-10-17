steal(
	'jquery/class'
).then(function ($) {

	/**
	 * The controller class helper offers to the developper tools arround controllers
	 */
	$.Class('mad.helper.ComponentHelper', /** @static */ {


		/**
		 * Insanciate and position a component
		 * 
		 */
		'create': function (refElement, position, Clazz, options) {
			var returnValue = null,
				$component = $('<' + Clazz.defaults.tag + ' id="' + (options.id || '') + '"/>');

			// insert the component functions of the reference element and the given position
			switch (position) {
			case 'inside_replace':
				refElement.empty();
				$component = $component.prependTo(refElement);
				break;

			case 'first':
				$component = $component.prependTo(refElement);
				break;

			case 'last':
				$component = $component.appendTo(refElement);
				break;

			case 'before':
				$component = $component.insertBefore(refElement);
				break;

			case 'after':
				$component = $component.insertAfter(refElement);
				break;
			}

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
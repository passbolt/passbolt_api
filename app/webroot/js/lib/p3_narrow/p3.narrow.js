/**!
 * Adds classes to an element (body by default) based on document width
 *
 * @copyright       Copyright 2013, Greenpeace International
 * @license         MIT License (opensource.org/licenses/MIT)
 * @version         0.0.2
 * @author          <a href="mailto:hello@raywalker.it">Ray Walker</a>,
 *                  based on original work by
 *                  <a href="http://www.more-onion.com/">More Onion</a>
 * @requires        <a href="http://jquery.com/">jQuery 1.1.4+</a>
 * @example         $.p3.narrow([options]);
 */
/* global jQuery */

(function($, w, d) {
	'use strict';

	var _p3 = $.p3 || {},
		defaults = {
			/* Selector or object to which the classes are added */
			el: 'body',
			/* Class names and their breakpoints */
			sizes: {
				threetwo:   320,
				four:       400,
				five:       500,
				six:        600,
				sixfive:    650,
				seven:      700,
				sevensome:  768,
				eightfive:  850,
				nine:       900,
				tablet:     480,
				desktop:    1024,
				wide:       1350,
				large:      1600
			},
			// Apply changes on resize
			onResize: true,
			// Apply changes on initialisation
			onLoad: true,
			// Throttle resize event timer in milliseconds
			delay: 100
		};

	_p3.narrow = function(options) {
		var config = $.extend(true, defaults, options || {}),
			$window = $(w),
			$el = $(config.el),
			wait = false;

		/**
		 * Returns the size of the document plus scrollbars
		 * @returns {int}
		 */
		function getWidth() {
			if (typeof w.innerWidth === 'number') {
				// Non-IE
				return w.innerWidth;
			} else if (d.documentElement && d.documentElement.clientWidth) {
				// IE 6+ in 'standards compliant mode'
				return d.documentElement.clientWidth;
			}
		}

		/**
		 * Assigns classes to the target element
		 */
		function checkNarrow() {
			var classString = '',
				width = getWidth();

			// For each configured breakpoint
			$.each(config.sizes, function(cls, size) {
				// If the document is larger or equal to this size
				if (width >= size) {
					// Add this classname to the element
					classString += ' ' + cls;
				} else {
					// Remove this class
					$el.removeClass(cls);
				}
			});

			// Apply new classes
			$el.addClass(classString);

			// Propagate an event while the class has been added.
			$(window).trigger('p3_narrow_checked');
		}

		/**
		 * Executes callback no more than once per interval
		 *
		 * @param {function}    callback
		 * @param {int}         interval
		 * @returns {undefined}
		 */
		function throttle(callback, interval) {
			if (wait) {
				return;
			}

			wait = true;

			setTimeout(function() {
				wait = false;
			}, interval);

			callback();
		}

		if (config.onResize) {
			$window.resize(function() {
				throttle(checkNarrow, config.delay);
			});
		}

		if (config.onLoad) {
			$window.ready(checkNarrow);
		}
	};

	$.p3 = _p3;

}(jQuery, this, document));

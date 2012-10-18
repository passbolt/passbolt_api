steal(
	'jquery/class'
).then(function ($) {

	$.Class('mad.helper.HtmlHelper', /** @static */ {

		'position': function ($element, $refElement, options) {
			options = options || [];

			// Optional parameters
			var contentOriented = options.contentOriented || 'bottom',
				hPos = options.hPos || 'right',
				vPos = options.vPos || 'top',

				// get the elements position and dimension
				refPos = $refElement.position(),
				refAbsPos = $refElement[0].getBoundingClientRect(),
				refWidth = $refElement.width(),
				refHeight = $refElement.height(),
				elWidth = $element.width(),
				elHeight = $element.height(),
				bodyWidth = $('body').width(),
				bodyHeight = $('body').height(),

				// Final position to apply
				top = 0,
				left = 0;

			// transform the position functions of the viewport
			var hDeltaRight = (refAbsPos.right + elWidth) - bodyWidth,
				hDeltaLeft = (refAbsPos.left - elWidth);
			if (hpos == 'right' && hDeltaRight > 0)  

			switch (hPos) {
				case 'right':
					left = refPos.left + refWidth;
					
					if (delta > 0) {
						left = left - delta;
					}
					break;
			}
			
			switch (vPos) {
				
			}
			
			$element.css('left', left);
		}
	}, /** @prototype */ { });

});
@page jQuery.within
@parent jquerypp

`jQuery.within` helps to determine all elements that have a certain position or area in common by providing `[jQuery.fn.withinBox]` and `[jQuery.fn.within]`. The following example returns all `div` elements on the point 200px left and 200px from the top:

	$('div').within(200, 200)

Use `$(el).withinBox(left, top, width, height)` to get all elements within a certain area:

	$('*').withinBox(200, 200, 100, 100)

> [jQuery.event.drag] uses *$.within* to determine dropable elements at the current position.

## Example

Move the mouse in the following example and it will show the ids for `div` elements within the current mouse position:

<iframe style="width: 100%; height: 330px" src="http://jsfiddle.net/hHLcg/embedded/result,html,js,css" allowfullscreen="allowfullscreen" frameborder="0">JSFiddle</iframe>

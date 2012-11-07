@page jQuery.selection
@parent jquerypp

`jQuery.selection` adds `[jQuery.fn.selection]` to get or set the current selection.

## Setting

You can select the text, for example on an element like this:

    <div id="text">This is some text</div>

Using `jQuery.fn.selection` by providing a start and end offset:

  	$('#text').selection(8, 12)

## Getting

A call without any parameters will return the current selection:
 
	  $('#text').selection() // -> { start : 8, end : 12, width : 4 }

Where the returned object contains:

- __start__ - The number of characters from the start of the element to the start of the selection.
- __end__ - The number of characters from the start of the element to the end of the selection.
- __width__ - The width of the selection range.

The selected text can be retrieved like this:

	  var text = $('#text').text(),
	      selected = text.substr(selection.start, selection.end);
	  selected // -> some

Selection works with all elements. If you want to get selection information of the document:
 
    $(document.body).selection();
     
## Demo
 
This demo shows setting the selection in various elements
 
@demo jquery/dom/selection/selection.html
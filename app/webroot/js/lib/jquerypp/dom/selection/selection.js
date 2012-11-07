steal('jquery','jquery/dom/range',function($){

var getWindow = function( element ) {
	return element ? element.ownerDocument.defaultView || element.ownerDocument.parentWindow : window
},
// A helper that uses range to abstract out getting the current start and endPos.
getElementsSelection = function(el, win){
	// get a copy of the current range and a range that spans the element
	var current = $.Range.current(el).clone(),
		entireElement = $.Range(el).select(el);
	// if there is no overlap, there is nothing selected
	if(!current.overlaps(entireElement)){
		return null;
	}
	// if the current range starts before our element
	if(current.compare("START_TO_START", entireElement) < 1){
		// the selection within the element begins at 0
		startPos = 0;
		// move the current range to start at our element
		current.move("START_TO_START",entireElement);
	}else{
		// Make a copy of the element's range.
		// Move it's end to the start of the selected range
		// The length of the copy is the start of the selected
		// range.
		fromElementToCurrent =entireElement.clone();
		fromElementToCurrent.move("END_TO_START", current);
		startPos = fromElementToCurrent.toString().length
	}
	
	// If the current range ends after our element
	if(current.compare("END_TO_END", entireElement) >= 0){
		// the end position is the last character
		endPos = entireElement.toString().length
	}else{
		// otherwise, it's the start position plus the current range
		// TODO: this doesn't seem like it works if current
		// extends to the left of the element.
		endPos = startPos+current.toString().length
	}
	return {
		start: startPos,
		end : endPos,
		width : endPos - startPos
	};
},
// Text selection works differently for selection in an input vs
// normal html elements like divs, spans, and ps.
// This function branches between the various methods of getting the selection.
getSelection = function(el){
	var win = getWindow(el);
	
	// `selectionStart` means this is an input element in a standards browser.
	if (el.selectionStart !== undefined) {

		if(document.activeElement 
		 	&& document.activeElement != el 
			&& el.selectionStart == el.selectionEnd 
			&& el.selectionStart == 0){
			return {start: el.value.length, end: el.value.length, width: 0};
		}
		return  {start: el.selectionStart, end: el.selectionEnd, width: el.selectionEnd - el.selectionStart};
	} 
	// getSelection means a 'normal' element in a standards browser.
	else if(win.getSelection){
		return getElementsSelection(el, win)
	} else{
		// IE will freak out, where there is no way to detect it, so we provide a callback if it does.
		try {
			// The following typically works for input elements in IE:
			if (el.nodeName.toLowerCase() == 'input') {
				var real = getWindow(el).document.selection.createRange(), 
					r = el.createTextRange();
				r.setEndPoint("EndToStart", real);
				
				var start = r.text.length
				return {
					start: start,
					end: start + real.text.length,
					width: real.text.length
				}
			}
			// This works on textareas and other elements
			else {
				var res = getElementsSelection(el,win)
				if(!res){
					return res;
				}
				// we have to clean up for ie's textareas which don't count for 
				// newlines correctly
				var current = $.Range.current().clone(),
					r2 = current.clone().collapse().range,
					r3 = current.clone().collapse(false).range;
				
				r2.moveStart('character', -1)
				r3.moveStart('character', -1)
				// if we aren't at the start, but previous is empty, we are at start of newline
				if (res.startPos != 0 && r2.text == "") {
					res.startPos += 2;
				}
				// do a similar thing for the end of the textarea
				if (res.endPos != 0 && r3.text == "") {
					res.endPos += 2;
				}
				
				return res
			}
		}catch(e){
			return {start: el.value.length, end: el.value.length, width: 0};
		}
	} 
},
// Selects text within an element.  Depending if it's a form element or
// not, or a standards based browser or not, we do different things.
select = function( el, start, end ) {
	var win = getWindow(el);
	// IE behaves bad even if it sorta supports
	// getSelection so we have to try the IE methods first. barf.
	if(el.setSelectionRange){
		if(end === undefined){
            el.focus();
            el.setSelectionRange(start, start);
		} else {
			el.select();
			el.selectionStart = start;
			el.selectionEnd = end;
		}
	} else if (el.createTextRange) {
		var r = el.createTextRange();
		r.moveStart('character', start);
		end = end || start;
		r.moveEnd('character', end - el.value.length);
		
		r.select();
	} else if(win.getSelection){
		var	doc = win.document,
			sel = win.getSelection(),
			range = doc.createRange(),
			ranges = [start,  end !== undefined ? end : start];
		getCharElement([el],ranges);
		range.setStart(ranges[0].el, ranges[0].count);
		range.setEnd(ranges[1].el, ranges[1].count);
		
		// removeAllRanges is necessary for webkit
        sel.removeAllRanges();
        sel.addRange(range);
		
	} else if(win.document.body.createTextRange){ //IE's weirdness
		var range = document.body.createTextRange();
		range.moveToElementText(el);
		range.collapse()
		range.moveStart('character', start)
		range.moveEnd('character', end !== undefined ? end : start)
        range.select();
	}

},
// If one of the range values is within start and len, replace the range
// value with the element and its offset.
replaceWithLess = function(start, len, range, el){
	if(typeof range[0] === 'number' && range[0] < len){
			range[0] = {
				el: el,
				count: range[0] - start
			};
	}
	if(typeof range[1] === 'number' && range[1] <= len){
			range[1] = {
				el: el,
				count: range[1] - start
			};;
	}
},
getCharElement = function( elems , range, len ) {
	var elem,
		start;
	
	len = len || 0;
	for ( var i = 0; elems[i]; i++ ) {
		elem = elems[i];
		// Get the text from text nodes and CDATA nodes
		if ( elem.nodeType === 3 || elem.nodeType === 4 ) {
			start = len
			len += elem.nodeValue.length;
			//check if len is now greater than what's in counts
			replaceWithLess(start, len, range, elem ) 
		// Traverse everything else, except comment nodes
		} else if ( elem.nodeType !== 8 ) {
			len = getCharElement( elem.childNodes, range, len );
		}
	}
	return len;
};
/**
 * @parent jQuery.selection
 * @function jQuery.fn.selection
 *
 * Set or retrieve the currently selected text range. It works on all elements:
 *
 *      $('#text').selection(8, 12)
 *      $('#text').selection() // -> { start : 8, end : 12, width: 4 }
 *
 * @param {Number} [start] Start position of the selection range
 * @param {Number} [end] End position of the selection range
 * @return {Object|jQuery} Returns either the jQuery object when setting the selection or
 * an object containing
 *
 * - __start__ - The number of characters from the start of the element to the start of the selection.
 * - __end__ - The number of characters from the start of the element to the end of the selection.
 * - __width__ - The width of the selection range.
 *
 * when no arguments are passed.
 */
$.fn.selection = function(start, end){
	if(start !== undefined){
		return this.each(function(){
			select(this, start, end)
		})
	}else{
		return getSelection(this[0])
	}
};
// for testing
$.fn.selection.getCharElement = getCharElement;

return $;
});
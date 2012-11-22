/** @license ES6/DOM4 polyfill for IE8 | @version 0.7 final | MIT License | github.com/termi */

// ==ClosureCompiler==
// @compilation_level ADVANCED_OPTIMIZATIONS
// @warning_level VERBOSE
// @jscomp_warning missingProperties
// @output_file_name a.ie8.js
// @check_types
// ==/ClosureCompiler==
/**
 * ES5 and DOM shim for IE < 8
 * @version 0.7 alpha-3
 * TODO::
 * 1. http://www.positioniseverything.net/explorer.html
 */

// [[[|||---=== GCC DEFINES START ===---|||]]]
/** @define {boolean} */
var __GCC__IS_DEBUG__ = false;
//IF __GCC____GCC__IS_DEBUG____ == true [
//0. Some errors in console
//1. Fix console From https://github.com/theshock/console-cap/blob/master/console.js
//]
/** @define {boolean} */
var __GCC__NODE_CONSTRUCTOR_AS_ACTIVX__ = true;
/** @define {boolean} */
var __GCC__NODE_CONSTRUCTOR_AS_DOM_ELEMENT__ = false;
/** @define {boolean} */
var __GCC__INCLUDE_DOMPARSER_SHIM__ = false;

/** @define {boolean} */
var __GCC__UNSTABLE_FUNCTIONS__ = false;
/** @define {boolean} */
var __GCC__FIX_OBJECT_DEFINE_PROPERTY_SET_VALUE_NOT_IGNORING_SETTER__ = true;
//IF __GCC____GCC__UNSTABLE_FUNCTIONS____ == true [
//]
// [[[|||---=== GCC DEFINES END ===---|||]]]

;(function(global, _append) {

/** Browser sniffing
 * GCC W U NO SUPPORT @cc ?
 * @type {boolean} */
var _browser_msie = window.eval && eval("/*@cc_on 1;@*/") && +((/msie (\d+)/i.exec(navigator.userAgent) || [])[1] || 0) || void 0;
if(!(_browser_msie < 9))return;

/** @const @type {boolean} */
var DEBUG = __GCC__IS_DEBUG__;


if(!global["Element"])((global["Element"] =
//Reprisent ActiveXObject as Node, Element and HTMLElement so `<element> instanceof Node` is working (!!!But no in IE9 with in "compatible mode")
	__GCC__NODE_CONSTRUCTOR_AS_ACTIVX__ ? ActiveXObject : __GCC__NODE_CONSTRUCTOR_AS_DOM_ELEMENT__ ? document.createTextNode("") : {}
).prototype)["ie"] = true;//fake prototype for IE < 8
if(!global["HTMLElement"])global["HTMLElement"] = global["Element"];//IE8
if(!global["Node"])global["Node"] = global["Element"];//IE8



var _temoObj;
//Not sure if it wrong. TODO:: tests for this
if(!global["DocumentFragment"]) {

	global["DocumentFragment"] = 
		global["Document"] || global["HTMLDocument"] ||//For IE8
		(_temoObj = function(){}, _temoObj.prototype = {}, _temoObj);//For IE < 8

}
if(!global["Document"])global["Document"] = global["DocumentFragment"];



global["_"] = {
	"ielt9shims" : [],
	"orig_" : global["_"]//Save original "_" - we will restore it in a.js
};

var _ = global["_"]["ielt9shims"]//"_" - container for shims what should be use in a.js

	/** @const */
  , document_createDocumentFragment = document.createDocumentFragment

	/** @const */
  , document_createElement = document.createElement

	/** @const */
  , document_createTextNode = document.createTextNode

	/** @const */
  , _document_documentElement = document.documentElement

	/** @const */
  , _throw = function(errStr) {
  		throw errStr instanceof Error ? errStr : new Error(errStr);
  }

	/** @const */
  , _throwDOMException = function(errStr) {
		var ex = Object.create(DOMException.prototype);
		ex.code = DOMException[errStr];
		ex.message = errStr +': DOM Exception ' + ex.code;
		throw ex;
	}

	/** @const */
  , _recursivelyWalk = function (nodes, cb) {
		for (var i = 0, len = nodes.length; i < len; i++) {
			var node = nodes[i],
				ret = cb(node);
			if (ret) {
				return ret;
			}
			if (node.childNodes && node.childNodes.length > 0) {
				ret = _recursivelyWalk(node.childNodes, cb);
				if (ret) {
					return ret;
				}
			}
		}
	}

	/** @const */
  , _safeExtend = function(obj, extention) {
		for(var key in extention)
			if(_hasOwnProperty(extention, key) && obj[key] !== extention[key])
				try {//prevent IE error "invalid argument."
					obj[key] = extention[key];
				}
				catch(e) { }
		
		return obj;
	}

  	/**
  	 * @const
     * Use native and probably broken function or Quick and safety for large string but non-full-standard function
	 * For system use only
	 * More standart solution in a.js
	 */
  , _String_trim = String.prototype.trim || function () {//Cache origin trim function
		var	str = this.replace(RE_left_spaces, ''),
			i = str.length;

		while (RE_space.test(str.charAt(--i))){}

		return str.slice(0, i + 1);
	}
	
	/** @const */
  , _String_split = String.prototype.split

	/** @const */
  , _String_substr = String.prototype.substr

	/** @const */
  , _Array_slice = Array.prototype.slice

	/** @const */
  , _Function_apply = Function.prototype.apply

	/** @const */
  , _Function_call = Function.prototype.call
	
	/** Use native "bind" or unsafe bind for service and performance needs
	 * @const
	 * @param {Object} object
	 * @param {...} var_args
	 * @return {Function} */
  , _unSafeBind = Function.prototype.bind || function(object, var_args) {
		var __method = this,
			args = _Array_slice.call(arguments, 1);
		return function () {
			return _Function_apply.call(__method, object, args.concat(_Array_slice.call(arguments)));
		}
	} 
	
	/** @const */
  , _hasOwnProperty = _unSafeBind.call(Function.prototype.call, Object.prototype.hasOwnProperty)

  	/** @type {Node} */
  , _testElement = document.createElement('p')

  , _txtTextElement
	
  , _Node_prototype = global["Node"].prototype
	
  , _Element_prototype = global["Element"].prototype

	/** @const */
  , _Node_contains = _testElement.contains || _Node_prototype.contains//TODO:: massive testing

	/** @const */
  , _Native_Date = Date

	/** @const @type {RegExp} */
  , RE_cloneElement_tagMatcher = /^\<([\w\:\-]*)[\>\ ]/i
	
	/** @const @type {RegExp} */
  , RE_left_spaces = /^\s+/

	/** @const @type {RegExp} */
  , RE_space = /\s/
	
	/** @type {boolean} */
  , _String_split_shim_isnonparticipating

	/** @type {*} */
  , _tmp_

	/** @type {Function} */
  , function_tmp

  , nodeList_methods_fromArray = ["every", "filter", "forEach", "indexOf", "join", "lastIndexOf", "map", "reduce", "reduceRight", "reverse", "slice", "some", "toString"]

	/** @const */
  , RE_style_alpha_filter = /alpha\(opacity=([^\)]+)\)/

	/** @const */
  , STRING_FOR_RE_style_important = "\\s*:\\s*(\\S+)\\s*(?:[$;]|(?:(!important)\\s*[$;]))"

	/** @type {Function}
	 * @this {Node} */
  , style_getOpacityFromMSFilter = function() {
		var val = (this.filter || "").match(RE_style_alpha_filter);

		return val ? (parseInt(val[1]) / 100) + "" : "";//can't replace parseInt to '+(val[1])'
	}

  , _CSSStyleDeclaration_prototype_methods = {
		/**
		 * @param {String} propertyName
		 * @return String
		 */
		"getPropertyValue" : function(propertyName) {
			return this.getAttribute(propertyName);
		}
		/**
		 * @param {String} propertyName
		 * @return String
		 */
		, "removeProperty" : function(propertyName) {
			this.removeAttribute(propertyName);
		}
		/**
		 * @param {String} propertyName
		 * @param {String} value
		 * @param {String} priority
		 * @return void
		 */
		, "setProperty" : function(propertyName, value, priority) {
			if(priority != "important") {
				this.setAttribute(propertyName, value);
			}
			else {
				var reg = new RegExp(propertyName + STRING_FOR_RE_style_important, "i")
					, val = ";" + propertyName + ":" + value + " !important;"
				;
				if(reg.test(this.cssText)) {
					this.cssText = this.cssText.replace(reg, val)
				}
				else this.cssText = this.cssText + val;
			}
		}
		/**
		@param {String} propertyName
		@return String
		*/
		, "getPropertyPriority" : function(propertyName) {
			var reg = new RegExp(propertyName + STRING_FOR_RE_style_important, "i");
			return ((this.cssText || "").match(reg) || [])[2] || "";
		}
		/**
		@param {Number} index
		@return String
		*/
		, "item" : function(index) {
			//TODO::
		}
	}

  , ATTRIBUTES_CUSTOM = {
		'for': 'htmlFor',
		'class': 'className',
		'value': 'defaultValue'
	}

	// attribute referencing URI data values need special treatment in IE | From nwmatcher
  , ATTRIBUTE_URIDATA = {
		'action': null,
		'cite': null,
		'codebase': null,
		'data': null,
		'href': null,
		'longdesc': null,
		'lowsrc': null,
		'src': null,
		'usemap': null
	}
  , DEFAULT_ATTRIBUTES_MAP = {
		'id': true,
		'value': true,
		'checked': true,
		'disabled': true,
		'ismap': true,
		'multiple': true,
		'readonly': true,
		'selected': true
	}

	// ------------------------------ ==================  Events  ================== ------------------------------
  , _ielt9_Event
	/** @type {Object} */
  , _EventInitFunctions

  , _Event_prototype

	/** @const @type {string} */
  , _event_UUID_prop_name = "uuid"

	/** @type {number} unique indentifier for event listener */
  , _event_UUID = 1//MUST be more then 0 | 0 - using for DOM0 events

	/** @const @type {string} */
  , _event_handleUUID = "_h_9e2"

	/** @const @type {string} */
  , _event_eventsUUID = "_e_8vj"

	/** @const @type {string} */
  , _event_nativeEventPropName = "ietl9_event"

	/** @const @type {Function */
  , _event_emptyFunction = function(){}

	/** @const @type {Object} */
  , _event_needCapturing = {}

	/** @type {boolean} */
  , _event_globalIsCaptureIndicator = false

	/** @type {Array.<Node>} */
  , _event_captureHandlerNodes = []

  , __is__DOMContentLoaded

	// ------------------------------ ==================  HTML5 shiv  ================== ------------------------------

  , html5_elements = 'abbr|article|aside|audio|canvas|command|datalist|details|figure|figcaption|footer|header|hgroup|keygen|mark|meter|nav|output|progress|section|source|summary|time|video'

  , html5_elements_array = html5_elements.split('|')
	
	/* Not all elements can be cloned in IE (this list can be shortend) **/
  , ielt9_elements = /^<|^(?:a|b|button|code|div|fieldset|form|map|h1|h2|h3|h4|h5|h6|i|object|iframe|img|input|label|li|link|ol|option|p|param|q|script|select|span|strong|style|table|tbody|td|textarea|tfoot|th|thead|tr|ul|optgroup)$/i

	// feature detection: whether the browser supports unknown elements
	/** @type {boolean}*/
  , supportsUnknownElements
	
  , safeFragment

	/** @type {Node} */
  , safeElement

  , _nativeCloneNode

  , _getScrollX

  , _getScrollY
;

document.compatMode === "CSS1Compat" ?
	((_getScrollX = function(){return _document_documentElement.scrollLeft}), (_getScrollY = function(){return _document_documentElement.scrollTop}))
	: 
	((_getScrollX = function(){return document.body.scrollTop}), (_getScrollY = function(){return document.body.scrollLeft}))
;


/*
TODO:: http://code.jquery.com/jquery-1.7.2.js:1537
var support = {};

// Run tests that need a body at doc ready
document.addEventListener('DOMContentLoaded', function() {
	var container, outer, inner, table, td, offsetSupport,
		marginDiv, conMarginTop, style, html, positionTopLeftWidthHeight,
		paddingMarginBorderVisibility, paddingMarginBorder,
		body = document.getElementsByTagName("body")[0];

	if ( !body ) {
		// Return for frameset docs that don't have a body
		return;
	}

	conMarginTop = 1;
	paddingMarginBorder = "padding:0;margin:0;border:";
	positionTopLeftWidthHeight = "position:absolute;top:0;left:0;width:1px;height:1px;";
	paddingMarginBorderVisibility = paddingMarginBorder + "0;visibility:hidden;";
	style = "style='" + positionTopLeftWidthHeight + paddingMarginBorder + "5px solid #000;";
	html = "<div " + style + "display:block;'><div style='" + paddingMarginBorder + "0;display:block;overflow:hidden;'></div></div>" +
		"<table " + style + "' cellpadding='0' cellspacing='0'>" +
		"<tr><td></td></tr></table>";

	container = document.createElement("div");
	container.style.cssText = paddingMarginBorderVisibility + "width:0;height:0;position:static;top:0;margin-top:" + conMarginTop + "px";
	body.insertBefore( container, body.firstChild );

	// Construct the test element
	div = document.createElement("div");
	container.appendChild( div );

	// Check if table cells still have offsetWidth/Height when they are set
	// to display:none and there are still other visible table cells in a
	// table row; if so, offsetWidth/Height are not reliable for use when
	// determining if an element has been hidden directly using
	// display:none (it is still safe to use offsets if a parent element is
	// hidden; don safety goggles and see bug #4512 for more information).
	// (only IE 8 fails this test)
	div.innerHTML = "<table><tr><td style='" + paddingMarginBorder + "0;display:none'></td><td>t</td></tr></table>";
	tds = div.getElementsByTagName( "td" );
	isSupported = ( tds[ 0 ].offsetHeight === 0 );

	tds[ 0 ].style.display = "";
	tds[ 1 ].style.display = "none";

	// Check if empty table cells still have offsetWidth/Height
	// (IE <= 8 fail this test)
	support.reliableHiddenOffsets = isSupported && ( tds[ 0 ].offsetHeight === 0 );

	// Check if div with explicit width and no margin-right incorrectly
	// gets computed margin-right based on width of container. For more
	// info see bug #3333
	// Fails in WebKit before Feb 2011 nightlies
	// WebKit Bug 13343 - getComputedStyle returns wrong value for margin-right
	if ( window.getComputedStyle ) {
		div.innerHTML = "";
		marginDiv = document.createElement( "div" );
		marginDiv.style.width = "0";
		marginDiv.style.marginRight = "0";
		div.style.width = "2px";
		div.appendChild( marginDiv );
		support.reliableMarginRight =
			( parseInt( ( window.getComputedStyle( marginDiv, null ) || { marginRight: 0 } ).marginRight, 10 ) || 0 ) === 0;
	}

	if ( typeof div.style.zoom !== "undefined" ) {
		// Check if natively block-level elements act like inline-block
		// elements when setting their display to 'inline' and giving
		// them layout
		// (IE < 8 does this)
		div.innerHTML = "";
		div.style.width = div.style.padding = "1px";
		div.style.border = 0;
		div.style.overflow = "hidden";
		div.style.display = "inline";
		div.style.zoom = 1;
		support.inlineBlockNeedsLayout = ( div.offsetWidth === 3 );

		// Check if elements with layout shrink-wrap their children
		// (IE 6 does this)
		div.style.display = "block";
		div.style.overflow = "visible";
		div.innerHTML = "<div style='width:5px;'></div>";
		support.shrinkWrapBlocks = ( div.offsetWidth !== 3 );
	}

	div.style.cssText = positionTopLeftWidthHeight + paddingMarginBorderVisibility;
	div.innerHTML = html;

	outer = div.firstChild;
	inner = outer.firstChild;
	td = outer.nextSibling.firstChild.firstChild;

	offsetSupport = {
		doesNotAddBorder: ( inner.offsetTop !== 5 ),
		doesAddBorderForTableAndCells: ( td.offsetTop === 5 )
	};

	inner.style.position = "fixed";
	inner.style.top = "20px";

	// safari subtracts parent border width here which is 5px
	offsetSupport.fixedPosition = ( inner.offsetTop === 20 || inner.offsetTop === 15 );
	inner.style.position = inner.style.top = "";

	outer.style.overflow = "hidden";
	outer.style.position = "relative";

	offsetSupport.subtractsBorderForOverflowNotVisible = ( inner.offsetTop === -5 );
	offsetSupport.doesNotIncludeMarginInBodyOffset = ( body.offsetTop !== conMarginTop );

	if ( window.getComputedStyle ) {
		div.style.marginTop = "1%";
		support.pixelMargin = ( window.getComputedStyle( div, null ) || { marginTop: 0 } ).marginTop !== "1%";
	}

	if ( typeof container.style.zoom !== "undefined" ) {
		container.style.zoom = 1;
	}

	body.removeChild( container );
	marginDiv = div = container = null;

	jQuery.extend( support, offsetSupport );
});

return support;
});
*/




//Emulating HEAD for ie < 9
document.head || (document.head = document.getElementsByTagName('head')[0]);

"defaultView" in document || (document.defaultView = document.parentWindow);

if(DEBUG) {
	//test DOMElement is an ActiveXObject
	if(!(_Function_call.call(document_createElement, document, "div") instanceof ActiveXObject))
		console.error("DOMElement is not an ActiveXObject. Probably you in IE > 8 'compatible mode'. <element> instanceof [Node|Element|HTMLElement] wouldn't work");
}

/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  Function.prototype  ==================================  */
/*  =======================================================================================  */


//Fix Function.prototype.apply to work with generic array-like object instead of an array
// test: (function(a,b){console.log(a,b)}).apply(null, {0:1,1:2,length:2})
_tmp_ = false;
try {
	_tmp_ = isNaN.apply(null, {})
}
catch(e) { }
if(!_tmp_) {
	Function.prototype.apply = function(contexts, args) {
		try {
			return args != void 0 ?
				_Function_apply.call(this, contexts, args) :
				_Function_apply.call(this, contexts);
		}
		catch (e) {
			if(e["number"] != -2146823260 ||//"Function.prototype.apply: Arguments list has wrong type"
				args.length === void 0 || //Not an iterable object
			   typeof args === "string"//Avoid using String
			  )
				_throw(e);

			return _Function_apply.call(this, contexts, Array["from"](args));
		}
	};
}

/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  Function.prototype  ==================================  */
/*  =======================================================================================  */


/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  String.prototype  ==================================  */
/*  =======================================================================================  */

// String.prototype.substr shim
//[BUGFIX, IE lt 9] IE < 9 substr() with negative value not working in IE
if("ab".substr(-1) !== "b") {
	//String.prototype._itlt9_substr_ = String.prototype.substr;
	String.prototype.substr = function(start, length) {
		return _String_substr.call(this, start < 0 ? (start = this.length + start) < 0 ? 0 : start : start, length);
	}
}

/*
[BUGFIX, IE lt 9, old safari] http://blog.stevenlevithan.com/archives/cross-browser-split
More better solution:: http://xregexp.com/
*/
if('te'.split(/(s)*/)[1] != void 0 ||
   '1_1'.split(/(_)/).length != 3) {
   _String_split_shim_isnonparticipating = /()??/.exec("")[1] === void 0; // NPCG: nonparticipating capturing group
   
	String.prototype.split = function (separator, limit) {
		var str = this;
		// if `separator` is not a regex, use the native `split`
		if(!(separator instanceof RegExp)) {//if (Object.prototype.toString.call(separator) !== "[object RegExp]") {
			//http://es5.github.com/#x15.5.4.14
			//If separator is undefined, then the result array contains just one String, which is the this value (converted to a String). If limit is not undefined, then the output array is truncated so that it contains no more than limit elements.
			if(separator === void 0 && limit === 0)return [];
			
			return _String_split.call(str, separator, limit);
		}

		var output = [],
			lastLastIndex = 0,
			flags = (separator.ignoreCase ? "i" : "") +
					(separator.multiline  ? "m" : "") +
					(separator.sticky     ? "y" : ""),
			separator1 = new RegExp(separator.source, flags + "g"), // make `global` and avoid `lastIndex` issues by working with a copy
			separator2 = null, match, lastIndex, lastLength;

		str = str + ""; // type conversion
		if (!_String_split_shim_isnonparticipating) {
			separator2 = new RegExp("^" + separator1.source + "$(?!\\s)", flags); // doesn't need /g or /y, but they don't hurt
		}

		/* behavior for `limit`: if it's...
		- `undefined`: no limit.
		- `NaN` or zero: return an empty array.
		- a positive number: use `Math.floor(limit)`.
		- a negative number: no limit.
		- other: type-convert, then use the above rules. */
		if (limit === void 0 || +limit < 0) {
			limit = Infinity;
		} else {
			limit = Math.floor(+limit);
			if (!limit) {
				return [];
			}
		}		
		
		while (match = separator1.exec(str)) {
			lastIndex = match.index + match[0].length; // `separator1.lastIndex` is not reliable cross-browser

			if (lastIndex > lastLastIndex) {
				output.push(str.slice(lastLastIndex, match.index));

				// fix browsers whose `exec` methods don't consistently return `undefined` for nonparticipating capturing groups
				// __ NOT WORKING __ !!!!
				if (!_String_split_shim_isnonparticipating && match.length > 1) {
					match[0].replace(separator2, function() {
						for (var i = 1, a = arguments, l = a.length - 2; i < l; i++) {//for (var i = 1; i < arguments.length - 2; i++) {
							if (a[i] === void 0) {
								match[i] = void 0;
							}
						}
					});
				}

				if (match.length > 1 && match.index < str.length) {
					output.push.apply(output, match.slice(1));//Array.prototype.push.apply(output, match.slice(1));
				}

				lastLength = match[0].length;
				lastLastIndex = lastIndex;

				if (output.length >= limit) {
					break;
				}
			}

			if (separator1.lastIndex === match.index) {
				separator1.lastIndex++; // avoid an infinite loop
			}
		}

		if (lastLastIndex === str.length) {
			if (lastLength || !separator1.test("")) {
				output.push("");
			}
		} else {
			output.push(str.slice(lastLastIndex));
		}

		return output.length > limit ? output.slice(0, limit) : output;
	}
}


/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  String.prototype  ==================================  */
/*  =======================================================================================  */



/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  Exception  ==================================  */
/*  =======================================================================================  */
if(!global["DOMException"]) {
	_tmp_ = (global["DOMException"] = function() { }).prototype = new Error;
	_tmp_.INDEX_SIZE_ERR = 1;
	//p.DOMSTRING_SIZE_ERR = 2; // historical
	_tmp_.HIERARCHY_REQUEST_ERR = 3;
	_tmp_.WRONG_DOCUMENT_ERR = 4;
	_tmp_.INVALID_CHARACTER_ERR = 5;
	//p.NO_DATA_ALLOWED_ERR = 6; // historical
	_tmp_.NO_MODIFICATION_ALLOWED_ERR = 7;
	_tmp_.NOT_FOUND_ERR = 8;
	_tmp_.NOT_SUPPORTED_ERR = 9;
	//p.INUSE_ATTRIBUTE_ERR = 10; // historical
	_tmp_.INVALID_STATE_ERR = 11;
	_tmp_.SYNTAX_ERR = 12;
	_tmp_.INVALID_MODIFICATION_ERR = 13;
	_tmp_.NAMESPACE_ERR = 14;
	_tmp_.INVALID_ACCESS_ERR = 15;
	//p.VALIDATION_ERR = 16; // historical
	_tmp_.TYPE_MISMATCH_ERR = 17;
}

/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  Exception  ==================================  */
/*  =======================================================================================  */

/*  ======================================================================================  */
/*  ======================================  Window  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */

//http://javascript.gakaa.com/window-scrollx-2-0-scrolly.aspx
if(!("pageXOffset" in global)) {
	_.push(function() {
		Object.defineProperty(global, "pageXOffset", {"get" : _getScrollX});
		Object.defineProperty(global, "pageYOffset", {"get" : _getScrollY});
	});
}

/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  Window  ======================================  */
/*  ======================================================================================  */

/*  ======================================================================================  */
/*  ======================================  Events  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */

/** @constructor */
function_tmp = global["Event"] = function() {
	//new operator for Event supported in a.js
	_throw("");
};

_EventInitFunctions = {
/**
 * @param {string=} _type
 * @param {boolean=} _bubbles
 * @param {boolean=} _cancelable
 */
"initEvent" : function(_type, _bubbles, _cancelable) {
	if(_type == void 0 || _bubbles == void 0 || _cancelable == void 0) {
		//WRONG_ARGUMENTS_ERR
		_throw('WRONG_ARGUMENTS_ERR');
	}
	var thisObj = this;

	thisObj.type = _type;
		//this.cancelBubble = //TODO:: <-- testing | Need this ???
	//	!(this.bubbles = _bubbles);
	thisObj.bubbles = _bubbles;
	thisObj.cancelable = _cancelable;//https://developer.mozilla.org/en/DOM/event.cancelable

	thisObj.isTrusted = false;
	thisObj.target = null;

	if(!thisObj.timeStamp)thisObj.timeStamp = +new _Native_Date();
	}
	,
	"initCustomEvent" : function(_type, _bubbles, _cancelable, _detail) {
	//https://developer.mozilla.org/en/DOM/CustomEvent
		_EventInitFunctions["initEvent"].call(this, _type, _bubbles, _cancelable);

	this.detail = _detail;
	}
	,
	"initUIEvent" : function(_type, _bubbles, _cancelable, _view, _detail) {
	//https://developer.mozilla.org/en/DOM/event.initUIEvent
		_EventInitFunctions["initCustomEvent"].call(this, _type, _bubbles, _cancelable, _detail);

	this.view = _view;
	}
	,
	"initMouseEvent" : function(_type, _bubbles, _cancelable, _view,
                 _detail, _screenX, _screenY, _clientX, _clientY,
                 _ctrlKey, _altKey, _shiftKey, _metaKey,
                 _button, _relatedTarget) {
	var thisObj = this;
	//https://developer.mozilla.org/en/DOM/event.initMouseEvent
		_EventInitFunctions["initUIEvent"].call(thisObj, _type, _bubbles, _cancelable, _view, _detail);

	thisObj.screenX = _screenX;
	thisObj.screenY = _screenY;
	thisObj.clientX = _clientX;
	thisObj.clientY = _clientY;
    thisObj.ctrlKey = _ctrlKey;
	thisObj.altKey = _altKey;
	thisObj.shiftKey = _shiftKey;
	thisObj.metaKey = _metaKey;
	thisObj.button = _button;
	thisObj.relatedTarget = _relatedTarget;
	}
};

_Event_prototype = function_tmp.prototype = {
	constructor : function_tmp,

    /** @this {_ielt9_Event} @lends {function_tmp.prototype}*/
    "preventDefault" : function() {
        if(this.cancelable === false)return;

        _ielt9_Event.getNativeEvent.call(this)["returnValue"] = this["returnValue"] = false;
        _ielt9_Event.destroyLinkToNativeEvent.call(this);
        this["defaultPrevented"] = true;
    } ,

    /** @this {_ielt9_Event} @lends {function_tmp.prototype} */
    "stopPropagation" : function() {
        _ielt9_Event.getNativeEvent.call(this)["cancelBubble"] = this["cancelBubble"] = true;
        _ielt9_Event.destroyLinkToNativeEvent.call(this);
    }
};
/** @this {_ielt9_Event} */
_Event_prototype["stopImmediatePropagation"] = function() {
	this["__stopNow"] = true;
	this.stopPropagation();
};
_Event_prototype["defaultPrevented"] = false;

for(_tmp_ in _EventInitFunctions)if(_hasOwnProperty(_EventInitFunctions, _tmp_)) {
	_Event_prototype[_tmp_] = function() {
		_EventInitFunctions[arguments.callee["name"]].apply(this, arguments);
		_safeExtend(this[_event_nativeEventPropName], this);
	};
	_Event_prototype[_tmp_]["name"] = _tmp_;
	}

/** @constructor Event constructor for document.createEvent and commonHandle */
_ielt9_Event = function(nativeEvent) {
	this[_event_nativeEventPropName] = nativeEvent;

	nativeEvent.returnValue = true;//default value

	_safeExtend(this, nativeEvent);
};

/** @this {_ielt9_Event} */
_ielt9_Event.getNativeEvent = function() {
	var nativeEvent = this[_event_nativeEventPropName];
	if(nativeEvent === void 0) {
		_throw("WRONG_THIS_ERR")
	}
	else if(nativeEvent === null) {
		//_ielt9_Event.destroyLinkToNativeEvent was fired
		nativeEvent = _ielt9_Event.getNativeEvent.fakeObject;
	}

	return nativeEvent;
};
_ielt9_Event.getNativeEvent.fakeObject = {};

/** @this {_ielt9_Event} */
_ielt9_Event.destroyLinkToNativeEvent = function() {
	if(this[_event_nativeEventPropName]) {
		this[_event_nativeEventPropName] = null;
	}
};

//inherit _ielt9_Event from Event
/** @constructor */
function_tmp = function() { };
function_tmp.prototype = _Event_prototype;
function_tmp = new function_tmp;
function_tmp.constructor = _ielt9_Event;
_ielt9_Event.prototype = function_tmp;


//fix [add|remove]EventListener & dispatchEvent for IE < 9

// See: https://github.com/arexkun/Vine
//	    https://github.com/kbjr/Events.js
//	    Use this for tests: http://ie.microsoft.com/testdrive/HTML5/ComparingEventModels/Default.html


function fixEvent(event) {
	if("__isFixed" in event)return;

	var thisObj = this,
		_button = ("button" in event) && event.button;
	
	// один объект события может передаваться по цепочке разным обработчикам
	// при этом кроссбраузерная обработка будет вызвана только 1 раз
	// Снизу, в функции commonHandle,, мы должны проверять на !event["__isFixed"]
	event["__isFixed"] = true;// пометить событие как обработанное

	//http://javascript.gakaa.com/event-detail.aspx
	//http://www.w3.org/TR/2011/WD-DOM-Level-3-Events-20110531/#event-type-click
	//indicates the current click count; the attribute value must be 1 when the user begins this action and increments by 1 for each click.
	if(event.type === "click" || event.type === "dblclick") {
		if(event.detail === void 0)event.detail = event.type === "click" ? 1 : 2;
		if(!event.button && fixEvent._clickButton !== void 0)_button = fixEvent._clickButton;
	}

	_append(event, _Event_prototype);

	if(!event["defaultPrevented"])event["defaultPrevented"] = false;

	if(!event.target)event.target = event.srcElement || document;// добавить target для IE
	/*
	if ( event.target && event.target.nodeType in {3 : void 0, 4 : void 0} ) {
		event.target = event.target.parentNode;
	}
	*/

	// добавить relatedTarget в IE, если это нужно
	if(event.relatedTarget === void 0 && event.fromElement)
		event.relatedTarget = event.fromElement == event.target ? event.toElement : event.fromElement;
	/*
	event.relatedTarget = event.relatedTarget ||
		event.type == 'mouseout' ? event.toElement :
		event.type == 'mouseover' ? event.fromElement : null;
	*/

	// вычислить pageX/pageY для IE
	if("clientX" in event && event.pageX == null) {
		/*event.pageX = event.clientX + (_document_documentElement.scrollLeft || body && body.scrollLeft || 0) - (_document_documentElement.clientLeft || 0);
		event.pageY = event.clientY + (_document_documentElement.scrollTop || body && body.scrollTop || 0) - (_document_documentElement.clientTop || 0);*/
		//Новая вервия нуждающаяся в проверки
		event.pageX = event.clientX + _getScrollX() - (_document_documentElement.clientLeft || 0);
		event.pageY = event.clientY + _getScrollY() - (_document_documentElement.clientTop || 0);
	}

	//Add 'which' for click: 1 == left; 2 == middle; 3 == right
	//Unfortunately the event.button property is not set for click events. It is however set for mouseup/down/move ... but not click | http://bugs.jquery.com/ticket/4164 <- It is fixing now
	if(!event.which && _button)event.which = _button & 1 ? 1 : _button & 2 ? 3 : _button & 4 ? 2 : 0;

	"timeStamp" in event || (event.timeStamp = +new _Native_Date());
	
	"eventPhase" in event || (event.eventPhase = (event.target == thisObj) ? 2 : 3); // "AT_TARGET" = 2, "BUBBLING_PHASE" = 3
	
	"currentTarget" in event || (event.currentTarget = thisObj);
		
		
	// событие DOMAttrModified
	//  TODO:: недоделано
	// TODO:: Привести event во всех случаях (для всех браузеров) в одинаковый вид с newValue, prevValue, propName и т.д.
	if(!event.attrName && event.propertyName)event.attrName = _String_split.call(event.propertyName, '.')[0];//IE При изменении style.width в propertyName передаст именно style.width, а не style, как нам надо

	return event;
}

if(  __GCC__UNSTABLE_FUNCTIONS__ ) {
	function windowCaptureHandler(nativeEvent) {
		var i,
			l = _event_captureHandlerNodes.length,
			k,
			_node;

		if(l) {
			_event_globalIsCaptureIndicator = true;
			nativeEvent.eventPhase = 1;
			for(k = l - 1 ; k >= 0 ; --k)commonHandler.call(_event_captureHandlerNodes[k], nativeEvent);
			nativeEvent.eventPhase = 3;
			for(i = 0 ; i < l ; ++i)commonHandler.call(_event_captureHandlerNodes[i], nativeEvent);
			_event_globalIsCaptureIndicator = false;
			_event_captureHandlerNodes = [];
		}
	}
}

// вспомогательный универсальный обработчик. Вызывается в контексте элемента всегда this = element
function commonHandler(nativeEvent) {
	if(fixEvent === void 0) {//фильтруем редко возникающую ошибку, когда событие отрабатывает после unload'а страницы. 
		return;
	}

	var thisObj = this,
		_ = thisObj["_"],
		errors,
		errorsMessages,
		_event,
		handlersKey;

	if(_["__stop_events__"])return;

	if(    __GCC__UNSTABLE_FUNCTIONS__    && !_event_globalIsCaptureIndicator && nativeEvent.bubbles !== false && nativeEvent.type in _event_needCapturing && thisObj != global) {
		_event_captureHandlerNodes.push(this);
		_event = nativeEvent;
	}
	else {
		errors = [];
		errorsMessages = [];
		handlersKey = _event_eventsUUID + (_event_globalIsCaptureIndicator ? "-" : "");
		
		if((!_ || !_[handlersKey])) {
			if(!("__dom0__" in nativeEvent))return;
			else {
				_ || (_ = {});
				_[handlersKey] || (_[handlersKey] = {});
			}
		}
		
		// получить объект события и проверить, подготавливали мы его для IE или нет
		nativeEvent || (nativeEvent = window.event);

		if("__custom_event" in nativeEvent) {
			_event = nativeEvent;
		}
		else if(!(_event = nativeEvent["__customEvent__"])) {
			if(nativeEvent.bubbles == void 0) {
				nativeEvent.bubbles = true;
				//TODO::
				//nativeEvent.bubbles = bubbleEventMap[nativeEvent.type]
			}
			if(nativeEvent.cancelable == void 0) {
				nativeEvent.cancelable = true;
				//TODO::
				//nativeEvent.bubbles = cancelableEventMap[nativeEvent.type]
			}

		// save event properties in fake 'event' object to allow store 'event' and use it in future
			_event = nativeEvent["__customEvent__"] = new _ielt9_Event(nativeEvent);
			_event.initEvent(nativeEvent.type, nativeEvent.bubbles, nativeEvent.cancelable);
			fixEvent.call(this, _event);
			_event.isTrusted = true;
			_event["__custom_event"] = void 0;
		}

		_event[_event_nativeEventPropName] = nativeEvent;
		nativeEvent.currentTarget = thisObj;//TODO:: check it

		//if(!("__isFixed" in nativeEvent))nativeEvent = fixEvent.call(thisObj, nativeEvent);

		var handlers = _[handlersKey][_event.type];
		if("__dom0__" in nativeEvent) {
			(handlers || (handlers = []))[0] = nativeEvent["__dom0__"];
		}

		if(handlers) {
			for(var g in handlers)if(_hasOwnProperty(handlers, g)) {
				var handler = handlers[g],
					context;

				if(typeof handler === "object") {
					context = handler;
					handler = handler.handleEvent;
				}

				try {
					//Передаём контекст и объект event, результат сохраним в event['result'] для передачи значения дальше по цепочке
					if( handler &&
						(
							_event['result'] = _Function_call.call(handler, context || thisObj, _event)
						)
						=== false
					  ) {//Если вернели false - остановим обработку функций
						_event.preventDefault();
						_event.stopPropagation();
					}
				}
				catch(e) {
					errors.push(e);//Все исключения - добавляем в массив, при этом не прерывая цепочку обработчиков.
					errorsMessages.push(e.message);
					if(console)console.error(e);
				}

				if(_event["__stopNow"])break;//Мгновенная остановка обработки событий
			}
			handlers[0] = void 0;//cleanup
			delete handlers[0];

			//return changed properties in native 'event' object
			nativeEvent.returnValue = _event.returnValue;
			nativeEvent.cancelBubble = _event.cancelBubble;
			//TODO:: check out that properties need to be returned in native 'event' object or _extend(nativeEvent, event);
			
			if(errors.length == 1) {//Если была только одна ошибка - кидаем ее дальше
				_throw(errors[0])
			}
			else if(errors.length > 1) {//Иначе делаем общий объект Error со списком ошибок в свойстве errors и кидаем его
				var e = new Error("Multiple errors thrown : " + _event.type + " : " + " : " + errorsMessages.join("|"));
				e.errors = errors;
				_throw(e);
			}
		}
	}

	if(thisObj === document && !_event.cancelBubble && _event.eventPhase === 3) {
		//Emelate bubbling from document to defaultView (window) | 2 from 2
		commonHandler.call(thisObj.defaultView, _event);
		nativeEvent.cancelBubble = true;//to prevent dubble event fire on window object. First emulated, second native bubbling
	}
}

if(!document.addEventListener) {
	_Node_prototype.addEventListener = _document_documentElement.addEventListener = global.addEventListener = document.addEventListener = function(_type, _handler, useCapture) {
		//TODO:: useCapture == true
		if(typeof _handler != "function" &&
		   !(typeof _handler === "object" && _handler.handleEvent)//Registering an EventListener with a function object that also has a handleEvent property -> Call EventListener as a function
		  ) {
			return;
		}

		if(    __GCC__UNSTABLE_FUNCTIONS__     && useCapture) {
			if(!_event_needCapturing[_type]) {
				_event_needCapturing[_type] = true;
				//window.addEventListener(_type, windowCaptureHandler, true);
				window.addEventListener(_type, windowCaptureHandler);
			}
		}
		
		var /** @type {Node} */
			thisObj = this,
			/** @type {Object} */
			_ = thisObj["_"],
			/** @type {Function} */
			_callback,
			/** @type {boolean} */
			_useInteractive = false,
			/** @type {string} */
			handlersKey = _event_eventsUUID + (    __GCC__UNSTABLE_FUNCTIONS__     && useCapture ? "-" : "");

		if(thisObj == global && (!("_" in document) || !(handlersKey in document["_"]) || !(_type in document["_"][handlersKey]))) {
			//Emulate bubbling from document to defaultView (window) | 1 from 2
			document.addEventListener(_type, _event_emptyFunction, useCapture);
		}

		if(!_)_ = thisObj["_"] = {};
		//_ = _[_event_phase] || (_[_event_phase] = {});

		switch(_type) {
			case "DOMContentLoaded":
				if (document.readyState == 'complete')return;

				if(thisObj === global)thisObj = document;

				_useInteractive = true;

				if(!__is__DOMContentLoaded) {
					__is__DOMContentLoaded = true;

					function poll() {
						try { document.documentElement.doScroll('left'); } catch(e) { setTimeout(poll, 50); return; }
						commonHandler.call(thisObj, {"type" : _type, "isTrusted" : true, "__custom_event" : void 0});
					}

					if ("createEventObject" in document && "doScroll" in document.documentElement) {
						try { if(!global.frameElement)poll() } catch(e) { }
					}
				}
			break;
			case "load":
				if("tagName" in thisObj && thisObj.tagName.toUpperCase() === "SCRIPT") {//[script:onload]
					//FROM https://github.com/jrburke/requirejs/blob/master/require.js
					//Probably IE. IE (at least 6-8) do not fire
					//script onload right after executing the script, so
					//we cannot tie the anonymous define call to a name.
					//However, IE reports the script as being in "interactive"
					//readyState at the time of the define call.
					_useInteractive = true;

					//Need to use old school onreadystate here since
					//when the event fires and the node is not attached
					//to the DOM, the evt.srcElement is null, so use
					//a closure to remember the node.
					thisObj.onreadystatechange = function (evt) {
						evt = evt || window.event;
						//Script loaded but not executed.
						//Clear loaded handler, set the real one that
						//waits for script execution.
						if (thisObj.readyState === 'loaded') {
							thisObj.onreadystatechange = null;
							thisObj.attachEvent("onreadystatechange", _unSafeBind.call(commonHandler, thisObj, {"type" : _type}));
						}
					};
					_type = "readystatechange";
				}
			break;
			case "DOMMouseScroll":
				_type = "mousewheel";//TODO:: Test it

			break;
		}
		
		/*
		TODO::
		Reference: http://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-EventTarget
		If multiple identical EventListeners are registered on the same EventTarget with the same parameters the duplicate instances are discarded. They do not cause the EventListener to be called twice and since they are discarded they do not need to be removed with the removeEventListener method.
		*/
		
		
		// исправляем небольшой глюк IE с передачей объекта window
		if(thisObj.setInterval && (thisObj != global && !thisObj["frameElement"]))thisObj = global;
		
		//Назначить функции-обработчику уникальный номер. По нему обработчик можно будет легко найти в списке events[type].
		if(!_handler[_event_UUID_prop_name])_handler[_event_UUID_prop_name] = ++_event_UUID;
		
		//Инициализовать служебную структуру events и обработчик _[handleUUID]. 
		//Основная его задача - передать вызов универсальному обработчику commonHandle с правильным указанием текущего элемента this. 
		//Как и events, _[handleUUID] достаточно инициализовать один раз для любых событий.
		if(!(_callback = _[_event_handleUUID])) {
			_callback = _[_event_handleUUID] = _unSafeBind.call(commonHandler, thisObj);
		}

		//Если обработчиков такого типа событий не существует - инициализуем events[type] и вешаем
		// commonHandle как обработчик на elem для запуска браузером по событию type.
		if(!_[handlersKey])_[handlersKey] = {};
		if(!_[handlersKey][_type]) {
			_[handlersKey][_type] = {};
			
			if(!_useInteractive)//[script:onload]
				thisObj.attachEvent('on' + _type, _callback);
		}
		
		//Добавляем пользовательский обработчик в список elem[handlersKey][type] под заданным номером. 
		//Так как номер устанавливается один раз, и далее не меняется - это приводит к ряду интересных фич.
		// Например, запуск add с одинаковыми аргументами добавит событие только один раз.
		_[handlersKey][_type][_handler[_event_UUID_prop_name]] = _handler;
	};

	_Node_prototype.addEventListener["__shim__"] = true;

	_Node_prototype.removeEventListener = _document_documentElement.removeEventListener = global.removeEventListener = document.removeEventListener = function(_type, _handler, useCapture) {
		var /** @type {Node} */
			thisObj = this,
			/** @type {Object} */
			_ = thisObj["_"],
			/** @type {string} */
			handlersKey = _event_eventsUUID + (    __GCC__UNSTABLE_FUNCTIONS__     && useCapture ? "-" : ""),
			/** @type {Function} */
			_callback,
			/** @type {Array} */
			handlers,
			/** @type {String} */
			any;
		
		if(typeof _handler != "function" || !_handler[_event_UUID_prop_name] || !_)return;
		if(    __GCC__UNSTABLE_FUNCTIONS__     && useCapture && !(_type in _event_needCapturing))return;
		if(!(_callback = _[_event_handleUUID]))return;

		//_ = _[_event_phase] || (_[_event_phase] = {});
		//if(!_)return;
		//Get handlers list
		handlers = _[handlersKey] && _[handlersKey][_type];
		//Delete handler by ID
		delete handlers[_handler[_event_UUID_prop_name]];
		//Check handlers list for emptiness
		for(any in handlers)if(_hasOwnProperty(handlers, any))return;

		//If handlers list is empty - detach native event handler
		thisObj.detachEvent("on" + _type, _callback);
		//  and delete handlers container
		delete _[handlersKey][_type];

		//If no any handlers on that element
		for(any in _[handlersKey])if(_hasOwnProperty(_[handlersKey], any))return;
		// delete container of handlers containers
		delete _[handlersKey];
	};

	_Node_prototype.removeEventListener["__shim__"] = true;

	document.attachEvent("onmousedown", function(){
		fixEvent._clickButton = event.button;
	});
	document.attachEvent("onclick", function(){
		fixEvent._clickButton = void 0;
	});
}

/**
dispatchEvent
This method allows the dispatch of events into the implementations event model. Events dispatched in this manner will have the same capturing and bubbling behavior as events dispatched directly by the implementation. The target of the event is the EventTarget on which dispatchEvent is called. 
Parameters 
evt of type Event
Specifies the event type, behavior, and contextual information to be used in processing the event.
Return Value 
boolean	
The return value of dispatchEvent indicates whether any of the listeners which handled the event called preventDefault. If preventDefault was called the value is false, else the value is true.

Exceptions 
EventException	
UNSPECIFIED_EVENT_TYPE_ERR: Raised if the Event's type was not specified by initializing the event before dispatchEvent was called. Specification of the Event's type as null or an empty string will also trigger this exception
 * @param {(Event|CustomEvent)} _event is an event object to be dispatched.
 * @this {Element} is the target of the event.
 * @return {boolean} The return value is false if at least one of the event handlers which handled this event called preventDefault. Otherwise it returns true.
 */
if(!document.dispatchEvent) {
	_Node_prototype.dispatchEvent = _document_documentElement.dispatchEvent = global.dispatchEvent = document.dispatchEvent = function(_event) {
		if(!_event.type)return true;

		//reinit event
		if(!_event.returnValue)_event.returnValue = true;
	  	if(_event.cancelBubble)_event.cancelBubble = false;
        delete _event["__stopNow"];

		/**
		 * @type {Node}
		 */
		var thisObj = this;
		
		try {
			return thisObj.fireEvent("on" + _event.type, _event);
		}
		catch(e) {
			//Shim for Custome events in IE < 9
			if(e["number"] === -2147024809 ||//"invalid argument."
			   thisObj === global ||	  	 //window has no 'fireEvent' method
			   (e["number"] === -2146827850 && !(_event.bubbles = false))) {//has no such method ("fireEvent")
				_event["__custom_event"] = true;
				var node = _event.target = thisObj,
					dom0event = "on" + _event.type,
					result;

				//Всплываем событие
				while(!_event.cancelBubble && node) {//Если мы вызвали stopPropogation() - больше не всплываем событие
					if((dom0event in node && typeof node[dom0event] == "function" && (_event["__dom0__"] = node[dom0event])) ||
					   ("_" in node && _event_eventsUUID in node["_"]))//Признак того, что на элемент могли навесить событие
						commonHandler.call(node, _event);
					//Если у события отключено всплытие - не всплываем его
					node = _event.bubbles ? (node === document ? document.defaultView : node.parentNode) : null;
					if("__dom0__" in _event)_event["__dom0__"] = void 0;
				}

				result = !_event.cancelBubble;
				_event = null;
				
				return result;
			}
			else _throw(e);
		}
	};

	_Node_prototype.dispatchEvent["__shim__"] = true;
}

if(!document.createEvent) {/*IE < 9 ONLY*/
	/**
	 * https://developer.mozilla.org/en/DOM/document.createEvent
	 * Not using. param {string} eventType is a string that represents the type of event to be created. Possible event types include "UIEvents", "MouseEvents", "MutationEvents", and "HTMLEvents". See https://developer.mozilla.org/en/DOM/document.createEvent#Notes section for details.
	 */
	document.createEvent = function() {
		return new _ielt9_Event(document.createEventObject());
	}
}

/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  Events  ======================================  */
/*  ======================================================================================  */


/*  ======================================================================================  */
/*  ========================================  DOM  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */

_.push(function() {
	var _InputElement_prototype = global["HTMLInputElement"] && global["HTMLInputElement"].prototype || _Element_prototype
		, _TextAreaElement_prototype = global["HTMLTextAreaElement"] && global["HTMLTextAreaElement"].prototype
		, _setSelectionRange = function (start, end/*, selectionDirection*/) {
			if(start == void 0)return;
			if(end == void 0)end = start;

			var thisObj = this
				, nodeName = thisObj.nodeName.toUpperCase()
				, selRange
			;

			if(nodeName != "INPUT" && nodeName != "TEXTAREA")return;

			selRange = thisObj.createTextRange();
			selRange.collapse(true);
			selRange.moveStart('character', start);
			selRange.moveEnd('character', end - start);
			selRange.select();
		}
		/**
		 * @param {boolean=} needStart true - return start point, otherwise - end point
		 */
		, _getSelectionStart_or_End = function(needStart) {
			var thisObj = this
				, selection
				, nodeName = thisObj.nodeName.toUpperCase()
				, range
				, stored_range
				, str
				, start
				, result
				, curElement
				, current__stop_events__1
				, current__stop_events__2
			;

			if(nodeName != "INPUT" && nodeName != "TEXTAREA")return;

			curElement = document.activeElement;
			selection = document["selection"];

			if(thisObj["_"]) {
				current__stop_events__1 = thisObj["_"]["__stop_events__"];
				thisObj["_"]["__stop_events__"] = true;
			}
			if(curElement["_"]) {
				current__stop_events__2 = curElement["_"]["__stop_events__"];
				curElement["_"]["__stop_events__"] = true;
			}
			
			try {
				thisObj.focus();
				range = selection["createRange"]();
				stored_range = range["duplicate"]();

				if(nodeName == "TEXTAREA") {
					//TODO: compare with http://stackoverflow.com/a/4207763/1587897
					//TODO: has issue with new lines?
					str = thisObj.value;
					range = stored_range;
	
					if(needStart) {
						range.moveEnd("character", str.length);
						result = range.text == "" ? str.length : str.lastIndexOf(range.text);
					}
					else {
						range.moveStart("character", -str.length);
						result = range.text.length;
					}
				}
				else {
					stored_range.moveToElementText(this);
					stored_range.setEndPoint('EndToEnd', range);
					start = stored_range.text.length - range.text.length;
					if(needStart) {
						result = start;
					}
					else {
						result = start + range.text.length;
					}
				}

				if(curElement && curElement.focus)curElement.focus();
			}
			catch(__e__) {
				result = void 0;
			}
			
			if(thisObj["_"] && !current__stop_events__1) {
				delete thisObj["_"]["__stop_events__"];
			}
			if(curElement["_"] && !current__stop_events__2) {
				delete curElement["_"]["__stop_events__"];
			}

			return result;
		}
		, properties = {
			"setSelectionRange" : {
				"value" : _setSelectionRange
			}
			, "selectionStart" : {
				"get" : function() {
					return _getSelectionStart_or_End.call(this, true);
				}
				, "set" : function(val) {
					_setSelectionRange.call(this, val, _getSelectionStart_or_End.call(this));
					return val;
				}
			}
			, "selectionEnd" : {
				"get" : function() {
					return _getSelectionStart_or_End.call(this);
				}
				, "set" : function(val) {
					_setSelectionRange.call(this, _getSelectionStart_or_End.call(this, true), val);
					return val;
				}
			}
		}
	;
	Object.defineProperties(_InputElement_prototype, properties);
	if(_TextAreaElement_prototype)Object.defineProperties(_TextAreaElement_prototype, properties);
});

/*  =======================================================================================  */
/*  ================================  NodeList.prototype  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */

/** @constructor */
function _NodeList() {}
_NodeList.prototype = new Array;

_tmp_ = new _NodeList;
_tmp_.push(1);
if(_tmp_.length) {//IE8 standart mode
	global["NodeList"] = _NodeList;//"NodeList" in global | Rewrite broken NodeList implimentation
}
else {//IE8 quirk mode, IE lt 8
	//Internet Explorer refuses to maintain the length property of a subclass created like this | http://dean.edwards.name/weblog/2006/11/hooray/
	// create an <iframe>
	_tmp_ = document.createElement("iframe");
	_tmp_.style.display = "none";
	(document.body || _document_documentElement).appendChild(_tmp_);

	// write a script into the <iframe> and steal its Array object
	_tmp_.contentWindow.document.write(
	"<script>parent.NodeList=Array;<\/script>"
	);
	_NodeList = global["NodeList"];
}

_NodeList.prototype["item"] = function(index) {
	return this[index];
};

//Inherit NodeList from Array
function extendNodeListPrototype(nodeListProto) {
	if(nodeListProto && nodeListProto !== Array.prototype) {
		for(var key in nodeList_methods_fromArray)if(_hasOwnProperty(key, nodeList_methods_fromArray)) {
			if(!nodeListProto[key])nodeListProto[key] = function() {
				return _Function_apply.call(Array.prototype[key], Array["from"](this), arguments);
			}
		}
	}
}
if(document.querySelectorAll)extendNodeListPrototype(document.querySelectorAll("#z").constructor.prototype);
/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  NodeList.prototype  ==================================  */
/*  ======================================================================================  */

/*  ================================ bug fixing  <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */


// IE - contains fails if argument is textnode
if(__GCC__UNSTABLE_FUNCTIONS__) {
	_txtTextElement = _Function_call.call(document_createTextNode, document, "");
	_testElement.appendChild(_txtTextElement);

	try {
	    _testElement.contains(_txtTextElement);
	    _tmp_ = false;
	} catch (e) {
		_tmp_ = true;
		_Node_prototype.contains = function contains(other) {
	    	if(other.nodeType === 3) {
			    return _recursivelyWalk(this.childNodes, function (node) {
			         if (node === other) return true;
			    }) || false;
			}
			else return _Function_call.call(_Node_contains, this, other);
		};
	}
}

// IE8 hurr durr doctype is null
if (document.doctype === null && _browser_msie > 7)//TODO:: this fix for IE < 8
	_.push(function() {
		var documentShim_doctype = document.childNodes[0];
		Object.defineProperty(documentShim_doctype, "nodeType", {
			get: function () { return 10 } 
		});
	    Object.defineProperty(document, "doctype", {configurable : true, enumerable : false, get : function () { return documentShim_doctype } });
	});

// IE8 hates you and your f*ing text nodes
// I mean text node and document fragment and document no inherit from node
// Extend Text.prototype and HTMLDocument.prototype with shims
// TODO:: Do something with IE < 8
if(!_Node_prototype.contains)_Node_prototype.contains = _Node_contains;
if (!_Function_call.call(document_createTextNode, document, "").contains){
	if(global["Text"] && global["Text"].prototype) {//IE8
	    _.push(_unSafeBind.call(_append, null, Text.prototype, _Node_prototype));
	}
	else {//IE < 8 TODO:: tests
		document.createTextNode = function(text) {
			text = _Function_call.call(document_createTextNode, this, text);
			text.contains = _Node_prototype.contains;
			return text;
		}
	}
}
if (!_Function_call.call(document_createDocumentFragment, document).contains && global["HTMLDocument"] && global["HTMLDocument"].prototype) {
    _.push(_unSafeBind.call(_append, null, global["HTMLDocument"].prototype, _Node_prototype));
}


//https://developer.mozilla.org/en/DOM/Element.children
//[IE lt 9] Fix "children" property in IE < 9
if(!("children" in _testElement))_.push(function() {
	Object.defineProperty(_Element_prototype, "children", {"get" : function() {
		var arr = [],
			child = this.firstChild;

		while(child) {
			if(child.nodeType == 1)arr.push(child);
			child = child.nextSibling;
		}

		return arr;
	}});
});

//[IE lt 9] Fix "offsetLeft" and "offsetTop" properties in IE < 9
_.push(function() {
	/**
	 * http://javascript.ru/ui/offset#popytka-2:-getboundingclientrect
	 * @param {Node} elem
	 * @param {boolean=} X_else_Y
	 * @return {number}
	 */
	function unsafeGetOffsetRect(elem, X_else_Y) {
		if("element" in elem && "defaults" in elem && "document" in elem) {//IE < 8 with htc -> this instanceof DispHTCDefaultDispatch
			elem = elem["element"];
		}

		var box = elem.getBoundingClientRect()//It might be an error here
			, _body
			, _documentElement
			, _doc
		;

		if((_doc = elem.ownerDocument) !== document) {
			_body = _doc && _doc.body;
			_documentElement = _doc && _doc.documentElement;
			if(!_body || !_documentElement) {
				return X_else_Y ? box.left : box.top;
			}
		}
		else {
			_body = document.body;
			_documentElement = _document_documentElement;
		}


	 	return X_else_Y ?
	 		(box.left + _getScrollX() - (_documentElement.clientLeft || _body.clientLeft || 0))
			:
	 		(box.top + _getScrollY() - (_documentElement.clientTop || _body.clientTop || 0))
		;
	}

	/**
	 * http://javascript.ru/ui/offset#popytka-1-summiruem-offset-y
	 * @param {Node} elem
	 * @param {boolean=} X_else_Y
	 * @return {number}
	 */
	function getOffsetSum(elem, X_else_Y) {
		var result = 0,
			prop = X_else_Y ? "offsetLeft" : "offsetTop";

		while(elem) {
			result = result + parseInt(elem[prop], 10);
			elem = elem.offsetParent;
		}
	 
		return result;
	}

	/**
	 * @param {Node} elem
	 * @param {boolean=} X_else_Y
	 * @return {number}
	 */
	function safeGetOffsetRect(elem, X_else_Y) {
		var result;
		try {
			result = unsafeGetOffsetRect(elem, X_else_Y);
		}
		catch(e) {
			result = getOffsetSum(elem, X_else_Y);
		}
		return result;

		//Broken impimintation up here
		//Here right impl from jQuery
		//TODO::

		/*
		jQuery.fn.extend({

	position: function() {
		if ( !this[0] ) {
			return null;
		}

		var elem = this[0],

		// Get *real* offsetParent
		offsetParent = this.offsetParent(),

		// Get correct offsets
		offset       = this.offset(),
		parentOffset = rroot.test(offsetParent[0].nodeName) ? { top: 0, left: 0 } : offsetParent.offset();

		// Subtract element margins
		// note: when an element has margin: auto the offsetLeft and marginLeft
		// are the same in Safari causing offset.left to incorrectly be 0
		offset.top  -= parseFloat( jQuery.css(elem, "marginTop") ) || 0;
		offset.left -= parseFloat( jQuery.css(elem, "marginLeft") ) || 0;

		// Add offsetParent borders
		parentOffset.top  += parseFloat( jQuery.css(offsetParent[0], "borderTopWidth") ) || 0;
		parentOffset.left += parseFloat( jQuery.css(offsetParent[0], "borderLeftWidth") ) || 0;

		// Subtract the two offsets
		return {
			top:  offset.top  - parentOffset.top,
			left: offset.left - parentOffset.left
		};
	},

	offsetParent: function() {
		return this.map(function() {
			var offsetParent = this.offsetParent || document.body;
			while ( offsetParent && (!rroot.test(offsetParent.nodeName) && jQuery.css(offsetParent, "position") === "static") ) {
				offsetParent = offsetParent.offsetParent;
			}
			return offsetParent;
		});
	}
});
		*/

	}
	Object.defineProperties(_Element_prototype, {
		"offsetLeft" : {
			"get" : function() {
			    return safeGetOffsetRect(this, true);
			}
		},
		"offsetTop" : {
			"get" : function() {
			    return safeGetOffsetRect(this);
			}
		}
	});
});

//TODO::window.innerWidth & window.innerHeight http://www.javascripter.net/faq/browserw.htm
//TODO::https://developer.mozilla.org/en/DOM/window.outerHeight
	

//[IE lt 9, old browsers] Traversal for IE < 9 and other
if(_testElement.childElementCount == void 0)_.push(function() {
	Object.defineProperties(_Element_prototype, {
		"firstElementChild" : {//https://developer.mozilla.org/en/DOM/Element.firstElementChild
			"get" : function() {
			    var node = this;
			    node = node.firstChild;
			    while(node && node.nodeType != 1) node = node.nextSibling;
			    return node;
			}
		},
		"lastElementChild" : {//https://developer.mozilla.org/En/DOM/Element.lastElementChild
			"get" : function() {
			    var node = this;
			    node = node.lastChild;
			    while(node && node.nodeType != 1) node = node.previousSibling;
			    return node;
			}
		},
		"nextElementSibling" : {//https://developer.mozilla.org/En/DOM/Element.nextElementSibling
			"get" : function() {
			    var node = this;
			    while(node = node.nextSibling) if(node.nodeType == 1) break;
			    return node;
			}
		},
		"previousElementSibling" : {//https://developer.mozilla.org/En/DOM/Element.previousElementSibling
			"get" : function() {
			    var node = this;
			    while(node = node.previousSibling) if(node.nodeType == 1) break;
	    		return node;
			}
		}
	})
});

// IE8 can't write to ownerDocument
/*TODO:: is this realy need?
try {
    _testElement.ownerDocument = 42;
} catch (e) {
	_.push(function() {
	    var pd = Object.getOwnPropertyDescriptor(Element.prototype, "ownerDocument");
	    var ownerDocument = pd.get;
	    Object.defineProperty(Element.prototype, "ownerDocument", {
	        get: function () {
	            if (this._ownerDocument) {
	                return this._ownerDocument;
	            } else {
	                return ownerDocument.call(this);
	            }
	        },
	        set: function (v) {
	            this._ownerDocument = v;
	        },
	        configurable: true
	    });
	})
}*/


/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  bug fixing  ==================================  */






/* is this stuff defined? */
if(!document.ELEMENT_NODE) {
	_tmp_ = {
		ELEMENT_NODE : 1,
		//ATTRIBUTE_NODE : 2,// historical
		TEXT_NODE : 3,
		//CDATA_SECTION_NODE : 4,// historical
		//ENTITY_REFERENCE_NODE : 5,// historical
		//ENTITY_NODE : 6,// historical
		PROCESSING_INSTRUCTION_NODE : 7,
		COMMENT_NODE : 8,
		DOCUMENT_NODE : 9,
		DOCUMENT_TYPE_NODE : 10,
		DOCUMENT_FRAGMENT_NODE : 11
		//NOTATION_NODE : 12// historical
	};
	_append(document, _tmp_);
	_append(_Node_prototype, _tmp_);
	_append(global["Node"], _tmp_);
}
/*var __ielt8__element_init__ = _Node_prototype["__ielt8__element_init__"];
if(__ielt8__element_init__) {//__ielt8__element_init__ in a.ielt8.js
	__ielt8__element_init__["plugins"].push(function(el) {
		_append(el, tmp);
	})
}*/

//https://developer.mozilla.org/En/DOM/Node.textContent
if(DEBUG && !("textContent" in _testElement)) {
	if(!('innerText' in _testElement) &&
	   (!('data' in _testElement) || !_testElement.appendData))
		_throw("IE is too old");
}
if(!("textContent" in _testElement))
	_.push(function() {
		Object.defineProperty(_Node_prototype, "textContent", {
			"get" : function() {
				if('innerText' in this)return this.innerText;
				if('data' in this && this.appendData)return this.data;
			},
			"set" : function(val) {
				if('innerText' in this)this.innerText = val;
				else if('data' in this && this.replaceData)this.replaceData(0, this.length, val);
				
				return val;
			}
		});
	});


//https://developer.mozilla.org/en/Document_Object_Model_(DOM)/Node.isEqualNode
if(!("isEqualNode" in _testElement)) {
	document.isEqualNode = _document_documentElement.isEqualNode = _Node_prototype.isEqualNode = function(node) {
		var i, len;

	    if(node === null ||
	       node.nodeType !== this.nodeType)return false;

	    if (node.nodeType === 10/*Node.DOCUMENT_TYPE_NODE*/) {
	        if (this.name !== node.name ||
	            this.publicId !== node.publicId ||
	            this.systemId !== node.systemId 
	        )return false;
	    }

	    if (node.nodeType === 1/*Node.ELEMENT_NODE*/) {
	        if (this.namespaceURI != node.namespaceURI ||
	            this.prefix != node.prefix ||
	            this.localName != node.localName
	        ) {
	            return false;
	        }
	        for (i = 0, len = this.attributes.length; i < len; i++) {
	            var attr = this.attributes[length];
	            var nodeAttr = node.getAttributeNS(attr.namespaceURI, attr.localName);
	            if (nodeAttr === null || nodeAttr.value !== attr.value)
	                return false;
	        }
	    }

	    if (node.nodeType === 7/*Node.PROCESSING_INSTRUCTION_NODE*/) {
	        if (this.target !== node.target || this.data !== node.data)
	            return false;
	    }

	    if (node.nodeType === 3/*Node.TEXT_NODE*/ || node.nodeType === 8/*Node.COMMENT_NODE*/) {
	        if (this.data !== node.data)
	            return false;
	    }
	    if (node.childNodes.length !== this.childNodes.length)return false;

	    for (i = 0, len = node.childNodes.length; i < len; i++) {
	        var isEqual = node.childNodes[i].isEqualNode(this.childNodes[i]);
	        if (isEqual === false) {
	            return false;
	        }
	    }

	    return true;
	};
}
/*
http://www.alistapart.com/articles/crossbrowserscripting
*/
if(!document.importNode) {
	document.importNode = function(node, allChildren) {
		/* find the node type to import */
		switch (node.nodeType) {
			case 1://document.ELEMENT_NODE:
				var newNode = document.createElement(node.nodeName),//create a new element
					attrs = node.attributes,
					attr,
					_childNodes,
					i,
					il;
					
				/* does the node have any attributes to add? */
				if (attrs && attrs.length > 0)
					/* add all of the attributes */
					for (i = 0, il = attrs.length ; i < il ;) {
						attr = node.attributes[i++];
						newNode.setAttribute(attr.nodeName, node.getAttribute(attr.nodeName));
					}
				/* are we going after children too, and does the node have any? */
				if (allChildren && (_childNodes = node.childNodes) && _childNodes.length > 0)
					/* recursively get all of the child nodes */
					for (i = 0, il = _childNodes.length; i < il;)
						newNode.appendChild(document.importNode(_childNodes[i++], allChildren));
				return newNode;
			break;
			
			case 3://document.TEXT_NODE:
			case 4://document.CDATA_SECTION_NODE:
			case 8://document.COMMENT_NODE:
				return document.createTextNode(node.nodeValue);
			break;
		}
		_throwDOMException("NOT_SUPPORTED_ERR");
		return null;
	};
	document.importNode["shim"] = true;
}

_tmp_ = 'compareDocumentPosition';
if(!(_tmp_ in document)) {
	var __name,
		__n1 = __name || 'DOCUMENT_POSITION_';//Use '__name || ' only for GCC not to inline __n1 param. In this case __name MUST be undefined
	_document_documentElement[_tmp_] = document[_tmp_] = _Node_prototype[_tmp_] = function(b) {
		var a = this;
		
		//compareDocumentPosition from http://ejohn.org/blog/comparing-document-position/
		return a.contains ?
				(a != b && a.contains(b) && 16) +
				(a != b && b.contains(a) && 8) +
				(a["sourceIndex"] >= 0 && b["sourceIndex"] >= 0 ?
					(a["sourceIndex"] < b["sourceIndex"] && 4) +
					(a["sourceIndex"] > b["sourceIndex"] && 2) :
				1) +
			0 : 0;
	};
	__name = 'DISCONNECTED';
	_document_documentElement[__n1 + __name] = document[__n1 + __name] = _Node_prototype[__n1 + __name] = 0x01;
	__name = 'PRECEDING';
	_document_documentElement[__n1 + __name] = document[__n1 + __name] = _Node_prototype[__n1 + __name] = 0x02;
	__name = 'FOLLOWING';
	_document_documentElement[__n1 + __name] = document[__n1 + __name] = _Node_prototype[__n1 + __name] = 0x04;
	__name = 'CONTAINS';
	_document_documentElement[__n1 + __name] = document[__n1 + __name] = _Node_prototype[__n1 + __name] = 0x08;
	__name = 'CONTAINED_BY';
	_document_documentElement[__n1 + __name] = document[__n1 + __name] = _Node_prototype[__n1 + __name] = 0x10;
}

if(!global.getComputedStyle) {//IE < 9
	/**
	 * @link https://developer.mozilla.org/en/DOM/window.getComputedStyle
	 * getCurrentStyle - функция возвращяет текущий стиль элемента
	 * @param {?Node} element HTML-Элемент
	 * @param {?string} pseudoElt A string specifying the pseudo-element to match. Must be null (or not specified) for regular elements.
	 * @this {Window}
	 * @return {CSSStyleDeclaration} Стиль элемента
	 */
	global.getComputedStyle = function(element, pseudoElt) {
        var _currentStyle = element.currentStyle || element.runtimeStyle;
		if(!("getPropertyValue" in _currentStyle)) {
			element.runtimeStyle.getPropertyValue = _CSSStyleDeclaration_prototype_methods["getPropertyValue"].bind(element);

			element.runtimeStyle.setProperty = element.runtimeStyle.removeProperty = function() {
				_throwDOMException("NO_MODIFICATION_ALLOWED_ERR");
			};

			element.runtimeStyle.getPropertyPriority = function(propertyName) {
				//TODO: tests
				return "";
			};
			element.runtimeStyle.item = _CSSStyleDeclaration_prototype_methods["item"];
		}

		var propDescription
			, _CSSStyleDeclProt
		;
		if((_CSSStyleDeclProt = global["CSSStyleDeclaration"]) && (_CSSStyleDeclProt = _CSSStyleDeclProt.prototype) && (!("float" in _currentStyle) || !("opacity" in _currentStyle))) {
			if(!("float" in _currentStyle)) {//TODO:: testing
				propDescription = Object.getOwnPropertyDescriptor(_CSSStyleDeclProt, "float");
				if(propDescription) {
					delete _CSSStyleDeclProt["float"];
				}
				Object.defineProperty(element.runtimeStyle, "float", {
					value : {
						valueOf : _unSafeBind.call(function(){
							return this.runtimeStyle.styleFloat || this.style.styleFloat
						}, element)
					}
				});
				if(propDescription) {
					Object.defineProperty(_CSSStyleDeclProt, "float", propDescription);
				}
			}

			if(!("opacity" in _currentStyle)) {//TODO:: testing
				propDescription = Object.getOwnPropertyDescriptor(_CSSStyleDeclProt, "opacity");
				if(propDescription) {
					delete _CSSStyleDeclProt["opacity"];
				}
				Object.defineProperty(element.runtimeStyle, "opacity", {
					value : {
						valueOf : _unSafeBind.call(function(){
							return this.runtimeStyle["msOpacity"] || this.style["msOpacity"] || style_getOpacityFromMSFilter.call(this.style) || style_getOpacityFromMSFilter.call(this.runtimeStyle);
						}, element)
					}
				});
				if(propDescription) {
					Object.defineProperty(_CSSStyleDeclProt, "opacity", propDescription);
				}
			}
		}
		else {
			if(!("float" in _currentStyle)) {
				element.runtimeStyle["float"] = {
					valueOf : _unSafeBind.call(function(){
						return this.runtimeStyle.styleFloat || this.style.styleFloat
					}, element)
				}
			}
			if(!("opacity" in _currentStyle)) {
				element.runtimeStyle["opacity"] = {
					valueOf : _unSafeBind.call(function(){
						return this.runtimeStyle["msOpacity"] || this.style["msOpacity"] || style_getOpacityFromMSFilter.call(this.style) || style_getOpacityFromMSFilter.call(this.runtimeStyle);
					}, element)
				}
			}
		}

		return element.currentStyle;
	}
}

//HTML5 shiv for IE < 9
document.createDocumentFragment = function() {
	var df =
			_Function_call.call(document_createDocumentFragment, this);

	if(global["DocumentFragment"] === global["Document"]) {
		//TODO:: if DocumentFragment is a fake DocumentFragment -> append each instance with Document methods
		_append(df, global["DocumentFragment"].prototype);//TODO: tests
	}

	return html5_document(df);
};

if(__GCC__INCLUDE_DOMPARSER_SHIM__) {
	if(!("DOMParser" in global)) {
		(function(global){
			var _DOMParser = global["DOMParser"] = function(){};

			function prepareTextForIFrame(text) {
				return text
					.replace(/<script[^>]*>[\s\S]*?<\/script>/gi, '')//remove script tags from HTML text
					//TODO:: not remove all <script (.*?)>, just <script>, <script type="text/javascript">, <script type="">, <script type="text/vbscript">. Due <script> can contains a template
					.replace(/"/g, '\\"')
				;
			}

			//http://stackoverflow.com/questions/4935664/how-to-create-a-new-htmldocument-in-ie
			_DOMParser.prototype["parseFromString"] = function(markup, type) {
				if(!type || type == "text/html" || /xml$/.test(type)) {
					markup = prepareTextForIFrame(markup);

					var iframe = document.createElement('iframe');
					iframe.style.display = 'none';
					iframe.src = 'javascript:document.write("' + markup + '")';
					document.body.appendChild(iframe);
					newHTMLDocument = iframe.contentDocument || iframe.contentWindow.document;

					newHTMLDocument["__destroy__"] = function() {
						var _doc = this.contentWindow.document;
						_doc.documentElement.innerHTML = "";
						_doc["_"] = _doc.documentElement["_"] = void 0;
						/*TODO:: filter build-in properties suche as "URL", "location", etc
						 Object.keys(_doc).forEach(function(key){
						 try{
						 _doc[key] = void 0;
						 }
						 catch(e){}
						 })
						 */
						document.body.removeChild(this);
					}.bind(iframe);

					markup = iframe = void 0;

					//TODO::
					//shimDocument(newHTMLDocument);
					newHTMLDocument.querySelector = document.querySelector;
					newHTMLDocument.querySelectorAll = document.querySelectorAll;

					return newHTMLDocument;
				}
				else {
					//Not supported
					return null;
				}
			}
		})(global);
	}
}



/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  HTML5 shiv  ==================================  */
/*  =======================================================================================  */

supportsUnknownElements = ((_testElement.innerHTML = '<x-x></x-x>'), _testElement.childNodes.length === 1 && _testElement.childNodes[0].nodeType === 1);
	
html5_elements = "|" + html5_elements + "|";

function shivedCreateElement(nodeName) {
	var node = this["__orig__createElement__"](nodeName);

	if(ielt9_elements.test(nodeName))return node;

	if(!~html5_elements.indexOf("|" + nodeName + "|")) {
		html5_elements_array.push(nodeName);
		html5_elements += (nodeName + "|");
		(safeFragment["__orig__createElement__"] || safeFragment.createElement/* || function(){}*/)(nodeName);
		//node.document.createElement(nodeName);
	}
	
	return safeFragment.appendChild(node);
}
shivedCreateElement["ielt9"] = true;

/** Making a document HTML5 element safe
 * Функция "включает" в IE < 9 HTML5 элементы
 * @param {Document} doc
 */
function html5_document(doc) { // pass in a document as an argument
	// create an array of elements IE does not support
	var a = -1;

	if(doc.createElement) {
		while (++a < html5_elements_array.length) { // loop through array
			doc.createElement(html5_elements_array[a]); // pass html5 element into createElement method on document
		}
		
		if(doc.createElement !== shivedCreateElement && !("ielt9" in doc.createElement)) {
			doc["__orig__createElement__"] = doc.createElement;
			doc.createElement = shivedCreateElement;
		}
	}

	return doc; // return document, great for safeDocumentFragment = html5_document(document.createDocumentFragment());
} // critique: array could exist outside the function for improved performance?

safeFragment = html5_document(_Function_call.call(document_createDocumentFragment, document));

if(!supportsUnknownElements) {
	 html5_document(document);
	 //style
	document.head.insertAdjacentHTML("beforeend", "<br><style>" +//<br> need for all IE
		// corrects block display not defined in IE6/7/8/9
		"article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}" +
		// adds styling not present in IE6/7/8/9
		"mark{background:#FF0;color:#000}" +
	"</style>");
}

//Test for broken 'cloneNode'
if(_Function_call.call(document_createElement, document, "x-x").cloneNode().outerHTML.indexOf("<:x-x>") === 0) {
	safeElement = safeFragment.appendChild("createElement" in safeFragment && safeFragment.createElement("div") || safeFragment.ownerDocument.createElement("div"));
	_nativeCloneNode = 
		_browser_msie === 8 ?
			_testElement["cloneNode"] :
			_browser_msie < 8 ?
				_Node_prototype["cloneNode"] : void 0;
	
	/**
	 * Issue: <HTML5_elements> become <:HTML5_elements> when element is cloneNode'd
	 * Solution: use an alternate cloneNode function, the default is broken and should not be used in IE anyway (for example: it should not clone events)
	 * В Internet Explorer'е функция <HTMLElement>.cloneNode "ломает" теги HTML5 при клонировании,
	 *  поэтому нужно использовать альтернативный способ клонирования
	 *
	 * Больше по теме: http://pastie.org/935834
	 *
	 * Альтернатива <Node>.cloneNode в IE < 9
	 * @param {boolean=} include_all [false] Клонировать ли все дочерние элементы? По-умолчанию, false
	 * @this {Node} element Элемент для клонирования
	 * @version 4
	 */
	_Node_prototype["cloneNode"] = function(include_all) {//Экспортируем cloneElement для совместимости и для вызова напрямую	
		var element = this,
			result,
			nodeBody;
		
		if(ielt9_elements.test(element.nodeName)) {//HTML4 element?
			result = _Function_call.call(element["__nativeCloneNode__"] || _nativeCloneNode, element, include_all);
		}
		else {//HTML5 element?
			safeElement.innerHTML = "";//Очистим от предыдущих элементов

			// set HTML5-safe element's innerHTML as input element's outerHTML
			if(include_all)nodeBody = element.outerHTML;
			else nodeBody = element.outerHTML.replace(element.innerHTML, "");
		
			safeElement.innerHTML = nodeBody.replace(/^\<\:/, "<").replace(/\<\/\:([\w\-]*\>)$/, "<$1");

			result = safeElement.firstChild; // return HTML5-safe element's first child, which is an outerHTML clone of the input element

			if(!result && !include_all) {//IE < 9 fail to create unknown tag
				//if(!result && include_all)->sinensy faild due can't write a solution
				nodeBody = nodeBody.match(RE_cloneElement_tagMatcher);
				if(nodeBody)nodeBody = nodeBody[1];
				if(nodeBody) {
					safeFragment.createElement(nodeBody);
					safeElement.innerHTML = nodeBody;
					result = safeElement.firstChild;
				}
			}
		}
			
		return safeFragment.appendChild(result);
	};

}


/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  HTML5 shiv  ======================================  */
/*  ======================================================================================  */


/*  ======================================================================================  */
/*  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  DOM  =======================================  */



//_testElement = _txtTextElement = tmp = function_tmp = nodeList_methods_fromArray = supportsUnknownElements = void 0;


/*  ======================================================================================  */
/*  ================================= Only for IE8 =======================================  */

/** @const */
var RE_REPLACER_FOR_ELEMENTS_BY_CLASSNAME = /\s+(?=\S)|^/g

	, _native_Node_getAttribute = _Element_prototype.getAttribute

	, _native_Node_setAttribute = _Element_prototype.setAttribute

	, _native_Node_removeAttribute = _Element_prototype.removeAttribute
;

//separate properties and attributes
DEFAULT_ATTRIBUTES_MAP.elementsToCheck = {};
DEFAULT_ATTRIBUTES_MAP.checkIfAttributeDefault = function(node, attrName) {
	if(attrName in this) {
		return this[attrName];
	}

	var nodeName = node.nodeName
		, result
	;
	if(!(node = DEFAULT_ATTRIBUTES_MAP.elementsToCheck[nodeName])) {
		node = DEFAULT_ATTRIBUTES_MAP.elementsToCheck[nodeName] = document_createElement(nodeName);
	}

	result = _native_Node_getAttribute.call(node, attrName) !== null;
	if(!result) {
		result =
			(result = node.constructor)
			&& (result = result.prototype)
			&& (result = Object.getOwnPropertyDescriptor(result, attrName))
			&& /\[native\scode\]/.test(result["get"] + "")
		;
	}

	return this[attrName] = result;
};
_Element_prototype.setAttribute = function(name, value) {
	var lowerName = name.toLowerCase();

	if(ATTRIBUTES_CUSTOM[lowerName] !== void 0) {
		name = ATTRIBUTES_CUSTOM[lowerName];
	}
	else if(ATTRIBUTE_URIDATA[lowerName] === void 0 && lowerName.indexOf("data-") !== 0 && !DEFAULT_ATTRIBUTES_MAP.checkIfAttributeDefault(this, lowerName)) {
		name = name.toUpperCase();
	}

	return _native_Node_setAttribute.call(this, name, value + "");
};
_Element_prototype.getAttribute = function(name) {
	var upperName
		, lowerName = name.toLowerCase()
		, result
	;

	if(ATTRIBUTES_CUSTOM[lowerName] !== void 0) {
		return _native_Node_getAttribute.call(this, ATTRIBUTES_CUSTOM[lowerName])
	}
	else if(ATTRIBUTE_URIDATA[lowerName] !== void 0) {
		return (result = this.attributes) && (result = result[lowerName]) && (result = result.nodeValue) && (result + "") || null;
	}
	else if(lowerName.indexOf("data-") !== 0 && !DEFAULT_ATTRIBUTES_MAP.checkIfAttributeDefault(this, lowerName)) {
		upperName = name.toUpperCase();
	}
	else {
		return _native_Node_getAttribute.call(this, name);
	}

	result = this[upperName];

	if(!result) {
		if(!(upperName in this) && (typeof (result = this[name]) === "string")) {
			result = this[upperName] = this[name];
			delete this[name];
		}
		else result = void 0;
	}

	return result ? result + "" : null;
};
_Element_prototype.removeAttribute = function(name) {
	var upperName
		, lowerName = name.toLowerCase()
        , result
    ;

	if(ATTRIBUTES_CUSTOM[lowerName] !== void 0) {
		return _native_Node_removeAttribute.call(this, ATTRIBUTES_CUSTOM[lowerName])
	}
	else if(ATTRIBUTE_URIDATA[lowerName] === void 0 && lowerName.indexOf("data-") !== 0 && !DEFAULT_ATTRIBUTES_MAP.checkIfAttributeDefault(this, lowerName)) {
		upperName = name.toUpperCase();
	}
	else {
		return _native_Node_removeAttribute.call(this, name);
	}

	result = upperName in this;

	if(result || this.getAttribute(name) !== null) {
		_native_Node_removeAttribute.call(this, upperName);
		delete this[upperName];
	}

	return result;
};

//getElementsByClassName shim
_tmp_ = "getElementsByClassName";
if(!_Element_prototype[_tmp_] && document.querySelectorAll) {
	_Element_prototype[_tmp_] = _document_documentElement[_tmp_] = document[_tmp_] = function(names) {
		//Here native querySelectorAll in IE8
		if(!names || !(names = _String_trim.call(names)))return new _NodeList;
		return _Function_call.call(this.querySelectorAll || document.querySelectorAll, this, names.replace(RE_REPLACER_FOR_ELEMENTS_BY_CLASSNAME, "."))
	};
}

if(_tmp_ = global["CSSStyleDeclaration"]) {
	if(!("opacity" in _document_documentElement.style)) {
		Object.defineProperty(_tmp_.prototype, "opacity", {
			"get" : style_getOpacityFromMSFilter,
			"set" : function(newVal) {
				if(typeof newVal == "object") {
					newVal = newVal.valueOf();
				}

				if(this.opacity !== style_getOpacityFromMSFilter.call(this)) {//Preset from style attribute
					delete this.opacity;
				}

				//DXImageTransform.Microsoft.Alpha(opacity=%d)
				//progid:DXImageTransform.Microsoft.Alpha(Opacity=%d)
				newVal = newVal && "alpha(opacity=" + (newVal >= 0.9999 ? "100" : newVal < 0 ? 0 : ~~(newVal * 100)) + ")" || "";

				if (RE_style_alpha_filter.test(this.filter)) {
					this.filter = this.filter.replace(RE_style_alpha_filter, newVal)
				}
				else {
					//this.zoom = 1; //need this???
					this.filter += " " + newVal;
				}

				this["msOpacity"] = this.opacity;
			}
		});
		Object.defineProperty(_tmp_.prototype, "msOpacity", {
			value : ""
		});
	}

	if(!("float" in _document_documentElement.style)) {
		Object.defineProperty(_tmp_.prototype, "float", {
			"get" : function() {
				return this.styleFloat
			},
			"set" : function(newVal) {
				return (this.styleFloat = newVal);
			}
		});
	}

	if(!("getPropertyValue" in _tmp_)) {
		(function(methos) {
			for(var i in methos) if(_hasOwnProperty.call(methos, i)) {
				this[i] = methos[i];
			}
		}).call(_tmp_, _CSSStyleDeclaration_prototype_methods);
	}
}

(function(_document_querySelectorAll, _document_querySelector, _Element_prototype_querySelectorAll, _Element_prototype_querySelector) {
	function _NodeList_from(iterable) {
      var length = iterable.length >>> 0,
        result = new _NodeList;

      for(var key = 0 ; key < length ; key++) {
        if(key in iterable)
          result.push(iterable[key]);
      }

      return result;
    }

	document.querySelectorAll = function() {
		return _NodeList_from(_document_querySelectorAll.apply(this, arguments))
	};
	document.querySelector = function() {
		return _document_querySelector.apply(this, arguments)
	};
	_Element_prototype.querySelectorAll = function() {
		return _NodeList_from(_Element_prototype_querySelectorAll.apply(this, arguments))
	};
	_Element_prototype.querySelector = function() {
		return _Element_prototype_querySelector.apply(this, arguments)
	};
})(document.querySelectorAll, document.querySelector, _Element_prototype.querySelectorAll, _Element_prototype.querySelector);


_testElement = _txtTextElement = _tmp_ = function_tmp = nodeList_methods_fromArray = supportsUnknownElements = void 0;


})(window, /** @const */function(obj, extention) {
		for(var key in extention)
			if(Object.prototype.hasOwnProperty.call(extention, key) && !Object.prototype.hasOwnProperty.call(obj, key))
				obj[key] = extention[key];
		
		return obj;
	});

steal('can/util','can/view', 'can/util/string', 'can/observe/compute',function( can ) {

	// ## ejs.js
	// `can.EJS`  
	// _Embedded JavaScript Templates._

	// Helper methods.
	var myEval = function( script ) {
		eval(script);
	},
		extend = can.extend,
		// Regular expressions for caching.
		quickFunc = /\s*\(([\$\w]+)\)\s*->([^\n]*)/,
		attrReg = /([^\s]+)=$/,
		newLine = /(\r|\n)+/g,
		attributeReplace = /__!!__/g,		
		tagMap = {
			"": "span", 
			table: "tr", 
			tr: "td", 
			ol: "li", 
			ul: "li", 
			tbody: "tr",
			thead: "tr",
			tfoot: "tr",
			select: "option",
			optgroup: "option"
		},
		tagToContentPropMap= {
			option: "textContent",
			textarea: "value"
		},
		// Escapes characters starting with `\`.
		clean = function( content ) {
			return content
				.split('\\').join("\\\\")
				.split("\n").join("\\n")
				.split('"').join('\\"')
				.split("\t").join("\\t");
		},
		bracketNum = function(content){
			return (--content.split("{").length) - (--content.split("}").length);
		},
		// Cross-browser attribute methods.
		// These should be mapped to the underlying library.
		attrMap = {
			"class" : "className",
			"value": "value",
			"textContent" : "textContent"
		},
		bool = can.each(["checked","disabled","readonly","required"], function(n){
			attrMap[n] = n;
		}),
		setAttr = function(el, attrName, val){
			// if this is a special property
			if ( attrMap[attrName] ) {
				// set the value as true / false
				el[attrMap[attrName]] = can.inArray(attrName,bool) > -1 ? true  : val;
			} else {
				el.setAttribute(attrName, val);
			}
		},
		getAttr = function(el, attrName){
			return attrMap[attrName]?
				el[attrMap[attrName]]:
				el.getAttribute(attrName);
		},
		removeAttr = function(el, attrName){
			if(can.inArray(attrName,bool) > -1){
				el[attrName] = false;
			} else{
				el.removeAttribute(attrName);
			}
		},
		// a helper to get the parentNode for a given element el
		// if el is in a documentFragment, it will return defaultParentNode
		getParentNode = function(el, defaultParentNode){
			return defaultParentNode && el.parentNode.nodeType === 11 ? defaultParentNode : el.parentNode;
		},
		// helper to know if property is not an expando on oldObserved's list of observes
		// this should probably be removed and oldObserved should just have a
		// property with observes
		observeProp = function(name){
			return name.indexOf("|") >= 0;
		},
		// a mapping of element ids to nodeList ids
		nodeMap = {},
		// a mapping of nodeList ids to nodeList
		nodeListMap = {},
		expando = "ejs_"+Math.random(),
		_id=0,
		id = function(node){
			if ( node[expando] ) {
				return node[expando];
			} else {
				return node[expando] = (node.nodeName ? "element_" : "obj_")+(++_id);
			}
		},
		// 
		register= function(nodeList){
			var nLId = id(nodeList);
			nodeListMap[nLId] = nodeList;
			
			can.each(nodeList, function(node){
				addNodeListId(node, nLId);
			});
		},
		addNodeListId = function(node, nodeListId){
			var nodeListIds = nodeMap[id(node)];
				if(!nodeListIds){
					nodeListIds = nodeMap[id(node)] = [];
				}
				nodeListIds.push(nodeListId);
		},
		unregister= function(nodeList){
			var nLId = id(nodeList);
			can.each(nodeList, function(node){
				removeNodeListId(node, nLId);
			});
			delete nodeListMap[nLId];
		},
		// removes a nodeListId from a node's nodeListIds
		removeNodeListId= function(node, nodeListId){
			var nodeListIds = nodeMap[id(node)];
			if( nodeListIds ) {
				var index = can.inArray(nodeListId, nodeListIds);
			
				if ( index >= 0 ) {
					nodeListIds.splice( index ,  1 );
				}
				if(!nodeListIds.length){
					delete nodeMap[id(node)];
				}
			}
			
		},
		// all lists
		replace= function(oldNodeList, newNodes){
			// for each node in the node list
			oldNodeList = can.makeArray( oldNodeList );
			
			can.each( oldNodeList, function(node){
				// for each nodeList the node is in
				can.each( can.makeArray( nodeMap[id(node)] ), function( nodeListId ){
					var nodeList = nodeListMap[nodeListId];
					var startIndex = can.inArray( node, nodeList ),
						endIndex = can.inArray( oldNodeList[oldNodeList.length - 1], nodeList  );
					
					// remove this nodeListId from each node
					if(startIndex >=0 && endIndex >= 0){
						//
						for( var i = startIndex; i <= endIndex; i++){
							var n = nodeList[i];
							removeNodeListId(n, nodeListId);
						}
						// swap in new nodes into the nodeLIst
						nodeList.splice.apply(nodeList, [startIndex,endIndex-startIndex+1 ].concat(newNodes));
						// tell these new nodes they belong to the nodeList
						can.each(newNodes, function( node ) {
							addNodeListId(node, nodeListId);
						});
					} else {
						unregister( nodeList );
					}
					
				});
				
			});
		},
		// Returns escaped/sanatized content for anything other than a live-binding
		contentEscape = function( txt ) {
			return (typeof txt == 'string' || typeof txt == 'number') ?
				can.esc( txt ) :
				contentText(txt);
		},
		// Returns text content for anything other than a live-binding 
		contentText =  function( input ) {	
			
			// If it's a string, return.
			if ( typeof input == 'string' ) {
				return input;
			}
			// If has no value, return an empty string.
			if ( !input && input !== 0 ) {
				return '';
			}

			// If it's an object, and it has a hookup method.
			var hook = (input.hookup &&

			// Make a function call the hookup method.
			function( el, id ) {
				input.hookup.call(input, el, id);
			}) ||

			// Or if it's a `function`, just use the input.
			(typeof input == 'function' && input);

			// Finally, if there is a `function` to hookup on some dom,
			// add it to pending hookups.
			if ( hook ) {
				pendingHookups.push(hook);
				return '';
			}

			// Finally, if all else is `false`, `toString()` it.
			return "" + input;
		},
		// The EJS constructor function
		EJS = function( options ) {
			// Supports calling EJS without the constructor
			// This returns a function that renders the template.
			if ( this.constructor != EJS ) {
				var ejs = new EJS(options);
				return function( data, helpers ) {
					return ejs.render(data, helpers);
				};
			}
			// If we get a `function` directly, it probably is coming from
			// a `steal`-packaged view.
			if ( typeof options == "function" ) {
				this.template = {
					fn: options
				};
				return;
			}
			// Set options on self.
			extend(this, options);
			this.template = scan(this.text, this.name);
		};

	can.EJS = EJS;
	/** 
	 * @Prototype
	 */
	EJS.prototype.
	/**
	 * Renders an object with view helpers attached to the view.
	 * 
	 *     new EJS({text: "<%= message %>"}).render({
	 *       message: "foo"
	 *     },{helper: function(){ ... }})
	 *     
	 * @param {Object} object data to be rendered
	 * @param {Object} [extraHelpers] an object with view helpers
	 * @return {String} returns the result of the string
	 */
	render = function( object, extraHelpers ) {
		object = object || {};
		return this.template.fn.call(object, object, new EJS.Helpers(object, extraHelpers || {}));
	};
	/**
	 * @Static
	 */
	extend(EJS, {
		// Called to return the content within a magic tag like `<%= %>`.
		// - escape - if the content returned should be escaped
		// - tagName - the tag name the magic tag is within or the one that proceeds the magic tag
		// - status - where the tag is in.  The status can be:
		//    - _STRING_ - The name of the attribute the magic tag is within
		//    - `1` - The magic tag is within a tag like `<div <%= %>>`
		//    - `0` - The magic tag is outside (or between) tags like `<div><%= %></div>`
		// - self - the `this` the template was called with
		// - func - the "wrapping" function.  For example:  `<%= task.attr('name') %>` becomes
		//   `(function(){return task.attr('name')})
		/**
		 * @hide
		 * called to setup unescaped text
		 * @param {Number|String} status
		 *   - "string" - the name of the attribute  <div string="HERE">
		 *   - 1 - in an html tag <div HERE></div>
		 *   - 0 - in the content of a tag <div>HERE</div>
		 *   
		 * @param {Object} self
		 * @param {Object} func
		 */
		txt: function(escape, tagName, status, self, func){
			// call the "wrapping" function and get the binding information
			var binding = can.compute.binder(func, self, function(newVal, oldVal){
				// call the update method we will define for each
				// type of attribute
				update(newVal, oldVal);
			});
			
			// If we had no observes just return the value returned by func.
			if(!binding.isListening){
				return (escape || status !== 0? contentEscape : contentText)(binding.value);
			}
			// The following are helper methods or varaibles that will
			// be defined by one of the various live-updating schemes.
			
			// The parent element we are listening to for teardown
			var	parentElement,
				nodeList,
				teardown= function(){
					binding.teardown();
					if ( nodeList ) {
						unregister( nodeList );
					}
				},
				// if the parent element is removed, teardown the binding
				setupTeardownOnDestroy = function(el){
					can.bind.call(el,'destroyed', teardown);
					parentElement = el;
				},
				// if there is no parent, undo bindings
				teardownCheck = function(parent){
					if(!parent){
						teardown();
						can.unbind.call(parentElement,'destroyed', teardown);
					}
				},
				// the tag type to insert
				tag = (tagMap[tagName] || "span"),
				// this will be filled in if binding.isListening
				update,
				// the property (instead of innerHTML elements) to adjust. For
				// example options should use textContent
				contentProp = tagToContentPropMap[tagName];
			
			
			// The magic tag is outside or between tags.
			if ( status === 0 && !contentProp ) {
				// Return an element tag with a hookup in place of the content
				return "<" +tag+can.view.hook(
				escape ? 
					// If we are escaping, replace the parentNode with 
					// a text node who's value is `func`'s return value.
					function(el, parentNode){
						// updates the text of the text node
						update = function(newVal){
							node.nodeValue = ""+newVal;
							teardownCheck(node.parentNode);
						};
						
						var parent = getParentNode(el, parentNode),
							node = document.createTextNode(binding.value);
							
						parent.insertBefore(node, el);
						parent.removeChild(el);
						setupTeardownOnDestroy(parent);
					} 
					:
					// If we are not escaping, replace the parentNode with a
					// documentFragment created as with `func`'s return value.
					function( span, parentNode ) {
						// updates the elements with the new content
						update = function(newVal){
							// is this still part of the DOM?
							var attached = nodes[0].parentNode;
							// update the nodes in the DOM with the new rendered value
							if( attached ) {
								makeAndPut(newVal);
							} else {
								// no longer attached
							}
							teardownCheck(nodes[0].parentNode);
						};
						
						// make sure we have a valid parentNode
						parentNode = getParentNode(span, parentNode);
						// A helper function to manage inserting the contents
						// and removing the old contents
						var nodes,
							makeAndPut = function(val){
								// create the fragment, but don't hook it up
								// we need to insert it into the document first
								var frag = can.view.frag(val, parentNode),
									// keep a reference to each node
									newNodes = can.makeArray(frag.childNodes),
									last = nodes ? nodes[nodes.length - 1] : span;
								
								// Insert it in the `document` or `documentFragment`
								if( last.nextSibling ){
									last.parentNode.insertBefore(frag, last.nextSibling);
								} else {
									last.parentNode.appendChild(frag);
								}
								// nodes hasn't been set yet
								if( !nodes ) {
									can.remove( can.$(span) );
									nodes = newNodes;
									// set the teardown nodeList
									nodeList = nodes;
									register(nodes);
								} else {
									can.remove( can.$(nodes) );
									replace(nodes,newNodes);
								}
							};
							// nodes are the nodes that any updates will replace
							// at this point, these nodes could be part of a documentFragment
						makeAndPut(binding.value, [span]);
						
						
						setupTeardownOnDestroy(parentNode);
						
				}) + "></" +tag+">";
			// In a tag, but not in an attribute
			} else if( status === 1 ) { 
				// remember the old attr name
				var attrName = binding.value.replace(/['"]/g, '').split('=')[0];
				pendingHookups.push(function(el) {
					update = function(newVal){
						var parts = (newVal|| "").replace(/['"]/g, '').split('='),
							newAttrName = parts[0];
						
						// Remove if we have a change and used to have an `attrName`.
						if((newAttrName != attrName) && attrName){
							removeAttr(el,attrName);
						}
						// Set if we have a new `attrName`.
						if(newAttrName){
							setAttr(el, newAttrName, parts[1]);
							attrName = newAttrName;
						}
					};
					setupTeardownOnDestroy(el);
				});

				return binding.value;
			} else { // In an attribute...
				var attributeName = status === 0 ? contentProp : status;
				// if the magic tag is inside the element, like `<option><% TAG %></option>`,
				// we add this hookup to the last element (ex: `option`'s) hookups.
				// Otherwise, the magic tag is in an attribute, just add to the current element's
				// hookups.
				(status === 0  ? lastHookups : pendingHookups ).push(function(el){
					// update will call this attribute's render method
					// and set the attribute accordingly
					update = function(){
						setAttr(el, attributeName, hook.render(), contentProp);
					};
					
					var wrapped = can.$(el),
						hooks;
					
					// Get the list of hookups or create one for this element.
					// Hooks is a map of attribute names to hookup `data`s.
					// Each hookup data has:
					// `render` - A `function` to render the value of the attribute.
					// `funcs` - A list of hookup `function`s on that attribute.
					// `batchNum` - The last event `batchNum`, used for performance.
					hooks = can.data(wrapped,'hooks');
					if ( ! hooks ) {
						can.data(wrapped, 'hooks', hooks = {});
					}
					
					// Get the attribute value.
					var attr = getAttr(el, attributeName, contentProp),
						// Split the attribute value by the template.
						parts = attr.split("__!!__"),
						hook;

					
					// If we already had a hookup for this attribute...
					if(hooks[attributeName]) {
						// Just add to that attribute's list of `function`s.
						hooks[attributeName].bindings.push(binding);
					} else {
						// Create the hookup data.
						hooks[attributeName] = {
							render: function() {
								var i =0,
									newAttr = attr.replace(attributeReplace, function() {
										return contentText( hook.bindings[i++].value );
									});
								return newAttr;
							},
							bindings: [binding],
							batchNum : undefined
						};
					}

					// Save the hook for slightly faster performance.
					hook = hooks[attributeName];

					// Insert the value in parts.
					parts.splice(1,0,binding.value);

					// Set the attribute.
					setAttr(el, attributeName, parts.join(""), contentProp);
					
					// Bind on change.
					//liveBind(observed, el, binder,oldObserved);
					setupTeardownOnDestroy(el);
				});
				return "__!!__";
			}
		},
		pending: function() {
			// TODO, make this only run for the right tagName
			if(true  || pendingHookups.length) {
				var hooks = pendingHookups.slice(0);
				lastHookups = hooks;
				pendingHookups = [];
				return can.view.hook(function(el){
					can.each(hooks, function(fn){
						fn(el);
					});
				});
			} else {
				return "";
			}
		},
		register: register,
		unregister: unregister,
		replace: replace,
		nodeMap: nodeMap,
		// a mapping of nodeList ids to nodeList
		nodeListMap: nodeListMap
});
	// Start scanning code.
	var tokenReg = new RegExp("(" +[ "<%%", "%%>", "<%==", "<%=", 
					"<%#", "<%", "%>", "<", ">", '"', "'"].join("|")+")","g"),
		// Commands for caching.
		startTxt = 'var ___v1ew = [];',
		finishTxt = "return ___v1ew.join('')",
		put_cmd = "___v1ew.push(",
		insert_cmd = put_cmd,
		// Global controls (used by other functions to know where we are).
		//  
		// Are we inside a tag?
		htmlTag = null,
		// Are we within a quote within a tag?
		quote = null,
		// What was the text before the current quote? (used to get the `attr` name)
		beforeQuote = null,
		// Used to mark where the element is.
		status = function(){
			// `t` - `1`.
			// `h` - `0`.
			// `q` - String `beforeQuote`.
			return quote ? "'"+beforeQuote.match(attrReg)[1]+"'" : (htmlTag ? 1 : 0);
		},
		pendingHookups = [],
		lastHookups = [],
		scan = function(source, name){
			var tokens = [],
				last = 0;
			
			source = source.replace(newLine, "\n");
			source.replace(tokenReg, function(whole, part, offset){
				// if the next token starts after the last token ends
				// push what's in between
				if(offset > last){
					tokens.push( source.substring(last, offset) );
				}
				// push the token 
				tokens.push(part);
				// update the position of the last part of the last token
				last = offset+part.length;
			});
			// if there's something at the end, add it
			if(last < source.length){
				tokens.push(source.substr(last));
			}
			
			var content = '',
				buff = [startTxt],
				// Helper `function` for putting stuff in the view concat.
				put = function( content, bonus ) {
					buff.push(put_cmd, '"', clean(content), '"'+(bonus||'')+');');
				},
				// A stack used to keep track of how we should end a bracket
				// `}`.  
				// Once we have a `<%= %>` with a `leftBracket`,
				// we store how the file should end here (either `))` or `;`).
				endStack =[],
				// The last token, used to remember which tag we are in.
				lastToken,
				// The corresponding magic tag.
				startTag = null,
				// Was there a magic tag inside an html tag?
				magicInTag = false,
				// The current tag name.
				tagName = '',
				// stack of tagNames
				tagNames = [],
				// Declared here.
				bracketCount,
				i = 0,
				token;

			// Reinitialize the tag state goodness.
			htmlTag = quote = beforeQuote = null;

			for (; (token = tokens[i++]) !== undefined;) {

				if ( startTag === null ) {
					switch ( token ) {
					case '<%':
					case '<%=':
					case '<%==':
						magicInTag = 1;
					case '<%#':
						// A new line -- just add whatever content within a clean.  
						// Reset everything.
						startTag = token;
						if ( content.length ) {
							put(content);
						}
						content = '';
						break;

					case '<%%':
						// Replace `<%%` with `<%`.
						content += '<%';
						break;
					case '<':
						// Make sure we are not in a comment.
						if(tokens[i].indexOf("!--") !== 0) {
							htmlTag = 1;
							magicInTag = 0;
						}
						content += token;
						break;
					case '>':
						htmlTag = 0;
						// if there was a magic tag
						// or it's an element that has text content between its tags, 
						// but content is not other tags add a hookup
						// TODO: we should only add `can.EJS.pending()` if there's a magic tag 
						// within the html tags.
						if(magicInTag || tagToContentPropMap[ tagNames[tagNames.length -1] ]){
							put(content, ",can.EJS.pending(),\">\"");
							content = '';
						} else {
							content += token;
						}
						// if it's a tag like <input/>
						if(lastToken.substr(-1) == "/"){
							// remove the current tag in the stack
							tagNames.pop();
							// set the current tag to the previous parent
							tagName = tagNames[tagNames.length-1];
						}
						break;
					case "'":
					case '"':
						// If we are in an html tag, finding matching quotes.
						if(htmlTag){
							// We have a quote and it matches.
							if(quote && quote === token){
								// We are exiting the quote.
								quote = null;
								// Otherwise we are creating a quote.
								// TODO: does this handle `\`?
							} else if(quote === null){
								quote = token;
								beforeQuote = lastToken;
							}
						}
					default:
						// Track the current tag
						if(lastToken === '<'){
							tagName = token.split(/\s/)[0];
							// If 
							if( tagName.indexOf("/") === 0 && tagNames.pop() === tagName.substr(1) ) {
								tagName = tagNames[tagNames.length-1]|| tagName.substr(1);
							} else {
								tagNames.push(tagName);
							}
						}
						content += token;
						break;
					}
				}
				else {
					// We have a start tag.
					switch ( token ) {
					case '%>':
						// `%>`
						switch ( startTag ) {
						case '<%':
							// `<%`
							
							// Get the number of `{ minus }`
							bracketCount = bracketNum(content);
							
							// We are ending a block.
							if (bracketCount == 1) {

								// We are starting on.
								buff.push(insert_cmd, "can.EJS.txt(0,'"+tagName+"'," + status() + ",this,function(){", startTxt, content);
								
								endStack.push({
									before: "",
									after: finishTxt+"}));\n"
								});
							}
							else {
								
								// How are we ending this statement?
								last = // If the stack has value and we are ending a block...
									endStack.length && bracketCount == -1 ? // Use the last item in the block stack.
									endStack.pop() : // Or use the default ending.
								{
									after: ";"
								};
								
								// If we are ending a returning block, 
								// add the finish text which returns the result of the
								// block.
								if (last.before) {
									buff.push(last.before);
								}
								// Add the remaining content.
								buff.push(content, ";",last.after);
							}
							break;
						case '<%=':
						case '<%==':
							// We have an extra `{` -> `block`.
							// Get the number of `{ minus }`.
							bracketCount = bracketNum(content);
							// If we have more `{`, it means there is a block.
							if( bracketCount ){
								// When we return to the same # of `{` vs `}` end with a `doubleParent`.
								endStack.push({
									before : finishTxt,
									after: "}));"
								});
							} 
							// Check if its a func like `()->`
							if(quickFunc.test(content)){
								var parts = content.match(quickFunc);
								content = "function(__){var "+parts[1]+"=can.$(__);"+parts[2]+"}";
							}
							
							// If we have `<%== a(function(){ %>` then we want
							// `can.EJS.text(0,this, function(){ return a(function(){ var _v1ew = [];`.
							buff.push(insert_cmd, "can.EJS.txt("+(startTag === '<%=' ? 1 : 0)+",'"+tagName+"'," + status()+",this,function(){ return ", content, 
								// If we have a block.
								bracketCount ? 
								// Start with startTxt `"var _v1ew = [];"`.
								startTxt : 
								// If not, add `doubleParent` to close push and text.
								"}));"
								);
							break;
						}
						startTag = null;
						content = '';
						break;
					case '<%%':
						content += '<%';
						break;
					default:
						content += token;
						break;
					}
					
				}
				lastToken = token;
			}
			
			// Put it together...
			if ( content.length ) {
				// Should be `content.dump` in Ruby.
				put(content);
			}
			buff.push(";");
			
			var template = buff.join(''),
				out = {
					out: 'with(_VIEW) { with (_CONTEXT) {' + template + " "+finishTxt+"}}"
				};
			// Use `eval` instead of creating a function, because it is easier to debug.
			myEval.call(out, 'this.fn = (function(_CONTEXT,_VIEW){' + out.out + '});\r\n//@ sourceURL=' + name + ".js");
			return out;
		};
	
	

	/**
	 * @class can.EJS.Helpers
	 * @parent can.EJS
	 * By adding functions to can.EJS.Helpers.prototype, those functions will be available in the 
	 * views.
	 * 
	 * The following helper converts a given string to upper case:
	 * 
	 * 	can.EJS.Helpers.prototype.toUpper = function(params)
	 * 	{
	 * 		return params.toUpperCase();
	 * 	}
	 * 
	 * Use it like this in any EJS template:
	 * 
	 * 	<%= toUpper('javascriptmvc') %>
	 * 
	 * To access the current DOM element return a function that takes the element as a parameter:
	 * 
	 * 	can.EJS.Helpers.prototype.upperHtml = function(params)
	 * 	{
	 * 		return function(el) {
	 * 			$(el).html(params.toUpperCase());
	 * 		}
	 * 	}
	 * 
	 * In your EJS view you can then call the helper on an element tag:
	 * 
	 * 	<div <%= upperHtml('javascriptmvc') %>></div>
	 * 
	 * 
	 * @constructor Creates a view helper.  This function 
	 * is called internally.  You should never call it.
	 * @param {Object} data The data passed to the 
	 * view.  Helpers have access to it through this._data
	 */
	EJS.Helpers = function( data, extras ) {
		this._data = data;
		this._extras = extras;
		extend(this, extras);
	};
	/**
	 * @prototype
	 */
	EJS.Helpers.prototype = {
		/**
		 * @function list
		 * @hide
		 * 
		 * `can.EJS.Helpers.list` iterates over an observable list and
		 * sets up live binding. `list` takes a list of observables and a callback 
		 * function with the signature `callback( currentItem, index, itemList )`
		 *
		 * Typically, this will look like:
		 *
		 *     <% list(items, function(item){ %>
		 *          <li><%= item.attr('name') %></li>
		 *     <% }) %>
		 *
		 * Whenever the list of observables changes, such as when an item is added or removed, 
		 * the EJS view will redraw the list in the DOM.
		 */
		// TODO Deprecated!!
		list : function(list, cb){
			can.each(list, function(item, i){
				cb(item, i, list)
			})
		}
	};

	// Options for `steal`'s build.
	can.view.register({
		suffix: "ejs",
		// returns a `function` that renders the view.
		script: function( id, src ) {
			return "can.EJS(function(_CONTEXT,_VIEW) { " + new EJS({
				text: src,
				name: id
			}).template.out + " })";
		},
		renderer: function( id, text ) {
			return EJS({
				text: text,
				name: id
			});
		}
	});

	return can;
});

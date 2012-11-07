steal('jquery',function(){
	
	EJS = function(template){
		var split = template.split(tokens);
		scan(split)
		return split;
	}
	var returnReg = /\r\n/g,
		retReg = /\r/g,
		newReg = /\n/g,
		nReg = /\n/,
		slashReg = /\\/g,
		quoteReg = /"/g,
		singleQuoteReg = /'/g,
		tabReg = /\t/g;
	var clean = function( content ) {
			return content.replace(slashReg, '\\\\').replace(newReg, '\\n').replace(quoteReg, '\\"').replace(tabReg, '\\t');
	}
	var put_cmd = "___v1ew.push(",
		insert_cmd = put_cmd,
		// the text that starts the view code (or block function)
		startTxt = 'var ___v1ew = [];',
		// the text that ends the view code (or block function)
		finishTxt = "return ___v1ew.join('')",
		setTokenAndSaveContent = function(state, token){
			state.tag = token;
			put(state)
		},
		put = function( state ) {
			if(state.content.length > 0) {
				state.buff.push(put_cmd, '"', clean(state.content.join('')), '");');
			}
			
		}
	var rules = ["<%%","%%>","<%==","<%=","<%#","<%","%>","<",">",'"',"'"];
	var tokens = new RegExp("(" +rules.join("|")+")")
	
	var scan = function(tokens){
		var content = [],
			buff= [],
			startTag =null,
			htmlTag = null;
			
		while(tokens.length){
			var token = tokens.shift();
			
			if ( startTag === null ) {
				switch ( token ) {
					case scanner.left:
					case scanner.eLeft:
					case scanner.eeLeft:
					case scanner.cmnt:
						// a new line, just add whatever content w/i a clean
						// reset everything
						startTag = token;
						if ( content.length > 0 ) {
							put(content);
						}
						empty();
						break;

					case scanner.dLeft:
						// replace <%% with <%
						content += scanner.left;
						break;
					default:
						content += token;
						break;
					}
				}
				else {
					//we have a start tag
					switch ( token ) {
					case scanner.right:
						// %>
						switch ( startTag ) {
						case scanner.left:
							// <%
							
							// get the number of { minus }
							bn = bracketNum(content);
							// how are we ending this statement
							var last = 
								// if the stack has value and we are ending a block
								endStack.length && bn == -1 ? 
								// use the last item in the block stack
								endStack.pop() : 
								// or use the default ending
								";";
							
							// if we are ending a returning block
							// add the finish text which returns the result of the
							// block 
							if(last === doubleParen) {
								buff.push(finishTxt)
							}
							// add the remaining content
							buff.push(content, last);
							
							// if we have a block, start counting 
							if(bn === 1 ){
								endStack.push(";")
							}
							break;
						case scanner.eLeft:
							// <%= clean content
							bn = bracketNum(content);
							if( bn ) {
								endStack.push(doubleParen)
							} 
							buff.push(insert_cmd, "jQuery.EJS.clean(", content,bn ? startTxt : doubleParen);
							break;
						case scanner.eeLeft:
							// <%== content
							
							// get the number of { minus } 
							bn = bracketNum(content);
							// if we have more {, it means there is a block
							if( bn ){
								// when we return to the same # of { vs } end wiht a doubleParen
								endStack.push(doubleParen)
							} 
							
							buff.push(insert_cmd, "jQuery.EJS.text(", content, 
								// if we have a block
								bn ? 
								// start w/ startTxt "var _v1ew = [])"
								startTxt : 
								// if not, add doubleParent to close push and text
								doubleParen
								);
							break;
						}
						startTag = null;
						empty();
						break;
					case scanner.dRight:
						content += scanner.right;
						break;
					default:
						content += token;
						break;
					}
				}
		}
	}
});

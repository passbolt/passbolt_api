steal.then(function() {
	var ignoreCheck = /\@documentjs-ignore/,
		commentReg = /\r?\n(?:\s*\*+)?/g,
		spaceReg = /\S/g,
		newLine = /\n/g,
		lineNumber = function( source ) {
			// reset lastIndex
			newLine.lastIndex = 0;

			var curLine = 0,
				curIndex, lines, len;


			return function( index ) {
				if (!lines ) {
					lines = source.split('\n');
					curIndex = lines[0].length + 1;
					len = lines.length;
				}
				// if we haven't already, split the 	
				if ( index <= curIndex ) {
					return curLine;
				}
				curLine++;
				while ( curLine < len && (curIndex += lines[curLine].length + 1) <= index ) {
					curLine++;
				}
				return curLine;
			}

		};

	//commentTime = 0;
	//processTime = 0;

	/**
	 * Represents a file.
	 * Breaks up file into comment and code parts.
	 * Creates new [DocumentJS.Pair | Doc.Pairs].
	 * @hide
	 */
	DocumentJS.Script = {

		// removes indent inline
		removeIndent: function( lines ) {
			// first calculate the amount of space to remove
			// and get lines starting with text content 
			var removeSpace = Infinity,
				noSpace = spaceReg,
				match, contentLines = [],
				hasContent = false,
				line, l;

			// for each line
			for ( l = 0; l < lines.length; l++ ) {
				line = lines[l];
				// test if it has something other than a space
				match = noSpace.exec(line);
				// if it does, and it's less than our current maximum
				if ( match && line && noSpace.lastIndex < removeSpace ) {
					// update our current maximum
					removeSpace = noSpace.lastIndex;
					// mark as starting to have content
					hasContent = true;
				}
				// if we have content now, add to contentLines
				if ( hasContent ) {
					contentLines.push(line);
				}
				// update the regexp position
				noSpace.lastIndex = 0;
			}
			// remove from the position before the last char
			removeSpace = removeSpace - 1;

			// go through content lines and remove the removeSpace
			if ( isFinite(removeSpace) && removeSpace !== 0 ) {
				for ( l = 0; l < contentLines.length; l++ ) {
					contentLines[l] = contentLines[l].substr(removeSpace);
				}
			}
			return contentLines;
		},
		getCommentCodePairs: function() {

		},
		group: new RegExp("(?:/\\*(?:[^*]|(?:\\*+[^*/]))*\\*+/\[^\\w\\{\\(\\[/]*[^\\n]*)", "g"),

		// (?:/\*+((?:[^*]|(?:\*+[^*/]))*)\*+/[^\w\{\(\[\"'\$]*([^\r\n]*))
		splitter: new RegExp("(?:/\\*+((?:[^*]|(?:\\*+[^*/]))*)\\*+/\[^\\w\\{\\(\\[\"'\$]*([^\\r\\n]*))"),

		/**
		 * Generates docs for a file.
		 * @param {Object} docScript an object that has src and text attributes.  It can also just be 
		 * the path of a file.
		 */
		process: function( docScript, objects ) {
			if ( typeof docScript == 'string' ) {
				docScript = {
					src: docScript
				}
			}

			var source = docScript.text || readFile(docScript.src);

			//check if the source has @documentjs-ignore
			if ( ignoreCheck.test(source) ) {
				return;
			}
			var script = {
				type: "script",
				name: docScript.src
			},
				scope = script,
				comments, type;

			print("  " + script.name);
			objects[script.name] = script;

			// handle markdown docs
			if (/\.md$/.test(docScript.src) ) {
				type = DocumentJS.Type.create(source, "", scope, objects, 'page', docScript.src.match(/([^\/]+)\.md$/)[1]);
				if ( type ) {

					objects[type.name] = type;
					//get the new scope if you need it
					// if we don't have a type, assume we can have children
					scope = !type.type || DocumentJS.types[type.type].hasChildren ? type : scope;
					type.src = docScript.src;
				}
				return;
			}

			comments = this.getComments(source);

			//clean comments
			for ( var i = 0; i < comments.length; i++ ) {
				var comment = comments[i];

				//var start = new Date;

				type = DocumentJS.Type.create(comment.comment, comment.code, scope, objects);

				//processTime = processTime + (new Date - start)
				if ( type ) {
					objects[type.name] = type;
					//get the new scope if you need it
					// if we don't have a type, assume we can have children
					scope = !type.type || DocumentJS.types[type.type].hasChildren ? type : scope;

					type.src = docScript.src;
					type.line = comment.line;
				}
			}


		},
		// gets an array of comments from this source
		// each comment has
		// - comment : an array of lines that make up the comment
		// - code : the line of code after the comment
		// - line : the line number of the comment
		getComments: function( source ) {
			var start = new Date;
			//var source = source.replace('\r\n','\n')
			var comments = [],
				match, getLine = lineNumber(source);

			this.group.lastIndex = 0;



			while ( match = this.group.exec(source) ) {

				//print("|TTT\n"+match[0]+"\n-------")

				var lastIndex =this.group.lastIndex,
					origComment = match[0],
					splits =origComment.match(this.splitter),
					// the comment after removing leading *
					comment = splits[1].replace(commentReg, '\n'),
					code = splits[2],
					lines = comment.split("\n");

				lines = this.removeIndent(lines);
				// probably want line numbers and such
				// an empty line
				if (!lines.length ) {
					continue;
				}
				var line =getLine(lastIndex - origComment.length );
				//print((lastIndex - origComment.length)+"->"+line+ " "+source.substr(lastIndex - origComment.length,50));
				
				
				comments.push({
					comment: lines,
					code: code,
					line: line
				})
			}
			//commentTime = commentTime + (new Date - start)
			return comments;
		}
	};

	DocumentJS.Type("script", {
		useName: false,
		hasChildren: true
	})
})
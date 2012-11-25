steal('steal','../distance','../showdown','documentjs/tags',function(s, distance, converter ,tags) {
	var typeCheckReg = /^\s*@(\w+)/,
		nameCheckReg = /^\s*@(\w+)[ \t]+([\w\.\$]+)/m,
		doubleAt = /@@/g;
	/**
	 * @class DocumentJS.Type
	 * @tag documentation
	 * Keeps track of types of directives in DocumentJS.  
	 * Each type is added to the types array.
	 * @param {Object} type
	 * @param {Object} props
	 */
	var Type = function( type, props ) {
		Type.types[type] = props;
		props.type = type;
	}

	s.extend(Type,
	/**
	 * @Static
	 */
	{
		types : {},
		/**
		 * Must get type and name
		 * @param {String|Array} comment
		 * @param {String} code
		 * @param {Object} scope
		 * @return {Object} type
		 */
		create: function( comment, code, scope, objects, defaultType, name ) {
			
			var firstLine = typeof comment == 'string' ? comment : comment[0],
				check = firstLine.match(typeCheckReg),
				props,
				type;

			if (!type ) {
				if (!(type = this.hasType(check ? check[1] : null))) { //try code
					type = this.guessType(code);
				}
				if(!type){
					type = defaultType;
				}
				if (!type ) {
					return null;
				}
			}
			
			if ( typeof type === 'string' ) {
				type = Type.types[type.toLowerCase()]
			}
			


			var nameCheck = firstLine.match(nameCheckReg)


			props = type.code(code)

			if (!props && !nameCheck && !name ) {
				return null;
			}

			if (!props ) {
				props = {};
			}
			if ( nameCheck && nameCheck[2] && nameCheck[1].toLowerCase() == type.type ) {
				props.name = nameCheck[2]
			}
			if ( name && !props.name ) {
				props.name = name;
			}
			// you are not going to process the comment the typical way
			// this is mostly for @add
			if ( type.init ) {
				return type.init(props, comment, objects)
			}


			if (!props.type ) {
				props.type = type.type;
			}
			if ( props.name ) {

				var parent = this.getParent(type, scope, objects)
				//print("    p="+(parent ? parent.name+":"+parent.type : ""))
				//if we are adding to an unlinked parent, add parent's name
				// if we have a parent ...
				if ( parent ) {

					if (!parent.type || Type.types[parent.type].useName ) {
						props.name = parent.name + "." + props.name
					}
					if ( props.name === 'toString' ) {
						// can't have an empty toString
						return null;
					}

					// only assign if parent isn't 
					if (!props.parents ) {
						props.parents = [];
					}
					props.parents.unshift(parent.name);

					if (props.name === "index") {
					}
					if ( objects[props.name] ) {
						var newProps = props;
						props = objects[props.name];
						s.extend(props, newProps);
					}
					if (!parent.children ) {
						parent.children = [];
					}
					parent.children.push(props.name)

				} else { // in the case that it doesn't have a parent 
					if ( objects[props.name] ) {
						var newProps = props;
						props = objects[props.name];
						s.extend(props, newProps);
					}
				}

				this.process(props, comment, type, objects)

				return props
			}
		},
		/**
		 * Get the type's parent
		 * @param {Object} type
		 * @param {Object} scope
		 * @return {Object} parent
		 */
		getParent: function( type, scope, objects ) {
			if (!type.parent ) {
				return;
			}


			while ( scope && scope.type && !type.parent.test(scope.type) ) {

				scope = objects[scope.parents ? scope.parents[0] : ""];

			}
			return scope;
		},
		/**
		 * Checks if type processor is loaded
		 * @param {Object} type
		 * @return {Object} type
		 */
		hasType: function( type ) {
			if (!type ) return null;

			return Type.types.hasOwnProperty(type.toLowerCase()) ? Type.types[type.toLowerCase()] : null;
		},
		/**
		 * Guess type from code
		 * @param {String} code
		 * @return {Object} type
		 */
		guessType: function( code ) {
			for ( var type in Type.types ) {
				if ( Type.types[type].codeMatch && Type.types[type].codeMatch(code) ) {
					return Type.types[type];
				}

			}
			return null;
		},
		suggestType: function( incorrect, line ) {
			var lowest = 1000,
				suggest = "",
				check = function( things ) {
					for ( var name in things ) {
						var dist = distance(incorrect.toLowerCase(), name.toLowerCase())
						if ( dist < lowest ) {
							lowest = dist
							suggest = name.toLowerCase()
						}
					}
				}
				check(Type.types);
			check(tags);

			if ( suggest ) {
				print("\nWarning!!\nThere is no @" + incorrect + " directive. did you mean @" + suggest + " ?\n")
			}
		},
		matchTag: /^\s*@(\w+)/,
		/**
		 * Process comments
		 * @param {Object} props
		 * @param {String} comment
		 * @param {Object} type
		 */
		process: function( props, comment, type, objects ) {
			var i = 0,
				lines = typeof comment == 'string' ? comment.split("\n") : comment,
				len = lines.length,
				typeDataStack = [],
				curTag, lastType, curData, lastData, defaultWrite = 'comment',
				//what data we are going to be called with
				tag;

			if (!props.comment ) {
				props[defaultWrite] = '';
			}
			// for each line
			for ( var l = 0; l < len; l++ ) {

				// see if it starts with something that looks like a @tag
				var line = lines[l],
					match = line.match(this.matchTag);

				// if we have a tag
				if ( match ) {
					// lower case it
					tag = match[1].toLowerCase();
					// get the tag object
					var curTag = tags[tag];

					// if we don't have a tag object
					if (!curTag ) {

						// if it's not a type, suggest it as a type and just add it
						// maybe they wanted @foobar
						if (!Type.types[tag] ) {
							this.suggestType(tag);
							props.comment += line + "\n"
						}
						continue;
					} else {
						// ??: why are we setting this?
						curTag.type = tag;
					}
					// call the tag types add method
					curData = curTag.add.call(props, line, curData, objects);

					// depending on curData, we do different things:
					// if we get ['push',{DATA}], this means we are an
					// 'inline' tag, meaning we are going to add
					// content to whatever tag we are currently in
					// @codestart and @codeend are the best examples of this
					if ( curData && curData.length == 2 && curData[0] == 'push' ) { //
						// push the current data and type on the stack
						typeDataStack.push({
							type: lastType,
							data: lastData
						})
						// set ourselves as the current lastType and the 2nd
						// item in the array as curData
						curData = curData[1];
						lastType = curTag;
					}
					// if we get ['pop', text],
					// add text to the previous parent tag
					else if ( curData && curData.length == 2 && curData[0] == 'pop' ) {
						// get the last tag
						var last = typeDataStack.pop();

						// as long as we had a previous tag
						if ( last && last.type ) {
							//call the previous tag's addMore
							last.type.addMore.call(props, curData[1], last.data);
						} else {
							// otherwise, add to the default place to write to
							props[defaultWrite] += "\n" + curData[1]
						}
						// restore the old data
						lastData = curData = last.data;
						lastType = curTag = last.type;

						// if we get ['default',PROPNAME]
						// we change default write to prop name
						// this will make it so if we aren't in a tag, all default
						// lines to to the defaultWrite
						// this is used by @constructor
					} else if ( curData && curData.length == 2 && curData[0] == 'default' ) {
						defaultWrite = curData[1];
					}
					// if we have anything else, we store it as the last thing we went to
					else if ( curData ) {
						lastType = curTag;
						lastData = curData;
					}
					else { // remove the last type because this is a single line tag
						lastType = null;
					}


				}
				else {
					// we have a normal line
					//clean up @@abc becomes @abc
					line = line.replace(doubleAt, "@");

					// if we a lastType (we are on a multi-line tag)
					if ( lastType ) {
						lastType.addMore.call(props, line, curData)
					} else {
						// write to the default place
						props[defaultWrite] += line + "\n"
					}
				}
			}

			// for each tag, let it run any post processing:
			//try {
				props.comment = converter.makeHtml(props.comment);
				//allow post processing
				for ( var tag in tags ) {
					if ( tags[tag].done ) {
						tags[tag].done.call(props);
					}
				}

			//} catch (e) {
			//	print("Error with converting to markdown\n"+e+"\n"+props.comment.substr(0,100)+"\n\n")
			//}

		}
	});
	return Type;
})

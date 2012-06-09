if ( steal.overwrite ) {
	load('steal/rhino/rhino.js');
} else {
	//what steal should send to functions.  This says send steal instead of jQuery.
	steal.send = steal;
}

steal(	'steal/generate/ejs.js',
		'documentjs/json.js', 
		'documentjs/showdown.js')
	.then('steal/build')
.then( function( $ ) {
	
	//if we already have DocumentJS, don't create another, this is so we can document documentjs
	if(typeof DocumentJS != 'undefined'){
		return;
	}
	
	/**
	 * @class DocumentJS
	 * @parent index 3
	 * 
	 * @description A documentation framework.
	 * 
     * There are several reasons why documentation is important:
     * 
     * * As apps grow, source code becomes complex and difficult to maintain.
     * * It's beneficial for customers because it helps to educate them on a product.
     * * Perhaps most importantly, it keeps a project going by bringing new developers up to speed - while also keeping the whole team on the same page.
	 * 
	 * DocumentJS is a new documentation solution for JavaScript applications. It makes creating, viewing, and maintaining documentation easy and fun. Out of the box, it features:
	 * 
     * * Fexible organization of your documentation
     * * An integrated documentation viewer where you can search your API
     * * Markdown support
     * * An extensible architecture
     * 
	 * DocumentJS provides powerful and easy to extend documentation functionality.
	 * It's smart enough to guess 
	 * at things like function names and parameters, but powerful enough to generate 
	 * <span class='highlight'>JavaScriptMVC's entire website</span>!
	 * 
	 * ###Organizing your documentation
	 *
	 * Let's use an hypothetical little CRM system as an example of how easy it is to organize your documentation with DocumentJS. 
	 * 
	 * First let's create our CRM documentation home page by creating a folder name __crm__. Paste this code into a file named __crm.js__ inside __crm__ folder.
	 * 
	 * @codestart
	 * /*
     *  * @@page index CRM
     *  * @@tag home
     *  *
     *  * ###Little CRM
     *  *  
     *  * Our little CRM only has two classes:
     *  *  
     *  * * Customer 
     *  * * Order 
     *  *|
	 * @codeend
	 * 
	 * Run the documentjs script to generate the docs:
	 * 
	 * @codestart
	 * documentjs/doc.bat crm
	 * @codeend
	 * 
	 * This is what you should see when you open __crm\docs.html__:
	 * 
	 * @image jmvc/images/crm_doc_demo_1.png
	 * 
	 * 
	 * There are a few things to notice:
	 * 
	 * * The example closes comments with _*|_.  You should close them with / instead of |.
	 * * We create a link to another class with _[Animal | here]_. 
	 * * We used the @@page directive to create the crm documentation home page. Don't worry about the @@tag directive for now, we'll get back to it later. 
	 * * In all the examples in this walkthrough we use markdown markup instead of html to make the documentation more maintainable and easier to read .
	 * 
	 * Next we document the two classes that make our little crm system. Paste each snippet of code into two files with names __customer.js__ and __order.js__:
	 * 
	 * __customer.js__
	 * 
	 * @codestart
     * /*
     *  * @@class Customer
     *  * @@parent crm 
     *  * @@constructor
     *  * Creates a new customer.
     *  * @@param {String} name
     *  *|
     *  var Customer = function(name) {
	 *     this.name = name;
     *  }
	 * @codeend 
	 * 
	 * __order.js__
	 * 
	 * @codestart
     * /*
     *  * @@class Order
     *  * @@parent crm 
     *  * @@constructor
     *  * Creates a new order.
     *  * @@param {String} id
     *  *|
     *  var Order = function(id) {
	 *     this.id = id;
     *  }
	 * @codeend 
	 * 
	 * After runnig the documentjs script once again you should be able to see this:
	 * 
	 * @image jmvc/images/crm_doc_demo_2.png
	 * 
	 * 
	 * We want to be able to both look for our customer's orders and dispatch them so let's add a _findById_ method to our Order class
	 * and a _dispatch_ method to our Order's prototype:
	 * 
	 * __order.js__
	 * 
	 * @codestart
	 * /*  
     *  * @class Order 
     *  * @parent crm 
     *  * @@constructor
     *  * Creates a new order.
     *  * @@param {String} id
     *  *|
     * var Order = function(id) {
     *     this.id = id;
     * }
     *
     * $.extend(Order,
     * /*
     * * @@static
     * *|
     * {
	 *    /*
	 *     * Finds an order by id.
	 *     * @@param {String} id Order identification number.
	 *     * @@param {Date} [date] Filter order search by this date.
	 *     *|
	 *     findById: function(id, date) {
     *
	 *     }
     *  });
     *
     * $.extend(Order.prototype,
     * /*
     *  * @@prototype
     *  *|
     *  {
	 *     /*
	 *      * Dispatch an order.
	 *      * @@return {Boolean} Returns true if order dispatched successfully.
	 *      *|
	 *      dispatch: function() {
	 *     
	 *      }
     * });
	 * @codeend
	 * 
	 * Go ahead and produce the docs by running the documentjs script. You should see your Order methods organized by static and protoype categories.
	 * 
	 * There's one last thing we need to cover - customizing the document viewer template. The default viewer template file name is __summary.ejs__ and it's
	 * located in __documentjs/jmvcdoc/summary.ejs__. You can use a customized template by copying __summary__.ejs into the __crm__ folder and changing it 
	 * according to your needs. Let's try changing the navigation menu __core__ item to __crm__:
	 * 
	 * @codestart
	 * &lt;li class="ui-menu-item"&gt;
	 *     &lt;a class="menuLink" href="#&amp;search=crm"&gt;&lt;span class="menuSpan"&gt;CRM&lt;/span&gt;&lt;/a&gt;
     * &lt;/li&gt;
	 * @codeend
	 *
	 * Remember the @@tag directive? We can now change it in our examples from _core_ to _crm_. You will notice that our crm page will show up
	 * every time you click the CRM menu item or type _crm_ in the documentation viewer search box.
	 * 
	 * If you need for DocumentJS not to document a particular script you can do that by adding the @document-ignore directive to the top of the file. 
	 * 
	 * As you see DocumentJS makes it super easy and fun to organize your documentation!
	 * 
	 * ###How DocumentJS works
	 * 
	 * DocumentJS architecture is organized around the concepts of [DocumentJS.types | types] and [DocumentJS.tags | tags]. Types are meant to represent every javascript construct 
	 * you might want to comment like classes, functions and attributes. Tags add aditional information to the comments of the type being processed.
	 * 
	 * DocumentJS works by loading a set of javascript files, then by spliting each file into type/comments pairs 
	 * and finally parsing each type's comments tag directives to produce a set of jsonp files (one per type) 
	 * that are used by the document viewer (jmvcdoc) to render the documentation.
	 * 
	 * DocumentJS was written thinking of extensibility and it's very easy to add custom type/tag directives to handle your specific documentation needs.
	 *
	 * DocumentJS currently requires [stealjs Steal] to be included on the pages you are documenting.   
	 * 
	 * ###Type directives
	 * 
	 * * [DocumentJS.types.page | @page] -  add a standalone page.
	 * * [DocumentJS.types.attribute | @attribute] -  document values on an object.
	 * * [DocumentJS.types.function | @function] - document functions.
	 * * [DocumentJS.types.class| @class] - document a class. 
	 * * [DocumentJS.types.prototype | @prototype] - add to the previous class or constructor's prototype functions.
	 * * [DocumentJS.types.static | @static] - add to the previous class or constructor's static functions.
	 * * [DocumentJS.types.add |@add] - add docs to a class or construtor described in another file.
	 * 
	 * ###Tag directives
	 * 
	 * * [DocumentJS.tags.alias|@alias] - another commonly used name for Class or Constructor.
	 * * [DocumentJS.tags.author|@author] - author of class.
	 * * [DocumentJS.tags.codestart|@codestart] -> [DocumentJS.tags.codeend|@codeend] - insert highlighted code block.
	 * * [DocumentJS.tags.constructor | @constructor] - documents a contructor function and its parameters.
	 * * [DocumentJS.tags.demo|@demo] - placeholder for an application demo.
	 * * [DocumentJS.tags.download|@download] - adds a download link.
	 * * [DocumentJS.tags.iframe|@iframe] - adds an iframe with example code.
	 * * [DocumentJS.tags.hide|@hide] - hide in Class view.
	 * * [DocumentJS.tags.inherits|@inherits] - what the Class or Constructor inherits.
	 * * [DocumentJS.tags.parent|@parent] - says under which parent the current type should be located. 
	 * * [DocumentJS.tags.param|@param] - A function's parameter.
	 * * [DocumentJS.tags.plugin|@plugin] - by which plugin this object gets steald.
	 * * [DocumentJS.tags.return|@return] - what a function returns.
	 * * [DocumentJS.tags.scope|@scope] - forces the current type to start scope.
	 * * [DocumentJS.tags.tag|@tag] - tags for searching.
	 * * [DocumentJS.tags.test|@test] - link for test cases.
	 * * [DocumentJS.tags.type|@type] - sets the type for the current commented code.
	 * * [DocumentJS.tags.image|@image] - adds an image.
	 * 
	 * 
	 * ###Inspiration
	 * 
	 * DocumentJS was inspired by the [http://api.jquery.com/ jQuery API Browser] by [http://remysharp.com/ Remy Sharp]
	 * 
	 * 
	 * @param {Array|String} scripts an array of script objects that have src and text properties like:
	 * @codestart
	 * [{src: "path/to/file.js", text: "var a= 1;"}, { ... }]
	 * @codeend
	 * @param {Object} options an options hash including
	 * 
	 *   . name - the name of the application
	 *   . out - where to generate the documentation files
	 */
	DocumentJS = function(scripts, options) {
		// an html file, a js file or a directory
		options = options || {};
		
		if(typeof scripts == 'string'){
			if(!options.out){
				if(/\.html?$|\.js$/.test(scripts)){
					options.out = scripts.replace(/[^\/]*$/, 'docs')
				}else{ //folder
					options.out = scripts+"/docs";
				}
			}
			steal.File(options.out).mkdir();
			scripts = DocumentJS.getScripts(scripts)
		}
		// an array of folders
		if(options.markdown){
			for(var i =0 ; i < options.markdown.length; i++){
				DocumentJS.files(options.markdown[i], function(path, f){
					if(/\.md$/.test(f)){
					  scripts.push( path )
				    }
				})
			}
			
			
			
		}
		// if options, get .md files ...
		
		
 		//all the objects live here, have a unique name
		DocumentJS.objects = {};
		
		//create each Script, which will create each class/constructor, etc
		print("PROCESSING SCRIPTS\n")
		for ( var s = 0; s < scripts.length; s++ ) {
			DocumentJS.Script.process(scripts[s], DocumentJS.objects)
		}
		
		
		print('\nGENERATING DOCS -> '+options.out+'\n')
		
		// generate individual JSONP forms of individual comments
		DocumentJS.generate(options)

		// make combined search data
		DocumentJS.searchData(DocumentJS.objects,options )

		//make summary page (html page to load it all)
		DocumentJS.summaryPage(options);
		
	};
	
	var extend = function( d, s ) {
			for ( var p in s ) d[p] = s[p];
			return d;
		},
		build = steal.build,
		docJS = DocumentJS;
	
	extend(docJS, {
		files : function(path, cb){
			var getJSFiles = function(dir){
			  var file = new steal.File(dir);
			  if(file.isFile()) {
				  cb(dir.replace('\\', '/'), dir);
			  } else {
				  file.contents(function(f, type){
					if(type == 'directory'){
				       getJSFiles(dir+"/"+f)
				    }else {
					  cb((dir+"/"+f).replace('\\', '/'), f);
				    }
				  });
			  }
			};
			getJSFiles(path);
		},
		// gets scripts from a path
		getScripts : function(file){
			
			var collection = [];
			if (/\.html?$/.test(file)) { // load all the page's scripts
				steal.build.open(file, function(scripts){
					scripts.each(function(script, text){
						if (text && script.src) {
							collection.push({
								src: script.rootSrc,
								text:  text
							})
						}
					});
				});
				collection.unshift({
					src: 'steal/steal.js',
					text:  readFile('steal/steal.js')  // this might need to change
				})
			}
			else if (/\.js$/.test(file)) { // load just this file
				collection.push(file)
			}
			else { // assume its a directory
				this.files(file, function(path, f){
					if(/\.(js|md)$/.test(f)){
					  collection.push( path )
				    }
				})
				
				
			}
			return collection;
		},
		generate : function(options){

			// go through all the objects and generate their docs
			var output = options.out ? options.out+ "/" : "";

			for ( var name in docJS.objects ) {
				if (docJS.objects.hasOwnProperty(name)){
					//get a copy of the object (we will modify it with children)
					var obj = docJS.extend({}, docJS.objects[name]),
						toJSON;
					
					// eventually have an option allow scripts
					if ( obj.type == 'script' || typeof obj != "object" ) {
						continue;
					}
					
					//get all children					
					obj.children = this.listedChildren(obj);
	
					var converted = name.replace(/ /g, "_")
										.replace(/&#46;/g, ".")
										.replace(/&gt;/g, "_gt_")
										.replace(/\*/g, "_star_")
					toJSON = this.out(obj, undefined, "c");
					new docJS.File(output + converted + ".json").save(toJSON);
				}
	
			}
			//print(commentTime);
			//print(processTime)
		},
		// takes an object and returns how DocumentJS likes to save data
		out: function(data, how, Char) {
			return (Char|| "C")+"(" + docJS.toJSON(data, how) + ")"
		},
		// tests if item is a shallow child of parent
		shallowParent: function( item, parent ) {
			if ( item.parents && parent ) {
				for ( var i = 0; i < item.parents.length; i++ ) {
					if ( item.parents[i] == parent.name ) {
						return true;
					}
				}
			}
			return false;
		},
		// returns all recustive 'hard' children and one level of 'soft' children.
		listedChildren: function( item, stealSelf, parent ) {
			var result = stealSelf ? [item.name] : [];
			if ( item.children && !this.shallowParent(item, parent) ) {
				for ( var c = 0; c < item.children.length; c++ ) {
					var child = docJS.objects[item.children[c]];
					var adds = this.listedChildren(child, true, item);
					if ( adds ) {
						result = result.concat(adds);
					}

				}
			}
			return result;
		},
		summaryPage: function( options ) {
			//find index page
			var path = options.out,
				base = path.replace(/[^\/]*$/, ""),
				renderData = {
					pathToRoot: new docJS.File(base.replace(/\/[^\/]*$/, "")).pathToRoot(),
					path: path,
					indexPage: docJS.objects.index
				}

			//checks if you have a summary
			if ( readFile(base + "summary.ejs") ) {
				print("Using summary at " + base + "summary.ejs");
				docJS.renderTo(base + "docs.html", base + "summary.ejs", renderData)
			} else {
				print("Using default page layout.  Overwrite by creating: " + base + "summary.ejs");
				docJS.renderTo(base + "docs.html", "documentjs/jmvcdoc/summary.ejs", renderData);
			}
		},
		renderTo: function( file, ejs, data ) {
			new docJS.File(file).save(new docJS.EJS({
				text: readFile(ejs)
			}).render(data));
		}
	})
	//Add things to StealJS we like, then remove them from the global namespace
	
	
	extend(docJS, steal); //even if we delete steal, we still have it's goodness
	DocumentJS.EJS = steal.EJS;
	DocumentJS.JSONparse = JSONparse;
	DocumentJS.toJSON = toJSON;
	DocumentJS.extend = extend;
	
	DocumentJS.converter = new Showdown.converter();
	
	delete Showdown;
	delete JSONparse;


}).then('documentjs/distance.js')
	.then('documentjs/searchdata.js')
	.then('documentjs/tags')
	.then('documentjs/types').then(function(){
		steal.send = undefined;
	});
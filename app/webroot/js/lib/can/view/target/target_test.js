/* jshint indent:false */
steal("can/view/target", "steal-qunit", function(target){
	
	
	module("can/view/target");
	
	test("basics", function(){
		
		// "<h1 class='foo {{#selected}}selected{{/selected}}' ><span>Hello {{message}}!</span></h1>"
		var classCallback = function( ){
			equal(this.nodeName.toLowerCase(), "h1", "class on the right element");
			this.className = "selected";
		},
			attributesCallback = function(){
				equal(this.nodeName.toLowerCase(), "h1", "attributes on the right element");
			},
			textNodeCallback = function( ){
				equal(this.nodeType, 3, "got a text node");
				this.nodeValue = "World";
			};
		
		
		
		var data = target([{
			tag: "h1",
			attrs: {
				"id" : "myh1",
				"class" : classCallback
			},
			attributes: [attributesCallback],
			children: [{
				tag: "span",
				children: [
					"Hello ",
					textNodeCallback,
					"!"
				]
			}]
		}]);
		
		equal( data.clone.childNodes.length, 1, "there is one child");
		
		var h1 = data.clone.childNodes[0];
		equal( h1.nodeName.toLowerCase(), "h1", "there is one h1");
		equal( h1.id, "myh1", "the h1 has the right id");
		
		equal( h1.childNodes.length, 1, "the h1 has span");
		
		equal( h1.childNodes[0].childNodes.length, 3, "the span has 3 children");
		
		deepEqual( data.paths,
			[{
				path: [0],
				callbacks: [
					{ callback: classCallback },
					{ callback: attributesCallback }
				],
				paths: [{
					path: [0,1],
					callbacks: [
						{callback: target.keepsTextNodes ? textNodeCallback : data.paths[0].paths[0].callbacks[0].callback }
					]
				}]
			}] );
		
		var result = data.hydrate();
		
		var newH1 = result.childNodes[0];
		equal(newH1.className, "selected", "got selected class name");
		equal(newH1.innerHTML.toLowerCase(), "<span>hello world!</span>");
		
	});
	
	
	test("replacing items", function(){
		var data = target([
			function(){
				this.parentNode.insertBefore(document.createTextNode("inserted"), this.nextSibling);
			},
			"hi",
			function(){
				equal(this.previousSibling.nodeValue, "hi", "previous is as expected");
			}]);
			
		data.hydrate();
	});
	
	test("comments", function(){
		
		var data = target([
			{ tag: "h1" },
			{comment: "foo bar"}
		]);
		var node = data.clone.childNodes[1];
		equal(node.nodeValue, "foo bar", "node value is right");
		equal(node.nodeType, 8, "node is a comment");
		
	});
	
	
	test("paths should be run in reverse order (#966)", function(){
		
		var data = target([{
			tag: "h1",
			attributes: [function(){}],
			children:  [
				function(){
					this.parentElement.insertBefore(document.createElement("div"),this.nextSibling);
				},
				{
					tag: "span",
					children: [function(){
						equal(this.nodeType,3, "got an element");
					}]
				}
			]
		}]);
		data.hydrate();
	});
	
});

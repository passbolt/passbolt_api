steal("can/view/parser", "steal-qunit", function(parser){
	
	
	module("can/view/parser");
	
	
	var makeChecks = function(tests){
		var count = 0;
		var makeCheck = function(name){
			
			return function(){
				if(count >= tests.length) {
					ok(false, "called "+name+" with "+arguments[0]);
				} else {
					var test = tests[count],
						args = test[1];
					equal(name, test[0], "test "+count+" called "+name);
					for(var i = 0 ; i < args.length; i++) {
						equal(arguments[i], args[i], (i+1)+" argument is right");
					}
					count++;
				}
				
				
			};
		};
		
		
		return {
			start: makeCheck("start"),
			end: makeCheck("end"),
			close: makeCheck("close"),
			attrStart: makeCheck("attrStart"),
			attrEnd: makeCheck("attrEnd"),
			attrValue: makeCheck("attrValue"),
			chars: makeCheck("chars"),
			comment: makeCheck("comment"),
			special: makeCheck("special"),
			done: makeCheck("done")
		};
	};
	
	
	test("html to html", function(){
		
		
		
		var tests = [
			["start", ["h1", false]],
			["attrStart", ["id"]],
			["attrValue", ["foo"]],
			["attrEnd", ["id"]],
			["special", ["#if"]],
			["special", ["."]],			//5
			["special", ["/if"]],
			["attrStart", ["class"]],
			["attrValue", ["a"]],
			["special", ["foo"]],
			["attrEnd", ["class"]],		//10
			["end", ["h1", false]],
			["chars", ["Hello "]],
			["special", ["message"]],
			["chars", ["!"]],
			["close",["h1"]],
			["done",[]]
		];
		
		
		
		parser("<h1 id='foo' {{#if}}{{.}}{{/if}} class='a{{foo}}'>Hello {{message}}!</h1>",makeChecks(tests));
		
	});
	
	test("special in an attribute in an in-tag section", function(){
		
		parser("<div {{#truthy}}foo='{{baz}}'{{/truthy}}></div>",makeChecks([
			["start", ["div", false]],
			["special", ["#truthy"]],
			["attrStart", ["foo"]],
			["special", ["baz"]],
			["attrEnd", ["foo"]],		//10
			["special",["/truthy"]],
			["end", ["div", false]],
			["close",["div"]],
			["done",[]]
		]));
		
	});
	
	test("special with a custom attribute", function(){
		
		parser('<div {{#attribute}} {{name}}="{{value}}" {{/attribute}}></div>',makeChecks([
			["start", ["div", false]],
			["special", ["#attribute"]],
			["special", ["name"]],
			["attrStart", [""]],
			["special", ["value"]],
			["attrEnd", [""]],		//10
			["special",["/attribute"]],
			["end", ["div", false]],
			["close",["div"]],
			["done",[]]
		]));
		
		
	});
	
	test("single attribute value", function(){
		
		parser('<input DISABLED/>',makeChecks([
			["start", ["input", true]],
			["attrStart", ["DISABLED"]],
			["attrValue", ["DISABLED"]],
			["attrEnd", ["DISABLED"]],
			["end", ["input", true]],
			["done",[]]
		]));
	});
	
	test("trailing linebreaks in IE", function(){
		parser("12345{{!\n  This is a\n  multi-line comment...\n}}67890\n",makeChecks([
			["chars", ["12345"]],
			["special", ["!\n  This is a\n  multi-line comment...\n"]],
			["chars", ["67890\n"]],
			["done",[]]
		]));
	});


	test("block are allowed inside anchor tags", function(){
		parser("<a><div></div></a>", makeChecks([
			['start', ['a', false]],
			['end', ['a', false]],
			['start', ['div', false]],
			['end', ['div', false]],
			['close', ['div']],
			['close', ['a']],
			['done', []]
		]));
	});

	test("supports single character attributes (#1132)", function(){
		parser('<circle r="25"></circle>', makeChecks([
			["start", ["circle", false]],
			["attrStart", ["r"]],
			["attrValue", ["25"]],
			["attrEnd", ["r"]],
			["end", ["circle", false]],
			["close", ["circle"]],
			["done", []]
		]));
	});

	test('accept custom tag with colon ":" #1108', function(){
		parser('<x:widget/>', makeChecks([
			["start", ["x:widget",true]],
			["end", ["x:widget", true]],
			["done", []]
		]));
	});
	
	
	test('output json', function(){
		var tests = [
			["start", ["h1", false]],
			["attrStart", ["id"]],
			["attrValue", ["foo"]],
			["attrEnd", ["id"]],
			["special", ["#if"]],
			["special", ["."]],			//5
			["special", ["/if"]],
			["attrStart", ["class"]],
			["attrValue", ["a"]],
			["special", ["foo"]],
			["attrEnd", ["class"]],		//10
			["end", ["h1", false]],
			["chars", ["Hello "]],
			["special", ["message"]],
			["chars", ["!"]],
			["close",["h1"]],
			["done",[]]
		];
		
		var intermediate = parser("<h1 id='foo' {{#if}}{{.}}{{/if}} class='a{{foo}}'>Hello {{message}}!</h1>",makeChecks(tests), true);
		
	
		
		parser(intermediate, makeChecks(tests) );
	});
	
	
});

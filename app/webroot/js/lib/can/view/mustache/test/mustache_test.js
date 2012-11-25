steal('funcunit/syn', 'can/view/mustache', function(){
	
module("can/view/mustache, rendering",{
	setup : function(){

		this.animals = ['sloth', 'bear', 'monkey']
		if(!this.animals.each){
			this.animals.each = function(func){
				for(var i =0; i < this.length; i++){
					func(this[i])
				}
			}
		}
		
		this.squareBrackets = "<ul>{{#animals}}" +
	               "<li>{{.}}</li>" + 
		      "{{/animals}}</ul>";
	    this.squareBracketsNoThis = "<ul>{{#animals}}" +
	               "<li>{{.}}</li>" + 
		      "{{/animals}}</ul>";
	    this.angleBracketsNoThis  = "<ul>{{#animals}}" +
	               "<li>{{.}}</li>" + 
		      "{{/animals}}</ul>";

	}
})

// Override expected spec result for whitespace only issues
var override = {
	comments: {
		'Standalone Without Newline': '!'
	},
	inverted: {
		'Standalone Line Endings': '|\n\n|',
		'Standalone Without Newline': '^\n/'
	},
	partials: {
		'Standalone Line Endings': '|\n>\n|', 
		'Standalone Without Previous Line': '  >\n>\n>', 
		'Standalone Without Newline': '>\n  >\n>', 
		'Standalone Indentation': '\\\n |\n<\n->\n|\n\n/\n'
	},
	sections: {
		'Standalone Line Endings': '|\n\n|',
		'Standalone Without Newline': '#\n/'
	}
};

// Add mustache specs to the test
can.each(['comments', /*'delimiters',*/ 'interpolation', 'inverted', 'partials', 'sections'/*, '~lambdas'*/], function(spec) {
	can.ajax({
		url: steal.config().root.join('can/view/mustache/spec/specs/' + spec + '.json') + '',
		dataType: 'json',
		async: false
	}).done(function(data) {
		can.each(data.tests, function(t) {
			test('specs/' + spec + ' - ' + t.name + ': ' + t.desc, function() {
				// can uses &#34; to escape double quotes, mustache expects &quot;.
				// can uses \n for new lines, mustache expects \r\n.
				var expected = (override[spec] && override[spec][t.name]) || t.expected.replace(/&quot;/g, '&#34;').replace(/\r\n/g, '\n');
				
				// Mustache's "Recursion" spec generates invalid HTML
				if (spec == 'partials' && t.name == 'Recursion') {
					t.partials.node = t.partials.node.replace(/</g,'[').replace(/\}>/g,'}]');
					expected = expected.replace(/</g,'[').replace(/>/g,']');
				}

				// register the partials in the spec
				if(t.partials){
					for(var name in t.partials) {
						can.view.registerView(name, t.partials[name])
					}
				}
				
				// register lambdas
				if (t.data.lambda && t.data.lambda.js) {
					t.data.lambda = eval('(' + t.data.lambda.js + ')');
				}

				same(new can.Mustache({ text: t.template }).render(t.data), expected);
			});
		});
	});
});

var getAttr = function(el, attrName){
		return attrName === "class"?
			el.className:
			el.getAttribute(attrName);
	}

test("registerNode, unregisterNode, and replace work", function(){
	// Reset the registered nodes
	for (var key in can.view.nodeMap) {
		if (can.view.nodeMap.hasOwnProperty(key)) {
			delete can.view.nodeMap[key];
		}
	}
	for (var key in can.view.nodeListMap) {
		if (can.view.nodeListMap.hasOwnProperty(key)) {
			delete can.view.nodeListMap[key];
		}
	}
	
	var ids = function(arr){
		return can.map(arr, function(item){
			return item.id
		})
	},
		two = {id: 2},
		listOne = [{id: 1},two,{id: 3}];
		
	can.view.registerNode(listOne);
	var listTwo = [two];
	
	can.view.registerNode(listTwo);
	
	var newLabel = {id: 4}
	can.view.replace(listTwo, [newLabel])
	
	same( ids(listOne), [1,4,3], "replaced" )
	same( ids(listTwo), [4] );
	
	can.view.replace(listTwo,[{id: 5},{id: 6}]);
	
	same( ids(listOne), [1,5,6,3], "replaced" );
	
	same( ids(listTwo), [5,6], "replaced" );
	
	can.view.replace(listTwo,[{id: 7}])
	
	same( ids(listOne), [1,7,3], "replaced" );
	
	same( ids(listTwo), [7], "replaced" );
	
	can.view.replace( listOne, [{id: 8}])
	
	same( ids(listOne), [8], "replaced" );
	same( ids(listTwo), [7], "replaced" );
	
	can.view.unregisterNode(listOne);
	can.view.unregisterNode(listTwo);
	
	
	
	same(can.view.nodeMap, {} );
	same(can.view.nodeListMap ,{} )
});

test("Model hookup", function(){
	
	// Single item hookup
	var template = '<p id="foo" {{data "name"}}>data rocks</p>';
	var obsvr = new can.Observe({ name: 'Austin' });
	var frag = new can.Mustache({ text: template }).render(obsvr);
	can.append( can.$('#qunit-test-area'), can.view.frag(frag));
	same(can.data(can.$('#foo'), 'name'), obsvr, 'data hooks worked and fetched');

	// Multi-item hookup
	var listTemplate = '<ul id="list">{{#list}}<li class="moo" id="li-{{name}}" {{data "obsvr"}}>{{name}}</li>{{/#list}}</ul>';
	var obsvrList = new can.Observe.List([ obsvr ]);
	var listFrag = new can.Mustache({ text: listTemplate }).render({ list: obsvrList });
	can.append(can.$('#qunit-test-area'), can.view.frag(listFrag));
	same(can.data(can.$('#li-Austin'), 'obsvr'), obsvr, 'data hooks for list worked and fetched');

	// Mulit-item update with hookup
	var obsvr2 = new can.Observe({ name: 'Justin' });
	obsvrList.push(obsvr2);
	same(can.data(can.$('#li-Justin'), 'obsvr'), obsvr2, 'data hooks for list push worked and fetched');

	// Delete last item added
	obsvrList.pop();
	same(can.$('.moo').length, 1, 'new item popped off and deleted from ui');
});

/*
// FIX THIS
test('Helpers sections not returning values', function(){
	Mustache.registerHelper('filter', function(attr,options){
		return true;
	});

	var template = "<div id='sectionshelper'>{{#filter}}moo{{/filter}}</div>";
	var frag = new can.Mustache({ text: template }).render({ });;
	can.append( can.$('#qunit-test-area'), can.view.frag(frag));
	same(can.$('#sectionshelper')[0].innerHTML, "moo", 'helper section worked');

});

// FIX THIS
test('Helpers with obvservables in them', function(){
	Mustache.registerHelper('filter', function(attr,options){
		return options.fn(attr === "poo");
	});

	var template = "<div id='sectionshelper'>{{#filter 'moo'}}moo{{/filter}}</div>";
	var obsvr = new can.Observe({ filter: 'moo' });
	var frag = new can.Mustache({ text: template }).render({ filter: obsvr });;
	can.append( can.$('#qunit-test-area'), can.view.frag(frag));
	same(can.$('#sectionshelper')[0].innerHTML, "", 'helper section showed none');

	obsvr.attr('filter', 'poo')
	same(can.$('#sectionshelper')[0].innerHTML, "poo", 'helper section worked');
});
*/

test('Tokens returning 0 where they should diplay the number', function(){
	var template = "<div id='zero'>{{completed}}</div>";
	var frag = new can.Mustache({ text: template }).render({ completed: 0 });;
	can.append( can.$('#qunit-test-area'), can.view.frag(frag));
	same(can.$('#zero')[0].innerHTML, "0", 'zero shown');
})

test('Inverted section function returning numbers',function() {
	var template = "<div id='completed'>{{^todos.completed}}hidden{{/todos.completed}}</div>";
	var obsvr = new can.Observe({ named: false });

	var todos = {
		completed: function(){
			return obsvr.attr('named');
		}
	};

	// check hidden there
	var frag = new can.Mustache({ text: template }).render({ todos: todos });
	can.append( can.$('#qunit-test-area'), can.view.frag(frag));
	same(can.$('#completed')[0].innerHTML, "hidden", 'hidden shown');
	
	// now update the named attribute
	obsvr.attr('named', true);
	same(can.$('#completed')[0].innerHTML, "", 'hidden gone');
});

test("Mustache live-binding with escaping", function() {
	var template = "<span id='binder1'>{{ name }}</span><span id='binder2'>{{{name}}}</span>";

	var teacher = new can.Observe({
		name: "<strong>Mrs Peters</strong>"
	});

	var template = new can.Mustache({ text: template }).render(teacher);
	can.append( can.$('#qunit-test-area'), can.view.frag(template));

	same(can.$('#binder1')[0].innerHTML, "&lt;strong&gt;Mrs Peters&lt;/strong&gt;");
	same(can.$('#binder2')[0].getElementsByTagName('strong')[0].innerHTML, "Mrs Peters");

	teacher.attr('name', '<i>Mr Scott</i>');

	same(can.$('#binder1')[0].innerHTML, "&lt;i&gt;Mr Scott&lt;/i&gt;");
	same(can.$('#binder2')[0].getElementsByTagName('i')[0].innerHTML, "Mr Scott")
});

test("Mustache truthy", function() {
	var t = {
		template: "{{#name}}Do something, {{name}}!{{/name}}",
		expected: "Do something, Andy!",
		data: { name: 'Andy' }
	};
	
	var expected = t.expected.replace(/&quot;/g, '&#34;').replace(/\r\n/g, '\n');
	same(new can.Mustache({ text: t.template }).render(t.data), expected);
});

test("Mustache falsey", function() {
	var t = {
		template: "{{^cannot}}Don't do it, {{name}}!{{/cannot}}",
		expected: "Don't do it, Andy!",
		data: { name: 'Andy' }
	};
	
	var expected = t.expected.replace(/&quot;/g, '&#34;').replace(/\r\n/g, '\n');
	same(new can.Mustache({ text: t.template }).render(t.data), expected);
});

test("Handlebars helpers", function() {
	can.Mustache.registerHelper('hello', function(options) {
		return 'Should not hit this';
	});
	can.Mustache.registerHelper('there', function(options) {
		return 'there';
	});
	can.Mustache.registerHelper('bark', function(obj, str, number, options) {
		var hash = options.hash || {};
		return 'The ' + obj + ' barked at ' + str + ' ' + number + ' times, ' +
			'then the ' + hash.obj + ' ' + hash.action + ' ' + 
			hash.where + ' times' + (hash.loud === true ? ' loudly' : '') + '.';
	});
	var t = {
		template: "{{hello}} {{there}}!\n{{bark name 'Austin and Andy' 3 obj=name action='growled and snarled' where=2 loud=true}}",
		expected: "Hello there!\nThe dog barked at Austin and Andy 3 times, then the dog growled and snarled 2 times loudly.",
		data: { name: 'dog', hello: 'Hello' }
	};
	
	var expected = t.expected.replace(/&quot;/g, '&#34;').replace(/\r\n/g, '\n');
	same(new can.Mustache({ text: t.template }).render(t.data), expected);
});

test("Handlebars advanced helpers (from docs)", function() {
	Mustache.registerHelper('exercise', function(group, action, num, options){
		if (group && group.length > 0 && action && num > 0) {
			return options.fn({
				group: group,
				action: action,
				where: options.hash.where,
				when: options.hash.when,
				num: num
			});
		}
		else {
			return options.inverse(this);
		}
	});
	
	var t = {
		template: "{{#exercise pets 'walked' 3 where='around the block' when=time}}" +
				"Along with the {{#group}}{{.}}, {{/group}}" +
				"we {{action}} {{where}} {{num}} times {{when}}." +
			"{{else}}" +
				"We were lazy today." +
			"{{/exercise}}",
		expected: "Along with the cat, dog, parrot, we walked around the block 3 times this morning.",
		expected2: "We were lazy today.",
		data: {
			pets: ['cat', 'dog', 'parrot'],
			time: 'this morning'
		}
	};
	
	same(new can.Mustache({ text: t.template }).render(t.data), t.expected);
	same(new can.Mustache({ text: t.template }).render({}), t.expected2);
});

test("Passing functions as data, then executing them", function() {
	var t = {
		template: "{{#nested}}{{welcome name}}{{/nested}}",
		expected: "Welcome Andy!",
		data: {
			name: 'Andy', 
			nested: {
				welcome: function(name) {
					return 'Welcome ' + name + '!';
				}
			}
		}
	};
	
	var expected = t.expected.replace(/&quot;/g, '&#34;').replace(/\r\n/g, '\n');
	same(new can.Mustache({ text: t.template }).render(t.data), expected);
});

test("Deeply nested partials", function() {
	var t = {
		template: "{{#nest1}}{{#nest2}}{{>partial}}{{/nest2}}{{/nest1}}",
		expected: "Hello!",
		partials: { partial: '{{#nest3}}{{name}}{{/nest3}}' },
		data: {
			nest1: {
				nest2: {
					nest3: {
						name: 'Hello!'
					}
				}
			}
		}
	};
	for(var name in t.partials) {
		can.view.registerView(name, t.partials[name])
	}
	
	same(new can.Mustache({ text: t.template }).render(t.data), t.expected);
});

test("Handlebars helper: if/else", function() {
	var t = {
		template: "{{#if name}}{{name}}{{/if}}{{#if missing}}{{else}} is missing!{{/if}}",
		expected: "Andy is missing!",
		data: { name: 'Andy' }
	};
	
	var expected = t.expected.replace(/&quot;/g, '&#34;').replace(/\r\n/g, '\n');
	same(new can.Mustache({ text: t.template }).render(t.data), expected);
});

test("Handlebars helper: unless", function() {
	var t = {
		template: "{{#unless missing}}Andy is missing!{{/unless}}",
		expected: "Andy is missing!",
		data: { name: 'Andy' }
	};
	
	var expected = t.expected.replace(/&quot;/g, '&#34;').replace(/\r\n/g, '\n');
	same(new can.Mustache({ text: t.template }).render(t.data), expected);
});

test("Handlebars helper: each", function() {
	var t = {
		template: "{{#each names}}{{this}} {{/each}}",
		expected: "Andy Austin Justin ",
		data: { names: ['Andy', 'Austin', 'Justin'] }
	};
	
	var expected = t.expected.replace(/&quot;/g, '&#34;').replace(/\r\n/g, '\n');
	same(new can.Mustache({ text: t.template }).render(t.data), expected);
});

test("Handlebars helper: with", function() {
	var t = {
		template: "{{#with person}}{{name}}{{/with}}",
		expected: "Andy",
		data: { person: { name: 'Andy' } }
	};
	
	var expected = t.expected.replace(/&quot;/g, '&#34;').replace(/\r\n/g, '\n');
	same(new can.Mustache({ text: t.template }).render(t.data), expected);
});

test("render with left bracket", function(){
	var compiled = new can.Mustache({text: this.squareBrackets, type: '['}).render({animals: this.animals})
	equals(compiled, "<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>", "renders with bracket")
})
test("render with with", function(){
	var compiled = new can.Mustache({text: this.squareBracketsNoThis, type: '['}).render({animals: this.animals}) ;
	equals(compiled, "<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>", "renders bracket with no this")
})
test("default carrot", function(){
	var compiled = new can.Mustache({text: this.angleBracketsNoThis}).render({animals: this.animals}) ;

	equals(compiled, "<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>")
})
test("render with double angle", function(){
	var text = "{{& replace_me }}{{{ replace_me_too }}}"+
			  "<ul>{{#animals}}" +
	               "<li>{{.}}</li>" + 
		      "{{/animals}}</ul>";
	var compiled = new can.Mustache({text: text}).render({animals: this.animals}) ;
	equals(compiled, "<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>", "works")
});

test("comments", function(){
	var text = "{{! replace_me }}"+
			  "<ul>{{#animals}}" +
	               "<li>{{.}}</li>" + 
		      "{{/animals}}</ul>";
	var compiled = new can.Mustache({text: text}).render({animals: this.animals}) ;
	equals(compiled,"<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>" )
});

test("multi line", function(){
	var text = "a \n b \n c",
		result = new can.Mustache({text: text}).render({}) ;
		
	equals(result, text)
})

test("multi line elements", function(){
    var text = "<img\n class=\"{{myClass}}\" />",
        result = new can.Mustache({text: text}).render({myClass: 'a'}) ;

    ok(result.indexOf( "<img\n class=\"a\"" ) !== -1, "Multi-line elements render correctly.");
})

test("escapedContent", function(){
	var text = "<span>{{ tags }}</span><label>&amp;</label><strong>{{ number }}</strong><input value='{{ quotes }}'/>";
	var compiled = new can.Mustache({text: text}).render({tags: "foo < bar < car > zar > poo",
							quotes : "I use 'quote' fingers \"a lot\"",
							number : 123}) ;
	
	var div = document.createElement('div');
	div.innerHTML = compiled;
	
	equals(div.getElementsByTagName('span')[0].firstChild.nodeValue, "foo < bar < car > zar > poo" );
	equals(div.getElementsByTagName('strong')[0].firstChild.nodeValue, 123 );
	equals(div.getElementsByTagName('input')[0].value, "I use 'quote' fingers \"a lot\"" );
	equals(div.getElementsByTagName('label')[0].innerHTML, "&amp;" );
})

test("unescapedContent", function(){
	var text = "<span>{{{ tags }}}</span><div>{{{ tags }}}</div><input value='{{ quotes }}'/>";
	var compiled = new can.Mustache({text: text}).render({tags: "<strong>foo</strong><strong>bar</strong>",
							quotes : "I use 'quote' fingers \"a lot\""}) ;
	
	var div = document.createElement('div');
	div.innerHTML = compiled;

	equals(div.getElementsByTagName('span')[0].firstChild.nodeType, 1 );
	equals(div.getElementsByTagName('div')[0].innerHTML.toLowerCase(), "<strong>foo</strong><strong>bar</strong>" );
	equals(div.getElementsByTagName('span')[0].innerHTML.toLowerCase(), "<strong>foo</strong><strong>bar</strong>" );
	equals(div.getElementsByTagName('input')[0].value, "I use 'quote' fingers \"a lot\"", "escapped no matter what" );
});

/*
not really applicable...but could update to work oince complete
test("returning blocks", function(){
	var somethingHelper = function(cb){
		return cb([1,2,3,4])
	}
	
	var res = can.view.
		render("//can/view/mustache/test_template.mustache",{
			something: somethingHelper, 
			items: ['a','b']
		});
	// make sure expected values are in res
	ok(/\s4\s/.test(res), "first block called" );
	equals(res.match(/ItemsLength4/g).length, 4, "innerBlock and each")
}); */

test("easy hookup", function(){
	var div = document.createElement('div');
	div.appendChild(can.view("//can/view/mustache/test/easyhookup.mustache",{text: "yes"}))
	
	ok( div.getElementsByTagName('div')[0].className.indexOf("yes") != -1, "has yes" )
});

test('multiple function hookups in a tag', function(){

	var text =	"<span {{(el)-> can.data(can.$(el),'foo','bar')}}" + 
		" {{(el)-> can.data(can.$(el),'baz','qux')}}>lorem ipsum</span>",
		compiled = new can.Mustache({ text: text }).render(),
		div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	var span = div.getElementsByTagName('span')[0];

	equals(can.data(can.$(span), 'foo'), 'bar', "first hookup");
	equals(can.data(can.$(span), 'baz'), 'qux', "second hookup");

})

/*
needs andy's helper logic
test("helpers", function() {
	can.Mustache.Helpers.prototype.simpleHelper = function()
	{
		return 'Simple';
	}
	
	can.Mustache.Helpers.prototype.elementHelper = function()
	{
		return function(el) {
			el.innerHTML = 'Simple';
		}
	}
	
	var text = "<div>{{ simpleHelper() }}</div>";
	var compiled = new can.Mustache({text: text}).render() ;
	equals(compiled, "<div>Simple</div>");
	
	text = "<div id=\"hookup\" {{ elementHelper() }}></div>";
	compiled = new can.Mustache({text: text}).render() ;
	can.append( can.$('#qunit-test-area'), can.view.frag(compiled));
	equals(can.$('#hookup')[0].innerHTML, "Simple");
}); */


test("attribute single unescaped, html single unescaped", function(){

	var text = "<div id='me' class='{{#task.completed}}complete{{/task.completed}}'>{{ task.name }}</div>";
	var task = new can.Observe({
		name : 'dishes'
	})
	var compiled = new can.Mustache({text: text}).render({ task: task }) ;
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled))
	

	equals(div.getElementsByTagName('div')[0].innerHTML,"dishes", "html correctly dishes")
	equals(div.getElementsByTagName('div')[0].className,"", "class empty")
	
	
	task.attr('name','lawn')
	
	equals(div.getElementsByTagName('div')[0].innerHTML,"lawn", "html correctly lawn")
	equals(div.getElementsByTagName('div')[0].className,"", "class empty")
	
	task.attr('completed', true);
	
	equals(div.getElementsByTagName('div')[0].className, "complete", "class changed to complete")
});


test("event binding / triggering on options", function(){
	var addEventListener = function(el, name, fn) {
		if (el.addEventListener) {
			el.addEventListener(name, fn, false);
		} else {
			el['on'+name] = fn;
		}
	};
	var frag = can.buildFragment("<select><option>a</option></select>",[document]);
	var qta = document.getElementById('qunit-test-area');
	qta.innerHTML = "";
	qta.appendChild(frag);
	
	/*qta.addEventListener("foo", function(){
		ok(false, "event handler called")
	},false)*/
	

	// destroyed events should not bubble
	addEventListener(qta.getElementsByTagName("option")[0], "foo", function(ev){
		ok(true,"option called");
		ev.stopPropagation && ev.stopPropagation();
		//ev.cancelBubble = true;
	});
	
	addEventListener(qta.getElementsByTagName("select")[0], "foo", function(){
		ok(true,"select called")
	});
	
	var ev;
	if (document.createEvent) {
		ev = document.createEvent("HTMLEvents");
	} else {
		ev = document.createEventObject("HTMLEvents");
	}
	
	if (ev.initEvent)
		ev.initEvent("foo", true , true);
	else
		ev.eventType = "foo";
		
	if (qta.getElementsByTagName("option")[0].dispatchEvent) {
		qta.getElementsByTagName("option")[0].dispatchEvent(ev); 
	} else {
		qta.getElementsByTagName("option")[0].onfoo(ev);
	}
	
	can.trigger(qta,"foo")
	
	stop();
	setTimeout(function(){
		start();
		ok(true);
	},100)
})


test("select live binding", function() {
	var text = "<select>{{ #todos }}<option>{{ name }}</option>{{ /todos }}</select>";
		Todos = new can.Observe.List([
			{id: 1, name: 'Dishes'}
		]),
		compiled = new can.Mustache({text: text}).render({todos: Todos}),
		div = document.createElement('div');

		div.appendChild(can.view.frag(compiled))
		equals(div.getElementsByTagName('option').length, 1, '1 item in list')
		
		Todos.push({id: 2, name: 'Laundry'})
		equals(div.getElementsByTagName('option').length, 2, '2 items in list')
		
		Todos.splice(0, 2);
		equals(div.getElementsByTagName('option').length, 0, '0 items in list')
});  

test('multiple hookups in a single attribute', function() {
	var text =	'<div class=\'{{ obs.foo }}' +
							'{{ obs.bar }}{{ obs.baz }}{{ obs.nest.what }}\'></div>',

	obs = new can.Observe({
		foo: 'a',
		bar: 'b',
		baz: 'c',
		nest: new can.Observe({
			what: 'd'
		})
	}),

	compiled = new can.Mustache({ text: text }).render({ obs: obs })
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	
 	var innerDiv = div.childNodes[0];
 
	equals(getAttr(innerDiv, 'class'), "abcd", 'initial render');
 
 	obs.attr('bar', 'e');
 
	equals(getAttr(innerDiv, 'class'), "aecd", 'initial render');
 	
 	obs.attr('bar', 'f');
 
	equals(getAttr(innerDiv, 'class'), "afcd", 'initial render');
	
	obs.nest.attr('what', 'g');
	
	equals(getAttr(innerDiv, 'class'), "afcg", 'nested observe');
});

test('adding and removing multiple html content within a single element', function(){
	
	var text =	'<div>{{ obs.a }}{{ obs.b }}{{ obs.c }}</div>',

	obs = new can.Observe({
		a: 'a',
		b: 'b',
		c: 'c'
	});

	compiled = new can.Mustache({ text: text }).render({ obs: obs })
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));

	equals(div.childNodes[0].innerHTML, 'abc', 'initial render');

	obs.attr({a: '', b : '', c: ''});

	equals(div.childNodes[0].innerHTML, '', 'updated values');
	
	obs.attr({c: 'c'});
	
	equals(div.childNodes[0].innerHTML, 'c', 'updated values');
});

test('live binding and removeAttr', function(){

	var text = '{{ #obs.show }}' + 
			'<p {{ obs.attributes }} class="{{ obs.className }}"><span>{{ obs.message }}</span></p>' + 
		'{{ /obs.show }}',

		obs = new can.Observe({
			show: true,
			className: 'myMessage',
			attributes: 'some=\"myText\"',
			message: 'Live long and prosper'
		}),

		compiled = new can.Mustache({ text: text }).render({ obs: obs }),

		div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));


	var p = div.getElementsByTagName('p')[0],
		span = p.getElementsByTagName('span')[0];
	
	equals(p.getAttribute("some"), "myText", 'initial render attr');
	equals(getAttr(p, "class"), "myMessage", 'initial render class');
	equals(span.innerHTML, 'Live long and prosper', 'initial render innerHTML');
	
	obs.removeAttr('className');
	
	equals(getAttr(p, "class"), '', 'class is undefined');
	
	obs.attr('className', 'newClass');
	
	equals(getAttr(p, "class"), 'newClass', 'class updated');
	
	obs.removeAttr('attributes');
	
	equals(p.getAttribute('some'), null, 'attribute is undefined');
	
	obs.attr('attributes', 'some="newText"');
	
	equals(p.getAttribute('some'), 'newText', 'attribute updated');
	
	obs.removeAttr('message');
	
	equals(span.innerHTML, '', 'text node value is empty');
	
	obs.attr('message', 'Warp drive, Mr. Sulu');
	
	equals(span.innerHTML, 'Warp drive, Mr. Sulu', 'text node updated');
	
	obs.removeAttr('show');
	
	equals(div.innerHTML, '', 'value in block statement is undefined');
	
	obs.attr('show', true);
	
	var p = div.getElementsByTagName('p')[0],
		span = p.getElementsByTagName('span')[0];
	
	equals(p.getAttribute("some"), "newText", 'value in block statement updated attr');
	equals(getAttr(p, "class"), "newClass", 'value in block statement updated class');
	equals(span.innerHTML, 'Warp drive, Mr. Sulu', 'value in block statement updated innerHTML');

});

test('hookup within a tag', function () {
	var text =	'<div {{ obs.foo }} '
		+ '{{ obs.baz }}>lorem ipsum</div>',

	obs = new can.Observe({
		foo: 'class="a"',
		baz: 'some=\'property\''
	}),

	compiled = new can.Mustache({ text: text }).render({ obs: obs });

	var div = document.createElement('div');
	div.appendChild(can.view.frag(compiled));
	var anchor = div.getElementsByTagName('div')[0];

	equals(getAttr(anchor, 'class'), 'a');
	equals(anchor.getAttribute('some'), 'property');

	obs.attr('foo', 'class="b"');
	equals(getAttr(anchor, 'class'), 'b');
	equals(anchor.getAttribute('some'), 'property');

	obs.attr('baz', 'some=\'new property\'');
	equals(getAttr(anchor, 'class'), 'b');
	equals(anchor.getAttribute('some'), 'new property');

	obs.attr('foo', 'class=""');
	obs.attr('baz', '');
	equals(getAttr(anchor, 'class'), "", 'anchor class blank');
	equals(anchor.getAttribute('some'), undefined, 'attribute "some" is undefined');
});

test('single escaped tag, removeAttr', function () {
	var text =	'<div {{ obs.foo }}>lorem ipsum</div>',

	obs = new can.Observe({
		foo: 'data-bar="john doe\'s bar"'
	}),

	compiled = new can.Mustache({ text: text }).render({ obs: obs });

	var div = document.createElement('div');
	div.appendChild(can.view.frag(compiled));
	var anchor = div.getElementsByTagName('div')[0];

	equals(anchor.getAttribute('data-bar'), "john doe's bar");

	obs.removeAttr('foo');
	equals(anchor.getAttribute('data-bar'), null);

	obs.attr('foo', 'data-bar="baz"');
	equals(anchor.getAttribute('data-bar'), 'baz');
});



test('html comments', function(){
	var text =	'<!-- bind to changes in the todo list --> <div> '
	+ '<%= obs.foo %></div>',

	obs = new can.Observe({
		foo: 'foo'
	})

	compiled = new can.Mustache({ text: text }).render({ obs: obs });

	var div = document.createElement('div');
	div.appendChild(can.view.frag(compiled));
})

test("hookup and live binding", function(){
	
	var text = "<div class='{{ task.completed }}' {{ (el)-> can.data(can.$(el),'task',task) }}>" +
		"{{ task.name }}" +
		"</div>",
		task = new can.Observe({
			completed: false,
			className: 'someTask',
			name: 'My Name'
		}),
		compiled = new can.Mustache({ text: text }).render({ task: task }),
		div = document.createElement('div');
	
	div.appendChild(can.view.frag(compiled))
	var child = div.getElementsByTagName('div')[0];
	ok( child.className.indexOf("false") > -1, "is incomplete" )
	ok( !!can.data(can.$(child), 'task'), "has data" )
	equals(child.innerHTML, "My Name", "has name")
	
	task.attr({
		completed: true,
		name: 'New Name'
	});
	
	ok( child.className.indexOf("true") != -1, "is complete" )
	equals(child.innerHTML, "New Name", "has new name")
	
})


test('multiple curly braces in a block', function() {
	var text =  '{{^obs.items}}' +
					'<li>No items</li>' +
				'{{/obs.items}}' +
				'{{#obs.items}}' +
					'<li>{{name}}</li>' +
				'{{/obs.items}}',

	obs = new can.Observe({
		items: []
	}),

	compiled = new can.Mustache({ text: text }).render({ obs: obs });

	var ul = document.createElement('ul');
	ul.appendChild(can.view.frag(compiled));

	equals(ul.getElementsByTagName('li')[0].innerHTML, 'No items', 'initial observable state');

	obs.attr('items', [{ name: 'foo' }]);
	equals(ul.getElementsByTagName('li')[0].innerHTML, 'foo', 'updated observable');
});

test("unescape bindings change", function(){
	var l = new can.Observe.List([
		{complete: true},
		{complete: false},
		{complete: true}
	]);
	var completed = function(){
		l.attr('length');
		var num = 0;
		l.each(function(item){
			if(item.attr('complete')){
				num++;
			}
		})
		return num;
	};
	
	var text =	'<div>{{ completed }}</div>',


	compiled = new can.Mustache({ text: text }).render({ completed: completed });

	var div = document.createElement('div');
	div.appendChild(can.view.frag(compiled));
	
	var child = div.getElementsByTagName('div')[0];
	equals(child.innerHTML, "2", "at first there are 2 true bindings");
	var item = new can.Observe({complete: true, id: "THIS ONE"})
	l.push(item);
	
	equals(child.innerHTML, "3", "now there are 3 complete");
	
	item.attr('complete',false);
	
	equals(child.innerHTML, "2", "now there are 2 complete");
	
	l.pop();
	
	item.attr('complete',true);
	
	equals(child.innerHTML, "2", "there are still 2 complete");
});


test("escape bindings change", function(){
	var l = new can.Observe.List([
		{complete: true},
		{complete: false},
		{complete: true}
	]);
	var completed = function(){
		l.attr('length');
		var num = 0;
		l.each(function(item){
			if(item.attr('complete')){
				num++;
			}
		})
		return num;
	};
	
	var text =	'<div>{{{ completed }}}</div>',


	compiled = new can.Mustache({ text: text }).render({ completed: completed });

	var div = document.createElement('div');
	div.appendChild(can.view.frag(compiled));
	
	var child = div.getElementsByTagName('div')[0];
	equals(child.innerHTML, "2", "at first there are 2 true bindings");
	var item = new can.Observe({complete: true})
	l.push(item);
	
	equals(child.innerHTML, "3", "now there are 3 complete");
	
	item.attr('complete',false);
	
	equals(child.innerHTML, "2", "now there are 2 complete");
});


test("tag bindings change", function(){
	var l = new can.Observe.List([
		{complete: true},
		{complete: false},
		{complete: true}
	]);
	var completed = function(){
		l.attr('length');
		var num = 0;
		l.each(function(item){
			if(item.attr('complete')){
				num++;
			}
		})
		return "items='"+num+"'";
	};
	
	var text =	'<div {{{ completed }}}></div>',


	compiled = new can.Mustache({ text: text }).render({ completed: completed });

	var div = document.createElement('div');
	div.appendChild(can.view.frag(compiled));
	
	var child = div.getElementsByTagName('div')[0];
	equals(child.getAttribute("items"), "2", "at first there are 2 true bindings");
	var item = new can.Observe({complete: true})
	l.push(item);
	
	equals(child.getAttribute("items"), "3", "now there are 3 complete");
	
	item.attr('complete',false);
	
	equals(child.getAttribute("items"), "2", "now there are 2 complete");
})

test("attribute value bindings change", function(){
	var l = new can.Observe.List([
		{complete: true},
		{complete: false},
		{complete: true}
	]);
	var completed = function(){
		l.attr('length');
		var num = 0;
		l.each(function(item){
			if(item.attr('complete')){
				num++;
			}
		})
		return num;
	};
	
	var text =	'<div items="{{{ completed }}}"></div>',


	compiled = new can.Mustache({ text: text }).render({ completed: completed });

	var div = document.createElement('div');
	div.appendChild(can.view.frag(compiled));
	
	var child = div.getElementsByTagName('div')[0];
	equals(child.getAttribute("items"), "2", "at first there are 2 true bindings");
	var item = new can.Observe({complete: true})
	l.push(item);
	
	equals(child.getAttribute("items"), "3", "now there are 3 complete");
	
	item.attr('complete',false);
	
	equals(child.getAttribute("items"), "2", "now there are 2 complete");
})

test("in tag toggling", function(){
		var text = "<div {{ obs.val }}></div>"
	
	
	var obs = new can.Observe({
		val : 'foo="bar"'
	})
	
	var compiled = new can.Mustache({text: text}).render({obs: obs});
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	
	obs.attr('val',"bar='foo'");
	obs.attr('val','foo="bar"')
	var d2 = div.getElementsByTagName('div')[0];
	// toUpperCase added to normalize cases for IE8
	equals( d2.getAttribute("foo") , "bar","bar set");
	equals( d2.getAttribute("bar") , null,"bar set")
});


// not sure about this w/ mustache
test("nested properties", function(){
	
	var text = "<div>{{ obs.name.first }}</div>"
	
	
	var obs = new can.Observe({
		name : {first : "Justin"}
	})
	
	var compiled = new can.Mustache({text: text}).render({obs: obs});
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	
	var div = div.getElementsByTagName('div')[0];

	equals(div.innerHTML, "Justin")

	obs.attr('name.first',"Brian")

	equals(div.innerHTML, "Brian")
	
});

test("tags without chidren or ending with /> do not change the state", function(){

	var text = "<table><tr><td/>{{{ obs.content }}}</tr></div>"
	var obs = new can.Observe({
		content: "<td>Justin</td>"
	})
	var compiled = new can.Mustache({text: text}).render({obs: obs});
	var div = document.createElement('div');
	var html = can.view.frag(compiled);
	div.appendChild(html);

	equal( div.getElementsByTagName('span').length, 0, "there are no spans");
	equal( div.getElementsByTagName('td').length, 2, "there are 2 td");
})



test("nested live bindings", function(){
	var items  = new can.Observe.List([
		{title: 0, is_done: false, id: 0}
	]);
	
	var div = document.createElement('div');
	div.appendChild(can.view("//can/view/mustache/test/nested_live_bindings.mustache",{items: items}))
	
	items.push({title: 1, is_done: false, id: 1});
	// this will throw an error unless Mustache protects against
	// nested objects

	items[0].attr('is_done',true);
});


test("trailing text", function(){
	can.view.mustache("count","There are {{ length }} todos")
	var div = document.createElement('div');
	div.appendChild( can.view("count", new can.Observe.List([{},{}])) );
	ok(/There are 2 todos/.test(div.innerHTML), "got all text")
})

test("recursive views", function(){
	
	var data = new can.Observe.List([
            {label:'branch1', children:[{id:2, label:'branch2'}]}
        ])
	
	var div = document.createElement('div');
	div.appendChild( can.view('//can/view/mustache/test/recursive.mustache',  {items: data}));
	ok(/class="?leaf"?/.test(div.innerHTML), "we have a leaf")
	
})

test("live binding textarea", function(){
	can.view.mustache("textarea-test","<textarea>Before{{ obs.middle }}After</textarea>");
	
	var obs = new can.Observe({middle: "yes"}),
		div = document.createElement('div');
	
	div.appendChild( can.view("textarea-test",{obs: obs}) )
	var textarea = div.firstChild
	
	equal(textarea.value, "BeforeyesAfter");
	
	obs.attr("middle","Middle")
	equal(textarea.value, "BeforeMiddleAfter")
	
})

});
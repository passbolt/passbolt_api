(function() {
	
module("can/view/ejs, rendering",{
	setup : function(){

		this.animals = ['sloth', 'bear', 'monkey']
		if(!this.animals.each){
			this.animals.each = function(func){
				for(var i =0; i < this.length; i++){
					func(this[i])
				}
			}
		}
		
		this.squareBrackets = "<ul><% this.animals.each(function(animal){%>" +
					   "<li><%= animal %></li>" + 
				  "<%});%></ul>"
		this.squareBracketsNoThis = "<ul><% animals.each(function(animal){%>" +
					   "<li><%= animal %></li>" + 
				  "<%});%></ul>"
		this.angleBracketsNoThis  = "<ul><% animals.each(function(animal){%>" +
					   "<li><%= animal %></li>" + 
				  "<%});%></ul>";

	}
})

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




test("render with left bracket", function(){
	var compiled = new can.EJS({text: this.squareBrackets, type: '['}).render({animals: this.animals})
	equals(compiled, "<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>", "renders with bracket")
})
test("render with with", function(){
	var compiled = new can.EJS({text: this.squareBracketsNoThis, type: '['}).render({animals: this.animals}) ;
	equals(compiled, "<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>", "renders bracket with no this")
})
test("default carrot", function(){
	var compiled = new can.EJS({text: this.angleBracketsNoThis}).render({animals: this.animals}) ;

	equals(compiled, "<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>")
})
test("render with double angle", function(){
	var text = "<%% replace_me %>"+
			  "<ul><% animals.each(function(animal){%>" +
				   "<li><%= animal %></li>" + 
			  "<%});%></ul>";
	var compiled = new can.EJS({text: text}).render({animals: this.animals}) ;
	equals(compiled, "<% replace_me %><ul><li>sloth</li><li>bear</li><li>monkey</li></ul>", "works")
});

test("comments", function(){
	var text = "<%# replace_me %>"+
			  "<ul><% animals.each(function(animal){%>" +
				   "<li><%= animal %></li>" + 
			  "<%});%></ul>";
	var compiled = new can.EJS({text: text}).render({animals: this.animals}) ;
	equals(compiled,"<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>" )
});

test("multi line", function(){
	var text = "a \n b \n c",
		result = new can.EJS({text: text}).render({}) ;
		
	equals(result, text)
})

test("multi line elements", function(){
	var text = "<img\n class=\"<%=myClass%>\" />",
		result = new can.EJS({text: text}).render({myClass: 'a'}) ;

	ok(result.indexOf( "<img\n class=\"a\"" ) !== -1, "Multi-line elements render correctly.");
})

test("escapedContent", function(){
	var text = "<span><%= tags %></span><label>&amp;</label><strong><%= number %></strong><input value='<%= quotes %>'/>";
	var compiled = new can.EJS({text: text}).render({tags: "foo < bar < car > zar > poo",
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
	var text = "<span><%== tags %></span><div><%= tags %></div><input value='<%== quotes %>'/>";
	var compiled = new can.EJS({text: text}).render({tags: "<strong>foo</strong><strong>bar</strong>",
							quotes : "I use 'quote' fingers \"a lot\""}) ;
	
	var div = document.createElement('div');
	div.innerHTML = compiled;

	equals(div.getElementsByTagName('span')[0].firstChild.nodeType, 1 );
	equals(div.getElementsByTagName('div')[0].firstChild.nodeValue.toLowerCase(), "<strong>foo</strong><strong>bar</strong>" );
	equals(div.getElementsByTagName('span')[0].innerHTML.toLowerCase(), "<strong>foo</strong><strong>bar</strong>" );
	equals(div.getElementsByTagName('input')[0].value, "I use 'quote' fingers \"a lot\"", "escapped no matter what" );
});

test("returning blocks", function(){
	var somethingHelper = function(cb){
		return cb([1,2,3,4])
	}
	
	var res = can.view.
		render("//can/view/ejs/test/test_template.ejs",{
			something: somethingHelper, 
			items: ['a','b']
		});
	// make sure expected values are in res
	ok(/\s4\s/.test(res), "first block called" );
	equals(res.match(/ItemsLength4/g).length, 4, "innerBlock and each")
});

test("easy hookup", function(){
	var div = document.createElement('div');
	div.appendChild(can.view("//can/view/ejs/test/easyhookup.ejs",{text: "yes"}))
	
	ok( div.getElementsByTagName('div')[0].className.indexOf("yes") != -1, "has yes" )
});

test('multiple function hookups in a tag', function(){

	var text =	"<span <%= (el)-> can.data(can.$(el),'foo','bar') %>" + 
		" <%= (el)-> can.data(can.$(el),'baz','qux') %>>lorem ipsum</span>",
		compiled = new can.EJS({ text: text }).render(),
		div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	var span = div.getElementsByTagName('span')[0];

	equals(can.data(can.$(span), 'foo'), 'bar', "first hookup");
	equals(can.data(can.$(span), 'baz'), 'qux', "second hookup");

})

test("helpers", function() {
	can.EJS.Helpers.prototype.simpleHelper = function()
	{
		return 'Simple';
	}
	
	can.EJS.Helpers.prototype.elementHelper = function()
	{
		return function(el) {
			el.innerHTML = 'Simple';
		}
	}
	
	var text = "<div><%= simpleHelper() %></div>";
	var compiled = new can.EJS({text: text}).render() ;
	equals(compiled, "<div>Simple</div>");
	
	text = "<div id=\"hookup\" <%= elementHelper() %>></div>";
	compiled = new can.EJS({text: text}).render() ;
	can.append( can.$('#qunit-test-area'), can.view.frag(compiled));
	equals(can.$('#hookup')[0].innerHTML, "Simple");
});

test('list helper', function(){
	
	var text = "<% list(todos, function(todo){ %><div><%= todo.name %></div><% }) %>";
	var	Todos = new can.Observe.List([
			{id: 1, name: 'Dishes'}
		]),
		compiled = new can.EJS({text: text}).render({todos: Todos}),
		div = document.createElement('div');

		div.appendChild(can.view.frag(compiled))
		equals(div.getElementsByTagName('div').length, 1, '1 item in list')
		
		Todos.push({id: 2, name: 'Laundry'})
		equals(div.getElementsByTagName('div').length, 2, '2 items in list')
		
		Todos.splice(0, 2);
		equals(div.getElementsByTagName('div').length, 0, '0 items in list')

		Todos.push({id: 4, name: 'Pick up sticks'});
		equals(div.getElementsByTagName('div').length, 1, '1 item in list again')

});

test("attribute single unescaped, html single unescaped", function(){

	var text = "<div id='me' class='<%== task.attr('completed') ? 'complete' : ''%>'><%== task.attr('name') %></div>";
	var task = new can.Observe({
		name : 'dishes'
	})
	var compiled = new can.EJS({text: text}).render({task:  task}) ;
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled))
	

	equals(div.getElementsByTagName('div')[0].innerHTML,"dishes", "html correctly dishes")
	equals(div.getElementsByTagName('div')[0].className,"", "class empty")
	
	
	task.attr('name','lawn')
	
	equals(div.getElementsByTagName('div')[0].innerHTML,"lawn", "html correctly lawn")
	equals(div.getElementsByTagName('div')[0].className,"", "class empty")
	
	task.attr('completed', true);
	
	equals(div.getElementsByTagName('div')[0].className,"complete", "class changed to complete")
});

test("event binding / triggering on things other than options", 1, function(){
	var frag = can.buildFragment("<ul><li>a</li></ul>",[document]);
	var qta = document.getElementById('qunit-test-area');
	qta.innerHTML = "";
	qta.appendChild(frag);
	
	// destroyed events should not bubble
	can.bind.call(qta.getElementsByTagName("li")[0], 'foo', function(event) {
		ok(true,"li called :)");
	});

	can.bind.call(qta.getElementsByTagName("ul")[0], 'foo', function(event) {
		ok(false,"ul called :(");
	});

	can.trigger(qta.getElementsByTagName('li')[0], 'foo', {}, false);

	qta.removeChild(qta.firstChild);
})

test("select live binding", function() {
	var text = "<select><% todos.each(function(todo){ %><option><%= todo.name %></option><% }) %></select>";
		Todos = new can.Observe.List([
			{id: 1, name: 'Dishes'}
		]),
		compiled = new can.EJS({text: text}).render({todos: Todos}),
		div = document.createElement('div');

		div.appendChild(can.view.frag(compiled))
		equals(div.getElementsByTagName('option').length, 1, '1 item in list')

		Todos.push({id: 2, name: 'Laundry'})
		equals(div.getElementsByTagName('option').length, 2, '2 items in list')

		Todos.splice(0, 2);
		equals(div.getElementsByTagName('option').length, 0, '0 items in list')
});

test("block live binding", function(){
	
	var text = "<div><% if( obs.attr('sex') == 'male' ){ %>"+
			"<span>Mr.</span>"+
		"<% } else { %>"+
		  "<label>Ms.</label>"+
		"<% } %>"+
		"</div>"
	
	
	var obs = new can.Observe({
		sex : 'male'
	})
	
	var compiled = new can.EJS({text: text}).render({obs: obs});
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled))
	
	// We have to test using nodeName and innerHTML (and not outerHTML) because IE 8 and under treats
	// user-defined properties on nodes as attributes.
	equals(div.getElementsByTagName('div')[0].firstChild.nodeName.toUpperCase(), "SPAN","initial span tag");
	equals(div.getElementsByTagName('div')[0].firstChild.innerHTML, "Mr.","initial span content");
	
	obs.attr('sex','female')
	
	equals(div.getElementsByTagName('div')[0].firstChild.nodeName.toUpperCase(), "LABEL","updated label tag");
	equals(div.getElementsByTagName('div')[0].firstChild.innerHTML, "Ms.","updated label content");
	
})

test("hookups in tables", function(){
	var text = "<table><tbody><% if( obs.attr('sex') == 'male' ){ %>"+
			"<tr><td>Mr.</td></tr>"+
		"<% } else { %>"+
		  "<tr><td>Ms.</td></tr>"+
		"<% } %>"+
		"</tbody></table>"
		
	var obs = new can.Observe({
		sex : 'male'
	})
	
	var compiled = new can.EJS({text: text}).render({obs: obs});
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	
	// We have to test using nodeName and innerHTML (and not outerHTML) because IE 8 and under treats
	// user-defined properties on nodes as attributes.
	equals(div.getElementsByTagName('tbody')[0].firstChild.firstChild.nodeName, "TD","initial tag");
	equals(div.getElementsByTagName('tbody')[0].firstChild.firstChild.innerHTML.replace(/(\r|\n)+/g, ""), 
		"Mr.","initial content");
	
	obs.attr('sex','female')
	
	equals(div.getElementsByTagName('tbody')[0].firstChild.firstChild.nodeName, "TD","updated tag");
	equals(div.getElementsByTagName('tbody')[0].firstChild.firstChild.innerHTML.replace(/(\r|\n)+/g, ""), 
		"Ms.","updated content");
})

test('multiple hookups in a single attribute', function() {
	var text =	'<div class=\'<%= obs.attr("foo") %>a<%= obs.attr("bar") %>b<%= obs.attr("baz") %>\'></div>',

	obs = new can.Observe({
		foo: '1',
		bar: '2',
		baz: '3'
	}),

	compiled = new can.EJS({ text: text }).render({ obs: obs })

	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	
	var innerDiv = div.childNodes[0];

	equals(getAttr(innerDiv, 'class'), "1a2b3", 'initial render');

	obs.attr('bar', '4');

	equals(getAttr(innerDiv, 'class'), "1a4b3", 'initial render');
	
	obs.attr('bar', '5');

	equals(getAttr(innerDiv, 'class'), "1a5b3", 'initial render');
});

test('adding and removing multiple html content within a single element', function(){
	
	var text =	'<div><%== obs.attr("a") %><%== obs.attr("b") %><%== obs.attr("c") %></div>',

	obs = new can.Observe({
		a: 'a',
		b: 'b',
		c: 'c'
	});

	compiled = new can.EJS({ text: text }).render({ obs: obs })
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));

	equals(div.firstChild.nodeName.toUpperCase(), 'DIV', 'initial render node name');
	equals(div.firstChild.innerHTML, 'abc', 'initial render text')

	obs.attr({a: '', b : '', c: ''});

	equals(div.firstChild.nodeName.toUpperCase(), 'DIV', 'updated render node name');
	equals(div.firstChild.innerHTML, '', 'updated render text')
	
	obs.attr({c: 'c'});
	
	equals(div.firstChild.nodeName.toUpperCase(), 'DIV', 'updated render node name');
	equals(div.firstChild.innerHTML, 'c', 'updated render text')
});

test('live binding and removeAttr', function(){

	var text = '<% if(obs.attr("show")) { %>' + 
			'<p <%== obs.attr("attributes") %> class="<%= obs.attr("className")%>"><span><%= obs.attr("message") %></span></p>' + 
		'<% } %>',

		obs = new can.Observe({
			show: true,
			className: 'myMessage',
			attributes: 'some=\"myText\"',
			message: 'Live long and prosper'
		}),

		compiled = new can.EJS({ text: text }).render({ obs: obs }),

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

	equals(span.innerHTML, 'undefined', 'text node value is undefined');

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
	var text =	'<div <%== obs.attr("foo") %> '
		+ '<%== obs.attr("baz") %>>lorem ipsum</div>',

	obs = new can.Observe({
		foo: 'class="a"',
		baz: 'some=\'property\''
	}),

	compiled = new can.EJS({ text: text }).render({ obs: obs });

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
	var text =	'<div <%= obs.attr("foo") %>>lorem ipsum</div>',

	obs = new can.Observe({
		foo: 'data-bar="john doe\'s bar"'
	}),

	compiled = new can.EJS({ text: text }).render({ obs: obs });

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
	+ '<%= obs.attr("foo") %></div>',

	obs = new can.Observe({
		foo: 'foo'
	})

	compiled = new can.EJS({ text: text }).render({ obs: obs });

	var div = document.createElement('div');
	div.appendChild(can.view.frag(compiled));
})

test("hookup and live binding", function(){
	
	var text = "<div class='<%= task.attr('completed') ? 'complete' : '' %>' <%= (el)-> can.data(can.$(el),'task',task) %>>" +
		"<%== task.attr('name') %>" +
		"</div>",
		task = new can.Observe({
			completed: false,
			className: 'someTask',
			name: 'My Name'
		}),
		compiled = new can.EJS({ text: text }).render({ task: task }),
		div = document.createElement('div');
	
	div.appendChild(can.view.frag(compiled))
	var child = div.getElementsByTagName('div')[0];
	ok( child.className.indexOf("complete") == -1, "is incomplete" )
	ok( !!can.data(can.$(child), 'task'), "has data" )
	equals(child.innerHTML, "My Name", "has name")
	
	task.attr({
		completed: true,
		name: 'New Name'
	});
	
	ok( child.className.indexOf("complete") != -1, "is complete" )
	equals(child.innerHTML, "New Name", "has new name")
	
})


/*
test('multiple curly braces in a block', function() {
	var text =  '<% if(!obs.attr("items").length) { %>' +
				'<li>No items</li>' +
				'<% } else { each(obs.items, function(item) { %>' +
						'<li><%= item.attr("name") %></li>' +
				'<% }) }%>',

	obs = new can.Observe({
		items: []
	}),

	compiled = new can.EJS({ text: text }).render({ obs: obs });

	var ul = document.createElement('ul');
	ul.appendChild(can.view.frag(compiled));

	equals(ul.innerHTML, '<li>No items</li>', 'initial observable state');

	obs.attr('items', [{ name: 'foo' }]);
	equals(u.innerHTML, '<li>foo</li>', 'updated observable');
});
*/

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
	
	var text =	'<div><%== completed() %></div>',


	compiled = new can.EJS({ text: text }).render({ completed: completed });

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
	
	var text =	'<div><%= completed() %></div>',


	compiled = new can.EJS({ text: text }).render({ completed: completed });

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
	
	var text =	'<div <%= completed() %>></div>',


	compiled = new can.EJS({ text: text }).render({ completed: completed });

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
	
	var text =	'<div items="<%= completed() %>"></div>',


	compiled = new can.EJS({ text: text }).render({ completed: completed });

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
		var text = "<div <%== obs.attr('val') %>></div>"
	
	
	var obs = new can.Observe({
		val : 'foo="bar"'
	})
	
	var compiled = new can.EJS({text: text}).render({obs: obs});
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	
	obs.attr('val',"bar='foo'");
	obs.attr('val','foo="bar"')
	var d2 = div.getElementsByTagName('div')[0];
	// toUpperCase added to normalize cases for IE8
	equals( d2.getAttribute("foo") , "bar","bar set");
	equals( d2.getAttribute("bar") , null,"bar set")
});

test("parent is right with bock", function(){
	var text =  '<ul><% if(!obs.attr("items").length) { %>' +
				'<li>No items</li>' +
				'<% } else { %> <%== obs.attr("content") %>'+
				'<% } %></ul>',

	obs = new can.Observe({
		content : "<li>Hello</li>",
		items: [{name : "Justin"}]
	}),

	compiled = new can.EJS({ text: text }).render({ obs: obs });
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	var ul = div.getElementsByTagName('ul')[0];
	var li = div.getElementsByTagName('li')[0];
	
	ok(ul, "we have a ul");
	ok(li, "we have a li")
	
});

test("property name only attributes", function(){
	
	var text = "<input type='checkbox' <%== obs.attr('val') ? 'checked' : '' %>/>"
	
	
	var obs = new can.Observe({
		val : true
	})
	
	var compiled = new can.EJS({text: text}).render({obs: obs});
	
	var div = document.getElementById('qunit-test-area');

	div.appendChild(can.view.frag(compiled));
	
	var input = div.getElementsByTagName('input')[0];
	can.trigger(input, 'click');
	obs.attr('val',false)

	ok(!input.checked, "not checked")


	obs.attr('val',true);

	ok(input.checked, "checked")
	div.removeChild(input)
});

test("nested properties", function(){
	
	var text = "<div><%= obs.attr('name.first')%></div>"
	
	
	var obs = new can.Observe({
		name : {first : "Justin"}
	})
	
	var compiled = new can.EJS({text: text}).render({obs: obs});
	
	var div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));
	
	var div = div.getElementsByTagName('div')[0];

	equals(div.innerHTML, "Justin")

	obs.attr('name.first',"Brian")

	equals(div.innerHTML, "Brian")
	
});

test("tags without chidren or ending with /> do not change the state", function(){
	
	var text = "<table><tr><td/><%== obs.attr('content') %></tr></div>"
	var obs = new can.Observe({
		content: "<td>Justin</td>"
	})
	var compiled = new can.EJS({text: text}).render({obs: obs});
	var div = document.createElement('div');
	var html = can.view.frag(compiled);
	
	div.appendChild(html)

	equal( div.getElementsByTagName('span').length, 0, "there are no spans");
	equal( div.getElementsByTagName('td').length, 2, "there are 2 td");
})



test("nested live bindings", function(){
	var items  = new can.Observe.List([
		{title: 0, is_done: false, id: 0}
	]);
	
	var div = document.createElement('div');
	div.appendChild(can.view("//can/view/ejs/test/nested_live_bindings.ejs",{items: items}))
	
	items.push({title: 1, is_done: false, id: 1});
	// this will throw an error unless EJS protects against
	// nested objects

	items[0].attr('is_done',true);
});

// Similar to the nested live bindings test, this makes sure templates with control blocks
// will eventually remove themselves if at least one change happens
// before things are removed.
// It is currently commented out because
// 
/*test("memory safe without parentElement of blocks", function(){
	
})*/

test("trailing text", function(){
	can.view.ejs("count","There are <%= this.attr('length') %> todos")
	var div = document.createElement('div');
	div.appendChild( can.view("count", new can.Observe.List([{},{}])) );
	ok(/There are 2 todos/.test(div.innerHTML), "got all text")
})

test("recursive views", function(){
	
	var data = new can.Observe.List([
			{label:'branch1', children:[{id:2, label:'branch2'}]}
		])
	
	var div = document.createElement('div');
	div.appendChild( can.view('//can/view/ejs/test/recursive.ejs',  {items: data}));
	ok(/class="leaf"|class=leaf/.test(div.innerHTML), "we have a leaf")
	
})

test("indirectly recursive views", function() {
	var unordered = new can.Observe.List([
		{ ol: [
			{ ul: [
				{ ol: [1, 2, 3] }
			]}
		]}
	]);
	can.view.cache = false;
	var div = document.createElement('div');	
	div.appendChild(can.view('//can/view/ejs/test/indirect1.ejs', {unordered: unordered}));
	document.getElementById('qunit-test-area').appendChild(div);
	ok(can.trim(can.$('#qunit-test-area ul > li > ol > li > ul > li > ol > li')[0].innerHTML) === "1", "Uncached indirectly recursive EJS working.");
	
	can.view.cache = true;
	div.appendChild(can.view('//can/view/ejs/test/indirect1.ejs', {unordered: unordered}));
	ok(can.trim(can.$('#qunit-test-area ul + ul > li > ol > li > ul > li > ol > li')[0].innerHTML) === "1", "Cached indirectly recursive EJS working.");
	document.getElementById('qunit-test-area').removeChild(div);

});

test("live binding select", function(){
	var text = "<select><% items.each(function(ob) { %>" +
		"<option value='<%= ob.attr('id') %>'><%= ob.attr('title') %></option>" +
		"<% }); %></select>",
		items	 = new can.Observe.List([
			{title: "Make bugs", is_done: true, id: 0},
			{title: "Find bugs", is_done: false, id: 1},
			{title: "Fix bugs", is_done: false, id: 2}
		]),
		compiled = new can.EJS({text: text}).render({items: items}),
		div = document.createElement('div');
		
		div.appendChild(can.view.frag(compiled))
		equal(div.getElementsByTagName('option').length, 3, '3 items in list')

		equal(div.getElementsByTagName('option')[0].value, ""+items[0].id,
			   'value attr set');
		equal(div.getElementsByTagName('option')[0].textContent, items[0].title,
			   'content of option');

		items.push({id: 3, name: 'Go to pub'})
		equal(div.getElementsByTagName('option').length, 4, '4 items in list')
});

test("live binding textarea", function(){
	can.view.ejs("textarea-test","<textarea>Before<%= obs.attr('middle') %>After</textarea>");
	
	var obs = new can.Observe({middle: "yes"}),
		div = document.createElement('div');
	
	var node = can.view("textarea-test", {obs: obs});
	div.appendChild(node);
	var textarea = div.firstChild
	
	equal(textarea.value, "BeforeyesAfter");
	
	obs.attr("middle","Middle")
	equal(textarea.value, "BeforeMiddleAfter")
	
})

test("reset on a live bound input", function(){
	var text = "<input type='text' value='<%= person.attr('name') %>'><button type='reset'>Reset</button>",
		person = new can.Observe({
			name: "Bob"
		}),
		compiled = new can.EJS({text: text}).render({person: person}),
		form = document.createElement('form'),
		input;
		
		form.appendChild(can.view.frag(compiled))
		input = form.getElementsByTagName('input')[0];
		form.reset();
		equals(input.value, "Bob", "value is correct");
});

test("A non-escaping live magic tag within a control structure and no leaks", function(){
	
	for(var prop in can.view.nodeMap){
		delete can.view.nodeMap[prop]
	}
	for(var prop in can.view.nodeListMap){
		delete can.view.nodeListMap[prop]
	}
	
	var text = "<div><% items.each(function(ob) { %>" +
		"<%== ob.attr('html') %>" +
		"<% }); %></div>",
		items	 = new can.Observe.List([
			{html: "<label>Hello World</label>"}
		]),
		compiled = new can.EJS({text: text}).render({items: items}),
		div = can.$('#qunit-test-area')[0]
		div.innerHTML = ""
	
	can.append( can.$('#qunit-test-area'), can.view.frag(compiled));
	
	ok(div.getElementsByTagName('label')[0], "label exists")
	
	items[0].attr("html","<p>hi</p>");
	
	equals(div.getElementsByTagName('label').length, 0, "label is removed")
	equals(div.getElementsByTagName('p').length, 1, "label is replaced by p")
	
	items.push({
		html: "<p>hola</p>"
	});
	
	equals(div.getElementsByTagName('p').length, 2, "label has 2 paragraphs")
	
	can.remove( can.$(div.firstChild) )
		
	same(can.view.nodeMap, {} );
	same(can.view.nodeListMap ,{} )
});


test("attribute unquoting", function() {
	var text = '<input type="radio" ' +
		'<%== facet.single ? \'name="facet-\' + facet.attr("id") + \'"\' : "" %> ' +
		'value="<%= facet.single ? "facet-" + facet.attr("id") : "" %>" />',
	facet = new can.Observe({
		id: 1,
		single: true
	}),

	compiled = new can.EJS({text: text}).render({ facet: facet }),
	div = document.createElement('div');
	div.appendChild(can.view.frag(compiled))

	equals(div.children[0].name, "facet-1");
	equals(div.children[0].value, "facet-1");
});

test("empty element hooks work correctly",function(){
	
	var text = '<div <%= function(e){ e.innerHTML = "1 Will show"; } %>></div>'+
		'<div <%= function(e){ e.innerHTML = "2 Will not show"; } %>></div>'+
		'3 Will not show';
	
	var compiled = new can.EJS({text: text}).render(),
		div = document.createElement('div');

	div.appendChild(can.view.frag(compiled));

	equal(div.childNodes.length, 3, "all three elements present")
	
});

test("live binding with parent dependent tags but without parent tag present in template",function(){
	
	var text = ['<tbody>',
				'<% if( person.attr("first") ){ %>',
				'<tr><td><%= person.first %></td></tr>',
				'<% }%>',
				'<% if( person.attr("last") ){ %>',
				'<tr><td><%= person.last %></td></tr>',
				'<% } %>',
				'</tbody>'];
	var person = new can.Observe({first: "Austin", last: "McDaniel"});

	var compiled = new can.EJS({text: text.join("\n")}).render({person: person});
	var table = document.createElement('table');
	
	table.appendChild(can.view.frag(compiled));

	equals(table.getElementsByTagName('tr')[0].firstChild.nodeName.toUpperCase(), "TD");
	equals(table.getElementsByTagName('tr')[0].firstChild.innerHTML, "Austin");
	equals(table.getElementsByTagName('tr')[1].firstChild.nodeName.toUpperCase(), "TD");
	equals(table.getElementsByTagName('tr')[1].firstChild.innerHTML, "McDaniel");

	person.removeAttr('first')

	equals(table.getElementsByTagName('tr')[0].firstChild.nodeName.toUpperCase(), "TD");
	equals(table.getElementsByTagName('tr')[0].firstChild.innerHTML, "McDaniel");

	person.removeAttr('last');

	equals(table.getElementsByTagName('tr').length, 0);
	
	person.attr('first',"Justin");
	
	equals(table.getElementsByTagName('tr')[0].firstChild.nodeName.toUpperCase(), "TD");
	equals(table.getElementsByTagName('tr')[0].firstChild.innerHTML, "Justin");
})


test("spaces between attribute name and value", function(){
	var text = '<input type="text" value = "<%= test %>" />',
		compiled = new can.EJS({text: text}).render({
			test : 'testing'
		}),
		div = document.createElement('div');
	
	div.appendChild(can.view.frag(compiled));
	var input = div.getElementsByTagName('input')[0];
	
	equal(input.value, 'testing');
	equal(input.type,"text")
})

test("live binding with computes", function() {
	var text = '<span><%= compute() %></span>',
		compute = can.compute(5),
		compiled = new can.EJS({text: text}).render({
			compute: compute
		}),
		div = document.createElement('div');
		
	div.appendChild(can.view.frag(compiled));

	var span = div.getElementsByTagName('span');
	equal(span.length, 1);

	span = span[0];

	equal(span.innerHTML, '5');
	compute(6);
	equal(span.innerHTML, '6');
	compute('Justin');
	equal(span.innerHTML, 'Justin');
	compute(true);
	equal(span.innerHTML, 'true');

})

test('testing for clean tables', function() {
	var games = new can.Observe.List();
  games.push({name: 'The Legend of Zelda', rating: 10});  
  games.push({name: 'The Adventures of Link', rating: 9});  
  games.push({name: 'Dragon Warrior', rating: 9});
  games.push({name: 'A Dude Named Daffl', rating: 8.5});  

	var res = can.view.render("//can/view/ejs/test/table_test.ejs",{
		games: games
	}),
	div = document.createElement('div');

	div.appendChild(can.view.frag(res));

	ok(!(/@@!!@@/.test(div.innerHTML)), "no placeholders" );

})

})();

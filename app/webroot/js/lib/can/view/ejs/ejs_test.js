steal("can/model", "can/view/ejs", "can/test", "steal-qunit", function () {
	QUnit.module('can/view/ejs, rendering', {
		setup: function () {
			can.view.ext = '.ejs';

			this.animals = [
				'sloth',
				'bear',
				'monkey'
			];
			if (!this.animals.each) {
				this.animals.each = function (func) {
					for (var i = 0; i < this.length; i++) {
						func(this[i]);
					}
				};
			}
			this.squareBrackets = '<ul><% this.animals.each(function(animal){%>' + '<li><%= animal %></li>' + '<%});%></ul>';
			this.squareBracketsNoThis = '<ul><% animals.each(function(animal){%>' + '<li><%= animal %></li>' + '<%});%></ul>';
			this.angleBracketsNoThis = '<ul><% animals.each(function(animal){%>' + '<li><%= animal %></li>' + '<%});%></ul>';
		}
	});
	var getAttr = function (el, attrName) {
		return attrName === 'class' ? el.className : el.getAttribute(attrName);
	};
	test('render with left bracket', function () {
		var compiled = new can.EJS({
			text: this.squareBrackets,
			type: '['
		})
			.render({
				animals: this.animals
			});
		equal(compiled, '<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>', 'renders with bracket');
	});
	test('render with with', function () {
		var compiled = new can.EJS({
			text: this.squareBracketsNoThis,
			type: '['
		})
			.render({
				animals: this.animals
			});
		equal(compiled, '<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>', 'renders bracket with no this');
	});
	test('default carrot', function () {
		var compiled = new can.EJS({
			text: this.angleBracketsNoThis
		})
			.render({
				animals: this.animals
			});
		equal(compiled, '<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>');
	});
	test('render with double angle', function () {
		var text = '<%% replace_me %>' + '<ul><% animals.each(function(animal){%>' + '<li><%= animal %></li>' + '<%});%></ul>';
		var compiled = new can.EJS({
			text: text
		})
			.render({
				animals: this.animals
			});
		equal(compiled, '<% replace_me %><ul><li>sloth</li><li>bear</li><li>monkey</li></ul>', 'works');
	});
	test('comments', function () {
		var text = '<%# replace_me %>' + '<ul><% animals.each(function(animal){%>' + '<li><%= animal %></li>' + '<%});%></ul>';
		var compiled = new can.EJS({
			text: text
		})
			.render({
				animals: this.animals
			});
		equal(compiled, '<ul><li>sloth</li><li>bear</li><li>monkey</li></ul>');
	});
	test('multi line', function () {
		var text = 'a \n b \n c',
			result = new can.EJS({
				text: text
			})
				.render({});
		equal(result, text);
	});
	test('multi line elements', function () {
		var text = '<img\n class="<%=myClass%>" />',
			result = new can.EJS({
				text: text
			})
				.render({
					myClass: 'a'
				});
		ok(result.indexOf('<img\n class="a"') !== -1, 'Multi-line elements render correctly.');
		// clear hookups b/c we are using .render;
		can.view.hookups = {};
	});
	test('escapedContent', function () {
		var text = '<span><%= tags %></span><label>&amp;</label><strong><%= number %></strong><input value=\'<%= quotes %>\'/>';
		var compiled = new can.EJS({
			text: text
		})
			.render({
				tags: 'foo < bar < car > zar > poo',
				quotes: 'I use \'quote\' fingers "a lot"',
				number: 123
			});
		var div = document.createElement('div');
		div.innerHTML = compiled;
		equal(div.getElementsByTagName('span')[0].firstChild.nodeValue, 'foo < bar < car > zar > poo');
		equal(div.getElementsByTagName('strong')[0].firstChild.nodeValue, 123);
		equal(div.getElementsByTagName('input')[0].value, 'I use \'quote\' fingers "a lot"');
		equal(div.getElementsByTagName('label')[0].innerHTML, '&amp;');
		// clear hookups b/c we are using .render;
		can.view.hookups = {};
	});
	test('unescapedContent', function () {
		var text = '<span><%== tags %></span><div><%= tags %></div><input value=\'<%== quotes %>\'/>';
		var compiled = new can.EJS({
			text: text
		})
			.render({
				tags: '<strong>foo</strong><strong>bar</strong>',
				quotes: 'I use \'quote\' fingers "a lot"'
			});
		var div = document.createElement('div');
		div.innerHTML = compiled;
		equal(div.getElementsByTagName('span')[0].firstChild.nodeType, 1);
		equal(div.getElementsByTagName('div')[0].firstChild.nodeValue.toLowerCase(), '<strong>foo</strong><strong>bar</strong>');
		equal(div.getElementsByTagName('span')[0].innerHTML.toLowerCase(), '<strong>foo</strong><strong>bar</strong>');
		equal(div.getElementsByTagName('input')[0].value, 'I use \'quote\' fingers "a lot"', 'escapped no matter what');
		// clear hookups b/c we are using .render;
		can.view.hookups = {};
	});
	test('returning blocks', function () {
		var somethingHelper = function (cb) {
			return cb([
				1,
				2,
				3,
				4
			]);
		};
		var res = can.view.render(can.test.path('view/ejs/test/test_template.ejs'), {
			something: somethingHelper,
			items: [
				'a',
				'b'
			]
		});
		// make sure expected values are in res
		ok(/\s4\s/.test(res), 'first block called');
		equal(res.match(/ItemsLength4/g)
			.length, 4, 'innerBlock and each');
	});
	test('easy hookup', function () {
		var div = document.createElement('div');
		div.appendChild(can.view(can.test.path('view/ejs/test/easyhookup.ejs'), {
			text: 'yes'
		}));
		ok(div.getElementsByTagName('div')[0].className.indexOf('yes') !== -1, 'has yes');
	});
	test('multiple function hookups in a tag', function () {
		var text = '<span <%= (el)-> can.data(can.$(el),\'foo\',\'bar\') %>' + ' <%= (el)-> can.data(can.$(el),\'baz\',\'qux\') %>>lorem ipsum</span>',
			compiled = new can.EJS({
				text: text
			})
				.render(),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var span = div.getElementsByTagName('span')[0];
		equal(can.data(can.$(span), 'foo'), 'bar', 'first hookup');
		equal(can.data(can.$(span), 'baz'), 'qux', 'second hookup');
	});
	test('helpers', function () {
		can.EJS.Helpers.prototype.simpleHelper = function () {
			return 'Simple';
		};
		can.EJS.Helpers.prototype.elementHelper = function () {
			return function (el) {
				el.innerHTML = 'Simple';
			};
		};
		var text = '<div><%= simpleHelper() %></div>';
		var compiled = new can.EJS({
			text: text
		})
			.render();
		equal(compiled, '<div>Simple</div>');
		text = '<div id="hookup" <%= elementHelper() %>></div>';
		compiled = new can.EJS({
			text: text
		})
			.render();
		can.append(can.$('#qunit-fixture'), can.view.frag(compiled));
		equal(can.$('#hookup')[0].innerHTML, 'Simple');
	});
	test('list helper', function () {
		var text = '<% list(todos, function(todo){ %><div><%= todo.name %></div><% }) %>';
		var todos = new can.List([{
			id: 1,
			name: 'Dishes'
		}]),
			compiled = new can.EJS({
				text: text
			})
				.render({
					todos: todos
				}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.getElementsByTagName('div')
			.length, 1, '1 item in list');
		todos.push({
			id: 2,
			name: 'Laundry'
		});
		equal(div.getElementsByTagName('div')
			.length, 2, '2 items in list');
		todos.splice(0, 2);
		equal(div.getElementsByTagName('div')
			.length, 0, '0 items in list');
		todos.push({
			id: 4,
			name: 'Pick up sticks'
		});
		equal(div.getElementsByTagName('div')
			.length, 1, '1 item in list again');
	});
	test('attribute single unescaped, html single unescaped', function () {
		var text = '<div id=\'me\' class=\'<%== task.attr(\'completed\') ? \'complete\' : \'\'%>\'><%== task.attr(\'name\') %></div>';
		var task = new can.Map({
			name: 'dishes'
		});
		var compiled = new can.EJS({
			text: text
		})
			.render({
				task: task
			});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.getElementsByTagName('div')[0].innerHTML, 'dishes', 'html correctly dishes');
		equal(div.getElementsByTagName('div')[0].className, '', 'class empty');
		task.attr('name', 'lawn');
		equal(div.getElementsByTagName('div')[0].innerHTML, 'lawn', 'html correctly lawn');
		equal(div.getElementsByTagName('div')[0].className, '', 'class empty');
		task.attr('completed', true);
		equal(div.getElementsByTagName('div')[0].className, 'complete', 'class changed to complete');
	});
	test('event binding / triggering on things other than options', 1, function () {
		var frag = can.buildFragment('<ul><li>a</li></ul>', [document]);
		var qta = document.getElementById('qunit-fixture');
		qta.innerHTML = '';
		qta.appendChild(frag);
		// destroyed events should not bubble
		can.bind.call(qta.getElementsByTagName('li')[0], 'foo', function (event) {
			ok(true, 'li called :)');
		});
		can.bind.call(qta.getElementsByTagName('ul')[0], 'foo', function (event) {
			ok(false, 'ul called :(');
		});
		can.trigger(qta.getElementsByTagName('li')[0], 'foo', {}, false);
		qta.removeChild(qta.firstChild);
	});
	test('select live binding', function () {
		var text = '<select><% todos.each(function(todo){ %><option><%= todo.name %></option><% }) %></select>',
			Todos = new can.List([{
				id: 1,
				name: 'Dishes'
			}]),
			compiled = new can.EJS({
				text: text
			})
				.render({
					todos: Todos
				}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.getElementsByTagName('option')
			.length, 1, '1 item in list');
		Todos.push({
			id: 2,
			name: 'Laundry'
		});
		equal(div.getElementsByTagName('option')
			.length, 2, '2 items in list');
		Todos.splice(0, 2);
		equal(div.getElementsByTagName('option')
			.length, 0, '0 items in list');
	});
	test('block live binding', function () {
		var text = '<div><% if( obs.attr(\'sex\') == \'male\' ){ %>' + '<span>Mr.</span>' + '<% } else { %>' + '<label>Ms.</label>' + '<% } %>' + '</div>';
		var obs = new can.Map({
			sex: 'male'
		});
		var compiled = new can.EJS({
			text: text
		})
			.render({
				obs: obs
			});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		// We have to test using nodeName and innerHTML (and not outerHTML) because IE 8 and under treats
		// user-defined properties on nodes as attributes.
		equal(div.getElementsByTagName('div')[0].firstChild.nodeName.toUpperCase(), 'SPAN', 'initial span tag');
		equal(div.getElementsByTagName('div')[0].firstChild.innerHTML, 'Mr.', 'initial span content');
		obs.attr('sex', 'female');
		equal(div.getElementsByTagName('div')[0].firstChild.nodeName.toUpperCase(), 'LABEL', 'updated label tag');
		equal(div.getElementsByTagName('div')[0].firstChild.innerHTML, 'Ms.', 'updated label content');
	});
	test('hookups in tables', function () {
		var text = '<table><tbody><% if( obs.attr(\'sex\') == \'male\' ){ %>' + '<tr><td>Mr.</td></tr>' + '<% } else { %>' + '<tr><td>Ms.</td></tr>' + '<% } %>' + '</tbody></table>';
		var obs = new can.Map({
			sex: 'male'
		});
		var compiled = new can.EJS({
			text: text
		})
			.render({
				obs: obs
			});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		// We have to test using nodeName and innerHTML (and not outerHTML) because IE 8 and under treats
		// user-defined properties on nodes as attributes.
		equal(div.getElementsByTagName('tbody')[0].firstChild.firstChild.nodeName, 'TD', 'initial tag');
		equal(div.getElementsByTagName('tbody')[0].firstChild.firstChild.innerHTML.replace(/(\r|\n)+/g, ''), 'Mr.', 'initial content');
		obs.attr('sex', 'female');
		equal(div.getElementsByTagName('tbody')[0].firstChild.firstChild.nodeName, 'TD', 'updated tag');
		equal(div.getElementsByTagName('tbody')[0].firstChild.firstChild.innerHTML.replace(/(\r|\n)+/g, ''), 'Ms.', 'updated content');
	});
	//Issue 233
	test('multiple tbodies in table hookup', function () {
		var text = '<table>' + '<% can.each(people, function(person){ %>' + '<tbody><tr><td><%= person.name %></td></tr></tbody>' + '<% }) %>' + '</table>',
			people = new can.List([{
				name: 'Steve'
			}, {
				name: 'Doug'
			}]),
			compiled = new can.EJS({
				text: text
			})
				.render({
					people: people
				}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.getElementsByTagName('tbody')
			.length, 2, 'two tbodies');
	});
	test('multiple hookups in a single attribute', function () {
		var text = '<div class=\'<%= obs.attr("foo") %>a<%= obs.attr("bar") %>b<%= obs.attr("baz") %>\'></div>',
			obs = new can.Map({
				foo: '1',
				bar: '2',
				baz: '3'
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					obs: obs
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var innerDiv = div.childNodes[0];
		equal(getAttr(innerDiv, 'class'), '1a2b3', 'initial render');
		obs.attr('bar', '4');
		equal(getAttr(innerDiv, 'class'), '1a4b3', 'initial render');
		obs.attr('bar', '5');
		equal(getAttr(innerDiv, 'class'), '1a5b3', 'initial render');
	});
	test('adding and removing multiple html content within a single element', function () {
		var text = '<div><%== obs.attr("a") %><%== obs.attr("b") %><%== obs.attr("c") %></div>',
			obs = new can.Map({
				a: 'a',
				b: 'b',
				c: 'c'
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					obs: obs
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.firstChild.nodeName.toUpperCase(), 'DIV', 'initial render node name');
		equal(div.firstChild.innerHTML, 'abc', 'initial render text');
		obs.attr({
			a: '',
			b: '',
			c: ''
		});
		equal(div.firstChild.nodeName.toUpperCase(), 'DIV', 'updated render node name');
		equal(div.firstChild.innerHTML, '', 'updated render text');
		obs.attr({
			c: 'c'
		});
		equal(div.firstChild.nodeName.toUpperCase(), 'DIV', 'updated render node name');
		equal(div.firstChild.innerHTML, 'c', 'updated render text');
	});
	test('live binding and removeAttr', function () {
		var text = '<% if(obs.attr("show")) { %>' +
				'<p <%== obs.attr("attributes") %> class="<%= obs.attr("className")%>"><span><%= obs.attr("message") %></span></p>' +
			'<% } %>',
			obs = new can.Map({
				show: true,
				className: 'myMessage',
				attributes: 'some="myText"',
				message: 'Live long and prosper'
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					obs: obs
				}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		
		var p = div.getElementsByTagName('p')[0],
			span = p.getElementsByTagName('span')[0];
		
		equal(p.getAttribute('some'), 'myText', 'initial render attr');
		equal(getAttr(p, 'class'), 'myMessage', 'initial render class');
		equal(span.innerHTML, 'Live long and prosper', 'initial render innerHTML');
		obs.removeAttr('className');
		equal(getAttr(p, 'class'), '', 'class is undefined');
		obs.attr('className', 'newClass');
		equal(getAttr(p, 'class'), 'newClass', 'class updated');
		obs.removeAttr('attributes');
		equal(p.getAttribute('some'), null, 'attribute is undefined');
		obs.attr('attributes', 'some="newText"');
		equal(p.getAttribute('some'), 'newText', 'attribute updated');
		obs.removeAttr('message');
		
		equal(span.innerHTML, '', 'text node value is empty');
		
		obs.attr('message', 'Warp drive, Mr. Sulu');
		equal(span.innerHTML, 'Warp drive, Mr. Sulu', 'text node updated');
		obs.removeAttr('show');
		equal(div.innerHTML, '', 'value in block statement is undefined');
		obs.attr('show', true);
		p = div.getElementsByTagName('p')[0];
		span = p.getElementsByTagName('span')[0];
		equal(p.getAttribute('some'), 'newText', 'value in block statement updated attr');
		equal(getAttr(p, 'class'), 'newClass', 'value in block statement updated class');
		equal(span.innerHTML, 'Warp drive, Mr. Sulu', 'value in block statement updated innerHTML');
	});
	test('hookup within a tag', function () {
		var text = '<div <%== obs.attr("foo") %> ' + '<%== obs.attr("baz") %>>lorem ipsum</div>',
			obs = new can.Map({
				foo: 'class="a"',
				baz: 'some=\'property\''
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					obs: obs
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var anchor = div.getElementsByTagName('div')[0];
		equal(getAttr(anchor, 'class'), 'a');
		equal(anchor.getAttribute('some'), 'property');
		obs.attr('foo', 'class="b"');
		equal(getAttr(anchor, 'class'), 'b');
		equal(anchor.getAttribute('some'), 'property');
		obs.attr('baz', 'some=\'new property\'');
		equal(getAttr(anchor, 'class'), 'b');
		equal(anchor.getAttribute('some'), 'new property');
		obs.attr('foo', 'class=""');
		obs.attr('baz', '');
		equal(getAttr(anchor, 'class'), '', 'anchor class blank');
		equal(anchor.getAttribute('some'), undefined, 'attribute "some" is undefined');
	});
	test('single escaped tag, removeAttr', function () {
		var text = '<div <%= obs.attr("foo") %>>lorem ipsum</div>',
			obs = new can.Map({
				foo: 'data-bar="john doe\'s bar"'
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					obs: obs
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var anchor = div.getElementsByTagName('div')[0];
		equal(anchor.getAttribute('data-bar'), 'john doe\'s bar');
		obs.removeAttr('foo');
		equal(anchor.getAttribute('data-bar'), null);
		obs.attr('foo', 'data-bar="baz"');
		equal(anchor.getAttribute('data-bar'), 'baz');
	});
	test('html comments', function () {
		var text = '<!-- bind to changes in the todo list --> <div><%= obs.attr("foo") %></div>',
			obs = new can.Map({
				foo: 'foo'
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					obs: obs
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.getElementsByTagName('div')[0].innerHTML, 'foo', 'Element as expected');
	});
	test('hookup and live binding', function () {
		var text = '<div class=\'<%= task.attr(\'completed\') ? \'complete\' : \'\' %>\' <%= (el)-> can.data(can.$(el),\'task\',task) %>>' + '<%== task.attr(\'name\') %>' + '</div>',
			task = new can.Map({
				completed: false,
				className: 'someTask',
				name: 'My Name'
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					task: task
				}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var child = div.getElementsByTagName('div')[0];
		ok(child.className.indexOf('complete') === -1, 'is incomplete');
		ok( !! can.data(can.$(child), 'task'), 'has data');
		equal(child.innerHTML, 'My Name', 'has name');
		task.attr({
			completed: true,
			name: 'New Name'
		});
		ok(child.className.indexOf('complete') !== -1, 'is complete');
		equal(child.innerHTML, 'New Name', 'has new name');
	});
	/*
	 test('multiple curly braces in a block', function() {
	 var text =  '<% if(!obs.attr("items").length) { %>' +
	 '<li>No items</li>' +
	 '<% } else { each(obs.items, function(item) { %>' +
	 '<li><%= item.attr("name") %></li>' +
	 '<% }) }%>',

	 obs = new can.Map({
	 items: []
	 }),

	 compiled = new can.EJS({ text: text }).render({ obs: obs });

	 var ul = document.createElement('ul');
	 ul.appendChild(can.view.frag(compiled));

	 equal(ul.innerHTML, '<li>No items</li>', 'initial observable state');

	 obs.attr('items', [{ name: 'foo' }]);
	 equal(u.innerHTML, '<li>foo</li>', 'updated observable');
	 });
	 */
	test('unescape bindings change', function () {
		var l = new can.List([{
			complete: true
		}, {
			complete: false
		}, {
			complete: true
		}]);
		var completed = function () {
			l.attr('length');
			var num = 0;
			l.each(function (item) {
				if (item.attr('complete')) {
					num++;
				}
			});
			return num;
		};
		var text = '<div><%== completed() %></div>',
			compiled = new can.EJS({
				text: text
			})
				.render({
					completed: completed
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var child = div.getElementsByTagName('div')[0];
		equal(child.innerHTML, '2', 'at first there are 2 true bindings');
		var item = new can.Map({
			complete: true,
			id: 'THIS ONE'
		});
		l.push(item);
		equal(child.innerHTML, '3', 'now there are 3 complete');
		item.attr('complete', false);
		equal(child.innerHTML, '2', 'now there are 2 complete');
		l.pop();
		item.attr('complete', true);
		equal(child.innerHTML, '2', 'there are still 2 complete');
	});
	test('escape bindings change', function () {
		var l = new can.List([{
			complete: true
		}, {
			complete: false
		}, {
			complete: true
		}]);
		var completed = function () {
			l.attr('length');
			var num = 0;
			l.each(function (item) {
				if (item.attr('complete')) {
					num++;
				}
			});
			return num;
		};
		var text = '<div><%= completed() %></div>',
			compiled = new can.EJS({
				text: text
			})
				.render({
					completed: completed
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var child = div.getElementsByTagName('div')[0];
		equal(child.innerHTML, '2', 'at first there are 2 true bindings');
		var item = new can.Map({
			complete: true
		});
		l.push(item);
		equal(child.innerHTML, '3', 'now there are 3 complete');
		item.attr('complete', false);
		equal(child.innerHTML, '2', 'now there are 2 complete');
	});
	test('tag bindings change', function () {
		var l = new can.List([{
			complete: true
		}, {
			complete: false
		}, {
			complete: true
		}]);
		var completed = function () {
			l.attr('length');
			var num = 0;
			l.each(function (item) {
				if (item.attr('complete')) {
					num++;
				}
			});
			return 'items=\'' + num + '\'';
		};
		var text = '<div <%= completed() %>></div>',
			compiled = new can.EJS({
				text: text
			})
				.render({
					completed: completed
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var child = div.getElementsByTagName('div')[0];
		equal(child.getAttribute('items'), '2', 'at first there are 2 true bindings');
		var item = new can.Map({
			complete: true
		});
		l.push(item);
		equal(child.getAttribute('items'), '3', 'now there are 3 complete');
		item.attr('complete', false);
		equal(child.getAttribute('items'), '2', 'now there are 2 complete');
	});
	test('attribute value bindings change', function () {
		var l = new can.List([{
			complete: true
		}, {
			complete: false
		}, {
			complete: true
		}]);
		var completed = function () {
			l.attr('length');
			var num = 0;
			l.each(function (item) {
				if (item.attr('complete')) {
					num++;
				}
			});
			return num;
		};
		var text = '<div items="<%= completed() %>"></div>',
			compiled = new can.EJS({
				text: text
			})
				.render({
					completed: completed
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var child = div.getElementsByTagName('div')[0];
		equal(child.getAttribute('items'), '2', 'at first there are 2 true bindings');
		var item = new can.Map({
			complete: true
		});
		l.push(item);
		equal(child.getAttribute('items'), '3', 'now there are 3 complete');
		item.attr('complete', false);
		equal(child.getAttribute('items'), '2', 'now there are 2 complete');
	});
	test('in tag toggling', function () {
		var text = '<div <%== obs.attr(\'val\') %>></div>';
		var obs = new can.Map({
			val: 'foo="bar"'
		});
		var compiled = new can.EJS({
			text: text
		})
			.render({
				obs: obs
			});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		obs.attr('val', 'bar=\'foo\'');
		obs.attr('val', 'foo="bar"');
		var d2 = div.getElementsByTagName('div')[0];
		// toUpperCase added to normalize cases for IE8
		equal(d2.getAttribute('foo'), 'bar', 'bar set');
		equal(d2.getAttribute('bar'), null, 'bar set');
	});
	test('parent is right with bock', function () {
		var text = '<ul><% if(!obs.attr("items").length) { %>' + '<li>No items</li>' + '<% } else { %> <%== obs.attr("content") %>' + '<% } %></ul>',
			obs = new can.Map({
				content: '<li>Hello</li>',
				items: [{
					name: 'Justin'
				}]
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					obs: obs
				});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var ul = div.getElementsByTagName('ul')[0];
		var li = div.getElementsByTagName('li')[0];
		ok(ul, 'we have a ul');
		ok(li, 'we have a li');
	});
	test('property name only attributes', function () {
		var text = '<input type=\'checkbox\' <%== obs.attr(\'val\') ? \'checked\' : \'\' %>/>';
		var obs = new can.Map({
			val: true
		});
		var compiled = new can.EJS({
			text: text
		})
			.render({
				obs: obs
			});
		var div = document.getElementById('qunit-fixture');
		div.appendChild(can.view.frag(compiled));
		var input = div.getElementsByTagName('input')[0];
		can.trigger(input, 'click');
		obs.attr('val', false);
		ok(!input.checked, 'not checked');
		obs.attr('val', true);
		ok(input.checked, 'checked');
		div.removeChild(input);
	});
	test('nested properties', function () {
		var text = '<div><%= obs.attr(\'name.first\')%></div>';
		var obs = new can.Map({
			name: {
				first: 'Justin'
			}
		});
		var compiled = new can.EJS({
			text: text
		})
			.render({
				obs: obs
			});
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		div = div.getElementsByTagName('div')[0];
		equal(div.innerHTML, 'Justin');
		obs.attr('name.first', 'Brian');
		equal(div.innerHTML, 'Brian');
	});
	test('tags without chidren or ending with /> do not change the state', function () {
		var text = '<table><tr><td></td><%== obs.attr(\'content\') %></tr></div>';
		var obs = new can.Map({
			content: '<td>Justin</td>'
		});
		var compiled = new can.EJS({
			text: text
		})
			.render({
				obs: obs
			});
		var div = document.createElement('div');
		var html = can.view.frag(compiled);
		div.appendChild(html);
		equal(div.getElementsByTagName('span')
			.length, 0, 'there are no spans');
		equal(div.getElementsByTagName('td')
			.length, 2, 'there are 2 td');
	});
	test('nested live bindings', function () {
		expect(0);
		var items = new can.List([{
			title: 0,
			is_done: false,
			id: 0
		}]);
		var div = document.createElement('div');
		div.appendChild(can.view(can.test.path('view/ejs/test/nested_live_bindings.ejs'), {
			items: items
		}));
		items.push({
			title: 1,
			is_done: false,
			id: 1
		});
		// this will throw an error unless EJS protects against
		// nested objects
		items[0].attr('is_done', true);
	});
	// Similar to the nested live bindings test, this makes sure templates with control blocks
	// will eventually remove themselves if at least one change happens
	// before things are removed.
	// It is currently commented out because
	//
	/*test("memory safe without parentElement of blocks", function(){

	 })*/
	test('trailing text', function () {
		can.view.ejs('count', 'There are <%= this.attr(\'length\') %> todos');
		var div = document.createElement('div');
		div.appendChild(can.view('count', new can.List([{}, {}])));
		ok(/There are 2 todos/.test(div.innerHTML), 'got all text');
	});
	test('recursive views', function () {
		var data = new can.List([{
			label: 'branch1',
			children: [{
				id: 2,
				label: 'branch2'
			}]
		}]);
		var div = document.createElement('div');
		div.appendChild(can.view(can.test.path('view/ejs/test/recursive.ejs'), {
			items: data
		}));
		ok(/class="leaf"|class=leaf/.test(div.innerHTML), 'we have a leaf');
	});
	test('indirectly recursive views', function () {
		var unordered = new can.List([{
			ol: [{
				ul: [{
					ol: [
						1,
						2,
						3
					]
				}]
			}]
		}]);
		can.view.cache = false;
		var div = document.createElement('div');
		div.appendChild(can.view(can.test.path('view/ejs/test/indirect1.ejs'), {
			unordered: unordered
		}));
		document.getElementById('qunit-fixture')
			.appendChild(div);
		var el = can.$('#qunit-fixture ul > li > ol > li > ul > li > ol > li')[0];
		ok( !! el && can.trim(el.innerHTML) === '1', 'Uncached indirectly recursive EJS working.');
		can.view.cache = true;
		div.appendChild(can.view(can.test.path('view/ejs/test/indirect1.ejs'), {
			unordered: unordered
		}));
		el = can.$('#qunit-fixture ul + ul > li > ol > li > ul > li > ol > li')[0];
		ok( !! el && can.trim(el.innerHTML) === '1', 'Cached indirectly recursive EJS working.');
		document.getElementById('qunit-fixture')
			.removeChild(div);
	});
	test('recursive views of previously stolen files shouldn\'t fail', function () {
		// Using preload to bypass steal dependency (necessary for "grunt test")
		can.view.preloadStringRenderer('view_ejs_test_indirect1_ejs', can.EJS({
			text: '<ul>' + '<% unordered.each(function(item) { %>' + '<li>' + '<% if(item.ol) { %>' + '<%== can.view.render(can.test.path(\'view/ejs/test/indirect2.ejs\'), { ordered: item.ol }) %>' + '<% } else { %>' + '<%= item.toString() %>' + '<% } %>' + '</li>' + '<% }) %>' + '</ul>'
		}));
		can.view.preloadStringRenderer('view_ejs_test_indirect2_ejs', can.EJS({
			text: '<ol>' + '<% ordered.each(function(item) { %>' + '<li>' + '<% if(item.ul) { %>' + '<%== can.view.render(can.test.path(\'view/ejs/test/indirect1.ejs\'), { unordered: item.ul }) %>' + '<% } else { %>' + '<%= item.toString() %>' + '<% } %>' + '</li>' + '<% }) %>' + '</ol>'
		}));
		var unordered = new can.Map.List([{
			ol: [{
				ul: [{
					ol: [
						1,
						2,
						3
					]
				}]
			}]
		}]);
		can.view.cache = false;
		var div = document.createElement('div');
		div.appendChild(can.view(can.test.path('view/ejs/test/indirect1.ejs'), {
			unordered: unordered
		}));
		document.getElementById('qunit-fixture')
			.appendChild(div);
		var el = can.$('#qunit-fixture ul > li > ol > li > ul > li > ol > li')[0];
		ok( !! el && can.trim(el.innerHTML) === '1', 'Uncached indirectly recursive EJS working.');
		can.view.cache = true;
		div.appendChild(can.view(can.test.path('view/ejs/test/indirect1.ejs'), {
			unordered: unordered
		}));
		el = can.$('#qunit-fixture ul + ul > li > ol > li > ul > li > ol > li')[0];
		ok( !! el && can.trim(el.innerHTML) === '1', 'Cached indirectly recursive EJS working.');
		document.getElementById('qunit-fixture')
			.removeChild(div);
	});
	test('live binding select', function () {
		var text = '<select><% items.each(function(ob) { %>' + '<option value=\'<%= ob.attr(\'id\') %>\'><%= ob.attr(\'title\') %></option>' + '<% }); %></select>',
			items = new can.List([{
				title: 'Make bugs',
				is_done: true,
				id: 0
			}, {
				title: 'Find bugs',
				is_done: false,
				id: 1
			}, {
				title: 'Fix bugs',
				is_done: false,
				id: 2
			}]),
			compiled = new can.EJS({
				text: text
			})
				.render({
					items: items
				}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.getElementsByTagName('option')
			.length, 3, '3 items in list');
		var option = div.getElementsByTagName('option')[0];
		equal(option.value, '' + items[0].id, 'value attr set');
		equal(option.textContent || option.text, items[0].title, 'content of option');
		items.push({
			id: 3,
			name: 'Go to pub'
		});
		equal(div.getElementsByTagName('option')
			.length, 4, '4 items in list');
	});
	test('live binding textarea', function () {
		can.view.ejs('textarea-test', '<textarea>Before<%= obs.attr(\'middle\') %>After</textarea>');
		var obs = new can.Map({
			middle: 'yes'
		}),
			div = document.createElement('div');
		var node = can.view('textarea-test', {
			obs: obs
		});
		div.appendChild(node);
		var textarea = div.firstChild;
		equal(textarea.value, 'BeforeyesAfter');
		obs.attr('middle', 'Middle');
		equal(textarea.value, 'BeforeMiddleAfter');
	});
	test('reset on a live bound input', function () {
		var text = '<input type=\'text\' value=\'<%= person.attr(\'name\') %>\'><button type=\'reset\'>Reset</button>',
			person = new can.Map({
				name: 'Bob'
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					person: person
				}),
			form = document.createElement('form'),
			input;
		form.appendChild(can.view.frag(compiled));
		input = form.getElementsByTagName('input')[0];
		form.reset();
		equal(input.value, 'Bob', 'value is correct');
	});
	test('A non-escaping live magic tag within a control structure and no leaks', function () {
		var nodeLists = can.view.nodeLists;
		for (var prop in nodeLists.nodeMap) {
			delete nodeLists.nodeMap[prop];
		}
		var text = '<div><% items.each(function(ob) { %>' + '<%== ob.attr(\'html\') %>' + '<% }); %></div>',
			items = new can.List([{
				html: '<label>Hello World</label>'
			}]),
			compiled = new can.EJS({
				text: text
			})
				.render({
					items: items
				}),
			div = can.$('#qunit-fixture')[0];
		div.innerHTML = '';
		can.append(can.$('#qunit-fixture'), can.view.frag(compiled));
		ok(div.getElementsByTagName('label')[0], 'label exists');
		items[0].attr('html', '<p>hi</p>');
		equal(div.getElementsByTagName('label')
			.length, 0, 'label is removed');
		equal(div.getElementsByTagName('p')
			.length, 1, 'label is replaced by p');
		items.push({
			html: '<p>hola</p>'
		});
		equal(div.getElementsByTagName('p')
			.length, 2, 'label has 2 paragraphs');
		can.remove(can.$(div.firstChild));
		deepEqual(nodeLists.nodeMap, {});
	});
	test('attribute unquoting', function () {
		var text = '<input type="radio" ' + '<%== facet.single ? \'name="facet-\' + facet.attr("id") + \'"\' : "" %> ' + 'value="<%= facet.single ? "facet-" + facet.attr("id") : "" %>" />',
			facet = new can.Map({
				id: 1,
				single: true
			}),
			compiled = new can.EJS({
				text: text
			})
				.render({
					facet: facet
				}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.children[0].name, 'facet-1');
		equal(div.children[0].value, 'facet-1');
	});
	test('empty element hooks work correctly', function () {
		var text = '<div <%= function(e){ e.innerHTML = "1 Will show"; } %>></div>' + '<div <%= function(e){ e.innerHTML = "2 Will not show"; } %>></div>' + '3 Will not show';
		var compiled = new can.EJS({
			text: text
		})
			.render(),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.childNodes.length, 3, 'all three elements present');
	});
	test('live binding with parent dependent tags but without parent tag present in template', function () {
		var text = [
			'<tbody>',
			'<% if( person.attr("first") ){ %>',
			'<tr><td><%= person.first %></td></tr>',
			'<% }%>',
			'<% if( person.attr("last") ){ %>',
			'<tr><td><%= person.last %></td></tr>',
			'<% } %>',
			'</tbody>'
		];
		var person = new can.Map({
			first: 'Austin',
			last: 'McDaniel'
		});
		var compiled = new can.EJS({
			text: text.join('\n')
		})
			.render({
				person: person
			});
		var table = document.createElement('table');
		table.appendChild(can.view.frag(compiled));
		equal(table.getElementsByTagName('tr')[0].firstChild.nodeName.toUpperCase(), 'TD');
		equal(table.getElementsByTagName('tr')[0].firstChild.innerHTML, 'Austin');
		equal(table.getElementsByTagName('tr')[1].firstChild.nodeName.toUpperCase(), 'TD');
		equal(table.getElementsByTagName('tr')[1].firstChild.innerHTML, 'McDaniel');
		person.removeAttr('first');
		equal(table.getElementsByTagName('tr')[0].firstChild.nodeName.toUpperCase(), 'TD');
		equal(table.getElementsByTagName('tr')[0].firstChild.innerHTML, 'McDaniel');
		person.removeAttr('last');
		equal(table.getElementsByTagName('tr')
			.length, 0);
		person.attr('first', 'Justin');
		equal(table.getElementsByTagName('tr')[0].firstChild.nodeName.toUpperCase(), 'TD');
		equal(table.getElementsByTagName('tr')[0].firstChild.innerHTML, 'Justin');
	});
	test('spaces between attribute name and value', function () {
		var text = '<input type="text" value = "<%= test %>" />',
			compiled = new can.EJS({
				text: text
			})
				.render({
					test: 'testing'
				}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var input = div.getElementsByTagName('input')[0];
		equal(input.value, 'testing');
		equal(input.type, 'text');
	});
	test('live binding with computes', function () {
		var text = '<span><%= compute() %></span>',
			compute = can.compute(5),
			compiled = new can.EJS({
				text: text
			})
				.render({
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
	});
	test('testing for clean tables', function () {
		var games = new can.List();
		games.push({
			name: 'The Legend of Zelda',
			rating: 10
		});
		games.push({
			name: 'The Adventures of Link',
			rating: 9
		});
		games.push({
			name: 'Dragon Warrior',
			rating: 9
		});
		games.push({
			name: 'A Dude Named Daffl',
			rating: 8.5
		});
		var res = can.view.render(can.test.path('view/ejs/test/table_test.ejs'), {
			games: games
		}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(res));
		ok(!/@@!!@@/.test(div.innerHTML), 'no placeholders');
	});
	test('inserting live-binding partials assume the correct parent tag', function () {
		can.view.ejs('rowView', '<% can.each(columns, function(col) { %>' + '<th><%= col.attr("name") %></th>' + '<% }) %>');
		can.view.ejs('tableView', '<table><tbody><tr>' + '<%== can.view.render("rowView", this) %>' + '</tr></tbody></table>');
		var data = {
			columns: new can.List([{
				name: 'Test 1'
			}, {
				name: 'Test 2'
			}])
		};
		var div = document.createElement('div');
		var dom = can.view('tableView', data);
		div.appendChild(dom);
		var ths = div.getElementsByTagName('th');
		equal(ths.length, 2, 'Got two table headings');
		equal(ths[0].innerHTML, 'Test 1', 'First column heading correct');
		equal(ths[1].innerHTML, 'Test 2', 'Second column heading correct');
		equal(can.view.render('tableView', data)
			.indexOf('<table><tbody><tr><td data-view-id='), 0, 'Rendered output starts' + 'as expected');
		// clear hookups b/c we are using .render;
		can.view.hookups = {};
	});
	// http://forum.javascriptmvc.com/topic/live-binding-on-mustache-template-does-not-seem-to-be-working-with-nested-properties
	test('Observe with array attributes', function () {
		can.view.ejs('observe-array', '<ul><% can.each(todos, function(todo, i) { %><li><%= todos.attr(""+i) %></li><% }) %></ul><div><%= this.attr("message") %></div>');
		var div = document.createElement('div');
		var data = new can.Map({
			todos: [
				'Line #1',
				'Line #2',
				'Line #3'
			],
			message: 'Hello',
			count: 2
		});
		div.appendChild(can.view('observe-array', data));
		equal(div.getElementsByTagName('li')[1].innerHTML, 'Line #2', 'Check initial array');
		equal(div.getElementsByTagName('div')[0].innerHTML, 'Hello', 'Check initial message');
		data.attr('todos.1', 'Line #2 changed');
		data.attr('message', 'Hello again');
		equal(div.getElementsByTagName('li')[1].innerHTML, 'Line #2 changed', 'Check updated array');
		equal(div.getElementsByTagName('div')[0].innerHTML, 'Hello again', 'Check updated message');
	});
	test('hookup this correctly', function () {
		var obj = {
			from: 'cows'
		};
		var html = '<span <%== (el) -> can.data(can.$(el), \'foo\', this.from) %>>tea</span>';
		var compiled = new can.EJS({
			text: html
		})
			.render(obj);
		var div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		var span = div.getElementsByTagName('span')[0];
		equal(can.data(can.$(span), 'foo'), obj.from, 'object matches');
	});
	//Issue 271
	test('live binding with html comment', function () {
		var text = '<table><tr><th>Todo</th></tr><!-- do not bother with me -->' + '<% todos.each(function(todo){ %><tr><td><%= todo.name %></td></tr><% }) %></table>',
			Todos = new can.List([{
				id: 1,
				name: 'Dishes'
			}]),
			compiled = new can.EJS({
				text: text
			})
				.render({
					todos: Todos
				}),
			div = document.createElement('div');
		div.appendChild(can.view.frag(compiled));
		equal(div.getElementsByTagName('table')[0].getElementsByTagName('td')
			.length, 1, '1 item in list');
		Todos.push({
			id: 2,
			name: 'Laundry'
		});
		equal(div.getElementsByTagName('table')[0].getElementsByTagName('td')
			.length, 2, '2 items in list');
		Todos.splice(0, 2);
		equal(div.getElementsByTagName('table')[0].getElementsByTagName('td')
			.length, 0, '0 items in list');
	});
	test('HTML comment with element callback', function () {
		var text = [
			'<ul>',
			'<% todos.each(function(todo) { %>',
			'<li<%= (el) -> can.data(can.$(el),\'todo\',todo) %>>',
			'<!-- html comment #1 -->',
			'<%= todo.name %>',
			'<!-- html comment #2 -->',
			'</li>',
			'<% }) %>',
			'</ul>'
		],
			Todos = new can.List([{
				id: 1,
				name: 'Dishes'
			}]),
			compiled = new can.EJS({
				text: text.join('\n')
			})
				.render({
					todos: Todos
				}),
			div = document.createElement('div'),
			li, comments;
		comments = function (el) {
			var count = 0;
			for (var i = 0; i < el.childNodes.length; i++) {
				if (el.childNodes[i].nodeType === 8) {
					++count;
				}
			}
			return count;
		};
		div.appendChild(can.view.frag(compiled));
		li = div.getElementsByTagName('ul')[0].getElementsByTagName('li');
		equal(li.length, 1, '1 item in list');
		equal(comments(li[0]), 2, '2 comments in item #1');
		Todos.push({
			id: 2,
			name: 'Laundry'
		});
		equal(li.length, 2, '2 items in list');
		equal(comments(li[0]), 2, '2 comments in item #1');
		equal(comments(li[1]), 2, '2 comments in item #2');
		Todos.splice(0, 2);
		equal(li.length, 0, '0 items in list');
	});
	// https://github.com/bitovi/canjs/issues/153
	test('Interpolated values when iterating through an Observe.List should still render when not surrounded by a DOM node', function () {
		can.view.ejs('issue-153-no-dom', '<% can.each(todos, function(todo) { %><span><%= todo.attr("name") %></span><% }) %>');
		can.view.ejs('issue-153-dom', '<% can.each(todos, function(todo) { %><%= todo.attr("name") %><% }) %>');
		var todos = [
			new can.Map({
				id: 1,
				name: 'Dishes'
			}),
			new can.Map({
				id: 2,
				name: 'Forks'
			})
		],
			data = {
				todos: new can.List(todos)
			}, arr = {
				todos: todos
			}, div = document.createElement('div');
		div.appendChild(can.view('issue-153-no-dom', arr));
		equal(div.getElementsByTagName('span')[0].innerHTML, 'Dishes', 'Array item rendered with DOM container');
		equal(div.getElementsByTagName('span')[1].innerHTML, 'Forks', 'Array item rendered with DOM container');
		div = document.createElement('div');
		div.appendChild(can.view('issue-153-no-dom', data));
		equal(div.getElementsByTagName('span')[0].innerHTML, 'Dishes', 'List item rendered with DOM container');
		equal(div.getElementsByTagName('span')[1].innerHTML, 'Forks', 'List item rendered with DOM container');
		div = document.createElement('div');
		div.appendChild(can.view('issue-153-dom', arr));
		equal(div.innerHTML, 'DishesForks', 'Array item rendered without DOM container');
		div = document.createElement('div');
		div.appendChild(can.view('issue-153-dom', data));
		equal(div.innerHTML, 'DishesForks', 'List item rendered without DOM container');
		data.todos.push(new can.Map({
			id: 3,
			name: 'Knives'
		}));
		equal(div.innerHTML, 'DishesForksKnives', 'New list item rendered without DOM container');
	});
	test('correctness of data-view-id and only in tag opening', function () {
		var text = [
			'<textarea><select><% can.each(this.items, function(item) { %>',
			'<option<%= (el) -> el.data(\'item\', item) %>><%= item.title %></option>',
			'<% }) %></select></textarea>'
		],
			items = [{
				id: 1,
				title: 'One'
			}, {
				id: 2,
				title: 'Two'
			}],
			compiled = new can.EJS({
				text: text.join('')
			})
				.render({
					items: items
				}),
			expected = '^<textarea data-view-id=\'[0-9]+\'><select><option data-view-id=\'[0-9]+\'>One</option>' + '<option data-view-id=\'[0-9]+\'>Two</option></select></textarea>$';
		ok(compiled.search(expected) === 0, 'Rendered output is as expected');
		// clear hookups b/c we are using .render;
		can.view.hookups = {};
	});
	test('return blocks within element tags', function () {
		var animals = new can.List([
			'sloth',
			'bear'
		]),
			template = '<ul>' + '<%==lister(animals, function(animal){%>' + '<li><%=animal %></li>' + '<%})%>' + '</ul>';
		var renderer = can.view.ejs(template);
		var div = document.createElement('div');
		var frag = renderer({
			lister: function (items, callback) {
				return function (el) {
					equal(el.nodeName.toLowerCase(), 'li', 'got the LI it created');
				};
			},
			animals: animals
		});
		div.appendChild(frag);
	});
	test('Each does not redraw items', function () {
		var animals = new can.List([
			'sloth',
			'bear'
		]),
			template = '<div>my<b>favorite</b>animals:' + '<%==each(animals, function(animal){%>' + '<label>Animal=</label> <span><%=animal %></span>' + '<%})%>' + '!</div>';
		var renderer = can.view.ejs(template);
		var div = document.createElement('div');
		var frag = renderer({
			animals: animals
		});
		div.appendChild(frag);
		div.getElementsByTagName('label')[0].myexpando = 'EXPANDO-ED';
		equal(div.getElementsByTagName('label')
			.length, 2, 'There are 2 labels');
		animals.push('turtle');
		equal(div.getElementsByTagName('label')[0].myexpando, 'EXPANDO-ED', 'same expando');
		equal(div.getElementsByTagName('span')[2].innerHTML, 'turtle', 'turtle added');
	});
	test('Each works with no elements', function () {
		var animals = new can.List([
			'sloth',
			'bear'
		]),
			template = '<%==each(animals, function(animal){%>' + '<%=animal %> ' + '<%})%>';
		var renderer = can.view.ejs(template);
		var div = document.createElement('div');
		var frag = renderer({
			animals: animals
		});
		div.appendChild(frag);
		animals.push('turtle');
		equal(div.innerHTML, 'sloth bear turtle ', 'turtle added');
	});
	test('Each does not redraw items (normal array)', function () {
		var animals = [
			'sloth',
			'bear',
			'turtle'
		],
			template = '<div>my<b>favorite</b>animals:' + '<%each(animals, function(animal){%>' + '<label>Animal=</label> <span><%=animal %></span>' + '<%})%>' + '!</div>';
		var renderer = can.view.ejs(template);
		var div = document.createElement('div');
		var frag = renderer({
			animals: animals
		});
		div.appendChild(frag);
		div.getElementsByTagName('label')[0].myexpando = 'EXPANDO-ED';
		//animals.push("dog")
		equal(div.getElementsByTagName('label')
			.length, 3, 'There are 2 labels');
		equal(div.getElementsByTagName('label')[0].myexpando, 'EXPANDO-ED', 'same expando');
		equal(div.getElementsByTagName('label')[0].myexpando, 'EXPANDO-ED', 'same expando');
		equal(div.getElementsByTagName('span')[2].innerHTML, 'turtle', 'turtle added');
	});
	test('list works within another branch', function () {
		var animals = new can.List([]),
			template = '<div>Animals:' + '<% if( animals.attr(\'length\') ){ %>~' + '<% animals.each(function(animal){%>' + '<span><%=animal %></span>' + '<%})%>' + '<% } else { %>' + 'No animals' + '<% } %>' + '!</div>';
		var renderer = can.view.ejs(template);
		var div = document.createElement('div');
		// $("#qunit-fixture").html(div);
		var frag = renderer({
			animals: animals
		});
		div.appendChild(frag);
		equal(div.getElementsByTagName('div')[0].innerHTML, 'Animals:No animals!');
		animals.push('sloth');
		equal(div.getElementsByTagName('span')
			.length, 1, 'There is 1 sloth');
		animals.pop();
		equal(div.getElementsByTagName('div')[0].innerHTML, 'Animals:No animals!');
	});
	test('each works within another branch', function () {
		var animals = new can.List([]),
			template = '<div>Animals:' + '<% if( animals.attr(\'length\') ){ %>~' + '<%==each(animals, function(animal){%>' + '<span><%=animal %></span>' + '<%})%>' + '<% } else { %>' + 'No animals' + '<% } %>' + '!</div>';
		var renderer = can.view.ejs(template);
		var div = document.createElement('div');
		var frag = renderer({
			animals: animals
		});
		div.appendChild(frag);
		equal(div.getElementsByTagName('div')[0].innerHTML, 'Animals:No animals!');
		animals.push('sloth');
		equal(div.getElementsByTagName('span')
			.length, 1, 'There is 1 sloth');
		animals.pop();
		equal(div.getElementsByTagName('div')[0].innerHTML, 'Animals:No animals!');
	});
	test('JS blocks within EJS tags shouldn\'t require isolation', function () {
		var isolatedBlocks = can.view.ejs('<% if (true) { %>' + '<% if (true) {%>' + 'hi' + '<% } %>' + '<% } %>'),
			sharedBlocks = can.view.ejs('<% if (true) { %>' + '<% if (true) { %>' + 'hi' + '<% }' + '} %>'),
			complexIsolatedBlocks = can.view.ejs('<% if (true) { %><% if (1) { %>' + '<% if ({ dumb: \'literal\' }) { %>' + '<% list(items, function(item) { %>' + '<%== item %>' + '<%== something(function(items){ %><%== items.length %><% }) %>' + '<% }) %>' + '<% } %>' + '<% } %><% } %>'),
			complexSharedBlocks = can.view.ejs('<% if (true) { if (1) { %>' + '<% if ({ dumb: \'literal\' }) { %>' + '<% list(items, function(item) { %>' + '<%== item %>' + '<%== something(function(items){ %><%== items.length %><% }) %>' + '<% }) %>' + '<% }' + '} } %>'),
			iteratedSharedBlocks = can.view.ejs('<% for (var i = 0; i < items.length; i++) { %>' + '<% if (this.items) { if (1) { %>' + 'hi' + '<% } } else { %>' + 'nope' + '<% } } %>'),
			iteratedString = can.view.ejs('<% for(var i = 0; i < items.length; i++) { %>' + '\t<% if(this.mode !== "RESULTS") {' + '\t\tif(items[i] !== "SOME_FAKE_VALUE") { %>' + '\t\t\thi' + '\t\t<% }' + '\t} else { %>' + '\t\tnope' + '\t<% }' + '} %>'),
			iteratedStringNewLines = can.view.ejs('<% for(var i = 0; i < items.length; i++) { %>' + '\t<% if(this.mode !== "RESULTS") {\n' + '\t\tif(items[i] !== "SOME_FAKE_VALUE") { %>' + '\t\t\thi' + '\t\t<% }\n' + '\t} else { %>' + '\t\tnope' + '\t<% }\n' + '} %>'),
			data = {
				items: [
					'one',
					'two',
					'three'
				],
				mode: 'SOMETHING',
				something: function (cb) {
					return cb([
						1,
						2,
						3,
						4
					]);
				}
			};
		var div = document.createElement('div');
		try {
			div.appendChild(isolatedBlocks(data));
		} catch (ex) {}
		equal(div.innerHTML, 'hi', 'Rendered isolated blocks');
		div.innerHTML = '';
		try {
			div.appendChild(sharedBlocks(data));
		} catch (ex) {}
		equal(div.innerHTML, 'hi', 'Rendered shared blocks');
		div.innerHTML = '';
		try {
			div.appendChild(complexIsolatedBlocks(data));
		} catch (ex) {}
		equal(div.innerHTML, 'one4two4three4', 'Rendered complex isolated blocks with helpers and object literals');
		div.innerHTML = '';
		try {
			div.appendChild(complexSharedBlocks(data));
		} catch (ex) {}
		equal(div.innerHTML, 'one4two4three4', 'Rendered complex shared blocks with helpers and object literals');
		div.innerHTML = '';
		try {
			div.appendChild(iteratedSharedBlocks(data));
		} catch (ex) {}
		equal(div.innerHTML, 'hihihi', 'Rendered iterated shared blocks');
		div.innerHTML = '';
		try {
			div.appendChild(iteratedString(data));
		} catch (ex) {}
		ok(div.innerHTML.match(/^\s*hi\s*hi\s*hi\s*$/), 'Rendered iterated shared blocks string');
		div.innerHTML = '';
		try {
			div.appendChild(iteratedStringNewLines(data));
		} catch (ex) {}
		ok(div.innerHTML.match(/^\s*hi\s*hi\s*hi\s*$/), 'Rendered iterated shared blocks string (with new lines)');
		can.view.render(can.test.path('view/ejs/test/shared_blocks.ejs'), {
			items: [
				'one',
				'two',
				'three'
			],
			mode: 'SOMETHING'
		});
		ok(div.innerHTML.match(/^\s*hi\s*hi\s*hi\s*$/), 'Rendered iterated shared blocks file');
	});
	// Issue #242
	// This won't be fixed as it would require a full JS parser
	/*
	 test("Variables declared in shared EJS blocks shouldn't get lost", function() {
	 var template = can.view.ejs(
	 "<%" +
	 "var bestTeam = teams[0];" +
	 "can.each(teams, function(team) { %>" +
	 "<div><%== team.name %></div>" +
	 "<% }) %>" +
	 "<div class='best'><%== bestTeam.name %>!</div>"),
	 data = {
	 teams: new can.List([
	 { name: "Packers", rank: 1 },
	 { name: "Bears", rank: 2 },
	 { name: "Vikings", rank: 3 },
	 { name: "Lions", rank: 4 },
	 ])
	 },
	 div = document.createElement('div');

	 try {
	 div.appendChild(template(data));
	 } catch (ex) { }
	 var children = div.getElementsByTagName('div');
	 equal( children.length, 5, "Rendered all teams and the best team");
	 equal( children[1].innerHTML, "Bears", "Lost again");
	 equal( children[4].innerHTML, "Packers!", "#1 team");
	 });
	 */
	//Issue 267
	test('Access .length with nested dot notation', function () {
		var template = '<span id="nested"><%= this.attr("list.length") %></span>' + '<span id="unnested"><%= this.list.attr("length") %></span>',
			obj = new can.Map({
				list: [
					0,
					1,
					2,
					3
				]
			}),
			renderer = can.view.ejs(template),
			div = document.createElement('div');
		div.appendChild(renderer(obj));
		ok(div.getElementsByTagName('span')[0].innerHTML === '4', 'Nested dot notation.');
		ok(div.getElementsByTagName('span')[1].innerHTML === '4', 'Not-nested dot notation.');
	});
	test('attributes in truthy section', function () {
		var template = can.view.ejs('<p <% if(attribute) {%>data-test="<%=attribute%>"<% } %>></p>');
		var data1 = {
			attribute: 'test-value'
		};
		var frag1 = template(data1);
		var div1 = document.createElement('div');
		div1.appendChild(frag1);
		equal(div1.children[0].getAttribute('data-test'), 'test-value', 'hyphenated attribute value');
		var data2 = {
			attribute: 'test value'
		};
		var frag2 = template(data2);
		var div2 = document.createElement('div');
		div2.appendChild(frag2);
		equal(div2.children[0].getAttribute('data-test'), 'test value', 'whitespace in attribute value');
	});
	test('outputting array of attributes', function () {
		var template = can.view.ejs('<p <% for(var i = 0; i < attribute.length; i++) { %><%=attribute[i].name%>="<%=attribute[i].value%>"<%}%>></p>');
		var data = {
			attribute: [{
				'name': 'data-test1',
				'value': 'value1'
			}, {
				'name': 'data-test2',
				'value': 'value2'
			}, {
				'name': 'data-test3',
				'value': 'value3'
			}]
		};
		var frag = template(data);
		var div = document.createElement('div');
		div.appendChild(frag);
		equal(div.children[0].getAttribute('data-test1'), 'value1', 'first value');
		equal(div.children[0].getAttribute('data-test2'), 'value2', 'second value');
		equal(div.children[0].getAttribute('data-test3'), 'value3', 'third value');
	});
	test('_bindings removed when element removed', function () {
		var template = can.view.ejs('<div id="game"><% if(game.attr("league")) { %><%= game.attr("name") %><% } %></div>'),
			game = new can.Map({
				'name': 'Fantasy Baseball',
				'league': 'Malamonsters'
			});
		var frag = template({
			game: game
		});
		var div = document.createElement('div');
		div.appendChild(frag);
		can.remove(can.$(div));
		stop();
		setTimeout(function () {
			start();
			equal(game._bindings, 0, 'No bindings left');
		}, 50);
	});
});

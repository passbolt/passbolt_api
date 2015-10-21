steal("can/view/live", "can/observe", "can/test", "steal-qunit", function () {
	QUnit.module('can/view/live');
	test('html', function () {
		var div = document.createElement('div'),
			span = document.createElement('span');
		div.appendChild(span);
		var items = new can.List([
			'one',
			'two'
		]);
		var html = can.compute(function () {
			var html = '';
			items.each(function (item) {
				html += '<label>' + item + '</label>';
			});
			return html;
		});
		can.view.live.html(span, html, div);
		equal(div.getElementsByTagName('label')
			.length, 2);
		items.push('three');
		equal(div.getElementsByTagName('label')
			.length, 3);
	});
	var esc = function (str) {
		return str.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;');
	};
	test('text', function () {
		var div = document.createElement('div'),
			span = document.createElement('span');
		div.appendChild(span);
		var items = new can.List([
			'one',
			'two'
		]);
		var text = can.compute(function () {
			var html = '';
			items.each(function (item) {
				html += '<label>' + item + '</label>';
			});
			return html;
		});
		can.view.live.text(span, text, div);
		equal(div.innerHTML, esc('<label>one</label><label>two</label>'));
		items.push('three');
		equal(div.innerHTML, esc('<label>one</label><label>two</label><label>three</label>'));
	});
	test('attributes', function () {
		var div = document.createElement('div');
		var items = new can.List([
			'class',
			'foo'
		]);
		var text = can.compute(function () {
			var html = '';
			if (items.attr(0) && items.attr(1)) {
				html += items.attr(0) + '=\'' + items.attr(1) + '\'';
			}
			return html;
		});
		can.view.live.attributes(div, text);
		equal(div.className, 'foo');
		items.splice(0, 2);
		equal(div.className, '');
		items.push('foo', 'bar');
		equal(div.getAttribute('foo'), 'bar');
	});
	test('attribute', function () {
		var div = document.createElement('div');
		div.className = 'foo ' + can.view.live.attributePlaceholder + ' ' + can.view.live.attributePlaceholder + ' end';
		var firstObject = new can.Map({});
		var first = can.compute(function () {
			return firstObject.attr('selected') ? 'selected' : '';
		});
		var secondObject = new can.Map({});
		var second = can.compute(function () {
			return secondObject.attr('active') ? 'active' : '';
		});
		can.view.live.attribute(div, 'class', first);
		can.view.live.attribute(div, 'class', second);
		equal(div.className, 'foo   end');
		firstObject.attr('selected', true);
		equal(div.className, 'foo selected  end');
		secondObject.attr('active', true);
		equal(div.className, 'foo selected active end');
		firstObject.attr('selected', false);
		equal(div.className, 'foo  active end');
	});
	test('specialAttribute with new line', function () {
		var div = document.createElement('div');
		var style = can.compute('style="width: 50px;\nheight:50px;"');
		can.view.live.specialAttribute(div, 'style', style);
		equal(div.style.height, '50px');
		equal(div.style.width, '50px');
	});
	test('list', function () {
		var div = document.createElement('div'),
			list = new can.List([
				'sloth',
				'bear'
			]),
			template = function (animal) {
				return '<label>Animal=</label> <span>' + animal + '</span>';
			};
		div.innerHTML = 'my <b>fav</b> animals: <span></span> !';
		var el = div.getElementsByTagName('span')[0];
		can.view.live.list(el, list, template, {});
		equal(div.getElementsByTagName('label')
			.length, 2, 'There are 2 labels');
		div.getElementsByTagName('label')[0].myexpando = 'EXPANDO-ED';
		list.push('turtle');
		equal(div.getElementsByTagName('label')[0].myexpando, 'EXPANDO-ED', 'same expando');
		equal(div.getElementsByTagName('span')[2].innerHTML, 'turtle', 'turtle added');
	});
	test('list with a compute', function () {
		var div = document.createElement('div'),
			map = new can.Map({
				animals: [
					'bear',
					'turtle'
				]
			}),
			template = function (animal) {
				return '<label>Animal=</label> <span>' + animal + '</span>';
			};
		var compute = can.compute(function () {
			return map.attr('animals');
		});
		div.innerHTML = 'my <b>fav</b> animals: <span></span> !';
		var el = div.getElementsByTagName('span')[0];
		can.view.live.list(el, compute, template, {});
		equal(div.getElementsByTagName('label')
			.length, 2, 'There are 2 labels');
		div.getElementsByTagName('label')[0].myexpando = 'EXPANDO-ED';
		map.attr('animals')
			.push('turtle');
		equal(div.getElementsByTagName('label')[0].myexpando, 'EXPANDO-ED', 'same expando');
		equal(div.getElementsByTagName('span')[2].innerHTML, 'turtle', 'turtle added');
		map.attr('animals', new can.List([
			'sloth',
			'bear',
			'turtle'
		]));
		var spans = div.getElementsByTagName('span');
		equal(spans.length, 3, 'there are 3 spans');
		ok(!div.getElementsByTagName('label')[0].myexpando, 'no expando');
	});
	test('list with a compute that returns a list', function () {
		var div = document.createElement('div'),
			template = function (num) {
				return '<label>num=</label> <span>' + num + '</span>';
			};
		var compute = can.compute([
			0,
			1
		]);
		div.innerHTML = 'my <b>fav</b> nums: <span></span> !';
		var el = div.getElementsByTagName('span')[0];
		can.view.live.list(el, compute, template, {});
		equal(div.getElementsByTagName('label')
			.length, 2, 'There are 2 labels');
		compute([
			0,
			1,
			2
		]);
		var spans = div.getElementsByTagName('span');
		equal(spans.length, 3, 'there are 3 spans');
	});
	test('text binding is memory safe (#666)', function () {
		for(var prop in can.view.nodeLists.nodeMap) {
			delete can.view.nodeLists.nodeMap[prop];
		}

		var div = document.createElement('div'),
			span = document.createElement('span'),
			el = can.$(div),
			text = can.compute(function () {
				return 'foo';
			});
		div.appendChild(span);
		can.$('#qunit-fixture')[0].appendChild(div);
		can.view.live.text(span, text, div);
		can.remove(el);
		stop();
		setTimeout(function () {
			ok(can.isEmptyObject(can.view.nodeLists.nodeMap), 'nothing in nodeMap');
			start();
		}, 100);
	});
	
	test('html live binding handles getting a function from a compute',5, function(){
		var handler = function(el){
			ok(true, "called handler");
			equal(el.nodeType, 3, "got a placeholder");
		};
		
		var div = document.createElement('div'),
			placeholder = document.createTextNode('');
		div.appendChild(placeholder);
		
		var count = can.compute(0);
		var html = can.compute(function(){
			if(count() === 0) {
				return "<h1>Hello World</h1>";
			} else {
				return handler;
			}
		});
		
		
		can.view.live.html(placeholder, html, div);
		
		equal(div.getElementsByTagName("h1").length, 1, "got h1");
		count(1);
		equal(div.getElementsByTagName("h1").length, 0, "got h1");
		count(0);
		equal(div.getElementsByTagName("h1").length, 1, "got h1");
		
		
	});

	test("can.live.attribute works with non-string attributes (#1790)", function() {
		var el = document.createElement('div'),
			compute = can.compute(function() {
				return 2;
			});

		can.view.elements.setAttr(el, "value", 1);
		can.view.live.attribute(el, 'value', compute);
		ok(true, 'No exception thrown.');
	});
});

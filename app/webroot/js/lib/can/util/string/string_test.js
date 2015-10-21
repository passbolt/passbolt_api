steal('can/util/string', './deparam/deparam_test.js', 'steal-qunit', function () {
	QUnit.module('can/util/string');
	test('can.sub', function () {
		equal(can.sub('a{b}', {
			b: 'c'
		}), 'ac');
		var foo = {
			b: 'c'
		};
		equal(can.sub('a{b}', foo, true), 'ac');
		ok(!foo.b, 'b\'s value was removed');
	});
	test('can.sub with undefined values', function () {
		var subbed = can.sub('test{exists} plus{noexists}', {
			exists: 'test'
		});
		deepEqual(subbed, null, 'Rendering with undefined values should return null');
		subbed = can.sub('test{exists} plus{noexists}', {
			exists: 'test'
		}, true);
		deepEqual(subbed, null, 'Rendering with undefined values should return null even when remove param is true');
	});
	test('can.sub with null values', function () {
		var subbed = can.sub('test{exists} plus{noexists}', {
			exists: 'test',
			noexists: null
		});
		deepEqual(subbed, null, 'Rendering with null values should return null');
		subbed = can.sub('test{exists} plus{noexists}', {
			exists: 'test',
			noexists: null
		}, true);
		deepEqual(subbed, null, 'Rendering with null values should return null even when remove param is true');
	});
	test('can.sub double', function () {
		equal(can.sub('{b} {d}', [{
			b: 'c',
			d: 'e'
		}]), 'c e');
	});
	test('String.underscore', function () {
		equal(can.underscore('Foo.Bar.ZarDar'), 'foo.bar.zar_dar');
	});
	test('can.sub remove', function () {
		var obj = {
			a: 'a'
		};
		equal(can.sub('{a}', obj, false), 'a');
		deepEqual(obj, {
			a: 'a'
		});
		equal(can.sub('{a}', obj, true), 'a');
		deepEqual(obj, {});
	});
	test('can.getObject Single root', function () {
		// ## Single root
		var root, result;
		// # Only get
		root = {
			foo: 'bar'
		};
		// exists
		result = can.getObject('foo', root);
		equal(result, 'bar', 'got \'bar\'');
		// not exists
		result = can.getObject('baz', root);
		equal(result, undefined, 'got \'undefined\'');
		// # With remove
		// exists
		root = {
			foo: 'bar'
		};
		result = can.getObject('foo', root, false);
		equal(result, 'bar', 'got \'bar\'');
		deepEqual(root, {}, 'root is empty');
		// not exists
		root = {
			foo: 'bar'
		};
		result = can.getObject('baz', root, false);
		equal(result, undefined, 'got \'undefined\'');
		deepEqual(root, {
			foo: 'bar'
		}, 'root is same');
		// # With add
		// exists
		root = {
			foo: 'bar'
		};
		result = can.getObject('foo', root, true);
		equal(result, 'bar', 'got \'bar\'');
		deepEqual(root, {
			foo: 'bar'
		}, 'root is same');
		// not exists
		root = {
			foo: 'bar'
		};
		result = can.getObject('baz', root, true);
		deepEqual(result, {}, 'got \'{}\'');
		deepEqual(root, {
			foo: 'bar',
			baz: {}
		}, 'added \'baz: {}\' into root');
	});
	test('can.getObject Multiple root', function () {
		// ## Multiple roots
		var root1, root2, roots, result;
		// # Only get
		root1 = {
			a: 1
		};
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		// exists in first root
		result = can.getObject('a', roots);
		equal(result, 1, 'got \'1\'');
		// exists in second root
		result = can.getObject('b', roots);
		equal(result, 2, 'got \'2\'');
		// not exists anywhere
		result = can.getObject('c', roots);
		equal(result, undefined, 'got \'undefined\'');
		// # With remove
		// exists in first root
		root1 = {
			a: 1
		};
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		result = can.getObject('a', roots, false);
		equal(result, 1, 'got \'1\'');
		deepEqual(root1, {}, 'root is empty');
		deepEqual(root2, {
			b: 2
		}, 'root is same');
		// exists in second root
		root1 = {
			a: 1
		};
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		result = can.getObject('b', roots, false);
		equal(result, 2, 'got \'2\'');
		deepEqual(root1, {
			a: 1
		}, 'root is same');
		deepEqual(root2, {}, 'root is empty');
		// not exists anywhere
		root1 = {
			a: 1
		};
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		result = can.getObject('c', roots, false);
		equal(result, undefined, 'got \'undefined\'');
		deepEqual(root1, {
			a: 1
		}, 'root is same');
		deepEqual(root2, {
			b: 2
		}, 'root is same');
		// # With add
		// exists in first root
		root1 = {
			a: 1
		};
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		result = can.getObject('a', roots, true);
		equal(result, 1, 'got \'1\'');
		deepEqual(root1, {
			a: 1
		}, 'root is same');
		deepEqual(root2, {
			b: 2
		}, 'root is same');
		// exists in second root
		root1 = {
			a: 1
		};
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		result = can.getObject('b', roots, true);
		equal(result, 2, 'got \'2\'');
		deepEqual(root1, {
			a: 1
		}, 'root is same');
		deepEqual(root2, {
			b: 2
		}, 'root is same');
		// not exists anywhere
		root1 = {
			a: 1
		};
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		result = can.getObject('c', roots, true);
		deepEqual(result, {}, 'got \'{}\'');
		deepEqual(root1, {
			a: 1,
			c: {}
		}, 'added \'c: {}\' into first root');
		deepEqual(root2, {
			b: 2
		}, 'root is same');
		// # One of roots is not an object
		// exists in second root
		root1 = undefined;
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		result = can.getObject('b', roots);
		equal(result, 2, 'got \'2\'');
		// exists in second root and remove
		root1 = undefined;
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		result = can.getObject('b', roots, false);
		equal(result, 2, 'got \'2\'');
		equal(root1, undefined, 'got \'undefined\'');
		deepEqual(root2, {}, 'deleted \'b\' from root');
		// not exists in any root and add
		root1 = undefined;
		root2 = {
			b: 2
		};
		roots = [
			root1,
			root2
		];
		result = can.getObject('a', roots, true);
		equal(result, undefined, 'got \'undefined\'');
		equal(root1, undefined, 'root is same');
		deepEqual(root2, {
			b: 2
		}, 'root is same');
	});
	test('can.getObject Deep objects', function () {
		// ## Deep objects
		var root, result;
		// # Only get
		root = {
			foo: {
				bar: 'baz'
			}
		};
		// exists
		result = can.getObject('foo.bar', root);
		equal(result, 'baz', 'got \'baz\'');
		// not exists
		result = can.getObject('foo.world', root);
		equal(result, undefined, 'got \'undefined\'');
		// # With remove
		// exists
		root = {
			foo: {
				bar: 'baz'
			}
		};
		result = can.getObject('foo.bar', root, false);
		equal(result, 'baz', 'got \'baz\'');
		deepEqual(root, {
			foo: {}
		}, 'deep object is empty');
		// not exists
		root = {
			foo: {
				bar: 'baz'
			}
		};
		result = can.getObject('foo.world', root, false);
		equal(result, undefined, 'got \'undefined\'');
		deepEqual(root, {
			foo: {
				bar: 'baz'
			}
		}, 'root is same');
		// # With add
		// exists
		root = {
			foo: {
				bar: 'baz'
			}
		};
		result = can.getObject('foo.bar', root, true);
		equal(result, 'baz', 'got \'baz\'');
		deepEqual(root, {
			foo: {
				bar: 'baz'
			}
		}, 'root is same');
		// not exists
		root = {
			foo: {
				bar: 'baz'
			}
		};
		result = can.getObject('foo.world', root, true);
		deepEqual(result, {}, 'got \'{}\'');
		deepEqual(root, {
			foo: {
				bar: 'baz',
				world: {}
			}
		}, 'added \'world: {}\' into deep object');
	});
	test('can.esc', function () {
		var text = can.esc(0);
		equal(text, '0', '0 value properly rendered');
		text = can.esc(null);
		deepEqual(text, '', 'null value returns empty string');
		text = can.esc();
		deepEqual(text, '', 'undefined returns empty string');
		text = can.esc(NaN);
		deepEqual(text, '', 'NaN returns empty string');
		text = can.esc('<div>&nbsp;</div>');
		equal(text, '&lt;div&gt;&amp;nbsp;&lt;/div&gt;', 'HTML escaped properly');
	});
	test('can.camelize', function () {
		var text = can.camelize(0);
		equal(text, '0', '0 value properly rendered');
		text = can.camelize(null);
		equal(text, '', 'null value returns empty string');
		text = can.camelize();
		equal(text, '', 'undefined returns empty string');
		text = can.camelize(NaN);
		equal(text, '', 'NaN returns empty string');
		text = can.camelize('-moz-index');
		equal(text, 'MozIndex');
		text = can.camelize('foo-bar');
		equal(text, 'fooBar');
	});
	test('can.hyphenate', function () {
		var text = can.hyphenate(0);
		equal(text, '0', '0 value properly rendered');
		text = can.hyphenate(null);
		equal(text, '', 'null value returns empty string');
		text = can.hyphenate();
		equal(text, '', 'undefined returns empty string');
		text = can.hyphenate(NaN);
		equal(text, '', 'NaN returns empty string');
		text = can.hyphenate('ABC');
		equal(text, 'ABC');
		text = can.hyphenate('dataNode');
		equal(text, 'data-node');
	});
});

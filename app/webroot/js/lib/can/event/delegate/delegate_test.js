steal('can/event/delegate', 'can/test', 'steal-qunit', function (event) {
	module('can/event/delegate');

	test('Delegate/undelegate are bound and unbound properly', 9, function() {
		var node1 = { name: 'root' },
			node2 = { name: 'mid', parent: node1 },
			node3 = { name: 'child', parent: node2 },
			fn;

		can.extend(node1, can.event, { propagate: 'parent' });
		can.extend(node2, can.event, { propagate: 'parent' });
		can.extend(node3, can.event, { propagate: 'parent' });

		// Verify delegate
		node1.delegate('', 'action', fn = function(ev) {
			equal(ev.target.name, 'custom', 'target is custom target');
			equal(ev.currentTarget.name, 'root', 'currentTarget is node1');
			equal(this.name, 'root', 'delegate is node1');
			equal(ev.descendants.length, 3, 'event has 3 descendants (node2, node3, custom target)');
			equal(ev.descendants[0].name, 'mid', 'first descendant is node2');
			equal(ev.descendants[1].name, 'child', 'second descendant is node3');
			equal(ev.descendants[2].name, 'custom', 'third descendant is custom target');
		});
		equal(node1.__bindEvents.action.length, 1);
		node3.dispatch({
			type: 'action',
			target: { name: 'custom' }
		});

		// Verify undelegate
		node1.undelegate('', 'action', fn);
		equal(node1.__bindEvents.action.length, 0);
		node3.dispatch({
			type: 'action',
			target: { name: 'custom' }
		});
	});

	test('Delegate selector is enforced', 17, function() {
		var DelegateClass = can.Construct.extend("DelegateClass", {
				init: function(options) {
					can.simpleExtend(this, options);
				}
			}),
			AnotherClass = DelegateClass.extend("AnotherClass"),
			node1 = new DelegateClass({ name: 'root' }),
			node2 = new AnotherClass({ name: 'mid', parentNode: node1 }),
			node3 = new DelegateClass({ name: 'child', parent: node2 }),
			fn, fn2;

		can.extend(DelegateClass.prototype, can.event, { propagate: 'parent' });
		can.extend(AnotherClass.prototype, can.event, { propagate: 'parentNode' });

		// Verify delegate
		node1.delegate('AnotherClass DelegateClass', 'action', fn = function(ev) {
			equal(ev.target.name, 'custom', 'target is custom target');
			equal(ev.currentTarget.name, 'root', 'currentTarget is node1');
			equal(this.name, 'root', 'delegate is node1');
			equal(ev.descendants.length, 3, 'event has 3 descendants (node2, node3, custom target)');
			equal(ev.descendants[0].name, 'mid', 'first descendant is node2');
			equal(ev.descendants[1].name, 'child', 'second descendant is node3');
			equal(ev.descendants[2].name, 'custom', 'third descendant is custom target');
		});
		// Support _shortName and shortName
		node1.delegate('another_class delegate_class', 'action', fn2 = function(ev) {
			equal(ev.target.name, 'custom', 'target is custom target');
			equal(ev.currentTarget.name, 'root', 'currentTarget is node1');
			equal(this.name, 'root', 'delegate is node1');
			equal(ev.descendants.length, 3, 'event has 3 descendants (node2, node3, custom target)');
			equal(ev.descendants[0].name, 'mid', 'first descendant is node2');
			equal(ev.descendants[1].name, 'child', 'second descendant is node3');
			equal(ev.descendants[2].name, 'custom', 'third descendant is custom target');
		});
		node1.delegate('NotTheRightClass', 'action', function(ev) {
			notEqual(this.name, 'root', 'This delegate should never fire');
		});
		equal(node1.__bindEvents.action.length, 3, 'delegate exists in the events');
		node3.dispatch({
			type: 'action',
			target: { name: 'custom' }
		});

		// Verify undelegate
		node1.undelegate('', 'action', fn);
		equal(node1.__bindEvents.action.length, 3, 'a non-matching selector should not remove the delegate');
		node1.undelegate('AnotherClass DelegateClass', 'action', fn);
		node1.undelegate('another_class delegate_class', 'action', fn2);
		equal(node1.__bindEvents.action.length, 1, 'a matching selector should remove the delegate');
		node3.dispatch({
			type: 'action',
			target: { name: 'custom' }
		});
	});
});

steal('can/event/propagate', 'can/test', 'steal-qunit', function (event) {
	module('can/event/propagate');

	test('Propagation', 9, function() {
		var node1 = { name: 'root' },
			node2 = { name: 'mid', parent: node1 },
			node3 = { name: 'child', parent: node2 };

		can.extend(node1, can.event, { propagate: 'parent' });
		can.extend(node2, can.event, { propagate: 'parent' });
		can.extend(node3, can.event, { propagate: 'parent' });

		// Test propagation
		node1.bind('action', function(ev) {
			equal(ev.target.name, 'child', 'target is node3');
			equal(ev.currentTarget.name, 'root', 'currentTarget is node1');
			equal(this.name, 'root', 'delegate is node1');
		});
		node2.bind('action', function(ev) {
			equal(ev.target.name, 'child', 'target is node3');
			equal(ev.currentTarget.name, 'mid', 'currentTarget is node2');
			equal(this.name, 'mid', 'delegate is node2');
		});
		node3.bind('action', function(ev) {
			equal(ev.target.name, 'child', 'target is node1');
			equal(ev.currentTarget.name, 'child', 'currentTarget is node1');
			equal(this.name, 'child', 'delegate is node1');
		});
		node3.dispatch('action');
	});

	test('Stop propagation', 6, function() {
		var node1 = { name: 'root' },
			node2 = { name: 'mid', parent: node1 },
			node3 = { name: 'child', parent: node2 };

		can.extend(node1, can.event, { propagate: 'parent' });
		can.extend(node2, can.event, { propagate: 'parent' });
		can.extend(node3, can.event, { propagate: 'parent' });

		// Test stop propagation
		node1.bind('stop', function(ev) {
			// This should never fire
			ok(false);
		});
		node2.bind('stop', function(ev) {
			equal(ev.target.name, 'child', 'target is node3');
			equal(ev.currentTarget.name, 'mid', 'currentTarget is node2');
			equal(this.name, 'mid', 'delegate is node2');
			ev.stopPropagation();
		});
		node3.bind('stop', function(ev) {
			equal(ev.target.name, 'child', 'target is node1');
			equal(ev.currentTarget.name, 'child', 'currentTarget is node1');
			equal(this.name, 'child', 'delegate is node1');
		});
		node3.dispatch('stop');
	});

	test('Prevent default', 9, function() {
		var node1 = { name: 'root' },
			node2 = { name: 'mid', parent: node1 },
			node3 = { name: 'child', parent: node2 };

		can.extend(node1, can.event, { propagate: 'parent' });
		can.extend(node2, can.event, { propagate: 'parent' });
		can.extend(node3, can.event, { propagate: 'parent' });

		// Test stop propagation
		node1.bind('stop', function(ev) {
			// This should never fire
			ok(false);
		});
		node2.bind('stop', function(ev) {
			equal(ev.target.name, 'child', 'target is node3');
			equal(ev.currentTarget.name, 'mid', 'currentTarget is node2');
			equal(this.name, 'mid', 'delegate is node2');
			ev.stopPropagation();
			equal(ev.isDefaultPrevented(), true, 'default is prevented');
		});
		node3.bind('stop', function(ev) {
			equal(ev.isDefaultPrevented(), false, 'default not prevented');
			equal(ev.target.name, 'child', 'target is node1');
			equal(ev.currentTarget.name, 'child', 'currentTarget is node1');
			equal(this.name, 'child', 'delegate is node1');
			equal(ev.isDefaultPrevented(), false, 'default not prevented');
			ev.preventDefault();
		});
		node3.dispatch('stop');
	});

	test('Events propagate even if the original target has no listeners', 3, function() {
		var node1 = { name: 'root' },
			node2 = { name: 'mid', parent: node1 },
			node3 = { name: 'child', parent: node2 };

		can.extend(node1, can.event, { propagate: 'parent' });
		can.extend(node2, can.event, { propagate: 'parent' });
		can.extend(node3, can.event, { propagate: 'parent' });

		node1.bind('action', function(ev) {
			equal(ev.target.name, 'child', 'target is node3');
			equal(ev.currentTarget.name, 'root', 'currentTarget is node1');
			equal(this.name, 'root', 'delegate is node1');
		});
		node3.dispatch('action');
	});

	test('Descendants are available to verify delegation', 7, function() {
		var node1 = { name: 'root' },
			node2 = { name: 'mid', parent: node1 },
			node3 = { name: 'child', parent: node2 };

		can.extend(node1, can.event, { propagate: 'parent' });
		can.extend(node2, can.event, { propagate: 'parent' });
		can.extend(node3, can.event, { propagate: 'parent' });

		node1.bind('action', function(ev) {
			equal(ev.target.name, 'custom', 'target is custom target');
			equal(ev.currentTarget.name, 'root', 'currentTarget is node1');
			equal(this.name, 'root', 'delegate is node1');
			equal(ev.descendants.length, 3, 'event has 3 descendants (node2, node3, custom target)');
			equal(ev.descendants[0].name, 'mid', 'first descendant is node2');
			equal(ev.descendants[1].name, 'child', 'second descendant is node3');
			equal(ev.descendants[2].name, 'custom', 'third descendant is custom target');
		});
		node3.dispatch({
			type: 'action',
			target: { name: 'custom' }
		});
	});
});

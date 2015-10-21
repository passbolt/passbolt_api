steal('can/event/namespace', 'can/test', 'steal-qunit', function () {
	module('can/event/namespace');

	test('Event with namespaces', function() {
		var node = can.extend({ name: 'root' }, can.event),
			count = 0;
			fn = function(ev) {
				++count;
			};

		count = 0;
		node.bind('action.namespace', fn);
		node.dispatch('action');
		equal(count, 1, 'Action with namespace fired');

		count = 0;
		node.dispatch('action');
		node.unbind('action.namespace', fn);
		node.dispatch('action');
		equal(count, 1, 'Action with namespace not fired after unbind');

		count = 0;
		node.bind('action.namespace', fn);
		node.dispatch('action');
		node.unbind('.namespace', fn);
		node.dispatch('action');
		equal(count, 1, 'Action unbound by namespace');
	});

	test('Events with multiple namespaces', function() {
		var node = can.extend({ name: 'root' }, can.event),
			count = 0;
			fn = function(ev) {
				++count;
			};

		count = 0;
		node.bind('action.namespace', fn);
		node.dispatch('action');
		node.unbind('.namespace', fn);
		node.dispatch('action');
		equal(count, 1, 'Action unbound by namespace');

		count = 0;
		node.bind('action.namespace.another.other', fn);
		node.dispatch('action');
		equal(count, 1, 'Action with multiple namespaces fired');
		node.unbind('.another', fn);
		node.dispatch('action');
		equal(count, 1, 'Any matching namespace should remove the event');
		node.bind('action.namespace.another.other', fn);
		node.unbind('action');
		node.dispatch('action');
		equal(count, 1, 'Normal event should remove listeners');

		count = 0;
		node.bind('action.namespace', fn);
		node.bind('other.namespace', fn);
		node.dispatch('action');
		node.dispatch('other');
		node.unbind('.namespace');
		node.dispatch('action');
		node.dispatch('action');
		node.dispatch('other');
		node.dispatch('other');
		equal(count, 2, 'Removing by namespace should remove all events assigned to it');

		count = 0;
		node.bind('action.namespace.another', fn);
		node.unbind('.namespace.other');
		node.dispatch('action');
		equal(count, 1, 'All namespaces much match when unbinding, if present');
	});
});

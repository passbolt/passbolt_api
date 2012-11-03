(function(){
	module("can/observe/list");

	test("filter", 8, function() {
		var original = new can.Observe.List([
			{
				name : 'Test 1',
				age : 20
			},
			{
				name : 'Test 2',
				age : 80
			},
			{
				name : 'Test 3',
				age : 1
			},
			{
				name : 'Test 4',
				age : 21
			}
		]);

		var state = new can.Observe({ minAge : 20 });

		var filtered = original.filter(function(element) {
			return element.attr('age') > state.attr('minAge');
		});

		original.attr('0.age', 22);
		equal(filtered.length, 3, 'Updating age adds a new item to filtered list');
		equal(filtered[filtered.length - 1].attr('age'), 22, 'Item has updated age');

		original.attr('1.age', 18);
		equal(filtered.length, 2, 'Updating age removes existing item from filtered list');

		state.attr('minAge', 80);
		original.attr('1.age', 87);
		equal(filtered.length, 1, 'Filtered list has one item');
		equal(filtered[0].attr('age'), 87, 'Contains single item with udpated age');

		state.attr('minAge', 29);
		original.push({
			name : 'Pushed tester',
			age : 28
		}, {
			name : 'Pushed tester 2',
			age : 30
		});
		equal(filtered.length, 2, 'Newly pushed element got updated according to filter');
		original.pop();
		equal(filtered.length, 1, 'Removed element also removed from filter');
		equal(filtered[0].attr('name'), 'Test 2', 'Older element remains');
	});

  test("attr updates items in position order", function(){
    var original = new can.Observe.List([
      {
        id : 1,
        name : 'Test 1',
        age : 20
      },
      {
        id : 2,
        name : 'Test 2',
        age : 80
      },
      {
        id : 3,
        name : 'Test 3',
        age : 1
      }
    ]);

    original.attr([
      {
        id : 1,
        name : 'Test 1',
        age : 120
      },
      {
        id : 2,
        name : 'Test 2',
        age : 180
      },
      {
        id : 3,
        name : 'Test 3',
        age : 101
      }
    ]);

    equal(original.attr('0.id'), 1);
    equal(original.attr('0.age'), 120, "Test 1's age incremented by 100 years");

    equal(original.attr('1.id'), 2);
    equal(original.attr('1.age'), 180, "Test 2's age incremented by 100 years");

    equal(original.attr('2.id'), 3);
    equal(original.attr('2.age'), 101, "Test 3's age incremented by 100 years");
  });

	test("map", function() {
		var original = new can.Observe.List([
			{
				name : 'Test 1',
				age : 20
			},
			{
				name : 'Test 2',
				age : 80
			},
			{
				name : 'Test 3',
				age : 1
			}
		]);

		var mapped = original.map(function(element) {
			return element.attr('name') + ' (' + element.attr('age') + ')';
		});

		equal(mapped.length, 3, 'All items mapped');
		original.attr('0.name', 'Updated test');
		original.attr('0.age', '24');

		equal(mapped[0], 'Updated test (24)', 'Mapping got updated');

		original.push({
			name : 'Push test',
			age : 99
		});

		equal(mapped[mapped.length - 1], 'Push test (' + 99 + ')');
		original.shift();
		equal(mapped.length, 3, 'Item got removed');
	});
})();
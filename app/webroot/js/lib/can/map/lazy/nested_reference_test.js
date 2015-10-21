/* jshint asi:true */
steal('can/map/lazy/nested_reference.js', 'steal-qunit', function () {

	QUnit.module("can/map/lazy/nested_reference");

	test("Basics", 3, function () {
		var data = [
			{id: 0, items: [
				{id: "0.0"},
				{id: "0.1"}
			]},
			{id: 1, items: [
				{id: "1.0"},
				{id: "1.1"},
				{id: "1.2"}
			]}
		];
		var nested = new can.NestedReference(data),
			ref1 = nested.make("1.items.1"),
			ref2 = nested.make("1.items.2");

		nested.make("0.items.1");

		equal(ref1(), "1.items.1", "Path created correctly");

		data[1].items.shift()

		equal(ref1(), "1.items.0", "Path updated correctly after shifting - ref1");
		equal(ref2(), "1.items.1", "Path updated correctly after shifting - ref2");

	});

	test("Removing children", 5, function () {
		var data = [
			{id: 0, items: [
				{id: "0.0"},
				{id: "0.1"}
			]},
			{id: 1, items: [
				{id: "1.0"},
				{id: "1.1"},
				{id: "1.2"}
			]}
		];
		var nested = new can.NestedReference(data),
			count = 0;

		nested.make("1.items.1");
		nested.make("1.items.2");
		nested.make("0.items.1");

		nested.removeChildren("1.items", function (child, ref) {
			count++;
			if (count === 1) {
				equal(child, data[1].items[1], "Reference removed - correct child");
				equal(ref, '1.items.1', "Reference removed - correct path");
			} else if (count === 2) {
				equal(child, data[1].items[2], "Reference removed - correct child");
				equal(ref, '1.items.2', "Referece removed - correct path");
			}
		});

		equal(nested.references.length, 1, "'1.items*' references removed, '0.items.1' remains.");
	});

	test("'.each' iterator", 2, function () {
		var data = [
				{id: 0, items: [
					{id: "0.0"},
					{id: "0.1"}
				]},
				{id: 1, items: [
					{id: "1.0"},
					{id: "1.1"}
				]}
			],
			nested = new can.NestedReference(data);

		nested.make("0.items");

		nested.make("1.items.0");

		var callbackCount = 0;
		nested.each(function (child, ref, path) {
			callbackCount++;
			if (callbackCount === 1) {
				equal(child, data[0].items, "First reference exists - '0.items'")
			} else {
				equal(child, data[1].items[0], "Second reference exists -'1.items.0'")
			}
		})
	});
});

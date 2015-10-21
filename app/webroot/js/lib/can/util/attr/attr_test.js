steal('can/util', 'can/view/mustache', 'can/util/attr', 'steal-qunit', function () {
	QUnit.module("can/util/attr");

	test("attributes event", function () {

		var div = document.createElement("div");


		var attrHandler1 = function(ev) {

			equal(ev.attributeName, "foo", "attribute name is correct");
			equal(ev.target, div, "target");
			equal(ev.oldValue, null, "oldValue");

			equal(div.getAttribute(ev.attributeName), "bar");
			can.unbind.call(can.$(div), "attributes", attrHandler1);
		};
		can.bind.call(can.$(div), "attributes", attrHandler1);

		can.attr.set(div, "foo", "bar");

		stop();

		setTimeout(function () {
			var attrHandler = function(ev) {
				ok(true, "removed event handler should be called");

				equal(ev.attributeName, "foo", "attribute name is correct");
				equal(ev.target, div, "target");
				equal(ev.oldValue, "bar", "oldValue should be 'bar'");

				equal(div.getAttribute(ev.attributeName), null, "value of the attribute should be null after the remove.");

				can.unbind.call(can.$(div), "attributes", attrHandler);
				start();
			};
			can.bind.call(can.$(div), "attributes", attrHandler);
			can.attr.remove(div, "foo");

		}, 50);

	});

	test("template attr updating", function () {

		var template = can.mustache("<div my-attr='{{value}}'></div>"),
			compute = can.compute("foo");

		var div = template({
			value: compute
		})
			.childNodes[0];

		can.bind.call(can.$(div), "attributes", function (ev) {

			equal(ev.oldValue, "foo");
			equal(ev.attributeName, "my-attr");

			start();
		});

		equal(div.getAttribute("my-attr"), "foo", "attribute set");

		stop();
		compute("bar");

	});

	test("attr.set CHECKED attribute works", function(){

		var input = document.createElement("input");
		input.type = "checkbox";

		document.getElementById("qunit-fixture").appendChild(input);

		can.attr.set(input, "CHECKED");
		equal(input.checked, true);

		input.checked = false;

		can.attr.set(input, "CHECKED");

		equal(input.checked, true);
		can.remove(can.$("#qunit-fixture>*"));
	});

	if (window.jQuery || window.Zepto) {

		test("zepto or jQuery - bind and unbind", function () {

			var div = document.createElement("div");
			var attrHandler = function(ev) {
				equal(ev.attributeName, "foo", "attribute name is correct");
				equal(ev.target, div, "target");
				equal(ev.oldValue, null, "oldValue");

				equal(div.getAttribute(ev.attributeName), "bar");

				$(div)
					.unbind("attributes", attrHandler)
					.attr("foo", "abc");

				setTimeout(function () {
					start();
				}, 20);
			};

			$(div)
				.bind("attributes", attrHandler);

			stop();
			$(div)
				.attr("foo", "bar");

		});

	}

	if (window.MooTools) {
		test("Mootools - addEvent, removeEvent, and set", function () {

			var div = document.createElement("div");
			var attrHandler = function(ev) {
				equal(ev.attributeName, "foo", "attribute name is correct");
				equal(ev.target, div, "target");
				equal(ev.oldValue, null, "oldValue");

				equal(div.getAttribute(ev.attributeName), "bar");

				$(div)
					.removeEvent("attributes", attrHandler);

				$(div)
					.set("foo", "abc");

				setTimeout(function () {
					start();
				}, 20);

			};
			$(div)
				.addEvent("attributes", attrHandler);

			stop();
			$(div)
				.set("foo", "bar");

		});
	}

	if (window.dojo) {
		test("Dojo - on, remove, and setAttr", function () {

			var div = document.createElement("div"),
				nodeList = new dojo.NodeList(div);

			var handler = nodeList.on("attributes", function (ev) {
				equal(ev.attributeName, "foo", "attribute name is correct");
				equal(ev.target, div, "target");
				equal(ev.oldValue, null, "oldValue");

				equal(div.getAttribute(ev.attributeName), "bar");

				handler.remove();

				dojo.setAttr(div, "foo", "abc");

				setTimeout(function () {
					start();
				}, 20);

			});

			stop();
			dojo.setAttr(div, "foo", "bar");

		});
	}

});

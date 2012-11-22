steal('can/util', 'can/control/modifier', function (can) {

	module("can/control/modifier");

	test("nested selectors", function () {
		var controllerClass = can.Control({
			".cat .paw click" : function () {
				paw++;
			},

			".cat .tail click" : function () {
				tail++;
			}
		});

		$('#test-content').html("<div class='cat'><div class='paw'></div><div class='tail'></div></div>");

		var controller1 = new controllerClass($("#test-content")),
			paw = 0,
			tail = 0;

		$('.tail').trigger('click');
		equal(tail, 1);
		equal(paw, 0);
	});


	asyncTest("pluginName", function () {

		var controllerClass = can.Control({
			defaults : {
				binder : undefined
			}
		}, {

			init : function () {
				this.options.binder = $(document.body);
				this.on();
			},

			" click:debounce(30)" : function () {
				ok(this instanceof can.Control, "Debounced function has the correct context.");
				fooToTheBar = true;
				run++;
			},

			"bar:debounce(30)" : function () {
				run2++;
			},

			"{binder} click:debounce(50)" : function () {
				run2++
			}

		});

		$('#test-content').html('<div id="foo"></div><div id="bar"></div>');

		/**/
		var controller1 = new controllerClass("#foo"),
			controller2 = new controllerClass("#bar"),
			run = 0,
			run2 = 0,
			fooToTheBar;

		// Do a bunch of clicks!

		$("#foo").trigger("click");
		$("#bar").trigger("click");
		$("#foo").trigger("click");
		$("#bar").trigger("click");
		$("#foo").trigger("click");
		$("#bar").trigger("click");

		// Make sure foo is still undefined (should be > 30ms before its defined)
		ok(!fooToTheBar, "`fooToTheBar` is undefined.");

		ok("bar" in controller1, "Method name gets aliased correctly");
		controller1.bar();
		controller1.bar();
		controller1.bar();
		controller1.bar();

		// Check if
		setTimeout(function () {
			ok(fooToTheBar, "`fooToTheBar` is true.");
			equals(run, 2, "`run` is 2");
			equals(run2, 1, "`run2` is 1");

			// Do a bunch more clicks!
			$("#foo").trigger("click");
			$("#bar").trigger("click");
			$("#foo").trigger("click");
			$("#bar").trigger("click");
			$("#foo").trigger("click");
			$("#bar").trigger("click");
			$(document.body).trigger('click');

			setTimeout(function () {
				ok(run === 5, "`run` is 5");
				start();
			}, 40);
		}, 40);

	});

});

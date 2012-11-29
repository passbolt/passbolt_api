steal('funcunit').then(function () {

	var testEnv = null;
	module("mad.helper", {
		// runs before each test
		setup: function () {
			stop();
			var url = '//lib/mad/test/testEnv/app.html';
//			var url = steal.idToUri('mad/test/testEnv/app.html').toString(); // sopen does not get full url, it needs relative url
			S.open(url, function () {
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
				S('body').hasClass('mad_test_app_ready', true, function () {
					start();
				});
			});
		},
		// runs after each test
		teardown: function () {}
	});

	test('HtmlHelper : create exception', function () {
		// inside replace
		var refElement = testEnv.mad.app.element,
			position = 'inside_replace',
			uid = "uid",
			component = '<div id="' + uid + '"/>';

		// refElement string, element not found
		raises(function () {
			mad.helper.HtmlHelper.create('#refElement', position, component);
		}, mad.error.WrongParametersException, mad.error.WrongParametersException.message);

		// refElement jquery, element not found
		raises(function () {
			mad.helper.HtmlHelper.create($('#refElement'), position, component);
		}, mad.error.WrongParametersException, mad.error.WrongParametersException.message);

		// wrong position
		raises(function () {
			mad.helper.HtmlHelper.create(refElement, 'unvalid_position', component);
		}, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
	});

	test('HtmlHelper : create', function () {
		var	component = null,
			$component = null,
			refElement = null,
			position = null,
			$next = null,
			$previous = null,
			$first = null,
			$last = null;

		// inside replace
		refElement = testEnv.mad.app.element;
		position = 'inside_replace';
		uid = "middle";
		component = '<div id="' + uid + '"/>';
		$component = testEnv.mad.helper.HtmlHelper.create(refElement, position, component);
		S('#' + uid).exists(1000, null, 'inside replace exists');
		S(refElement).find('#' + uid).exists(1000, null, 'inside replace well positionned');

		// before
		refElement = testEnv.$('#middle');
		position = 'before';
		uid = "before";
		component = '<div id="' + uid + '"/>';
		$component = testEnv.mad.helper.HtmlHelper.create(refElement, position, component);
		S('#' + uid).exists(1000, null, 'before exists');
		$previous = S(refElement).prev();
		equal($previous[0].id == uid, true, 'before well positionned');

		// after
		refElement = testEnv.$('#middle');
		position = 'after';
		uid = "after";
		component = '<div id="' + uid + '"/>';
		$component = testEnv.mad.helper.HtmlHelper.create(refElement, position, component);
		S('#' + uid).exists(1000, null, 'after exists');
		$next = S(refElement).next();
		equal($next[0].id == uid, true, 'after well positionned');

		// first
		refElement = testEnv.mad.app.element;
		position = 'first';
		uid = 'first';
		component = '<div id="' + uid + '"/>';
		$component = testEnv.mad.helper.HtmlHelper.create(refElement, position, component);
		S('#' + uid).exists(1000, null, 'first exists');
		$first = S(refElement).children().first();
		equal($first[0].id == uid, true, 'first well positionned');

		// last
		refElement = testEnv.mad.app.element;
		position = 'last';
		uid = 'last';
		component = '<div id="' + uid + '"/>';
		$component = testEnv.mad.helper.HtmlHelper.create(refElement, position, component);
		S('#' + uid).exists(1000, null, 'last exists');
		$last = S(refElement).children().last();
		equal($last[0].id == uid, true, 'last well positionned');
	});

});
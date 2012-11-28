steal('funcunit', function () {

	module("mad.model", {
		// runs before each test
		setup: function () {},
		// runs after each test
		teardown: function () {}
	});

	test('mad.model.Model : Instanciation', function () {
		var model = new mad.model.Model({
			attrA: 'valueA',
			attrB: 'valueB',
			attrC:' valueC'
		});
		ok(model instanceof mad.model.Model, 'The instanciated mdoel is an instance of the right type');
		equal(model.attrA, 'valueA', 'The value of the attribute attrA is the exepcted value');
		equal(model.attrB, 'valueB', 'The value of the attribute attrA is the exepcted value');
		equal(model.attrB, 'valueB', 'The value of the attribute attrA is the exepcted value');
	});

});
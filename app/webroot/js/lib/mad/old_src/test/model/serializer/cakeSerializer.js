steal(
	'funcunit'
).then(function () {

	module("mad.model.serializer", {
		// runs before each test
		setup: function () {
			stop();
			var url = 'lib/mad/test/testEnv/app.html';
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

	test('mad.model.serializer.CakeSerializer : From', function () {
		// @todo chech why on then the pipe does not release response and request
		var persons = testEnv.demo.model.Person.findAll({}, function (persons, response, request) {
			var cakeData = response.body,
				person1 = cakeData[0],
				Class = testEnv.demo.model.Person,
				s = testEnv.mad.model.serializer.CakeSerializer.from(person1, Class);

			for (var attrName in Class.attributes) {
				if (!Class.isModelAttribute(attrName)) {
					equal(person1.Person[attrName], s[attrName]);
				} 
				else {
					var subMdlAttr = Class.getAttribute(attrName);
					if (subMdlAttr.multiple) {
						can.each(person1[attrName], function (item, i) {
							for (var subMdlAttrName in subMdlAttr.modelReference.attributes) {
								equal(person1[attrName][i][subMdlAttrName], s[attrName][i][subMdlAttrName]);
							}
						});
						
					} else {
						for (var subMdlAttrName in subMdlAttr.modelReference.attributes) {
							equal(person1[attrName][i][subMdlAttrName], s[attrName][i][subMdlAttrName]);
						}
					}
				}
			}
		});
	});

	test('mad.model.serializer.CakeSerializer : To', function () {
		var persons = testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			var person1 = persons[0],
				Class = testEnv.demo.model.Person,
				s = testEnv.mad.model.serializer.CakeSerializer.to(person1, Class);
			for (var attrName in Class.attributes) {
				if (!Class.isModelAttribute(attrName)) {
					equal(person1[attrName], s['Person'][attrName]);
				} else {
					var subMdlAttr = Class.getAttribute(attrName);
					if (subMdlAttr.multiple) {
						can.each(person1[attrName], function (item, i) {
							for (var subMdlAttrName in subMdlAttr.modelReference.attributes) {
								equal(person1[attrName][i][subMdlAttrName], s[attrName][i][subMdlAttrName]);
							}
						});
						
					} else {
						for (var subMdlAttrName in subMdlAttr.modelReference.attributes) {
							equal(person1[attrName][i][subMdlAttrName], s[attrName][i][subMdlAttrName]);
						}
					}
				}
			}
		});
	});

});

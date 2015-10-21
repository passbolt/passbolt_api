steal('can/util/fixture', 'can/model', 'can/test', 'steal-qunit', function () {
	QUnit.module('can/util/fixture');
	test('static fixtures', function () {
		stop();
		can.fixture('GET something', can.test.path('util/fixture/fixtures/test.json'));
		can.fixture('POST something', can.test.path('util/fixture/fixtures/test.json'));
		can.ajax({
			url: 'something',
			dataType: 'json'
		})
			.done(function (data) {
				equal(data.sweet, 'ness', 'can.get works');
				can.ajax({
					url: 'something',
					method: 'POST',
					dataType: 'json'
				})
					.done(function (data) {
						equal(data.sweet, 'ness', 'can.post works');
						start();
					});
			});
	});
	test('templated static fixtures', function () {
		stop();
		can.fixture('GET some/{id}', can.test.path('util/fixture/fixtures/stuff.{id}.json'));
		can.ajax({
			url: 'some/3',
			dataType: 'json'
		})
			.done(function (data) {
				equal(data.id, 3, 'Got data with proper id');
				start();
			});
	});
	test('dynamic fixtures', function () {
		stop();
		can.fixture.delay = 10;
		can.fixture('something', function () {
			return [{
				sweet: 'ness'
			}];
		});
		can.ajax({
			url: 'something',
			dataType: 'json'
		})
			.done(function (data) {
				equal(data.sweet, 'ness', 'can.get works');
				start();
			});
	});
	test('fixture function', 3, function () {
		stop();
		var url = can.test.path('util/fixture/fixtures/foo.json');
		can.fixture(url, can.test.path('util/fixture/fixtures/foobar.json'));
		can.ajax({
			url: url,
			dataType: 'json'
		})
			.done(function (data) {
				equal(data.sweet, 'ner', 'url passed works');
				can.fixture(url, can.test.path('util/fixture/fixtures/test.json'));
				can.ajax({
					url: url,
					dataType: 'json'
				})
					.done(function (data) {
						equal(data.sweet, 'ness', 'replaced');
						can.fixture(url, null);
						can.ajax({
							url: url,
							dataType: 'json'
						})
							.done(function (data) {
								equal(data.a, 'b', 'removed');
								start();
							});
					});
			});
	});
	// Converters only work with jQuery
	if (typeof jQuery !== 'undefined') {
		test('fixtures with converters', function () {
			stop();
			can.ajax({
				url: can.test.path('util/fixture/fixtures/foobar.json'),
				dataType: 'json fooBar',
				converters: {
					'json fooBar': function (data) {
						// Extract relevant text from the xml document
						return 'Mr. ' + data.name;
					}
				},
				fixture: function () {
					return {
						name: 'Justin'
					};
				},
				success: function (prettyName) {
					start();
					equal(prettyName, 'Mr. Justin');
				}
			});
		});
	}

	test('can.fixture.store fixtures', function () {
		stop();
		can.fixture.store('thing', 1000, function (i) {
			return {
				id: i,
				name: 'thing ' + i
			};
		}, function (item, settings) {
			if (settings.data.searchText) {
				var regex = new RegExp('^' + settings.data.searchText);
				return regex.test(item.name);
			}
		});
		can.ajax({
			url: 'things',
			dataType: 'json',
			data: {
				offset: 100,
				limit: 200,
				order: ['name ASC'],
				searchText: 'thing 2'
			},
			fixture: '-things',
			success: function (things) {
				equal(things.data[0].name, 'thing 29', 'first item is correct');
				equal(things.data.length, 11, 'there are 11 items');
				start();
			}
		});
	});

	test('simulating an error', function () {
		var st = '{type: "unauthorized"}';
		can.fixture('/foo', function (request, response) {
			return response(401, st);
		});
		stop();
		can.ajax({
			url: '/foo',
			dataType: 'json'
		})
			.done(function () {
				ok(false, 'success called');
				start();
			})
			.fail(function (original, type, text) {
				ok(true, 'error called');
				equal(text, st, 'Original text passed');
				start();
			});
	});

	test('rand', function () {
		var rand = can.fixture.rand;
		var num = rand(5);
		equal(typeof num, 'number');
		ok(num >= 0 && num < 5, 'gets a number');
		stop();
		var zero, three, between, next = function () {
				start();
			};
		// make sure rand can be everything we need
		setTimeout(function timer() {
			var res = rand([1, 2, 3]);

			if (res.length === 0) {
				zero = true;
			} else if (res.length === 3) {
				three = true;
			} else {
				between = true;
			}
			if (zero && three && between) {
				ok(true, 'got zero, three, between');
				next();
			} else {
				setTimeout(timer, 10);
			}
		}, 10);
	});

	test('_getData', function () {
		var data = can.fixture._getData('/thingers/{id}', '/thingers/5');
		equal(data.id, 5, 'gets data');
		data = can.fixture._getData('/thingers/5?hi.there', '/thingers/5?hi.there');
		deepEqual(data, {}, 'gets data');
	});

	test('_getData with double character value', function () {
		var data = can.fixture._getData('/days/{id}/time_slots.json', '/days/17/time_slots.json');
		equal(data.id, 17, 'gets data');
	});

	test('_compare', function () {
		var same = can.Object.same({
			url: '/thingers/5'
		}, {
			url: '/thingers/{id}'
		}, can.fixture._compare);
		ok(same, 'they are similar');
		same = can.Object.same({
			url: '/thingers/5'
		}, {
			url: '/thingers'
		}, can.fixture._compare);
		ok(!same, 'they are not the same');
	});

	test('_similar', function () {
		var same = can.fixture._similar({
			url: '/thingers/5'
		}, {
			url: '/thingers/{id}'
		});
		ok(same, 'similar');
		same = can.fixture._similar({
			url: '/thingers/5',
			type: 'get'
		}, {
			url: '/thingers/{id}'
		});
		ok(same, 'similar with extra pops on settings');
		var exact = can.fixture._similar({
			url: '/thingers/5',
			type: 'get'
		}, {
			url: '/thingers/{id}'
		}, true);
		ok(!exact, 'not exact');
		exact = can.fixture._similar({
			url: '/thingers/5'
		}, {
			url: '/thingers/5'
		}, true);
		ok(exact, 'exact');
	});

	test('fixture function gets id', function () {
		can.fixture('/thingers/{id}', function (settings) {
			return {
				id: settings.data.id,
				name: 'justin'
			};
		});
		stop();
		can.ajax({
			url: '/thingers/5',
			dataType: 'json',
			data: {
				id: 5
			}
		})
			.done(function (data) {
				ok(data.id);
				start();
			});
	});

	test('replacing and removing a fixture', function () {
		var url = can.test.path('util/fixture/fixtures/remove.json');
		can.fixture('GET ' + url, function () {
			return {
				weird: 'ness!'
			};
		});
		stop();
		can.ajax({
			url: url,
			dataType: 'json'
		})
			.done(function (json) {
				equal(json.weird, 'ness!', 'fixture set right');
				can.fixture('GET ' + url, function () {
					return {
						weird: 'ness?'
					};
				});
				can.ajax({
					url: url,
					dataType: 'json'
				})
					.done(function (json) {
						equal(json.weird, 'ness?', 'fixture set right');
						can.fixture('GET ' + url, null);
						can.ajax({
							url: url,
							dataType: 'json'
						})
							.done(function (json) {
								equal(json.weird, 'ness', 'fixture set right');
								start();
							});
					});
			});
	});
	
	test('can.fixture.store with can.Model', function () {
		var store = can.fixture.store(100, function (i) {
			return {
				id: i,
				name: 'Object ' + i
			};
		}),
			Model = can.Model({
				findAll: 'GET /models',
				findOne: 'GET /models/{id}',
				create: 'POST /models',
				update: 'PUT /models/{id}',
				destroy: 'DELETE /models/{id}'
			}, {});
		can.fixture('GET /models', store.findAll);
		can.fixture('GET /models/{id}', store.findOne);
		can.fixture('POST /models', store.create);
		can.fixture('PUT /models/{id}', store.update);
		can.fixture('DELETE /models/{id}', store.destroy);
		stop();
		Model.findAll()
			.done(function (models) {
				equal(models.length, 100, 'Got 100 models for findAll with no parameters');
				equal(models[95].name, 'Object 95', 'All models generated properly');
				Model.findOne({
					id: 51
				})
					.done(function (data) {
						equal(data.id, 51, 'Got correct object id');
						equal('Object 51', data.name, 'Object name generated correctly');
						new Model({
							name: 'My test object'
						})
							.save()
							.done(function (newmodel) {
								equal(newmodel.id, 100, 'Id got incremented');
								equal(newmodel.name, 'My test object');
								// Tests creating, deleting, updating
								Model.findOne({
									id: 100
								})
									.done(function (model) {
										equal(model.id, 100, 'Loaded new object');
										model.attr('name', 'Updated test object');
										model.save()
											.done(function (model) {
												equal(model.name, 'Updated test object', 'Successfully updated object');
												model.destroy()
													.done(function (deleted) {
														start();
													});
											});
									});
							});
					});
			});
	});

	test('can.fixture.store returns 404 on findOne with bad id (#803)', function () {
		var store = can.fixture.store(2, function (i) {
			return {
				id: i,
				name: 'Object ' + i
			};
		}),
			Model = can.Model({
				findOne: 'GET /models/{id}'
			}, {});

		can.fixture('GET /models/{id}', store.findOne);
		stop();

		Model.findOne({ id: 3 })
					.fail(function (data, status, statusText) {
						equal(status, 'error', 'Got an error');
						equal(statusText, 'Requested resource not found', 'Got correct status message');
						start();
					});
	});
	
	test('can.fixture.store returns 404 on update with a bad id (#803)', function () {
		var store = can.fixture.store(5, function (i) {
			return {
				id: i,
				name: 'Object ' + i
			};
		}),
			Model = can.Model({
				update: 'POST /models/{id}'
			}, {});

		stop();
		
		can.fixture('POST /models/{id}', store.update);

		Model.update(6, {'jedan': 'dva'})
					.fail(function (data, status, statusText) {
						equal(status, 'error', 'Got an error');
						equal(statusText, 'Requested resource not found', 'Got correct status message');
						start();
					});
	});
	
	test('can.fixture.store returns 404 on destroy with a bad id (#803)', function () {
		var store = can.fixture.store(2, function (i) {
			return {
				id: i,
				name: 'Object ' + i
			};
		}),
			Model = can.Model({
				destroy: 'DELETE /models/{id}'
			}, {});

		stop();
		
		can.fixture('DELETE /models/{id}', store.destroy);

		Model.destroy(6)
					.fail(function (data, status, statusText) {
						equal(status, 'error', 'Got an error');
						equal(statusText, 'Requested resource not found', 'Got correct status message');
						start();
					});
	});

	test('can.fixture.store can use id of different type (#742)', function () {
		var store = can.fixture.store(100, function (i) {
				return {
					id: i,
					parentId: i * 2,
					name: 'Object ' + i
				};
			}),
			Model = can.Model({
				findAll: 'GET /models'
			}, {});
		can.fixture('GET /models', store.findAll);
		stop();
		Model.findAll({ parentId: '4' })
			.done(function (models) {
				equal(models.length, 1, 'Got one model');
				deepEqual(models[0].attr(), { id: 2, parentId: 4, name: 'Object 2' });
				start();
			});
	});

	test('can.fixture with response callback', 4, function () {
		can.fixture.delay = 10;
		can.fixture('responseCb', function (orig, response) {
			response({
				sweet: 'ness'
			});
		});
		can.fixture('responseErrorCb', function (orig, response) {
			response(404, 'This is an error from callback');
		});
		stop();
		can.ajax({
			url: 'responseCb',
			dataType: 'json'
		})
			.done(function (data) {
				equal(data.sweet, 'ness', 'can.get works');
				start();
			});
		stop();
		can.ajax({
			url: 'responseErrorCb',
			dataType: 'json'
		})
			.fail(function (orig, error, text) {
				equal(error, 'error', 'Got error status');
				equal(text, 'This is an error from callback', 'Got error text');
				start();
			});
		stop();
		can.fixture('cbWithTimeout', function (orig, response) {
			setTimeout(function () {
				response([{
					epic: 'ness'
				}]);
			}, 10);
		});
		can.ajax({
			url: 'cbWithTimeout',
			dataType: 'json'
		})
			.done(function (data) {
				equal(data[0].epic, 'ness', 'Got responsen with timeout');
				start();
			});
	});

	test('store create works with an empty array of items', function () {
		var store = can.fixture.store(0, function () {
			return {};
		});
		store.create({
			data: {}
		}, function (responseData, responseHeaders) {
			equal(responseData.id, 0, 'the first id is 0');
		});
	});

	test('store creates sequential ids', function () {
		var store = can.fixture.store(0, function () {
			return {};
		});
		store.create({
			data: {}
		}, function (responseData, responseHeaders) {
			equal(responseData.id, 0, 'the first id is 0');
		});
		store.create({
			data: {}
		}, function (responseData, responseHeaders) {
			equal(responseData.id, 1, 'the second id is 1');
		});
		store.destroy({
			data: {
				id: 0
			}
		});
		store.create({
			data: {}
		}, function (responseData, responseHeaders) {
			equal(responseData.id, 2, 'the third id is 2');
		});
	});

	test('fixture updates request.data with id', function() {
		expect(1);
		stop();


		can.fixture('foo/{id}', function(request) {
			equal(request.data.id, 5);
			start();
		});

		can.ajax({
			url: 'foo/5'
		});
	});
	
	test("create a store with array and comparison object",function(){
		
		var store = can.fixture.store([
			{id: 1, modelId: 1, year: 2013, name: "2013 Mustang", thumb: "http://mustangsdaily.com/blog/wp-content/uploads/2012/07/01-2013-ford-mustang-gt-review-585x388.jpg"},
			{id: 2, modelId: 1, year: 2014, name: "2014 Mustang", thumb: "http://mustangsdaily.com/blog/wp-content/uploads/2013/03/2014-roush-mustang.jpg"},
			{id: 2, modelId: 2, year: 2013, name: "2013 Focus", thumb: "http://images.newcars.com/images/car-pictures/original/2013-Ford-Focus-Sedan-S-4dr-Sedan-Exterior.png"},
			{id: 2, modelId: 2, year: 2014, name: "2014 Focus", thumb: "http://ipinvite.iperceptions.com/Invitations/survey705/images_V2/top4.jpg"},
			{id: 2, modelId: 3, year: 2013, name: "2013 Altima", thumb: "http://www.blogcdn.com/www.autoblog.com/media/2012/04/04-2013-nissan-altima-1333416664.jpg"},
			{id: 2, modelId: 3, year: 2014, name: "2014 Altima", thumb: "http://www.blogcdn.com/www.autoblog.com/media/2012/04/01-2013-nissan-altima-ny.jpg"},
			{id: 2, modelId: 4, year: 2013, name: "2013 Leaf", thumb: "http://www.blogcdn.com/www.autoblog.com/media/2012/04/01-2013-nissan-altima-ny.jpg"},
			{id: 2, modelId: 4, year: 2014, name: "2014 Leaf", thumb: "http://images.thecarconnection.com/med/2013-nissan-leaf_100414473_m.jpg"}
		],{year: 'i'});
		
		
		can.fixture('GET /presetStore', store.findAll);
		stop();
		can.ajax({ url: "/presetStore", method: "get", data: {year: 2013, modelId:1} }).then(function(response){
			
			equal(response.data[0].id, 1, "got the first item");
			equal(response.data.length, 1, "only got one item");
			start();
		});
		
	});

	test("store with objects allows .create, .update and .destroy (#1471)", 6, function(){

		var store = can.fixture.store([
			{id: 1, modelId: 1, year: 2013, name: "2013 Mustang", thumb: "http://mustangsdaily.com/blog/wp-content/uploads/2012/07/01-2013-ford-mustang-gt-review-585x388.jpg"},
			{id: 2, modelId: 1, year: 2014, name: "2014 Mustang", thumb: "http://mustangsdaily.com/blog/wp-content/uploads/2013/03/2014-roush-mustang.jpg"},
			{id: 3, modelId: 2, year: 2013, name: "2013 Focus", thumb: "http://images.newcars.com/images/car-pictures/original/2013-Ford-Focus-Sedan-S-4dr-Sedan-Exterior.png"},
			{id: 4, modelId: 2, year: 2014, name: "2014 Focus", thumb: "http://ipinvite.iperceptions.com/Invitations/survey705/images_V2/top4.jpg"},
			{id: 5, modelId: 3, year: 2013, name: "2013 Altima", thumb: "http://www.blogcdn.com/www.autoblog.com/media/2012/04/04-2013-nissan-altima-1333416664.jpg"},
			{id: 6, modelId: 3, year: 2014, name: "2014 Altima", thumb: "http://www.blogcdn.com/www.autoblog.com/media/2012/04/01-2013-nissan-altima-ny.jpg"},
			{id: 7, modelId: 4, year: 2013, name: "2013 Leaf", thumb: "http://www.blogcdn.com/www.autoblog.com/media/201204/01-2013-nissan-altima-ny.jpg"},
			{id: 8, modelId: 4, year: 2014, name: "2014 Leaf", thumb: "http://images.thecarconnection.com/med/2013-nissan-leaf_100414473_m.jpg"}
		]);


		can.fixture('GET /cars', store.findAll);
		can.fixture('POST /cars', store.create);
		can.fixture('PUT /cars/{id}', store.update);
		can.fixture('DELETE /cars/{id}', store.destroy);

		var Car = can.Model.extend({
			resource: '/cars'
		}, {});

		stop();
		Car.findAll().then(function(cars) {
			equal(cars.length, 8, 'Got all cars');
			return cars[1].destroy();
		}).then(function() {
			return Car.findAll();
		}).then(function(cars) {
			equal(cars.length, 7, 'One car less');
			equal(cars.attr('1.name'), '2013 Focus', 'Car actually deleted');
		}).then(function() {
			var altima = new Car({
				modelId: 3,
				year: 2015,
				name: "2015 Altima"
			});

			return altima.save();
		}).then(function(saved) {
			ok(typeof saved.id !== 'undefined');
			saved.attr('name', '2015 Nissan Altima');
			return saved.save();
		}).then(function(updated) {
			equal(updated.attr('name'), '2015 Nissan Altima');
			return Car.findAll();
		}).then(function (cars) {
			equal(cars.length, 8, 'New car created');
			start();
		});
	});

	test("fixture: false flag circumvents can.fixture", function() {

		can.fixture("GET /thinger/mabobs", function (settings) {
			return {
				thingers: "mabobs"
			};
		});

		stop();
		can.ajax({
			url: "/thinger/mabobs",
			method: "GET",
			fixture: false,
			error: function() {
				ok(true, 'AJAX errors out as expected');
				start();
			}
		});

	});
	
	
});

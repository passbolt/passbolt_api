/* global Person: true */
/* global User: true */
/* global Hero: true */
steal('can/util', 'can/model', 'can/model/queue', 'can/util/fixture', 'can/test', 'steal-qunit', function () {
	QUnit.module('can/model/queue', {
		setup: function () {}
	});
	test('queued requests will not overwrite attrs', function () {
		var delay = can.fixture.delay;
		can.fixture.delay = 1000;
		can.Model.extend('Person', {
			create: function (id, attrs, success, error) {
				return can.ajax({
					url: '/people/' + id,
					data: attrs,
					type: 'post',
					dataType: 'json',
					fixture: function () {
						return {
							name: 'Justin'
						};
					},
					success: success
				});
			}
		}, {});
		var person = new Person({
			name: 'Justin'
		}),
			personD = person.save();
		person.attr('name', 'Brian');
		stop();
		personD.then(function (person) {
			start();
			equal(person.name, 'Brian', 'attrs were not overwritten with the data from the server');
			can.fixture.delay = delay;
		});
	});
	test('error will clean up the queue', 2, function () {
		can.Model('User', {
			create: 'POST /users',
			update: 'PUT /users/{id}'
		}, {});
		can.fixture('POST /users', function (req) {
			return {
				id: 1
			};
		});
		can.fixture('PUT /users/{id}', function (req, respondWith) {
			respondWith(500);
		});
		var u = new User({
			name: 'Goku'
		});
		stop();
		u.save();
		var err = u.save();
		u.save();
		u.save();
		u.save();
		err.fail(function () {
			start();
			equal(u._requestQueue.attr('length'), 4, 'Four requests are in the queue');
			stop();
			u._requestQueue.bind('change', function () {
				start();
				equal(u._requestQueue.attr('length'), 0, 'Request queue was emptied');
			});
		});
	});
	test('backup works as expected', function () {
		can.Model('User', {
			create: 'POST /users',
			update: 'PUT /users/{id}'
		}, {});
		can.fixture('POST /users', function (req) {
			return {
				id: 1,
				name: 'Goku'
			};
		});
		can.fixture('PUT /users/{id}', function (req, respondWith) {
			respondWith(500);
		});
		var u = new User({
			name: 'Goku'
		});
		stop();
		var save = u.save();
		u.attr('name', 'Krillin');
		save.then(function () {
			start();
			equal(u.attr('name'), 'Krillin', 'Name is not overwritten when save is successful');
			stop();
		});
		var err = u.save();
		err.fail(function () {
			u.restore(true);
			start();
			equal(u.attr('name'), 'Goku', 'Name was restored to the last value successfuly returned from the server');
		});
	});
	test('abort will remove requests made after the aborted request', function () {
		can.Model('User', {
			create: 'POST /users',
			update: 'PUT /users/{id}'
		}, {});
		can.fixture('POST /users', function (req) {
			return {
				id: 1,
				name: 'Goku'
			};
		});
		can.fixture('PUT /users/{id}', function (req, respondWith) {
			return req.data;
		});
		var u = new User({
			name: 'Goku'
		});
		u.save();
		u.save();
		var abort = u.save();
		u.save();
		u.save();
		equal(u._requestQueue.attr('length'), 5);
		abort.abort();
		equal(u._requestQueue.attr('length'), 2);
	});
	test('id will be set correctly, although update data is serialized before create is done', function () {
		var delay = can.fixture.delay;
		can.fixture.delay = 1000;
		can.Model('Hero', {
			create: 'POST /superheroes',
			update: 'PUT /superheroes/{id}'
		}, {});
		can.fixture('POST /superheroes', function (req) {
			return {
				id: 'FOOBARBAZ'
			};
		});
		can.fixture('PUT /superheroes/{id}', function (req, respondWith) {
			start();
			equal(req.data.id, 'FOOBARBAZ', 'Correct id is set');
			can.fixture.delay = delay;
			return req.data;
		});
		var u = new Hero({
			name: 'Goku'
		});
		u.save();
		u.save();
		stop();
	});
	test('queue uses serialize (#611)', function () {
		can.fixture('POST /mymodel', function (request) {
			equal(request.data.foo, 'bar');
			start();
		});
		var MyModel = can.Model.extend({
			create: '/mymodel'
		}, {
			serialize: function () {
				return {
					foo: 'bar'
				};
			}
		});
		stop();
		new MyModel()
			.save();
	});
});

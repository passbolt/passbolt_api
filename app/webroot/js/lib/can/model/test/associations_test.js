var MyTest;
module('jquery/model/associations', {
	setup: function () {
		$.Model('MyTest.Person', {
			serialize: function () {
				return 'My name is ' + this.name;
			}
		});
		$.Model('MyTest.Loan');
		$.Model('MyTest.Issue');
		$.Model('MyTest.Customer', {
			attributes: {
				person: 'MyTest.Person.model',
				loans: 'MyTest.Loan.models',
				issues: 'MyTest.Issue.models'
			},
			update: function (id, attrs) {
				return $.ajax({
					url: '/people/' + id,
					data: attrs,
					type: 'post',
					dataType: 'json',
					fixture: function () {
						return [{
							loansAttr: attrs.loans,
							personAttr: attrs.person
						}];
					}
				});
			}
		}, {});
	}
});
test('associations work', function () {
	var c = new MyTest.Customer({
		id: 5,
		person: {
			id: 1,
			name: 'Justin'
		},
		issues: [],
		loans: [{
			amount: 1000,
			id: 2
		}, {
			amount: 19999,
			id: 3
		}]
	});
	equal(c.person.name, 'Justin', 'association present');
	equal(c.person.Class, MyTest.Person, 'belongs to association typed');
	equal(c.issues.length, 0);
	equal(c.loans.length, 2);
	equal(c.loans[0].Class, MyTest.Loan);
});
test('Model association serialize on save', function () {
	var c = new MyTest.Customer({
		id: 5,
		person: {
			id: 1,
			name: 'thecountofzero'
		},
		issues: [],
		loans: []
	}),
		cSave = c.save();
	stop();
	cSave.then(function (customer) {
		start();
		equal(customer.personAttr, 'My name is thecountofzero', 'serialization works');
	});
});
test('Model.List association serialize on save', function () {
	var c = new MyTest.Customer({
		id: 5,
		person: {
			id: 1,
			name: 'thecountofzero'
		},
		issues: [],
		loans: [{
			amount: 1000,
			id: 2
		}, {
			amount: 19999,
			id: 3
		}]
	}),
		cSave = c.save();
	stop();
	cSave.then(function (customer) {
		start();
		ok(true, 'called back');
		equal(customer.loansAttr.constructor, can.List, 'we get an observe list back');
	});
});

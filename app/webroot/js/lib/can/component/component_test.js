steal("can", "can/map/define", "can/component", "can/view/stache" ,"can/route", "steal-qunit", function () {
	
	QUnit.module('can/component', {
		setup: function () {
			can.remove(can.$("#qunit-fixture>*"));
		}
	});

	var Paginate = can.Map.extend({
		count: Infinity,
		offset: 0,
		limit: 100,
		// Prevent negative counts
		setCount: function (newCount, success, error) {
			return newCount < 0 ? 0 : newCount;
		},
		// Prevent negative offsets
		setOffset: function (newOffset) {
			return newOffset < 0 ?
				0 :
				Math.min(newOffset, !isNaN(this.count - 1) ?
					this.count - 1 :
					Infinity);
		},
		// move next
		next: function () {
			this.attr('offset', this.offset + this.limit);
		},
		prev: function () {
			this.attr('offset', this.offset - this.limit);
		},
		canNext: function () {
			return this.attr('offset') < this.attr('count') -
				this.attr('limit');
		},
		canPrev: function () {
			return this.attr('offset') > 0;
		},
		page: function (newVal) {
			if (newVal === undefined) {
				return Math.floor(this.attr('offset') / this.attr('limit')) + 1;
			} else {
				this.attr('offset', (parseInt(newVal) - 1) * this.attr('limit'));
			}
		},
		pageCount: function () {
			return this.attr('count') ?
				Math.ceil(this.attr('count') / this.attr('limit')) : null;
		}
	});

	test("basic tabs", function () {

		// new Tabs() .. 
		can.Component.extend({
			tag: "tabs",
			template: "<ul>" +
				"{{#panels}}" +
				"<li {{#isActive}}class='active'{{/isActive}} can-click='makeActive'>{{title}}</li>" +
				"{{/panels}}" +
				"</ul>" +
				"<content></content>",
			viewModel: {
				panels: [],
				addPanel: function (panel) {

					if (this.attr("panels")
						.length === 0) {
						this.makeActive(panel);
					}
					this.attr("panels")
						.push(panel);
				},
				removePanel: function (panel) {
					var panels = this.attr("panels");
					can.batch.start();
					panels.splice(panels.indexOf(panel), 1);
					if (panel === this.attr("active")) {
						if (panels.length) {
							this.makeActive(panels[0]);
						} else {
							this.removeAttr("active");
						}
					}
					can.batch.stop();
				},
				makeActive: function (panel) {
					this.attr("active", panel);
					this.attr("panels")
						.each(function (panel) {
							panel.attr("active", false);
						});
					panel.attr("active", true);

				},
				// this is viewModel, not mustache
				// consider removing viewModel as arg
				isActive: function (panel) {
					return this.attr('active') === panel;
				}
			}
		});
		can.Component.extend({
			// make sure <content/> works
			template: "{{#if active}}<content></content>{{/if}}",
			tag: "panel",
			viewModel: {
				active: false,
				title: "@"
			},
			events: {
				" inserted": function () {
					can.viewModel(this.element[0].parentNode)
						.addPanel(this.viewModel);
				},
				" removed": function () {
					if (!can.viewModel(this.element[0].parentNode)) {
						console.log("bruke");
					}
					can.viewModel(this.element[0].parentNode)
						.removePanel(this.viewModel);
				}
			}
		});

		var template = can.view.mustache("<tabs>{{#each foodTypes}}<panel title='{{title}}'>{{content}}</panel>{{/each}}</tabs>");

		var foodTypes = new can.List([{
			title: "Fruits",
			content: "oranges, apples"
		}, {
			title: "Breads",
			content: "pasta, cereal"
		}, {
			title: "Sweets",
			content: "ice cream, candy"
		}]);

		can.append(can.$("#qunit-fixture"), template({
			foodTypes: foodTypes
		}));

		var testArea = can.$("#qunit-fixture")[0],
			lis = testArea.getElementsByTagName("li");
		equal(lis.length, 3, "three lis added");

		foodTypes.each(function (type, i) {
			equal(lis[i].innerHTML, type.attr("title"), "li " + i + " has the right content");
		});

		foodTypes.push({
			title: "Vegies",
			content: "carrots, kale"
		});

		//lis = testArea.getElementsByTagName("li");
		equal(lis.length, 4, "li added");

		foodTypes.each(function (type, i) {
			equal(lis[i].innerHTML, type.attr("title"), "li " + i + " has the right content");
		});

		equal(testArea.getElementsByTagName("panel")
			.length, 4, "panel added");

		foodTypes.shift();

		equal(lis.length, 3, "removed li after shifting a foodType");

		foodTypes.each(function (type, i) {
			equal(lis[i].innerHTML, type.attr("title"), "li " + i + " has the right content");
		});

		// test changing the active element
		var panels = testArea.getElementsByTagName("panel");

		equal(lis[0].className, "active", "the first element is active");
		equal(panels[0].innerHTML, "pasta, cereal", "the first content is shown");
		equal(panels[1].innerHTML, "", "the second content is removed");

		can.trigger(lis[1], "click");

		equal(lis[1].className, "active", "the second element is active");
		equal(lis[0].className, "", "the first element is not active");

		equal(panels[0].innerHTML, "", "the second content is removed");
		equal(panels[1].innerHTML, "ice cream, candy", "the second content is shown");

		can.remove(can.$("#qunit-fixture>*"));
	});

	test("lexical scoping", function() {
		can.Component.extend({
			tag: "hello-world",
			leakScope: false,
			template: can.view.mustache("{{greeting}} <content>World</content>{{exclamation}}"),
			viewModel: {greeting: "Hello"}
		});
		var template = can.view.mustache("<hello-world>{{greeting}}</hello-world>");
		can.append(can.$("#qunit-fixture"), template({
			greeting: "World",
			exclamation: "!"
		}));
		var hello = can.$("#qunit-fixture hello-world");
		equal(can.trim(hello[0].innerHTML), "Hello World");
		can.remove(can.$("#qunit-fixture > *"));

		can.Component.extend({
			tag: "hello-world-no-template",
			leakScope: false,
			viewModel: {greeting: "Hello"}
		});
		template = can.view.mustache("<hello-world-no-template>{{greeting}}</hello-world-no-template>");
		can.append(can.$("#qunit-fixture"), template({
			greeting: "World",
			exclamation: "!"
		}));
		hello = can.$("#qunit-fixture hello-world-no-template");
		equal(can.trim(hello[0].innerHTML), "Hello",
			  "If no template is provided to can.Component, treat <content> bindings as dynamic.");
		can.remove(can.$("#qunit-fixture > *"));
	});

	test("dynamic scoping", function() {
		can.Component.extend({
			tag: "hello-world",
			leakScope: true,
			template: can.view.mustache("{{greeting}} <content>World</content>{{exclamation}}"),
			viewModel: {greeting: "Hello"}
		});
		var template = can.view.mustache("<hello-world>{{greeting}}</hello-world>");
		can.append(can.$("#qunit-fixture"), template({
			greeting: "World",
			exclamation: "!"
		}));
		var hello = can.$("#qunit-fixture hello-world");
		equal(can.trim(hello[0].innerHTML), "Hello Hello!");
		can.remove(can.$("#qunit-fixture > *"));
	});

	test("treecombo", function () {

		can.Component.extend({
			tag: "treecombo",
			template: "<ul class='breadcrumb'>" +
				"<li can-click='emptyBreadcrumb'>{{title}}</li>" +
				"{{#each breadcrumb}}" +
				"<li can-click='updateBreadcrumb'>{{title}}</li>" +
				"{{/each}}" +
				"</ul>" +
				"<ul class='options'>" +
				"<content>" +
				"{{#selectableItems}}" +
				"<li {{#isSelected}}class='active'{{/isSelected}} can-click='toggle'>" +
				"<input type='checkbox' {{#isSelected}}checked{{/isSelected}}/>" +
				"{{title}}" +
				"{{#if children.length}}" +
				"<button class='showChildren' can-click='showChildren'>+</button>" +
				"{{/if}}" +
				"</li>" +
				"{{/selectableItems}}" +
				"</content>" +
				"</ul>",
			viewModel: {
				items: [],
				breadcrumb: [],
				selected: [],
				title: "@",
				selectableItems: function () {
					var breadcrumb = this.attr("breadcrumb");

					// if there's an item in the breadcrumb
					if (breadcrumb.attr('length')) {

						// return the last item's children
						return breadcrumb.attr("" + (breadcrumb.length - 1) + '.children');
					} else {

						// return the top list of items
						return this.attr('items');
					}
				},
				showChildren: function (item, el, ev) {
					ev.stopPropagation();
					this.attr('breadcrumb')
						.push(item);
				},
				emptyBreadcrumb: function () {
					this.attr("breadcrumb")
						.attr([], true);
				},
				updateBreadcrumb: function (item) {
					var breadcrumb = this.attr("breadcrumb"),
						index = breadcrumb.indexOf(item);
					breadcrumb.splice(index + 1, breadcrumb.length - index - 1);
				},
				toggle: function (item) {
					var selected = this.attr('selected'),
						index = selected.indexOf(item);
					if (index === -1) {
						selected.push(item);
					} else {
						selected.splice(index, 1);
					}
				}
			},
			helpers: {
				isSelected: function (options) {
					if (this.attr("selected")
						.indexOf(options.context) > -1) {
						return options.fn();
					} else {
						return options.inverse();
					}
				}
			}
		});

		var template = can.view.mustache("<div><treecombo items='locations' title='Locations'></treecombo></div>");

		var base = new can.Map({});

		can.append(can.$("#qunit-fixture"), template(base));

		var items = [{
			id: 1,
			title: "Midwest",
			children: [{
				id: 5,
				title: "Illinois",
				children: [{
					id: 23423,
					title: "Chicago"
				}, {
					id: 4563,
					title: "Springfield"
				}, {
					id: 4564,
					title: "Naperville"
				}]
			}, {
				id: 6,
				title: "Wisconsin",
				children: [{
					id: 232423,
					title: "Milwaulkee"
				}, {
					id: 45463,
					title: "Green Bay"
				}, {
					id: 45464,
					title: "Madison"
				}]
			}]
		}, {
			id: 2,
			title: "East Coast",
			children: [{
				id: 25,
				title: "New York",
				children: [{
					id: 3413,
					title: "New York"
				}, {
					id: 4613,
					title: "Rochester"
				}, {
					id: 4516,
					title: "Syracuse"
				}]
			}, {
				id: 6,
				title: "Pennsylvania",
				children: [{
					id: 2362423,
					title: "Philadelphia"
				}, {
					id: 454663,
					title: "Harrisburg"
				}, {
					id: 454664,
					title: "Scranton"
				}]
			}]
		}];

		stop();

		setTimeout(function () {

			base.attr('locations', items);

			var itemsList = base.attr('locations');

			// check that the DOM is right
			var treecombo = can.$("#qunit-fixture treecombo")[0],
				breadcrumb = can.$("#qunit-fixture .breadcrumb")[0],
				breadcrumbLIs = breadcrumb.getElementsByTagName('li'),
				options = can.$("#qunit-fixture .options")[0],
				optionsLis = options.getElementsByTagName('li');

			equal(breadcrumbLIs.length, 1, "Only the default title is shown");
			equal(breadcrumbLIs[0].innerHTML, "Locations", "The correct title from the attribute is shown");

			equal(optionsLis.length, itemsList.length, "first level items are displayed");

			// Test toggling selected, first by clicking
			can.trigger(optionsLis[0], "click");

			equal(optionsLis[0].className, "active", "toggling something not selected adds active");

			ok(optionsLis[0].getElementsByTagName('input')[0].checked, "toggling something not selected checks checkbox");
			equal(can.viewModel(treecombo, "selected")
				.length, 1, "there is one selected item");
			equal(can.viewModel(treecombo, "selected.0"), itemsList.attr("0"), "the midwest is in selected");

			// adjust the state and everything should update
			can.viewModel(treecombo, "selected")
				.pop();
			equal(optionsLis[0].className, "", "toggling something not selected adds active");

			// Test going in a location
			can.trigger(optionsLis[0].getElementsByTagName('button')[0], "click");
			equal(breadcrumbLIs.length, 2, "Only the default title is shown");
			equal(breadcrumbLIs[1].innerHTML, "Midwest", "The breadcrumb has an item in it");
			ok(/Illinois/.test(optionsLis[0].innerHTML), "A child of the top breadcrumb is displayed");

			// Test going in a location without children
			can.trigger(optionsLis[0].getElementsByTagName('button')[0], "click");
			ok(/Chicago/.test(optionsLis[0].innerHTML), "A child of the top breadcrumb is displayed");
			ok(!optionsLis[0].getElementsByTagName('button')
				.length, "no show children button");

			// Test poping off breadcrumb
			can.trigger(breadcrumbLIs[1], "click");
			equal(breadcrumbLIs[1].innerHTML, "Midwest", "The breadcrumb has an item in it");
			ok(/Illinois/.test(optionsLis[0].innerHTML), "A child of the top breadcrumb is displayed");

			// Test removing everything
			can.trigger(breadcrumbLIs[0], "click");
			equal(breadcrumbLIs.length, 1, "Only the default title is shown");
			equal(breadcrumbLIs[0].innerHTML, "Locations", "The correct title from the attribute is shown");

			start();

		}, 100);

	});

	test("deferred grid", function () {

		// This test simulates a grid that reads a `deferreddata` property for 
		// items and displays them.
		// If `deferreddata` is a deferred, it waits for those items to resolve.
		// The grid also has a `waiting` property that is true while the deferred is being resolved.

		can.Component.extend({
			tag: "grid",
			viewModel: {
				items: [],
				waiting: true
			},
			template: "<table><tbody><content></content></tbody></table>",
			events: {
				init: function () {
					this.update();
				},
				"{viewModel} deferreddata": "update",
				update: function () {
					var deferred = this.viewModel.attr('deferreddata'),
						viewModel = this.viewModel;

					if (can.isDeferred(deferred)) {
						this.viewModel.attr("waiting", true);
						deferred.then(function (items) {
							viewModel.attr('items')
								.attr(items, true);
						});
					} else {
						viewModel.attr('items')
							.attr(deferred, true);
					}
				},
				"{items} change": function () {
					this.viewModel.attr("waiting", false);
				}
			}
		});

		// The context object has a `set` property and a
		// deferredData property that reads from it and returns a new deferred.
		var SimulatedScope = can.Map.extend({
			set: 0,
			deferredData: function () {

				var deferred = new can.Deferred();
				var set = this.attr('set');
				if (set === 0) {
					setTimeout(function () {
						deferred.resolve([{
							first: "Justin",
							last: "Meyer"
						}]);
					}, 100);
				} else if (set === 1) {
					setTimeout(function () {
						deferred.resolve([{
							first: "Brian",
							last: "Moschel"
						}]);
					}, 100);
				}
				return deferred;
			}
		});
		var viewModel = new SimulatedScope();

		var template = can.view.mustache("<grid deferreddata='viewModel.deferredData'>" +
			"{{#each items}}" +
			"<tr>" +
			"<td width='40%'>{{first}}</td>" +
			"<td width='70%'>{{last}}</td>" +
			"</tr>" +
			"{{/each}}" +
			"</grid>");

		can.append(can.$("#qunit-fixture"), template({
			viewModel: viewModel
		}));

		var gridScope = can.viewModel("#qunit-fixture grid");
		
		equal(gridScope.attr("waiting"), true, "The grid is initially waiting on the deferreddata to resolve");
		
		stop();

		var waitingHandler = function() {
			gridScope.unbind('waiting', waitingHandler);

			setTimeout(function () {
				var tds = can.$("#qunit-fixture td");
				equal(tds.length, 2, "there are 2 tds");

				gridScope.bind("waiting", function (ev, newVal) {
					if (newVal === false) {
						setTimeout(function () {
							equal(tds[0].innerHTML, "Brian", "td changed to brian");
							start();
						}, 10);

					}
				});
				
				// update set to change the deferred.
				viewModel.attr("set", 1);

			}, 10);
		};
		
		gridScope.bind('waiting', waitingHandler);
	});

	test("nextprev", function () {

		can.Component.extend({
			tag: "next-prev",
			template: '<a href="javascript://"' +
				'class="prev {{#paginate.canPrev}}enabled{{/paginate.canPrev}}" can-click="paginate.prev">Prev</a>' +
				'<a href="javascript://"' +
				'class="next {{#paginate.canNext}}enabled{{/paginate.canNext}}" can-click="paginate.next">Next</a>'
		});

		var paginator = new Paginate({
			limit: 20,
			offset: 0,
			count: 100
		});
		var template = can.view.mustache("<next-prev paginate='paginator'></next-prev>");

		var frag = template({
			paginator: paginator
		});

		can.append(can.$("#qunit-fixture"), frag);

		var prev = can.$("#qunit-fixture .prev")[0],
			next = can.$("#qunit-fixture .next")[0];

		ok(!/enabled/.test(prev.className), "prev is not enabled");
		ok(/enabled/.test(next.className), "next is  enabled");

		can.trigger(next, "click");
		ok(/enabled/.test(prev.className), "prev is enabled");
	});

	test("page-count", function () {

		can.Component.extend({
			tag: "page-count",
			template: 'Page <span>{{page}}</span>.'
		});

		var paginator = new Paginate({
			limit: 20,
			offset: 0,
			count: 100
		});

		var template = can.view.mustache("<page-count page='paginator.page'></page-count>");

		can.append(can.$("#qunit-fixture"), template(new can.Map({
			paginator: paginator
		})));

		var spans = can.$("#qunit-fixture span");
		equal(spans[0].innerHTML, "1");
		paginator.next();
		equal(spans[0].innerHTML, "2");
		paginator.next();
		equal(spans[0].innerHTML, "3");

	});

	test("hello-world and whitespace around custom elements", function () {

		can.Component.extend({
			tag: "hello-world",
			template: "{{#if visible}}{{message}}{{else}}Click me{{/if}}",
			viewModel: {
				visible: false,
				message: "Hello There!"
			},
			events: {
				click: function () {
					this.viewModel.attr("visible", true);
				}
			}
		});

		var template = can.view.mustache("  <hello-world></hello-world>  ");

		can.append(can.$("#qunit-fixture"), template({}));

		can.trigger(can.$("#qunit-fixture hello-world"), "click");

		equal(can.$("#qunit-fixture hello-world")[0].innerHTML, "Hello There!");

	});

	test("self closing content tags", function () {

		can.Component({
			"tag": "my-greeting",
			template: "<h1><content/></h1>",
			viewModel: {
				title: "can.Component"
			}
		});

		var template = can.view.mustache("<my-greeting><span>{{site}} - {{title}}</span></my-greeting>");

		can.append(can.$("#qunit-fixture"), template({
			site: "CanJS"
		}));

		equal(can.$("#qunit-fixture span")
			.length, 1, "there is an h1");
	});

	test("can.viewModel utility", function() {
		can.Component({
			tag: "my-taggy-tag",
			template: "<h1>hello</h1>",
			viewModel: {
				foo: "bar"
			}
		});
		can.append(can.$("#qunit-fixture"),
				   can.view.mustache("<my-taggy-tag id='x'></my-taggy-tag>")());
		var el = can.$("my-taggy-tag");
		equal(can.viewModel(el), can.data(el, "viewModel"), "one argument grabs the viewModel object");
		equal(can.viewModel(el, "foo"), "bar", "two arguments fetches a value");
		can.viewModel(el, "foo", "baz");
		equal(can.viewModel(el, "foo"), "baz", "Three arguments sets the value");
		if (window.$ && $.fn) {
			el = $("my-taggy-tag");
			equal(el.viewModel(), can.data(el, "viewModel"), "jQuery helper grabs the viewModel object");
			equal(el.viewModel("foo"), "baz", "jQuery helper with one argument fetches a property");
			equal(el.viewModel("foo", "bar").get(0), el.get(0), "jQuery helper returns the element");
			equal(el.viewModel("foo"), "bar", "jQuery helper with two arguments sets the property");
		}
	});

	test("can.viewModel backwards compatible with can.scope", function() {
		equal(can.viewModel, can.scope, "can helper");
		if (window.$ && $.fn) {
			equal($.scope, $.viewModel, "jQuery helper");
		}
	});

	test("can.viewModel creates one if it doesn't exist", function(){
		can.append(can.$("#qunit-fixture"), can.view.mustache("<div id='me'></div>")());
		var el = can.$("#me");
		var viewModel = can.viewModel(el);
		ok(!!viewModel, "viewModel created where it didn't exist.");
		equal(viewModel, can.data(el, "viewModel"), "viewModel is in the data.");
	});

	test('setting passed variables - two way binding', function () {
		can.Component({
			tag: "my-toggler",
			template: "{{#if visible}}<content/>{{/if}}",
			viewModel: {
				visible: true,
				show: function () {
					this.attr('visible', true);
				},
				hide: function () {
					this.attr("visible", false);
				}
			}
		});

		can.Component({
			tag: "my-app",
			viewModel: {
				visible: true,
				show: function () {
					this.attr('visible', true);
				}
			}
		});
		var template = can.view.mustache("<my-app>" +
			'{{^visible}}<button can-click="show">show</button>{{/visible}}' +
			'<my-toggler visible="visible">' +
			'content' +
			'<button can-click="hide">hide</button>' +
			'</my-toggler>' +
			'</my-app>');

		can.append(can.$("#qunit-fixture"), template({}));

		var testArea = can.$("#qunit-fixture")[0],
			buttons = testArea.getElementsByTagName("button");

		equal(buttons.length, 1, "there is one button");
		equal(buttons[0].innerHTML, "hide", "the button's text is hide");

		can.trigger(buttons[0], "click");

		equal(buttons.length, 1, "there is one button");
		equal(buttons[0].innerHTML, "show", "the button's text is show");

		can.trigger(buttons[0], "click");
		equal(buttons.length, 1, "there is one button");
		equal(buttons[0].innerHTML, "hide", "the button's text is hide");
	});

	test("helpers reference the correct instance (#515)", function () {
		expect(2);
		can.Component({
			tag: 'my-text',
			template: '<p>{{valueHelper}}</p>',
			viewModel: {
				value: '@'
			},
			helpers: {
				valueHelper: function () {
					return this.attr('value');
				}
			}
		});

		var template = can.view.mustache('<my-text value="value1"></my-text><my-text value="value2"></my-text>');

		can.append(can.$('#qunit-fixture'), template({}));

		var testArea = can.$("#qunit-fixture")[0],
			myTexts = testArea.getElementsByTagName("my-text");

		equal(myTexts[0].children[0].innerHTML, 'value1');
		equal(myTexts[1].children[0].innerHTML, 'value2');
	});

	test('access hypenated attributes via camelCase or hypenated', function () {
		can.Component({
			tag: 'hyphen',
			viewModel: {
				'camelCase': '@'
			},
			template: '<p>{{valueHelper}}</p>',
			helpers: {
				valueHelper: function () {
					return this.attr('camelCase');
				}
			}
		});

		var template = can.view.mustache('<hyphen camel-case="value1"></hyphen>');

		can.append(can.$('#qunit-fixture'), template({}));

		var testArea = can.$("#qunit-fixture")[0],
			hyphen = testArea.getElementsByTagName("hyphen");

		equal(hyphen[0].children[0].innerHTML, 'value1');

	});

	test("a map as viewModel", function () {

		var me = new can.Map({
			name: "Justin"
		});

		can.Component.extend({
			tag: 'my-viewmodel',
			template: "{{name}}}",
			viewModel: me
		});

		var template = can.view.mustache('<my-viewmodel></my-viewmodel>');
		equal(template()
			.childNodes[0].innerHTML, "Justin");

	});

	test("content in a list", function () {
		var template = can.view.mustache('<my-list>{{name}}</my-list>');

		can.Component.extend({
			tag: "my-list",
			template: "{{#each items}}<li><content/></li>{{/each}}",
			viewModel: {
				items: new can.List([{
					name: "one"
				}, {
					name: "two"
				}])
			}
		});

		var lis = template()
			.childNodes[0].getElementsByTagName("li");

		equal(lis[0].innerHTML, "one", "first li has correct content");
		equal(lis[1].innerHTML, "two", "second li has correct content");

	});

	test("don't update computes unnecessarily", function () {
		var sourceAge = 30,
			timesComputeIsCalled = 0;
		var age = can.compute(function (newVal) {
			timesComputeIsCalled++;
			if (timesComputeIsCalled === 1) {
				ok(true, "reading initial value to set as years");
			} else if (timesComputeIsCalled === 2) {
				equal(newVal, 31, "updating value to 31");
			} else if (timesComputeIsCalled === 3) {
				ok(true, "called back another time after set to get the value");
			} else {
				ok(false, "You've called the callback " + timesComputeIsCalled + " times");
			}

			if (arguments.length) {
				sourceAge = newVal;
			} else {
				return sourceAge;
			}
		});

		can.Component.extend({
			tag: "age-er"
		});

		var template = can.view.mustache("<age-er years='age'></age-er>");

		template({
			age: age
		});

		age(31);

	});

	test("component does not respect can.compute passed via attributes (#540)", function () {

		var data = {
			compute: can.compute(30)
		};

		can.Component.extend({
			tag: "my-component",
			template: "<span>{{blocks}}</span>"
		});

		var template = can.view.mustache("<my-component blocks='compute'></my-component>");

		var frag = template(data);

		equal(frag.childNodes[0].childNodes[0].innerHTML, "30");

	});

	test("defined view models (#563)", function () {

		var HelloWorldModel = can.Map.extend({
			visible: true,
			toggle: function () {
				this.attr("visible", !this.attr("visible"));
			}
		});

		can.Component.extend({
			tag: "my-helloworld",
			template: "<h1>{{#if visible}}visible{{else}}invisible{{/if}}</h1>",
			viewModel: HelloWorldModel
		});

		var template = can.view.mustache("<my-helloworld></my-helloworld>");

		var frag = template({});

		equal(frag.childNodes[0].childNodes[0].innerHTML, "visible");
	});

	test("viewModel not rebound correctly (#550)", function () {

		var nameChanges = 0;

		can.Component.extend({
			tag: "viewmodel-rebinder",
			events: {
				"{name} change": function () {
					nameChanges++;
				}
			}
		});

		var template = can.view.mustache("<viewmodel-rebinder></viewmodel-rebinder>");

		var frag = template();
		var viewModel = can.viewModel(can.$(frag.childNodes[0]));

		var n1 = can.compute(),
			n2 = can.compute();

		viewModel.attr("name", n1);
		
		n1("updated");
		
		viewModel.attr("name", n2);
		
		n2("updated");
		
		
		equal(nameChanges, 2);
	});

	test("content extension stack overflow error", function () {

		can.Component({
			tag: 'outer-tag',
			template: '<inner-tag>inner-tag CONTENT <content/></inner-tag>'
		});

		can.Component({
			tag: 'inner-tag',
			template: 'inner-tag TEMPLATE <content/>'
		});

		// currently causes Maximum call stack size exceeded
		var template = can.view.mustache("<outer-tag>outer-tag CONTENT</outer-tag>");

		// RESULT = <outer-tag><inner-tag>inner-tag TEMPLATE inner-tag CONTENT outer-tag CONTENT</inner-tag></outer-tag>

		var frag = template();

		equal(frag.childNodes[0].childNodes[0].innerHTML, 'inner-tag TEMPLATE inner-tag CONTENT outer-tag CONTENT');

	});

	test("inserted event fires twice if component inside live binding block", function () {

		var inited = 0,
			inserted = 0;

		can.Component({
			tag: 'child-tag',

			viewModel: {
				init: function () {
					inited++;
				}
			},
			events: {
				' inserted': function () {
					inserted++;
				}
			}
		});

		can.Component({
			tag: 'parent-tag',

			template: '{{#shown}}<child-tag></child-tag>{{/shown}}',

			viewModel: {
				shown: false
			},
			events: {
				' inserted': function () {
					this.viewModel.attr('shown', true);
				}
			}
		});

		var frag = can.view.mustache("<parent-tag></parent-tag>")({});

		can.append(can.$("#qunit-fixture"), frag);

		equal(inited, 1);
		equal(inserted, 1);

	});

	test("Scope as Map constructors should follow '@' default values (#657)", function () {
		var PanelViewModel = can.Map.extend({
			title: "@"
		});

		can.Component.extend({
			tag: "panel",
			viewModel: PanelViewModel
		});

		var frag = can.view.mustache('<panel title="Libraries">Content</panel>')({
			title: "hello"
		});
		can.append(can.$("#qunit-fixture"), frag);

		equal(can.viewModel(can.$("panel")[0])
			.attr("title"), "Libraries");
	});

	test("@ keeps properties live now", function () {

		can.Component.extend({
			tag: "attr-fun",
			template: "<h1>{{fullName}}</h1>",
			viewModel: {
				firstName: "@",
				lastName: "@",
				fullName: function () {
					return this.attr("firstName") + " " + this.attr("lastName");
				}
			}
		});

		var frag = can.view.mustache("<attr-fun first-name='Justin' last-name='Meyer'></attr-fun>")();

		var attrFun = frag.childNodes[0];

		can.$("#qunit-fixture")[0].appendChild(attrFun);

		equal(attrFun.childNodes[0].innerHTML, "Justin Meyer");

		can.attr.set(attrFun, "first-name", "Brian");

		stop();

		setTimeout(function () {
			equal(attrFun.childNodes[0].innerHTML, "Brian Meyer");
			can.remove(can.$(attrFun));
			start();
		}, 100);

	});

	test("id, class, and dataViewId should be ignored (#694)", function () {
		can.Component.extend({
			tag: "stay-classy",
			viewModel: {
				notid: "foo",
				notclass: 5,
				notdataviewid: {}
			}
		});

		var data = {
			idFromData: "id-success",
			classFromData: "class-success",
			dviFromData: "dvi-success"
		};
		var frag = can.view.mustache(
			"<stay-classy id='an-id' notid='idFromData'" +
			" class='a-class' notclass='classFromData'" +
			" notdataviewid='dviFromData'></stay-classy>")(data);
		can.append(can.$("#qunit-fixture"), frag);

		var viewModel = can.viewModel(can.$("stay-classy")[0]);

		equal(viewModel.attr("id"), undefined);
		equal(viewModel.attr("notid"), "id-success");
		equal(viewModel.attr("class"), undefined);
		equal(viewModel.attr("notclass"), "class-success");
		equal(viewModel.attr("dataViewId"), undefined);
		equal(viewModel.attr("notdataviewid"), "dvi-success");
	});

	test("Component can-click method should be not called while component's init", function () {

		var called = false;

		can.Component.extend({
			tag: "child-tag"
		});

		can.Component.extend({
			tag: "parent-tag",
			template: '<child-tag can-click="method"></child-tag>',
			viewModel: {
				method: function () {
					called = true;
				}
			}
		});

		can.view.mustache('<parent-tag></parent-tag>')();

		equal(called, false);
	});
	

	test('Same component tag nested', function () {
		can.Component({
			'tag': 'my-tag',
			template: '<p><content/></p>'
		});
		//simplest case
		var template = can.view.mustache('<my-tag>Outter<my-tag>Inner</my-tag></my-tag>');
		//complex case
		var template2 = can.view.mustache('<my-tag>3<my-tag>2<my-tag>1<my-tag>0</my-tag></my-tag></my-tag></my-tag>');
		//edge case for new logic (same custom tag at same depth as one previously encountered)
		var template3 = can.view.mustache('<my-tag>First</my-tag><my-tag>Second</my-tag>');
		can.append(can.$('#qunit-fixture'), template({}));
		equal(can.$('#qunit-fixture p').length, 2, 'proper number of p tags');
		can.append(can.$('#qunit-fixture'), template2({}));
		equal(can.$('#qunit-fixture p').length, 6, 'proper number of p tags');
		can.append(can.$('#qunit-fixture'), template3({}));
		equal(can.$('#qunit-fixture p').length, 8, 'proper number of p tags');

	});

	test("Component events bind to window", function(){
		window.tempMap = new can.Map();
		
		can.Component.extend({
			tag: "window-events",
			events: {
				"{tempMap} prop": function(){
					ok(true, "called templated event");
				}
			}
		});
		
		var template = can.view.mustache('<window-events></window-events>');
		
		template();
		
		window.tempMap.attr("prop","value");
		
		// IE 6-8 throws an error when deleting globals created via assignment:
		// http://perfectionkills.com/understanding-delete/#ie_bugs
		window.tempMap = undefined;
		try{
			delete window.tempMap;
		} catch(e) {}
		
	});

	test('Same component tag nested', function () {
		can.Component({
			'tag': 'my-tag',
			template: '<p><content/></p>'
		});
		//simplest case
		var template = can.view.mustache('<my-tag>Outter<my-tag>Inner</my-tag></my-tag>');
		//complex case
		var template2 = can.view.mustache('<my-tag>3<my-tag>2<my-tag>1<my-tag>0</my-tag></my-tag></my-tag></my-tag>');
		//edge case for new logic (same custom tag at same depth as one previously encountered)
		var template3 = can.view.mustache('<my-tag>First</my-tag><my-tag>Second</my-tag>');
		can.append(can.$('#qunit-fixture'), template({}));
		equal(can.$('#qunit-fixture p').length, 2, 'proper number of p tags');
		can.append(can.$('#qunit-fixture'), template2({}));
		equal(can.$('#qunit-fixture p').length, 6, 'proper number of p tags');
		can.append(can.$('#qunit-fixture'), template3({}));
		equal(can.$('#qunit-fixture p').length, 8, 'proper number of p tags');

	});
	
	asyncTest('stache integration', function(){

		can.Component.extend({
			tag: 'my-tagged',
			template: '{{p1}},{{p2.val}},{{p3}},{{p4}}'
		});
		
		var stache = can.stache("<my-tagged p1='v1' p2='{v2}' p3='{{v3}}'></my-tagged>");
		var mustache = can.mustache("<my-tagged p1='v1' p2='{v2}' p3='{{v3}}'></my-tagged>");
		
		var data = new can.Map({
			v1: "value1",
			v2: {val: "value2"},
			v3: "value3",
			value3: "value 3",
			VALUE3: "VALUE 3"
		});
		
		var stacheFrag = stache(data),
			stacheResult = stacheFrag.childNodes[0].innerHTML.split(",");
			
		var mustacheFrag = mustache(data),
			mustacheResult = mustacheFrag.childNodes[0].innerHTML.split(",");
		
		equal(stacheResult[0], "v1", "stache uses attribute values");
		equal(stacheResult[1], "value2", "stache single {} cross binds value");
		equal(stacheResult[2], "value3", "stache  {{}} cross binds attribute");
		
		equal(mustacheResult[0], "value1", "mustache looks up attribute values");
		equal(mustacheResult[1], "value2", "mustache single {} cross binds value");
		equal(mustacheResult[2], "value 3", "mustache  {{}} cross binds string value");
		
		data.attr("v1","VALUE1");
		data.attr("v2",new can.Map({val: "VALUE 2"}));
		data.attr("v3","VALUE3");
		can.attr.set( stacheFrag.childNodes[0],"p4","value4");
		
		stacheResult = stacheFrag.childNodes[0].innerHTML.split(",");
		mustacheResult = mustacheFrag.childNodes[0].innerHTML.split(",");
		
		equal(stacheResult[0], "v1", "stache uses attribute values so it should not change");
		equal(mustacheResult[0], "VALUE1", "mustache looks up attribute values and updates immediately");
		equal(stacheResult[1], "VALUE 2", "stache single {} cross binds value and updates immediately");
		equal(mustacheResult[1], "VALUE 2", "mustache single {} cross binds value and updates immediately");
		
		equal(stacheResult[2], "value3", "stache {{}} cross binds attribute changes so it wont be updated immediately");
		
		setTimeout(function(){
			
			stacheResult = stacheFrag.childNodes[0].innerHTML.split(",");
			mustacheResult = mustacheFrag.childNodes[0].innerHTML.split(",");
			equal(stacheResult[2], "VALUE3", "stache  {{}} cross binds attribute");
			equal(mustacheResult[2], "value 3", "mustache sticks with old value even though property has changed");
			
			equal(stacheResult[3], "value4", "stache sees new attributes");
			
			start();
		},20);

	});

	test("can.Construct are passed normally", function(){
		var Constructed = can.Construct.extend({foo:"bar"},{});
		
		can.Component.extend({
			tag: "con-struct",
			template: "{{con.foo}}"
		});
		
		var stached = can.stache("<con-struct con='{Constructed}'></con-struct>");
		
		var res = stached({
			Constructed: Constructed
		});
		equal(res.childNodes[0].innerHTML, "bar");
		
		
	});

	//!steal-remove-start
	if (can.dev) {
		test("passing unsupported attributes gives a warning", function(){

			var oldlog = can.dev.warn;
			can.dev.warn = function (text) {
				ok(text, "got a message");
				can.dev.warn = oldlog;
			};
			can.Component.extend({
				tag: 'my-thing',
				template: 'hello'
			});
			var stache = can.stache("<my-thing id='{productId}'></my-tagged>");
			stache(new can.Map({productId: 123}));
		});
	}
	//!steal-remove-end

	test("stache conditionally nested components calls inserted once (#967)", function(){
		expect(2);

		can.Component.extend({
			tag: "can-parent-stache",
			viewModel: {
				shown: true
			},
			template: can.stache("{{#if shown}}<can-child></can-child>{{/if}}")
		});
		can.Component.extend({
			tag: "can-parent-mustache",
			viewModel: {
				shown: true
			},
			template: can.mustache("{{#if shown}}<can-child></can-child>{{/if}}")
		});
		can.Component.extend({
			tag: "can-child",
			events: {
				inserted: function(){
					this.viewModel.attr('bar', 'foo');
					ok(true, "called inserted once");
				}
			}
		});

		var template = can.stache("<can-parent-stache></can-parent-stache>");

		can.append(can.$('#qunit-fixture'), template());

		var template2 = can.stache("<can-parent-mustache></can-parent-mustache>");

		can.append(can.$('#qunit-fixture'), template2());

	});
	
	test("hyphen-less tag names", function () {
		var template = can.view.mustache('<span></span><foobar></foobar>');
		can.Component.extend({
			tag: "foobar",
			template: "<div>{{name}}</div>",
			viewModel: {
				name: "Brian"
			}
		});
		can.append(can.$('#qunit-fixture'), template());
		equal(can.$('#qunit-fixture div')[0].innerHTML, "Brian");

	});

	test('nested component within an #if is not live bound(#1025)', function() {
		can.Component.extend({
			tag: 'parent-component',
			template: can.stache('{{#if shown}}<child-component></child-component>{{/if}}'),
			viewModel: {
				shown: false
			}
		});

		can.Component.extend({
			tag: 'child-component',
			template: can.stache('Hello world.')
		});

		var template = can.stache('<parent-component></parent-component>');
		var frag = template({});

		equal(frag.childNodes[0].innerHTML, '', 'child component is not inserted');
		can.viewModel(frag.childNodes[0]).attr('shown', true);

		equal(frag.childNodes[0].childNodes[0].innerHTML, 'Hello world.', 'child component is inserted');
		can.viewModel(frag.childNodes[0]).attr('shown', false);

		equal(frag.childNodes[0].innerHTML, '', 'child component is removed');
	});

	test('component does not update viewModel on id, class, and data-view-id attribute changes (#1079)', function(){
		
		can.Component.extend({
			tag:'x-app'
		});

		var frag=can.stache('<x-app></x-app>')({});
		
		var el = frag.childNodes[0];
		var viewModel = can.viewModel(el);
		
		// element must be inserted, otherwise attributes event will not be fired
		can.append(can.$("#qunit-fixture"),frag);
		
		// update the class
		can.addClass(can.$(el),"foo");
		
		stop();
		setTimeout(function(){
			equal(viewModel.attr('class'),undefined, "the viewModel is not updated when the class attribute changes");
			start();
		},20);
		
	});

	test('viewModel objects with Constructor functions as properties do not get converted (#1261)', 1, function(){
		stop();

		var Test = can.Map.extend({
			test: 'Yeah'
		});

		can.Component.extend({
			tag:'my-app',
			viewModel: {
				MyConstruct: Test
			},
			events: {
				'{MyConstruct} something': function() {
					ok(true, 'Event got triggered');
					start();
				}
			}
		});

		var frag = can.stache('<my-app></my-app>')();

		// element must be inserted, otherwise attributes event will not be fired
		can.append(can.$("#qunit-fixture"),frag);

		can.trigger(Test, 'something');

		can.remove(can.$("#qunit-fixture>*"));
	});

	test('removing bound viewModel properties on destroy #1415', function(){
		var state = new can.Map({
			product: {
				id: 1,
				name: "Tom"
			}
		});

		can.Component.extend({
			tag: 'destroyable-component',
			events: {
				destroy: function(){
					this.viewModel.attr('product', null);
				}
			}
		});

		var frag = can.stache('<destroyable-component product="{product}"></destroyable-component>')(state);

		// element must be inserted, otherwise attributes event will not be fired
		can.append(can.$("#qunit-fixture"),frag);

		can.remove(can.$("#qunit-fixture>*"));

		ok(state.attr('product') == null, 'product was removed');
	});

	test('changing viewModel property rebinds {viewModel.<...>} events (#1529)', 2, function(){
		can.Component.extend({
			tag: 'rebind-viewmodel',
			events: {
				inserted: function(){
					this.viewModel.attr('item', {});
				},
				'{scope.item} change': function() {
					ok(true, 'Change event on scope');
				},
				'{viewModel.item} change': function() {
					ok(true, 'Change event on viewModel');
				}
			}
		});

		can.append(can.$("#qunit-fixture"), can.stache('<rebind-viewmodel></rebind-viewmodel>')());
		can.viewModel(can.$("#qunit-fixture rebind-viewmodel")).attr('item.name', 'CDN');

	});



	test('Component two way binding loop (#1579)', function() {
		var changeCount = 0;
		
		can.Component.extend({
			tag: 'product-swatch-color'
		});


		can.Component.extend({
			tag: 'product-swatch',
			template: can.stache('<product-swatch-color variations="{variations}"></product-swatch-color>'),
			viewModel: can.Map.extend({
				define: {
					variations: {
						set: function(variations) {
							if(changeCount > 500) {
								return;
							}
							changeCount++;
							return new can.List(variations.attr());
						}
					}
				}
			})
		});

		can.append( can.$("#qunit-fixture"), can.stache('<product-swatch></product-swatch>')() );
		
		can.batch.start();
		can.viewModel( can.$("#qunit-fixture product-swatch") ).attr('variations', new can.List());
		can.batch.stop();
		
		
		ok(changeCount < 500, "more than 500 events");
	});

	test('DOM trees not releasing when referencing can.Map inside can.Map in template (#1593)', function() {
		var baseTemplate = can.stache('{{#if show}}<my-outside></my-outside>{{/if}}'),
			show = can.compute(true),
			state = new can.Map({
				inner: 1
			});

		var removeCount = 0;

		can.Component.extend({
			tag: 'my-inside',
			events: {
				removed: function() {
					removeCount++;
				}
			}
		});

		can.Component.extend({
			tag: 'my-outside',
			template: can.stache('{{#if state.inner}}<my-inside></my-inside>{{/if}}')
		});

		can.append( can.$("#qunit-fixture"), baseTemplate({
			show: show,
			state: state
		}) );
		
		show(false);
		state.removeAttr('inner');

		equal(removeCount, 1, 'internal removed once');

		show(true);
		state.attr('inner', 2);

		state.removeAttr('inner');

		equal(removeCount, 2, 'internal removed twice');

	});

	test("attach events on init", function(){
		expect(2);
		can.Component.extend({
			tag: 'app-foo',
			template: can.stache('<div>click me</div>'),
			events: {
				init: function(){
					this.on("div", 'click', 'doSomethingfromInit');
				},
				inserted: function(){
					this.on("div", 'click', 'doSomethingfromInserted');
				},
				doSomethingfromInserted: function(){
					ok(true, "bound in inserted");
				},
				doSomethingfromInit: function(){
					ok(true, "bound in init");
				}
			}
		});
		can.append( can.$("#qunit-fixture"), can.stache("<app-foo></app-foo>")({}));
		can.trigger(can.$('#qunit-fixture div'), 'click');
	});

	if(can.isFunction(Object.keys)) {
		test('<content> node list cleans up properly as direct child (#1625, #1627)', 2, function() {
			var size = Object.keys(can.view.nodeLists.nodeMap).length;
			var items = [];
			var viewModel = new can.Map({
				show: false
			});
			var toggle = function() {
				viewModel.attr('show', !viewModel.attr('show'));
			};

			for (var i = 0; i < 100; i++) {
				items.push({
					// Random 5 character String
					name: Math.random().toString(36)
						.replace(/[^a-z]+/g, '').substr(0, 5)
				});
			}

			can.Component.extend({
				tag: 'grandparent-component',
				template: can.stache('{{#if show}}<parent-component></parent-component>{{/if}}'),
				scope: viewModel
			});

			can.Component.extend({
				tag: 'parent-component',
				template: can.stache('{{#items}}<child-component>\n:)\n</child-component>{{/items}}'),
				scope: {
					items: items
				}
			});

			can.Component.extend({
				tag: 'child-component',
				template: can.stache('<div>\n<content/>\n</div>')
			});

			can.append(can.$("#qunit-fixture"), can.stache('<grandparent-component></grandparent-component>')());

			toggle();
			equal(Object.keys(can.view.nodeLists.nodeMap).length - size, 0,
				'No new items added to nodeMap');

			toggle();
			equal(Object.keys(can.view.nodeLists.nodeMap).length - size, 0,
				'No new items added to nodeMap');

			can.remove(can.$("#qunit-fixture>*"));
		});

		asyncTest('<content> node list cleans up properly, directly nested (#1625, #1627)', function() {
			var items = [];
			for (var i = 0; i < 2; i++) {
				items.push({
					name: 'test ' + i,
					parentAttrInContent: 'test ' + i
				});
			}

			can.Component.extend({
				tag: 'parent-component',
				template: can.stache('{{#items}}<child-component>{{parentAttrInContent}}</child-component>{{/items}}'),
				scope: {
					items: items
				}
			});

			can.Component.extend({
				tag: 'child-component',
				template: can.stache('<div>{{#if bar}}<content/>{{/if}}</div>'),
				scope: {
					bar: true
				}
			});

			can.append(can.$("#qunit-fixture"), can.stache('<parent-component></parent-component>')());

			var old = can.unbindAndTeardown;
			var count = 0;
			can.unbindAndTeardown = function(name) {
				if(name === 'parentAttrInContent') {
					count++;
				}
				return old.call(this, arguments);
			};
			
			can.remove(can.$("#qunit-fixture>*"));

			// Dispatches async
			setTimeout(function() {
				equal(count, 2, '2 items unbound');
				can.unbindAndTeardown = old;
				
				start();
			}, 20);
		});

		test("components control destroy method is called", function(){
			expect(0);
			can.Component.extend({
				tag: 'comp-control-destroy-test',
				template: can.stache('<div>click me</div>'),
				events: {
					"{document} click" : function () {
						ok(true, "click registered");
					}
				}
			});
			can.append(can.$("#qunit-fixture"), can.stache("<comp-control-destroy-test></comp-control-destroy-test>")({}));
			can.remove(can.$("#qunit-fixture>*"));
			can.trigger(can.$(document), 'click');
		});
	}
});

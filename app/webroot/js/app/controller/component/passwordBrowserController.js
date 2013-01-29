steal(
	'mad/controller/component/gridController.js',
	'app/controller/component/copyLoginButtonController.js',
	'app/controller/component/copySecretButtonController.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.PasswordBrowserController
	 * @inherits {mad.controller.component.GridController}
	 * @parent index 
	 * 
	 * Our password grid controller
	 * 
	 * @constructor
	 * Creates a new Password Browser Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.PasswordBrowserController}
	 */
	mad.controller.component.GridController.extend('passbolt.controller.component.PasswordBrowserController', /** @static */ {

		'defaults': {
			// the type of the item rendered by the grid
			itemClass: passbolt.model.Resource,
			// the list of resources displayed by the grid
			resources: new passbolt.model.Resource.List(),
			// the list of displayed categories
			// categories: new passbolt.model.Category.List()
			categories: []
		}

	}, /** @prototype */ {
		/**
		 * The current selected resources
		 * @type {string}
		 */
		'selectedResources': {},
		/**
		 * The current focused resource
		 * @type {string}
		 */
		'focusedResource': null,

		// Constructor like
		'init': function (el, options) {
			// The map to use to make jstree working with our category model
			options.map = new mad.object.Map({
				'id': 'id',
				'name': 'name',
				'username': 'username',
				'uri': 'uri',
				'modified': 'modified',
				'copyLogin': 'id',
				'copySecret': 'id',
				'Category': 'Category'
			});

			// the columns names
			options.columnNames = ['', 'Name', 'Username', 'Uri', 'Modified', '', ''];

			// the columns model
			options.columnModel = [{
				'name': 'multipleSelect',
				'index': 'multipleSelect',
				'cellAdapter': function (cellElement, cellValue, mappedItem, item, columnModel) {
					var availableValues = [];
					availableValues[item.id] = '';
					var checkbox = mad.helper.ComponentHelper.create(
						cellElement,
						'inside_replace',
						mad.form.element.CheckboxController, {
							'id': 'multiple_select_checkbox_' + item.id, 
							'name': 'test',
							'cssClasses': ['js_checkbox_multiple_select'],
						 	'availableValues': availableValues
						}
					);
					checkbox.render();
				}
			}, {
				'name': 'name',
				'index': 'name',
				'valueAdapter': function (value, item, columnModel, rowNum) {
					var returnValue = value;
					can.each(item.Category, function (category, i) {
						returnValue += ' <span class="password_browser_category_label">' + category.name + '</span>';
					});
					return returnValue;
				}
			}, {
				'name': 'username',
				'index': 'username'
			}, {
				'name': 'uri',
				'index': 'uri'
			}, {
				'name': 'modified',
				'index': 'modified',
				'valueAdapter': function (value, item, columnModel, rowNum) {
					return moment(value).fromNow();
				}
			}, {
				'name': 'copyLogin',
				'index': 'copyLogin',
				'cellAdapter': function (cellElement, cellValue, mappedItem, item, columnModel) {
					mad.helper.ComponentHelper.create(
						cellElement,
						'inside_replace',
						passbolt.controller.component.CopyLoginButtonController,
						{ 'state': 'hidden', 'value': item, 'browser': mad.app.getComponent('js_passbolt_password_browser') }
					);
				}
			}, {
				'name': 'copySecret',
				'index': 'copySecret',
				'cellAdapter': function (cellElement, cellValue, mappedItem, item, columnModel) {
					mad.helper.ComponentHelper.create(
						cellElement,
						'inside_replace',
						passbolt.controller.component.CopySecretButtonController,
						{ 'state': 'hidden', 'value': item, 'browser': mad.app.getComponent('js_passbolt_password_browser') }
					);
				}
			}];

			this._super(el, options);
		},

		/**
		 * Insert a resource in the grid
		 * @param {mad.model.Model} resource The resource to insert
		 * @param {string} refResourceId The reference resource id. By default the grid view object
		 * will choose the root as reference element.
		 * @param {string} position The position of the newly created item. You can pass in one
		 * of those strings: "before", "after", "inside", "first", "last". By dhe default value 
		 * is set to last.
		 * @return {void}
		 */
		'insertItem': function (resource, refResourceId, position) {
			// add the resource to the list of observed resources
			this.options.resources.push(resource);
			// insert the item to the grid
			this._super(resource, refResourceId, position);
		},

		/**
		 * Remove an item to the grid
		 * @param {mad.model.Model} item The item to remove
		 * @return {void}
		 */
		'removeItem': function (item) {
			// if the grid is in the selected state, check that the resource to delete is not selected
			if (this.state.is('selected')) {
				// if the resource deleted is the currently selected item
				if (item.id == this.crtSelectedResourceId) {
					// switch to the start state
					this.setState('ready');
					// inform the world that the resource is unselected
					mad.bus.trigger('resource_unselected', item);
				}
			}
			// remove the item to the grid
			this._super(item);
		},
		
		/**
		 * Refresh item
		 * @param {mad.model.Model} item The item to refresh
		 * @return {void}
		 */
		'refreshItem': function (resource) {
			var self = this;
			
			// if the password browser is filter by category
			if(this.options.categories.length) {
				var belongToDisplayedCat = false;
			
				// check if the resource belongs to a displayed category
				can.each(resource.Category, function (resourceCategory, i) {
					// the resource belongs to a destroy categories
					if (self.options.categories.indexOf(resourceCategory.id) != -1) {
						belongToDisplayedCat = true;
					}
				});
				// remove the resource if it does not belong to a displayed categories
				if(!belongToDisplayedCat) {
					this.removeItem(resource);
					return;
				}
			}
			
			this._super(resource);
		},

		/**
		 * reset
		 * @return {void}
		 */
		'reset': function () {
			// reset the list of observed resourced (Bon, make a splice seems to be the only solution)
			this.options.resources.splice(0, this.options.resources.length);
		},

		/**
		 * Load resources in the grid
		 * @param {passbolt.model.Resource.List} resources The list of resources to
		 * load into the grid
		 * @return {void}
		 */
		'load': function (resources, reset) {
			// load the resources
			this._super(resources);
		},

		// /**
		 // * Select a resource
		 // * @param {Model} resource The target selected resource
		 // * @return {void}
		 // */
		// 'select': function (resource) {
			// //
		// },

		/* ************************************************************** */
		/* LISTEN TO THE MODEL EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when a resource is created. If the resource is created in a category
		 * displayed by the grid. Add the new resource a the top of the grid
		 * @param {mad.model.Model} model The model reference
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The created resource
		 * @return {void}
		 */
		'{passbolt.model.Resource} created': function (model, ev, resource) {
			var self = this;

			// If the new resource belongs to one of the categories displayed by the resource
			// browser -> Insert it
			// @todo an indexOf function will be very usefull here
			can.each(this.options.categories, function (category, i) {
				can.each(resource.Category, function (rsCategory, j) {
					if (category.id == rsCategory.id) {
						self.insertItem(resource, null, 'first');
						return false; // break
					}
				});
			});
		},

		/**
		 * Observe when a resource is updated. If the resource is rendered by the grid
		 * refresh its content. We listen the model directly, listening on changes on
		 * a list seems too much here (one event for each updated attribute)
		 * @param {mad.model.Model} model The model reference
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The updated resource
		 * @return {void}
		 */
		'{passbolt.model.Resource} updated': function (model, ev, resource) {
			if (this.options.resources.indexOf(resource) != -1) {
				this.refreshItem(resource);
			}
		},

		/**
		 * Observe when resources are removed to the list
		 * @param {mad.model.Model} model The model reference
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resources The removed resource
		 * @return {void}
		 */
		'{resources} remove': function (model, ev, resources) {
			var self = this;
			can.each(resources, function (resource, i) {
				self.removeItem(resource);
			});
		},

		/**
		 * Observe when a category is removed. And remove from the grid all the resources
		 * which are not belonging to a displayed Category.
		 * @param {mad.model.Model} model The model reference
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} category The removed category
		 * @return {void}
		 */
		'{passbolt.model.Category} destroyed': function (model, ev, category) {
			var self = this;

			// remove from the list of categories which are displayed the just deleted categories
			// and its children
			var destroyedCategories = mad.model.Model.nestedToList(category, 'children');
			var destroyedCategoriesIds = [];
			can.each(destroyedCategories, function(destroyedCategory, h) {
				var indexof = self.options.categories.indexOf (destroyedCategory.id);
				if (indexof != -1) {
					// remove the destroyed categories from the display categories array
					self.options.categories.splice(indexof, 1);
				}
				destroyedCategoriesIds.push(destroyedCategory.id);
			});
			
			// update the resource which belong to a destroyed category
			// commented because of the last work on the resource model instances updated and trigerring an update event
			// can.each(this.options.resources, function (resource, i) {
				// can.each(resource.Category, function (resourceCategory, j) {
					// // the resource belongs to a destroy categories
					// if (destroyedCategoriesIds.indexOf(resourceCategory.id) != -1) {
						// self.refreshItem(resource);
					// }
				// });
			// });
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		'unselect': function (item) {
			// if in multiple selected state
			// the component will allow multiple select
			if (this.state.is('multipleSelected')) {
				// remove the resource from the previously selected resources
				delete this.selectedResources[item.id];
				// unselect the item in grid
				this.view.unselectItem(item);
			} else {
				// remove the resource from the previously selected resources
				delete this.selectedResources[item.id];
				// uncheck the associated checkbox
				var chkBxComponent = mad.app.getComponent('multiple_select_checkbox_' + item.id);
				chkBxComponent.reset();
				// unselect the item in grid
				this.view.unselectItem(item);
				// notice the app about the just unselected resource
				mad.bus.trigger('resource_unselected', item);
			}
		},
		
		'select': function (item) {
			// if in multiple selected state
			// the component will allow multiple select
			if (this.state.is('multipleSelected')) {
				// save the new selected resource
				this.selectedResources[item.id] = item;
				// select the item in grid
				this.view.selectItem(item);
				
			// else 
			} else {
				this.selectedResources[item.id] = item;
				// check the associated checkbox
				var chkBxComponent = mad.app.getComponent('multiple_select_checkbox_' + item.id);
				chkBxComponent.setValue([item.id]);
				// select the item in grid
				this.view.selectItem(item);

				// notice the application about this selection
				mad.bus.trigger('resource_selected', item);
			}
		},

		/**
		 * Observe when an item is selected in the grid
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The selected item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_selected': function (el, ev, item, srcEvent) {
			// if the component is already in selected state & the resource is currently selected 
			// -> unselect it
			if (this.state.is('selected')
				&& typeof this.selectedResources[item.id] != 'undefined') {
				this.unselect(item);
			// unselect all the previously selected resource
			// and select the given resource
			} else {
				
				// if the grid is not in selected state, force it to switch into
				if (!this.state.is('selected')) {
					this.setState('selected');
				}
				
				// unselect the currently selected resources
				if (!$.isEmptyObject(this.selectedResources)) {
					for (var i in this.selectedResources){
						this.unselect(this.selectedResources[i]);						
					}
				}
				
				// select the resource
				this.select(item);
			}
		},

		/**
		 * Listen to the check event on any checkbox form element components. 
		 * 
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} data The id of the resource which has been checked
		 * @return {void}
		 */
		' checkbox.checked': function (el, ev, data) {
			var self = this;
			
			// if the grid is in initial state, switch it to selected
			if (this.state.is('ready')) {
				this.setState('selected');
			}
			// if the grid is already in selected state, switch to multipleSelected
			// to allow multiple selection
			else if (this.state.is('selected')) {
				can.each(this.selectedResources, function (resource, i) {
					// notice the app about the unselected resource
					// ok this is not the best way to do it
					mad.bus.trigger('resource_unselected', resource);
				});
				this.setState('multipleSelected');
			}
			
			// find the resource functions of its id in the displayed resources array
			// and select it
			// @todo check to use maybe the search function, check the already done function search, and check what have been done on the server side
			can.each(this.options.resources, function (resource, i) {
				if (resource.id == data) {
					self.select(resource);
					return false; // break
				}
			});
		},

		/**
		 * Listen to the uncheck event on any checkbox form element components. 
		 * 
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} data The id of the resource which has been unchecked
		 * @return {void}
		 */
		' checkbox.unchecked': function (el, ev, data) {
			var self = this;
			
			// find the resource functions of its id in the displayed resources array
			// and unselect it
			// @todo check to use maybe the search function, check the already done function search, and check what have been done on the server side
			can.each(this.options.resources, function (resource, i) {
				if (resource.id == data) {
					self.unselect(resource);
					return false; // break
				}
			});
			
			// if there is no more selected resources, switch the grid to its initial state
			if ($.isEmptyObject(this.selectedResources)) {
				this.setState('ready');
			}
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the browser filter
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {passbolt.model.Filter} filter The filter to apply
		 * @return {void}
		 */
		'{mad.bus} filter_resources_browser': function (element, evt, filter) {
			var self = this;
			// store the filter
			this.filter = filter;
			// reset the state variables
			this.options.categories = [];
			// this.options.categories = new can.Observe.List([]); // with an observable list, it would be great, but it is not working properly with event !

			// override the current list of categories displayed with the new ones
			can.each(filter.tags, function (category, i) {
				var subCategories = category.getSubCategories(true);
				can.each(subCategories, function(subCategory, i){
					self.options.categories.push(subCategory.id);
				});
				// $.merge(self.options.categories, category.getSubCategories(true));
			});

			// if a resource was selected, inform the system that the resource is no more selected
			if (this.state.is('selected')) {
				mad.bus.trigger('resource_unselected', this.crtSelectedResourceId);
			}

			// change the state of the component to loading 
			this.setState('loading');
			// load resources for the given filter
			passbolt.model.Resource.findAll({
				'categories_id': can.map(filter.tags, function (tag, i) { return tag.id; }).join(','),
				'keywords': filter.keywords,
				'recursive': true
			}, function (resources, response, request) {
				// The callback is out of date, an other filter has been performed
				// @todo do something like filter.isRelativeTo(dataBrol) => bool
				if (request.originParams.keywords != self.filter.keywords ||
						request.originParams.categories_id != can.map(self.filter.tags, function (tag, i) { return tag.id; }).join(',')) {
					steal.dev.log('(OutOfDate) Cancel passbolt.model.Resource.findAll request callback in passbolt.controller.component.PasswordBrowserController');
					return;
				}
				// load the resources in the browser
				self.load(resources);
				// change the state to ready
				self.setState('ready');
			});
		},

		/**
		 * Observe when the application is ready and load the tree with the roots
		 * categories
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'{mad.bus} app_ready': function (ui, event) {
			var self = this;

			this.setState('loading');
			// load default resources
			passbolt.model.Resource.findAll({
			}, function (resources, response, request) {
				// load the resources in the browser
				self.load(resources);
				// change the state to ready
				self.setState('ready');
			});
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Ready.
		 * The ready state is fired automatically after the Component is rendered
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) {
			// nothing to do
		},

		/**
		 * Listen to the change relative to the state selected
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateSelected': function (go) {
			if (go) {
				this.view.hideColumn('modified');
				this.view.hideColumn('copyLogin');
				this.view.hideColumn('copySecret');
			} else {
				this.view.showColumn('modified');
				this.view.showColumn('copyLogin');
				this.view.showColumn('copySecret');
			}
		},

		/**
		 * Listen to the change relative to the state multipleSelected
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateMultipleSelected': function (go) {
			if (go) {
				//
			} else {
				// unckeck all the previously checked checkboxes
				// get all the checkbox controller
				// @todo find a way to get component controller from their class name
				// var controllers = $('.js_checkbox_multiple_select', this.element).controllers("checkbox_controller");
				// for (var i = 0; i<controllers.length; i++) {
					// controllers[i].reset();
				// }
			}
		}

	});

});
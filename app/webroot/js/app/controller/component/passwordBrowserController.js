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
			categories: new passbolt.model.Category.List()
		}

	}, /** @prototype */ {
		/**
		 * The current selected resource id
		 * @type {string}
		 */
		'crtSelectedResourceId': null,
		/**
		 * The current focused resource id
		 * @type {string}
		 */
		'crtFocusedResourceId': null,

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
			options.columnNames = ['Name', 'Username', 'Uri', 'Modified', '', ''];

			// the columns model
			options.columnModel = [{
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
			// add the resource to the list of the observed resources
			this.options.resources.push(resource);
			// remove the item to the grid
			this._super(resource, refResourceId, position);
		},

		/**
		 * Remove an item to the grid
		 * @param {mad.model.Model} item The item to remove
		 * @return {void}
		 */
		'removeItem': function (item) {
			// if the grid is in the selected state, check that the resource to delete is not selected
			if (this.state.is('resourceSelected')) {
				// if the resource deleted is the currently selected item
				if (item.id == this.crtSelectedResourceId) {
					// switch to the start state
					this.setState('ready');
					// inform the world that the resource is unselected
					mad.eventBus.trigger('resource_unselected', item);
				}
			}
			// remove the item to the grid
			this._super(item);
		},

		/**
		 * Load resources in the grid
		 * @param {passbolt.model.Resource.List} resources The list of resources to
		 * load into the grid
		 * @return {void}
		 */
		'load': function (resources) {
			// load the resources
			this._super(resources);
			// rebind the controller with the changes on the options
			this.on();
		},

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

			// check that the new resource is tagged by a category displayed by the 
			// password browser.
			// too costly, but indexOf does not work in the lists, maybe the test is not
			// done on id of model but on another data. Take a look, maybe relative to model
			// binding
			can.each(this.options.categories, function (category, i) {
				can.each(resource.Category, function (rsCategory, j) {
					if (category.id == rsCategory.id) {
						self.insertItem(resource, null, 'first');
						self.on();
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
		 * Observe when categories are removed to the list. And remove resources
		 * which own this categories.
		 * @param {mad.model.Model} model The model reference
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} categories The removed categories
		 * @return {void}
		 */
		'{categories} remove': function (model, ev, categories) {
			var self = this;

			// remove all the resource which are tagged by one of the deleted categories
			// and remove them to the password browser.
			// too costly, but indexOf does not work in the lists, maybe the test is not
			// done on id of model but on another data. Take a look, maybe relative to model
			// binding
			can.each(categories, function (categoryRemoved, i) {
				can.each(self.options.resources, function (resource, j) {
					can.each(resource.Category, function (category, h) {
						if (category.id == categoryRemoved.id) {
							self.removeItem(resource);
						}
					});
				});
			});
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when an item is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The selected item instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		' item_selected': function (el, ev, item, srcEvent) {
			// if the resource selected is the same than the previous one unselect
			if (item.id == this.crtSelectedResourceId) {
				this.setState('ready');
				mad.eventBus.trigger('resource_unselected', item);
			} else {
				this.setState('ready');
				this.crtSelectedResourceId = item.id;
				this.setState('resourceSelected');
				mad.eventBus.trigger('resource_selected', item);
			}
		},

		/**
		 * Observe when a resource is unselected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The selected item instance or its id
		 * @return {void}
		 */
		' item_unselected': function (el, ev, item) {
			this.setState('ready');
			mad.eventBus.trigger('resource_unselected', item);
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
		'{passbolt.eventBus} filter_resources_browser': function (element, evt, filter) {
			var self = this;
			// store the filter
			this.filter = filter;

			// override the current list of categories displayed with the new ones
			this.options.categories = [];
			can.each(filter.tags, function (category, i) {
				$.merge(self.options.categories, category.getSubCategories(true));
			});

			// if a resource was selected, inform the system that the resource is no more selected
			if (this.state.is('resourceSelected')) {
				mad.eventBus.trigger('resource_unselected', this.crtSelectedResourceId);
			}

			// change the state of the component to loading 
			this.setState('loading');
			// load resources of the selected categories
			passbolt.model.Resource.findAll({
				'categories_id': can.map(filter.tags, function (tag, i) { return tag.id; }).join(','),
				'keywords': filter.keywords,
				'recursive': true
			}, function (resources, response, request) {
				// The callback is out of date, an other set of categories have been selected
				// check the filter is the current filter
//				if (can.map(filter.tags, function (tag, i) { return tag.id; }).join(',') != request.originParams.categories_id) {
//					steal.dev.log('(OutOfDate) Cancel passbolt.model.Resource.findAll request callback in passbolt.controller.component.PasswordBrowserController');
//					return;
//				}
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
			if (go) {
				this.crtSelectedResourceId = null;
				this.crtFocusedResourceId = null;
			}
		},

		/**
		 * Listen to the change relative to the state ResourceSelected
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateResourceSelected': function (go) {
			if (go) {
				this.view.hideColumn('modified');
				this.view.hideColumn('copyLogin');
				this.view.hideColumn('copySecret');
				this.view.selectItem(this.crtSelectedResourceId);
			} else {
				this.view.showColumn('modified');
				this.view.showColumn('copyLogin');
				this.view.showColumn('copySecret');
				this.view.unselectItem(this.crtSelectedResourceId);
			}
		}

	});

});
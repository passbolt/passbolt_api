import moment from 'moment';
import 'urijs/punycode';
import 'urijs/SecondLevelDomains';
import 'urijs/IPv6';
import URI from 'urijs/URI';
import 'mad/component/grid';
import 'mad/component/contextual_menu';
import 'mad/form/element/checkbox';
import 'app/model/resource';
import 'app/model/category';
import 'app/model/favorite';
import 'app/component/favorite';
import 'app/view/component/password_browser';
import 'app/view/template/component/password_workspace_all_items_empty.ejs!';

/**
 * @inherits {mad.component.Grid}
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
 * @return {passbolt.component.PasswordBrowserController}
 */
var PasswordBrowser = passbolt.component.PasswordBrowser = mad.component.Grid.extend('passbolt.component.PasswordBrowser', /** @static */ {

	defaults: {
		// the type of the item rendered by the grid
		itemClass: passbolt.model.Resource,
		// the view class to use. Overriden so we can put our own logic.
		viewClass: passbolt.view.component.PasswordBrowser,
		// the list of watched categories
		categories: [],
		// the selected resources, you can pass an existing list as parameter of the constructor to share the same list
		selectedRs: new can.Model.List(),
		// Prefix each row id with resource_
		prefixItemId: 'resource_',
        // Override the silentLoading parameter.
        silentLoading: false
	}

}, /** @prototype */ {

	/**
	 * The filter used to filter the browser.
	 * @type {passbolt.model.Filter}
	 */
	filterSettings: null,

	/**
	 * Keep a trace of the old filter used to filter the browser.
	 * @type {passbolt.model.Filter}
	 */
	oldFilterSettings: null,

	// Constructor like
	init: function (el, options) {

		// The map to use to make our grid working with our resource model
		options.map = new mad.Map({
			id: 'id',
			name: 'name',
			username: 'username',
			secret: 'Secret',
			uri: 'uri',
			modified: 'modified',
			owner: 'Creator.username',
			Category: 'Category'
		});

		// the columns model
		options.columnModel = [new mad.model.GridColumn({
			name: 'multipleSelect',
			index: 'multipleSelect',
			css: ['selections s-cell'],
			label: '<div class="input checkbox"> \
						<input type="checkbox" name="select all" value="checkbox-select-all" id="checkbox-select-all" disabled="disabled"> \
						<label for="checkbox-select-all">select all</label> \
					</div>',
			cellAdapter: function (cellElement, cellValue, mappedItem, item, columnModel) {
				var availableValues = {};
				availableValues[item.id] = '';
				var checkbox = mad.helper.Component.create(
					cellElement,
					'inside_replace',
					mad.form.Checkbox, {
						id: 'multiple_select_checkbox_' + item.id,
						name: 'test',
						cssClasses: ['js_checkbox_multiple_select'],
						availableValues: availableValues
					}
				);
				checkbox.start();
			}
		}), new mad.model.GridColumn({
			name: 'favorite',
			index: 'favorite',
			css: ['selections s-cell'],
			label: '<a href="#"> \
						<i class="icon fav"></i> \
						<span class="visuallyhidden">fav</span> \
					</a>',
			cellAdapter: function (cellElement, cellValue, mappedItem, item, columnModel) {
				var availableValues = {};
				availableValues[item.id] = '';
				var favorite = mad.helper.Component.create(
					cellElement,
					'inside_replace',
					passbolt.component.Favorite, {
						id: 'favorite_' + item.id,
						name: 'test2',
						instance: item
					}
				);
				favorite.start();
			}
		}), new mad.model.GridColumn({
			name: 'name',
			index: 'name',
			css: ['m-cell'],
			label: __('Resource'),
			sortable: true
		}), new mad.model.GridColumn({
			name: 'username',
			index: 'username',
			css: ['m-cell'],
			label: __('Username'),
			sortable: true
		}), new mad.model.GridColumn({
			name: 'secret',
			index: 'secret',
			css: ['m-cell', 'password'],
			label: __('Password'),
			cellAdapter: function (cellElement, cellValue, mappedItem, item, columnModel) {
				var secret = '';
				if (typeof cellValue[0] != 'undefined') {
					secret = cellValue[0].data
				}
				mad.helper.Html.create(
					cellElement,
					'inside_replace',
					'<div class="secret-copy">' +
						'<a id="grid_secret_copy_' + item.id + '" href="#copy_secret">' +
							'<span>copy password to clipboard</span>' +
						'</a>' +
						'<pre>' + secret + '</pre>' +
					'</div>'
				);
			}
		}), new mad.model.GridColumn({
			name: 'uri',
			index: 'uri',
			css: ['l-cell'],
			label: __('URI'),
			sortable: true,
			cellAdapter: function (cellElement, cellValue, mappedItem, item, columnModel) {
				var uri = URI(cellValue);

				// If the uri is an url and is not absolute.
				// Add the default http:// protocol
				if (!uri.is('absolute') && uri.is('url')) {
					uri.protocol('http');
				}

				mad.helper.Html.create(
					cellElement,
					'inside_replace',
					'<a href="' + uri.toString() + '" target="_blank">' + cellValue + '</a>'
				);
			}
		}), new mad.model.GridColumn({
			name: 'modified',
			index: 'modified',
			css: ['m-cell'],
			sortable: true,
			label: __('Modified'),
			valueAdapter: function (value, mappedItem, item, columnModel) {
				return passbolt.Common.datetimeGetTimeAgo(value);
			}
		}), new mad.model.GridColumn({
			name: 'owner',
			index: 'owner',
			css: ['m-cell'],
			label: __('Owner'),
			sortable: true
		})];

		this._super(el, options);
	},

	/**
	 * Get a target column model of the grid.
	 * If no target
	 *
	 * @todo move this function to the parent class mad.grid
	 * @return {mad.model.Model}
	 */
	getColumnModel: function (name) {
		var returnValue = null;
		if (name != undefined) {
			for (var i in this.options.columnModel) {
				if (this.options.columnModel[i].name == name) {
					return this.options.columnModel[i];
				}
			}
		} else {
			returnValue = this.options.columnModel;
		}
		return returnValue;
	},

	/**
	 * Show the contextual menu
	 * @param {passbolt.model.Resource} item The item to show the contextual menu for
	 * @param {string} x The x position where the menu will be rendered
	 * @param {string} y The y position where the menu will be rendered
     * @param {HTMLElement} eventTarget The element the event occurred on
	 */
	showContextualMenu: function (item, x, y, eventTarget) {
		// Get the offset position of the clicked item.
		var $item = $('#' + this.options.prefixItemId + item.id);
		var item_offset = $item.offset();

		// Instantiate the contextual menu menu.
		var contextualMenu = new mad.component.ContextualMenu(null, {
			state: 'hidden',
			source: eventTarget,
			coordinates: {
				x: x,
				y: item_offset.top
			}
		});
		contextualMenu.start();

		// get the permission on the category.
		var canRead = passbolt.model.Permission.isAllowedTo(item, passbolt.READ),
			canUpdate = passbolt.model.Permission.isAllowedTo(item, passbolt.UPDATE),
			canAdmin = passbolt.model.Permission.isAllowedTo(item, passbolt.ADMIN);

		// Add Copy username action.
		var action = new mad.model.Action({
			id: 'js_password_browser_menu_copy_username',
			label: 'Copy username',
			initial_state: !canRead ? 'disabled' : 'ready',
			action: function (menu) {
				var data = {
					name : 'username',
					data : item.username
				};
				mad.bus.trigger('passbolt.plugin.app.copy', data);
				menu.remove();
			}
		});
		contextualMenu.insertItem(action);
		// Add Copy password action.
		var action = new mad.model.Action({
            id: 'js_password_browser_menu_copy_password',
			label: 'Copy password',
			initial_state: !canRead ? 'disabled' : 'ready',
			action: function (menu) {
				var secret = item.Secret[0].data;
				mad.bus.trigger('passbolt.plugin.app.decrypt-copy', secret);
				menu.remove();
			}
		});
		contextualMenu.insertItem(action);
		// Add Copy URI action.
		var action = new mad.model.Action({
            id: 'js_password_browser_menu_copy_uri',
			label: 'Copy URI',
			initial_state: !canRead ? 'disabled' : 'ready',
			action: function (menu) {
				var data = {
					name : 'URL',
					data : item.uri
				};
				mad.bus.trigger('passbolt.plugin.app.copy', data);
				menu.remove();
			}
		});
		contextualMenu.insertItem(action);

		// Add Open URI in a new tab action.
		var action = new mad.model.Action({
            id: 'js_password_browser_menu_open_uri',
			label: 'Open URI in a new tab',
			initial_state: !canRead ? 'disabled' : 'ready',
			cssClasses: ['separator-after'],
			action: function (menu) {
				var uri = item.uri;
				var win = window.open(uri, '_blank');
				win.focus();
				menu.remove();
			}
		});
		contextualMenu.insertItem(action);

		// Add Edit action.
		var action = new mad.model.Action({
            id: 'js_password_browser_menu_edit',
			label: 'Edit',
			initial_state: !canUpdate ? 'disabled' : 'ready',
			action: function (menu) {
				mad.bus.trigger('request_resource_edition', item);
				menu.remove();
			}
		});
		contextualMenu.insertItem(action);

		// Add Share action.
		var action = new mad.model.Action({
            id: 'js_password_browser_menu_share',
			label: 'Share',
			initial_state: !canAdmin ? 'disabled' : 'ready',
			action: function (menu) {
				mad.bus.trigger('request_resource_sharing', item);
				menu.remove();
			}
		});
		contextualMenu.insertItem(action);
		// Add Delete action.
		var action = new mad.model.Action({
            id: 'js_password_browser_menu_delete',
			label: 'Delete',
			initial_state: !canUpdate ? 'disabled' : 'ready',
			action: function (menu) {
				mad.bus.trigger('request_resource_deletion', item);
				menu.remove();
			}
		});
		contextualMenu.insertItem(action);

		// Display the menu.
		contextualMenu.setState('ready');
	},

	/**
	 * Insert a resource in the grid
	 * @param {mad.model.Model} resource The resource to insert
	 * @param {string} refResourceId The reference resource id. By default the grid view object
	 * will choose the root as reference element.
	 * @param {string} position The position of the newly created item. You can pass in one
	 * of those strings: "before", "after", "inside", "first", "last". By dhe default value
	 * is set to last.
	 *
	 * @todo remove this function check todo inside
	 */
	insertItem: function (resource, refResourceId, position) {
		// insert the item to the grid
		this._super(resource, refResourceId, position);
        // Reset state to ready (to remove other states such as empty).
		// @todo Remove this state change. The control of the state should be operated by the caller script.
        this.setState('ready');
	},

	/**
	 * Remove an item to the grid
	 * @param {mad.model.Model} item The item to remove
	 * @todo check if we can remove this function by moving the state empty into the grid
	 */
	removeItem: function (item) {
		// remove the item to the grid
		this._super(item);
        // If no resources are left, set empty state.
        if (this.options.items.length == 0) {
            this.state.addState('empty');
        }
	},

	/**
	 * Refresh item
	 * @param {mad.model.Model} item The item to refresh
	 */
	refreshItem: function (resource) {
		var self = this;

		// If the item doesn't exist
		if (!this.itemExists(resource)) {
			return;
		}

		// if the password browser is filter by category
		if(this.options.categories.length) {
			// Is the resource belonging to a displayed category
			var belongToDisplayedCat = false;

			// check if the resource belongs to a displayed category
			can.each(resource.Category, function (resourceCategory, i) {
				// the resource belongs to a destroy categories
				if (self.options.categories.indexOf(resourceCategory.id) != -1) {
					belongToDisplayedCat = true;
				}
			});
			// remove the resource if it does not belong to a displayed category
			if(!belongToDisplayedCat) {
				this.removeItem(resource);
				return;
			}
		}

		// if the resource has not been removed from the grid, update it
		this._super(resource);

		// If the item was previously selected, update the view that has been altered when the item has been refreshed.
		if (this.isSelected(resource)) {
			// Select the checkbox (if it is not already done).
			var id = 'multiple_select_checkbox_' + resource.id,
				checkbox = mad.getControl(id, 'mad.form.Checkbox');
			checkbox.setValue([resource.id]);

			// Make the item selected in the view.
			this.view.selectItem(resource);
		}
	},

	/**
	 * Before selecting an item
	 * @param {mad.model.Model} item The item to select
	 */
	beforeSelect: function (item) {
		var returnValue = true;

		if (this.state.is('selection')) {
			// if an item has already been selected
			// if the item is already selected, unselect it
			if (this.isSelected(item)) {
				this.unselect(item);
				this.setState('ready');
				returnValue = false;
			} else {
				for (var i = this.options.selectedRs.length - 1; i > -1; i--) {
					this.unselect(this.options.selectedRs[i]);
				}
			}
		}

		return returnValue;
	},

	/**
	 * Before unselecting an item
	 * @param {mad.model.Model} item The item to unselect
	 */
	beforeUnselect: function (item) {
		var returnValue = true;
		return returnValue;
	},

	/**
	 * Is the item selected
	 *
	 * @param {mad.Model}
	 * @return {bool}
	 */
	isSelected: function (item) {
		// TODO does not work with the multiple selection feature.
		if (this.options.selectedRs.length > 0 && this.options.selectedRs[0].id == item.id) {
			return true;
		}
		return false;
	},

	/**
	 * Select an item
	 * @param {mad.model.Model} item The item to select
	 * @param {boolean} silent Do not propagate any event (default:false)
	 */
	select: function (item, silent) {
		silent = typeof silent == 'undefined' ? false : silent;

		// If the item doesn't exist
		if (!this.itemExists(item)) {
			return;
		}

        // If resource is already selected, we do nothing.
		// Refresh the view
        if (this.isSelected(item)) {
            return;
        }

		// Unselect the previously selected resources, if not in multipleSelection.
		if (!this.state.is('multipleSelection') &&
			this.options.selectedRs.length > 0) {
			this.unselect(this.options.selectedRs[0]);
		}

		// Add the resource to the list of selected items.
		this.options.selectedRs.push(item);

		// Select the checkbox (if it is not already done).
		var id = 'multiple_select_checkbox_' + item.id,
			checkbox = mad.getControl(id, 'mad.form.Checkbox');
		checkbox.setValue([item.id]);

		// Make the item selected in the view.
		this.view.selectItem(item);

		// Notify the application about this selection.
		if (!silent) {
			mad.bus.trigger('resource_selected', item);
		}
	},

	/**
	 * Unselect an item
	 * @param {mad.model.Model} item The item to unselect
	 * @param {boolean} silent Do not propagate any event (default:false)
	 */
	unselect: function (item, silent) {
		silent = silent || false;

		// If the item doesn't exist
		if (!this.itemExists(item)) {
			return;
		}

		// Uncheck the associated checkbox (if it is not already done).
		var id = 'multiple_select_checkbox_' + item.id,
			checkbox = mad.getControl(id, 'mad.form.Checkbox');

		// Uncheck the checkbox by reseting it. Brutal.
		checkbox.reset();

		// Unselect the item in grid.
		this.view.unselectItem(item);

		// Remove the resource from the previously selected resources.
		mad.model.List.remove(this.options.selectedRs, item);

		// Notify the app about the just unselected resource.
		if (!silent) {
			mad.bus.trigger('resource_unselected', item);
		}
	},

	/**
	 * Filter the browser using a filter settings object
	 * @param {passbolt.model.Filter} filter The filter to
	 */
	filterBySettings: function(filter) {
		var self = this,
			// The deferred used for the resources find all request.
			def = $.Deferred();

		// Save the old filter settings.
		this.oldFilterSettings = this.filterSettings;
		// Clone the given filter to avoid any changes problem.
		this.filterSettings = filter.clone();

		//
		//this.options.categories = [];
		//
		//// Set state to filter case.
		//if (filter.case != undefined) {
		//	// Remove class belonging to previous filter.
		//	if (this.filterSettings != undefined) {
		//		self.element.removeClass(this.filterSettings.case);
		//	}
		//	// Add class for current filter. (used in styleguide).
		//	self.element.addClass(filter.case);
		//}
		//
		//// override the current list of categories displayed with the new ones
		//// and the relative sub-categories
		//var filteredCategory = filter.getForeignModels('Category');
		//if(filteredCategory) {
		//	can.each(filteredCategory, function (category, i) {
		//		var subCategories = category.getSubCategories(true);
		//		can.each(subCategories, function(subCategory, i){
		//			self.options.categories.push(subCategory.id);
		//		});
		//	});
		//}

		// If the current filter case is different than the previous filter case
		//   or this is the initial filtering (loading)
		if (this.oldFilterSettings == null
			|| this.filterSettings.case != this.oldFilterSettings.case) {

			// Mark the component as loading.
			// Complete it once the passwords are retrieved and rendered.
			this.setState('loading');

			// Remove all elements from the grid
			this.reset();

			// Retrieve the resources.
			passbolt.model.Resource.findAll({
				filter: this.filterSettings,
				recursive: true,
				silentLoading: false
			}).then(function (resources, response, request) {
				// If the browser has been destroyed before the request completed.
				if (self.element == null) return;

				// If the grid was marked as filtered, reset it.
				self.filtered = false;

				// Load the resources in the browser.
				self.load(resources);

				var states = ['ready'];
				if (filter.case != undefined) {
					states.push(filter.case);
				}
				if (!resources.length) {
					states.push('empty');
				}
				self.setState(states);

				// If the resources are ordered.
				if (filter.order != undefined) {
					var sortedColumnModel = self.getColumnModel(filter.order);
					if (sortedColumnModel != null) {
						var orderAsc = true;
						// @todo introduce asc/desc sort
						// @todo should be cleaned with the filter refactoring PASSBOLT-1571
						if (filter.order == 'modified') {
							orderAsc = false;
						}
						self.view.markColumnAsSorted(sortedColumnModel, orderAsc);
					}
				}

				def.resolve();
			});
		} else {
			def.resolve();
		}

		// When the resources have been retrieved.
		$.when(def).done(function() {
			// Filter by keywords.
			var keywords = self.filterSettings.getKeywords();
			if (keywords != '') {
				self.filterByKeywords(keywords, {
					searchInFields: ['username', 'name', 'uri', 'description']
				});
			} else if (self.isFiltered()){
				self.resetFilter();
			}
		});

		return def;
	},

	/**
	 * Does the item exist
	 * @param {passbolt.Model} item The item to check if it existing
	 * @return {boolean}
	 * @todo PASSBOLT-1614 move this function into mad grid.
	 */
	itemExists: function (item) {
		return this.view.getItemElement(item).length > 0 ? true : false;
	},

	/**
	 * Reset the filtering
	 * @todo PASSBOLT-1614 move this function into mad grid.
	 */
	resetFilter: function () {
		var self = this;
		this.options.isFiltered = false;

		can.each(this.options.items, function(item, i) {
			self.view.showItem(item);
		});
	},

	/* ************************************************************** */
	/* LISTEN TO THE MODEL EVENTS */
	/* ************************************************************** */

	/**
	* Observe when a resource is created.
	* If the created resource belong to a displayed category, add the resource to the grid.
	* @param {mad.model.Model} model The model reference
	* @param {HTMLEvent} ev The event which occurred
	* @param {passbolt.model.Resource} resource The created resource
	*/
	'{passbolt.model.Resource} created': function (model, ev, resource) {
		var self = this;

		// If the resourcs belongs to one or several categories
		if (resource.Category.length) {
			// If the new resource belongs to one of the categories displayed by the resource
			// browser -> Insert it
			resource.Category.each(function(category, i) {
				if(self.options.categories.indexOf(category.id) != -1) {
					self.insertItem(resource, null, 'first');
					return false; // break
				}
			});
		}
		// Else insert it whatever the applied filter.
		// TODO Discuss this behavior with the team
		else {
			self.insertItem(resource, null, 'first');
		}
	},

	/**
	* Observe when a resource is updated.
	* If the resource is displayed by he grid, refresh it.
	* note : We listen the model directly, listening on changes on
	* a list seems too much here (one event for each updated attribute)
	* @param {mad.model.Model} model The model reference
	* @param {HTMLEvent} ev The event which occurred
	* @param {passbolt.model.Resource} resource The updated resource
	*/
	'{passbolt.model.Resource} updated': function (model, ev, resource) {
		if (this.options.items.indexOf(resource) != -1) {
			this.refreshItem(resource);
		}
	},

	/**
	* Observe when a category is removed. And remove from the grid all the resources
	* which are not belonging to a displayed Category.
	* @param {mad.model.Model} model The model reference
	* @param {HTMLEvent} ev The event which occurred
	* @param {passbolt.model.Category} category The removed category
	*/
	'{passbolt.model.Category} destroyed': function (model, ev, category) {
		var self = this;

		// remove from the list of displayed categories the given deleted category and its children
		var destroyedCategories = mad.model.Model.nestedToList(category, 'children');
		can.each(destroyedCategories, function(destroyedCategory, h) {
			var indexof = self.options.categories.indexOf(destroyedCategory.id);
			if (indexof != -1) {
				// remove the destroyed categories from the display categories array
				self.options.categories.splice(indexof, 1);
			}
		});
	},

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	* Observe when an item is selected in the grid.
	* This event comes from the grid view
	* @param {HTMLElement} el The element the event occurred on
	* @param {HTMLEvent} ev The event which occurred
	* @param {mixed} item The selected item instance or its id
	* @param {HTMLEvent} ev The source event which occurred
	*/
	' item_selected': function (el, ev, item, srcEvent) {
		// switch to select state
		this.setState('selection');

		if (this.beforeSelect(item)) {
			this.select(item);
		}
	},

	/**
	* An item has been right selected
	* @param {HTMLElement} el The element the event occurred on
	* @param {HTMLEvent} ev The event which occurred
	* @param {passbolt.model.Resource} item The right selected item instance or its id
	* @param {HTMLEvent} srcEvent The source event which occurred
	*/
    ' item_right_selected': function (el, ev, item, srcEvent) {
        // Select item.
		this.select(item);
		// Show contextual menu.
		this.showContextualMenu(item, srcEvent.pageX, srcEvent.pageY, srcEvent.target);
	},

	/**
	* A password has been clicked.
	* @param {HTMLElement} el The element the event occurred on
	* @param {HTMLEvent} ev The event which occurred
	* @param {passbolt.model.Resource} item The right selected item instance or its id
	* @param {HTMLEvent} srcEvent The source event which occurred
	*/
	' password_clicked': function (el, ev, item, srcEvent) {
		// Get secret out of Resource object.
		var secret = item.Secret[0].data;
		// Request decryption. (delegated to plugin).
		mad.bus.trigger('passbolt.plugin.app.decrypt-copy', secret);
	},

	/**
	* Listen to the check event on any checkbox form element components.
	*
	* @param {HTMLElement} el The element the event occurred on
	* @param {HTMLEvent} ev The event which occurred
	* @param {mixed} rsId The id of the resource which has been checked
	*/
	'.js_checkbox_multiple_select checked': function (el, ev, rsId) {
		// if the grid is in initial state, switch it to selected
		if (this.state.is('ready')) {
			this.setState('selection');
		}
		// if the grid is already in selected state, switch to multipleSelected
		// @todo Multiple selection has been disabled
		//else if (this.state.is('selection')) {
		//	this.setState('multipleSelection');
		//}

		// find the resource to select functions of its id
		var i = mad.model.List.indexOf(this.options.items, rsId);
		var resource = this.options.items[i];

		if (this.beforeSelect(resource)) {
			this.select(resource);
		}
	},

	/**
	* Listen to the uncheck event on any checkbox form element components.
	*
	* @param {HTMLElement} el The element the event occurred on
	* @param {HTMLEvent} ev The event which occurred
	* @param {mixed} rsId The id of the resource which has been unchecked
	*/
	'.js_checkbox_multiple_select unchecked': function (el, ev, rsId) {
		// find the resource to select functions of its id
		var i = mad.model.List.indexOf(this.options.items, rsId);
		var resource = this.options.items[i];

		if (this.beforeUnselect()) {
			this.unselect(resource);
		}

		// if there is no more selected resources, switch the grid to its initial state
		if (!this.options.selectedRs.length) {
			this.setState('ready');

		// else if only one resource is selected
		} else if (this.options.selectedRs.length == 1) {
			this.setState('selection');
		}
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Listen to the workspace filter event.
	 * @param {jQuery} element The source element
	 * @param {Event} event The jQuery event
	 * @param {passbolt.model.Filter} filter The filter settings
	 */
	'{mad.bus.element} filter_workspace': function(el, ev, filter) {
		this.filterBySettings(filter);
	},

	/**
	 * Observe when an item is unselected
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {passbolt.model.Resource|array} items The unselected items
	 */
	'{selectedRs} remove': function (el, ev, items) {
		for (var i in items) {
			this.unselect(items[i]);
		}
	},

	/* ************************************************************** */
	/* LISTEN TO THE STATE CHANGES */
	/* ************************************************************** */

	/**
	* Listen to the change relative to the state Ready.
	* The ready state is fired automatically after the Component is rendered
	* @param {boolean} go Enter or leave the state
	*/
	stateReady: function (go) {
		// nothing to do
	},

	/**
	* Listen to the change relative to the state selected
	* @param {boolean} go Enter or leave the state
	*/
	stateSelection: function (go) {
		// nothing to do
	},

	/**
	* Listen to the change relative to the state multipleSelected
	* @param {boolean} go Enter or leave the state
	*/
	stateMultipleSelection: function (go) {
		// nothing to do
	},

    /**
     * Listen to changes related to state empty (when there are no passwords to show).
     * @param {boolean} go Enter or leave the state
     */
    stateEmpty: function (go) {
        if (go) {
            if (this.filterSettings.case == 'all_items') {
                var empty_html = mad.View.render("app/view/template/component/password_workspace_all_items_empty.ejs");
                $('.tableview-content', self.element).prepend(empty_html);
            }
        }
        else {
            // Remove any empty content html from page.
            // (empty content is the html displayed when the workspace is empty).
            $('.empty-content', self.element).remove();
        }
    }

});

export default PasswordBrowser;

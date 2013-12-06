steal(
    'mad/controller/component/gridController.js',
    'app/model/user.js',
    'app/model/group.js',
	'app/model/profile.js'
).then(function () {

        /*
         * @class passbolt.controller.component.UserBrowserController
         * @inherits {mad.controller.component.GridController}
         * @parent index
         *
         * Our password grid controller
         *
         * @constructor
         * Creates a new User Browser Controller
         *
         * @param {HTMLElement} element the element this instance operates on.
         * @param {Object} [options] option values for the controller.  These get added to
         * this.options and merged with defaults static variable
         * @return {passbolt.controller.component.UserBrowserController}
         */
        mad.controller.component.GridController.extend('passbolt.controller.component.UserBrowserController', /** @static */ {

            'defaults': {
                // the type of the item rendered by the grid
                itemClass: passbolt.model.User,
                // the list of resources displayed by the grid
                users: new can.Model.List(),
                // the list of displayed categories
                // categories: new passbolt.model.Category.List()
                groups: [],
                // the selected resources, you can pass an existing list as parameter of the constructor to share the same list
                selectedUsers: new can.Model.List()
            }

        }, /** @prototype */ {

            // Constructor like
            'init': function (el, options) {

                // The map to use to make our grid working with our resource model
                options.map = new mad.object.Map({
                    'id': 'id',
                    'name': 'name',
                    'username': 'username',
                    'modified': 'modified',
                    'Group': 'Group',
					'Profile': 'Profile'
                });

                // the columns model
                options.columnModel = [{
                    'name': 'multipleSelect',
                    'index': 'multipleSelect',
                    'header': {
                        'css': ['selections s-cell'],
                        'label': '<div class="input checkbox"> \
							<input type="checkbox" name="select all" value="checkbox-select-all" id="checkbox-select-all"> \
							<label for="checkbox-select-all">select all</label> \
						</div>'
                    },
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
                        checkbox.start();
                    }
                },  {
                    'name': 'name',
                    'index': 'Profile',
                    'header': {
                        'css': ['m-cell'],
                        'label': __('User')
                    },
                    'valueAdapter': function (value, item, columnModel, rowNum) {
                        return '<img src="img/user.png" alt="your picture" width="30" height="30">' + item.Profile.first_name + ' ' + item.Profile.last_name;
                    }
                }, {
                    'name': 'username',
                    'index': 'username',
                    'header': {
                        'css': ['m-cell'],
                        'label': __('Username')
                    }
                },  {
                    'name': 'modified',
                    'index': 'modified',
                    'header': {
                        'css': ['m-cell'],
                        'label': __('Modified')
                    },
                    'valueAdapter': function (value, item, columnModel, rowNum) {
                        return moment(value).fromNow();
                    }
                }];

				//this.createUser();

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
            'insertItem': function (user, refUserId, position) {
                // add the resource to the list of observed resources
                this.options.users.push(user);
                // insert the item to the grid
                this._super(user, refUserId, position);
            },

            /**
             * Remove an item to the grid
             * @param {mad.model.Model} item The item to remove
             * @return {void}
             */
            'removeItem': function (item) {
                // remove the item to the grid
                this._super(item);
            },

            /**
             * reset
             * @return {void}
             */
            'reset': function () {
                // reset the list of observed resources
                // by removing a resource from the resources list stored in options, the Browser will
                // update itself (check "{resources} remove" listener)
                this.options.users.splice(0, this.options.users.length);
            },

            /**
             * Load resources in the grid
             * @param {passbolt.model.Resource.List} resources The list of resources to
             * load into the grid
             * @return {void}
             */
            'load': function (users) {
                // load the resources
                this._super(users);
            },

			/**
			 * Before selecting an item
			 * @param {mad.model.Model} item The item to select
			 * @return {void}
			 */
			'beforeSelect': function (item) {
				var self = this,
					returnValue = true;

				if (this.state.is('selection')) {
					// if an item has already been selected
					// if the item is already selected, unselect it
					if (this.options.selectedUsers.length > 0 && this.options.selectedUsers[0].id == item.id) {
						this.unselect(item);
						this.setState('ready');
						returnValue = false;
					} else {
						for (var i = this.options.selectedUsers.length - 1; i > -1; i--) {
							this.unselect(this.options.selectedUsers[i]);
						}
					}
				}

				return returnValue;
			},

			/**
			 * Select an item
			 * @param {mad.model.Model} item The item to select
			 * @param {boolean} silent Do not propagate any event (default:false)
			 * @return {void}
			 */
			'select': function (item, silent) {
				var self = this;
				silent = typeof silent == 'undefined' ? false : silent;

				// add the resource to the list of selected items
				this.options.selectedUsers.push(item);
				// check the checkbox (if it is not already done)
				mad.app.getComponent('multiple_select_checkbox_' + item.id)
					.setValue([item.id]);
				// make the item selected in the view
				this.view.selectItem(item);

				// notice the application about this selection
				if (!silent) {
					mad.bus.trigger('user_selected', item);
				}
			},

			/**
			 * Before unselecting an item
			 * @param {mad.model.Model} item The item to unselect
			 * @return {void}
			 */
			'beforeUnselect': function (item) {
				var returnValue = true;
				return returnValue;
			},

			/**
			 * Unselect an item
			 * @param {mad.model.Model} item The item to unselect
			 * @param {boolean} silent Do not propagate any event (default:false)
			 * @return {void}
			 */
			'unselect': function (item, silent) {
				silent = typeof silent == 'undefined' ? false : silent;

				// uncheck the associated checkbox (if it is not already done)
				mad.app.getComponent('multiple_select_checkbox_' + item.id)
					.reset();
				// unselect the item in grid
				this.view.unselectItem(item);

				// remove the resource from the previously selected resources
				mad.model.List.remove(this.options.selectedUsers, item);

				// notice the app about the just unselected resource
				if (!silent) {
					mad.bus.trigger('user_unselected', item);
				}
			},

			'createUser': function(){
				// get the category from the filter
				/*var categories = [];
				this.options.filter.tags.each(function(val, i){
					categories.push({
						'id': val.id
					});
				});*/
				// create the resource which will be used by the form builder to populate the fields
				var user = new passbolt.model.User();

				// get the dialog
				var dialog = new mad.controller.component.DialogController(null, {label: __('Add User')})
					.start();

				// attach the component to the dialog
				var form = dialog.add(passbolt.controller.form.user.CreateFormController, {
					data: user,
					callbacks : {
						submit: function (data) {
							var user = new passbolt.model.User(data['passbolt.model.User']);
							user.save();
							dialog.remove();
						}
					}
				});
				form.load(user);
			},

            /* ************************************************************** */
            /* LISTEN TO THE MODEL EVENTS */
            /* ************************************************************** */

            /**
             * Observe when a resource is created.
             * If the created resource belong to a displayed category, add the resource to the grid.
             * @param {mad.model.Model} model The model reference
             * @param {HTMLEvent} ev The event which occured
             * @param {passbolt.model.Resource} resource The created resource
             * @return {void}
             */
            /*'{passbolt.model.User} created': function (model, ev, resource) {
                var self = this;

                // If the new resource belongs to one of the categories displayed by the resource
                // browser -> Insert it
                resource.Category.each(function(category, i) {
                    if(self.options.categories.indexOf(category.id) != -1) {
                        self.insertItem(resource, null, 'first');
                        return false; // break
                    }
                });
            },*/

            /**
             * Observe when a resource is updated.
             * If the resource is displayed by he grid, refresh it.
             * note : We listen the model directly, listening on changes on
             * a list seems too much here (one event for each updated attribute)
             * @param {mad.model.Model} model The model reference
             * @param {HTMLEvent} ev The event which occured
             * @param {passbolt.model.Resource} resource The updated resource
             * @return {void}
             */
            /*'{passbolt.model.Resource} updated': function (model, ev, resource) {
                if (this.options.resources.indexOf(resource) != -1) {
                    this.refreshItem(resource);
                }
            },*/

            /**
             * Observe when resources are removed from the list of displayed resources and
             * remove it from the grid
             * @param {mad.model.Model} model The model reference
             * @param {HTMLEvent} ev The event which occured
             * @param {passbolt.model.Resource} resources The removed resource
             * @return {void}
             */
            /*'{resources} remove': function (model, ev, resources) {
                var self = this;
                can.each(resources, function (resource, i) {
                    self.removeItem(resource);
                });
            },*/

            /**
             * Observe when a category is removed. And remove from the grid all the resources
             * which are not belonging to a displayed Category.
             * @param {mad.model.Model} model The model reference
             * @param {HTMLEvent} ev The event which occured
             * @param {passbolt.model.Category} category The removed category
             * @return {void}
             */
            /*'{passbolt.model.Category} destroyed': function (model, ev, category) {
                var self = this;

                // remove from the list of displayed categories the given deleted category and its children
                var destroyedCategories = mad.model.Model.nestedToList(category, 'children');
                var destroyedCategoriesIds = [];
                can.each(destroyedCategories, function(destroyedCategory, h) {
                    var indexof = self.options.categories.indexOf(destroyedCategory.id);
                    if (indexof != -1) {
                        // remove the destroyed categories from the display categories array
                        self.options.categories.splice(indexof, 1);
                    }
                    // destroyedCategoriesIds.push(destroyedCategory.id);
                });
            },*/

            /* ************************************************************** */
            /* LISTEN TO THE VIEW EVENTS */
            /* ************************************************************** */

            /**
             * Observe when an item is selected in the grid.
             * This event comes from the grid view
             * @param {HTMLElement} el The element the event occured on
             * @param {HTMLEvent} ev The event which occured
             * @param {mixed} item The selected item instance or its id
             * @param {HTMLEvent} ev The source event which occured
             * @return {void}
             */
            ' item_selected': function (el, ev, item, srcEvent) {
                var self = this;
				console.log("selected");

                // switch to select state
                this.setState('selection');

                if (this.beforeSelect(item)) {
                    this.select(item);
                }
            },

            /**
             * Listen to the check event on any checkbox form element components.
             *
             * @param {HTMLElement} el The element the event occured on
             * @param {HTMLEvent} ev The event which occured
             * @param {mixed} rsId The id of the resource which has been checked
             * @return {void}
             */
            '.js_checkbox_multiple_select checked': function (el, ev, userId) {
                var self = this;

                // if the grid is in initial state, switch it to selected
                if (this.state.is('ready')) {
                    this.setState('selection');
                }
                // if the grid is already in selected state, switch to multipleSelected
                else if (this.state.is('selection')) {
                    this.setState('multipleSelection');
                }

                // find the resource to select functions of its id
                var i = mad.model.List.indexOf(this.options.users, userId);
                var user = this.options.users[i];

                if (this.beforeSelect(user)) {
                    this.select(user);
                }
            },

            /**
             * Listen to the uncheck event on any checkbox form element components.
             *
             * @param {HTMLElement} el The element the event occured on
             * @param {HTMLEvent} ev The event which occured
             * @param {mixed} rsId The id of the resource which has been unchecked
             * @return {void}
             */
            /*'.js_checkbox_multiple_select unchecked': function (el, ev, rsId) {
                var self = this;

                // find the resource to select functions of its id
                var i = mad.model.List.indexOf(this.options.resources, rsId);
                var resource = this.options.resources[i];

                if (this.beforeUnselect()) {
                    self.unselect(resource);
                }

                // if there is no more selected resources, switch the grid to its initial state
                if (!this.options.selectedRs.length) {
                    this.setState('ready');

                    // else if only one resource is selected
                } else if (this.options.selectedRs.length == 1) {
                    this.setState('selection');
                }
            },*/

            /* ************************************************************** */
            /* LISTEN TO THE APP EVENTS */
            /* ************************************************************** */

            /**
             * Observe when the application is ready and load the tree with the roots
             * categories
             * @param {jQuery} element The source element
             * @param {Event} event The jQuery event
             * @return {void}
             */
            'afterStart': function () {
                var self = this;

                this.setState('loading');
                // load default resources
                passbolt.model.User.findAll({}, function (users, response, request) {
                    // load the resources in the browser
                    self.load(users);
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
            'stateSelection': function (go) {
                // nothing to do
            },

            /**
             * Listen to the change relative to the state multipleSelected
             * @param {boolean} go Enter or leave the state
             * @return {void}
             */
            'stateMultipleSelection': function (go) {
                // nothing to do
            }

        });

    });
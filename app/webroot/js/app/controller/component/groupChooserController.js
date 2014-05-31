steal(
    'mad/controller/component/dynamicTreeController.js',
	'app/view/component/groupChooser.js',
    'app/model/group.js',
	'app/model/groupUser.js'
).then(function () {

        /*
         * @class passbolt.controller.GroupChooserController
         * @inherits mad.controller.component.DynamicTreeController
         * @parent index
         *
         * Our group chooser component.
         * It will allow the user to select a group.
         *
         * @constructor
         * Creates a new Group Chooser Controller.
         *
         * @param {HTMLElement} element the element this instance operates on.
         * @param {Object} [options] option values for the controller.  These get added to
         * this.options and merged with defaults static variable
         * @return {passbolt.controller.GroupChooserController}
         */
        mad.controller.component.DynamicTreeController.extend('passbolt.controller.component.GroupChooserController', /** @static */ {

            'defaults': {
                'label': 'Group Chooser',
				// Specific view for groupChooser to handle dropping of elements.
				'viewClass': passbolt.view.component.groupChooser,
                'itemClass': passbolt.model.Group,
                'templateUri': 'mad/view/template/component/tree.ejs',
                // The map to use to make jstree working with our category model
                'map': new mad.object.Map({
                    'id': 'id',
                    'label': 'name'
                })
            }

        }, /** @prototype */ {

            /* ************************************************************** */
            /* LISTEN TO THE APP EVENTS */
            /* ************************************************************** */

            /**
             * Called right after the start function
             * @return {void}
             * @see {mad.controller.ComponentController}
             */
            'afterStart': function() {
                var self = this;
                // load categories function of the selected database
                this.setState('loading');
                passbolt.model.Group.findAll({

                }, function (groups, response, request) {
                    // load the tree with the groups
                    self.load(groups);
                    self.setState('ready');
                }, function (response) { });
            },

			/**
			 * Show the contextual menu
			 * @param {passbolt.model.Group} item The item to show the contextual menu for
			 * @param {string} x The x position where the menu will be rendered
			 * @param {string} y The y position where the menu will be rendered
			 * @return {void}
			 */
			'showContextualMenu': function (item, x, y) {
				var menuItems = mad.model.Action.models([
					{
						'id': uuid(),
						'label': 'Open',
						'action': function (menu) {
							mad.bus.trigger('category_selected', item);
							menu.remove();
						}
					}, {
						'id': uuid(),
						'label': 'Create user',
						'action': function (menu) {
							mad.bus.trigger('request_user_creation', item);
							menu.remove();
						}},
					{
						'id': uuid(),
						'label': 'Create group',
						'action': function (menu) {
							mad.bus.trigger('request_group_creation', item);
							menu.remove();
						}
					}, {
						'id': uuid(),
						'label': 'Rename...',
						'action': function (menu) {
							mad.bus.trigger('request_group_edition', item);
							menu.remove();
						}
					}, {
						'id': uuid(),
						'label': 'Remove',
						'action': function (menu) {
							mad.bus.trigger('request_group_deletion', item);
							menu.remove();
						}
					}
				]);

				// Contextual menu
				var contextualMenu = new mad.controller.component.ContextualMenuController(null, {'mouseX': x, 'mouseY': y});
				contextualMenu.start();
				contextualMenu.load(menuItems);
			},

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */

			/**
			 * An item has been selected
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @param {passbolt.model.Category} item The selected item instance or its id
			 * @param {HTMLEvent} srcEvent The source event which occured
			 * @return {void}
			 */
			' item_selected': function (el, ev, item, srcEvent) {
				mad.bus.trigger('group_selected', item);
			},


			/**
			 * A user has been dragged and dropped on a group
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @param {jQuery.Drop} drop The drop object
			 * @param {jQuery.Drag} drag The drag object
			 * @param {HTMLEvent} srcEvent
			 */
			' group_dropon': function(el, ev, drop, drag, srcEvent) {
				var groupId = drop.element.parent().attr("id");
				var userId = drag.element.attr("id");
				// Save it.
				new passbolt.model.GroupUser({
					group_id : groupId,
					user_id : userId
				})
				.save();
			},

			/**
			 * An item has been right selected
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @param {passbolt.model.Category} item The right selected item instance or its id
			 * @param {HTMLEvent} srcEvent The source event which occured
			 * @return {void}
			 */
			' item_right_selected': function (el, ev, item, srcEvent) {
				this._super(el, ev, item, srcEvent);
				this.showContextualMenu(item, srcEvent.pageX, srcEvent.pageY);
			}

        });

    });
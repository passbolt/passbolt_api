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
			}

        });

    });
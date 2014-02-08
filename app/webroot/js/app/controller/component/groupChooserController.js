steal(
    'mad/controller/component/dynamicTreeController.js',
    'app/model/group.js'
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
                'viewClass': mad.view.component.tree.List,
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
            }

        });

    });
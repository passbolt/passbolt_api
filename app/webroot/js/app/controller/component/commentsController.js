steal(
    'app/view/component/comments.js'
).then(function () {

        /*
         * @class passbolt.controller.CommentsController
         * @inherits mad.controller.component.ComponentController
         * @parent index
         *
         * @constructor
         * Creates a new Comments controller
         *
         * @param {HTMLElement} element the element this instance operates on.
         * @param {Object} [options] option values for the controller.  These get added to
         * this.options and merged with defaults static variable
         * @return {passbolt.controller.CommentsController}
         */
        mad.controller.ComponentController.extend('passbolt.controller.component.CommentsController', /** @static */ {

            'defaults': {
                'label': 'Comments Controller',
                'viewClass': passbolt.view.component.Comments,
                'templateUri': 'app/view/template/component/comments.ejs'
                // the resource to bind the component on
                //'resource': null,
                // the selected resources, you can pass an existing list as parameter of the constructor to share the same list
                //'selectedRs': new can.Model.List()
            }

        }, /** @prototype */ {

            /**
             * before start hook.
             * @return {void}
             */
            'beforeRender': function() {
                this._super();
                // pass the new resource to the view
                //this.setViewData('resource', this.options.resource);
            },

            /**
             * Load details of a resource
             * @param {passbolt.model.Resource} resource The resource to load
             * @return {void}
             */
            'load': function (resource) {
                // push the new resource in the options to be able to listen the resource
                // change in the function name
                /*this.options.resource = resource;
                // if the component is not started, start it
                if(this.state.is(null)) {
                    this.start();
                    // otherwise refresh the component
                } else {
                    // refresh the view
                    this.refresh();
                }*/
            }
        });

    });
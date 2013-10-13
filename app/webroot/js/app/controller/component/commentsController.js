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
                'resource': null
            }

        }, /** @prototype */ {
					
					// Constructor like
					'init': function(el, opts) {
						this._super(el, opts);
						console.log('the resource to take care');
						console.log(this.options.resource);
					}
					
        });

    });
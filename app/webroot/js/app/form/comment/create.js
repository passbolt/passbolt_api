import 'mad/form/form';
import 'app/view/component/comments';
import 'app/view/template/form/comment/add.ejs!';

passbolt.form.comment = passbolt.form.comment || {};

/**
 * @inherits {mad.Form}
 * @parent index
 *
 * @constructor
 * Instanciate a Comment Create Form
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.form.comment.Create}
 */
var Create = passbolt.form.comment.Create = mad.Form.extend('passbolt.form.comment.Create', /** @static */ {
	defaults: {
		templateBased: true,
		/**
		 * the foreign Model on which to plug the comments system
		 */
		foreignModel: null,
		/**
		 * The foreign id where to plug the new comments
		 */
		foreignId: null,
		/**
		 * default callback
		 */
		callbacks: {
			submit: function (data) {
				// TODO : validate
				var instance = new passbolt.model.Comment(data['passbolt.model.Comment'])
					.save();
			}
		},
		templateUri: 'app/view/template/form/comment/add.ejs',
		commentContentField: null
	}
}, /** @prototype */ {

	/**
	 * Init.
	 * @param el
	 * @param options
	 */
	init: function(el, options) {
		this._super(el, options);
		this.setViewData('user', passbolt.model.User.getCurrent());
	},

	/**
	 * After start hook.
	 * Initialize the form elements.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		// parent_id hidden field
		this.addElement(
			new mad.form.Textbox($('.js_comment_parent_id', this.element), {
				modelReference: 'passbolt.model.Comment.parent_id'
			}).start()
		);

		// foreign_model hidden field
		this.addElement(
			new mad.form.Textbox($('.js_comment_foreign_model', this.element), {
				modelReference: 'passbolt.model.Comment.foreign_model'
			}).start().setValue('Resource')
		);

		// foreign_id hidden field
		this.addElement(
			new mad.form.Textbox($('.js_comment_foreign_id', this.element), {
				modelReference: 'passbolt.model.Comment.foreign_id'
			}).start().setValue(this.options.foreignId)
		);

		// feedback.
		this.options.commentContentField = new mad.form.Textbox($('.js_comment_content', this.element), {
			modelReference: 'passbolt.model.Comment.content'
		}).start();
		this.addElement(
			this.options.commentContentField,
			new mad.form.Feedback($('.js_comment_content_feedback', this.element), {}).start()
		);
	},

    /**
     * Empty content of the comment content field.
     */
    emptyContent: function() {
        this.options.commentContentField.setValue('');
    },

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * State ready.
	 * Empty the comment content field.
	 */
	stateReady: function() {
		this.options.commentContentField.setValue('');
	},

    /**
     * State hidden.
     * @param go
     */
    stateHidden: function (go) {
        this._super(go);
        // Reinitialize number of validations to avoid inline validation
        // each time the form appears.
        this.validations = 0;
    }
});

export default Create;
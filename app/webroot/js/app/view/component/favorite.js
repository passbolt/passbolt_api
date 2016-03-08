import 'mad/view/view';
import 'app/view/template/component/favorite.ejs!';

/**
 * @inherits mad.view.View
 */
var Favorite = passbolt.view.component.Favorite = mad.View.extend('passbolt.view.component.Favorite', /** @static */ {

}, /** @prototype */ {

	/**
	 * Mark as a favorite.
	 */
	favorite: function (el, ev) {
		$('i', this.element).removeClass('fav').addClass('unfav');
	},

	/**
	 * Unmark as a favorite.
	 */
	unfavorite: function (el, ev) {
		$('i', this.element).removeClass('unfav').addClass('fav');
	}

});
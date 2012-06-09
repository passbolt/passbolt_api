steal('jquery/dom/cookie',function(){
Favorites = {
	toggle: function( who ) {
		var favs = this.findAll();
		var isfav = Favorites.isFavorite(who);
		if ( isfav ) {
			for ( var f = 0; f < favs.length; f++ )
			if ( favs[f].name == who.name ) {
				favs.splice(f, 1);
				break;
			}
		} else {
			favs.push(who);
		}
		fav = $.toJSON(favs)
		$.cookie("favorites", fav, {
			expires: 364
		});
		return !isfav
	},
	findAll: function() {
		var fav = $.cookie("favorites"),
			favs;
		if (!fav ) {
			favs = []
		} else {
			favs = eval("(" + fav + ")");
		}
		return favs;
	},
	isFavorite: function( who ) {
		var favs = Favorites.findAll();

		for ( var f = 0; f < favs.length; f++ )
		if ( favs[f].name == who.name ) return true;
		return false;
	}
};
})
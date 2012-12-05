App = window.App || {};

App.Search = (function () {
	var searchInput,
		searchResults,
		doSearch,
		limit = 25,
		paginationContainer;


	var handleKeyEvent = function (event) {
		App.InlineSearch.delay(function () {
			searchResults.empty();
			doSearch(searchInput.val());
		}, 200);
	};

	var paginate = function (event) {
		var active = $(event.target);
		var page = active.attr('page');
		event.preventDefault();

		App.InlineSearch.executeSearch(searchInput.val(), searchResults, undefined, page);
	};

	var createPagination = function (event, results) {
		var element, i;
		var total = results.total;
		var page = results.page;
		var pages = Math.floor(total / limit);

		paginationContainer.empty();

		for (i = 1; i <= pages; i++) {
			element = $('<a href="#"></a>');
			element.text(i);
			element.attr('page', i);
			if (i == page) {
				element.addClass('active');
			}
			paginationContainer.append(element);
		}
	};

	var init = function () {
		searchInput = $('.standalone-search .search-input');
		searchResults = $('#search-results');
		paginationContainer = $('#search-pagination');

		searchInput.bind('keyup', handleKeyEvent);
		$(document).bind('search.complete', createPagination);
		paginationContainer.delegate('a', 'click', paginate);

		doSearch = App.InlineSearch.createSearch(searchResults);
		var params = $.getQueryParameters();
		if (params.q) {
			searchInput.val(params.q).trigger('keyup');
		}
	};


	return {
		init: init
	};
})();

$(App.Search.init);

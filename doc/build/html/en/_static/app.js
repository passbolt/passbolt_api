if (/cakephp\.org/.test(document.domain)) {
	document.domain = 'cakephp.org';
}

App = {};
App.config = {
	url: 'http://search.cakephp.org/search',
	version: '2-2'
};

App.Book = (function() {

	function init() {

		// Make top nav responsive.
		$('#cakephp-global-navigation').menuSelect({'class': 'nav-select'});

		// Make side navigation go into a lightbox.
		$('#tablet-nav').bind('click', function (e) {
			e.preventDefault();

			// Squirt the nav into the modal.
			var contents = $('#sidebar-navigation').html();
			var modal = $('#nav-modal').html(contents);
			modal.append('<a href="#" class="close-reveal-modal">&#215;</a>');
			modal.reveal({
				animation: 'fade'
			});
		});

		// Display page contents
		$('#page-contents-button').bind('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			$('#page-contents').fadeIn('fast');
		});

		var hideContents = function (e) {
			$('#page-contents').fadeOut('fast');
		};
	
		$(document).bind('click', hideContents);
		$(document).bind('keyup', function (e) {
			if (e.keyCode == 27) {
				hideContents();
			}
		});
	}
 
	function compare_scores(a, b) {
		return b.score - a.score;
	}
 
	return {
		init : init
	};
})();

// Inline search form, and standalone search form.
App.InlineSearch = (function () {

	var segments = location.pathname.split('/');
	var base = location.href.replace(location.protocol + '//' + location.host, '').split('/').slice(0, 2).join('/') + '/';
	var searchResults;
	var searchInput;
	var doSearch;

	var delay = (function(){
		var timer;
		return function(callback, ms){
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
	})();

	var positionElement = function (to, element) {
		var position = to.offset();
		var height = to.outerHeight();
		var width = to.outerWidth();

		element.css({
			position: 'absolute',
			top: position.top + height + window.scrollY + 'px',
			left: position.left + 'px',
			width: width + 'px'
		});
	};

	var handleKeyEvent = function (event) {
		if ($(this).val() === '') {
			searchResults.fadeOut('fast');
		} else {
			var _this = $(this);
			delay(function() {
				positionElement(_this, searchResults);
				searchResults.show();
				doSearch(_this.val());
			}, 200);
		}
	};

	// escape key
	var handleEscape = function (event) {
		if (event.keyCode == 27) {
			searchResults.fadeOut('fast');
		}
	};

	// Returns a function that is used for executing searches.
	// Allows re-use between inline and standalone search page.
	var createSearch = function (searchResult, limit) {
		return function (value) {
			executeSearch(value, searchResult, limit);
		};
	};

	var executeSearch = function (value, searchResults, limit, page) {
		var query = {lang: window.lang, q: value, version: App.config.version};
		if (page) {
			query.page = page;
		}
		var url = App.config.url + '?' + jQuery.param(query);
		var xhr = $.ajax({
			url: url,
			dataType: 'json',
			type: 'GET'
		});

		xhr.done(function (response) {
			var results;
			searchResults.empty().append('<ul></ul>');
			results = response.data;
			if (limit) {
				results = response.data.slice(0, limit);
			}
			var ul = searchResults.find('ul');
			$.each(results, function(index, item) {
				var li = $('<li></li>');
				var link = $('<a></a>');
				link.attr('href', base + item.url);
				if (item.title) {
					link.append('<strong>' + item.title + '</strong><br />');
				}
				var span = $('<span></span>');
				span.text(item.contents.join("\n"));
				link.append(span);
				li.append(link);
				ul.append(li);
			});
			$(document).trigger('search.complete', [response]);
		});
	};

	var init = function () {
		searchInput = $('.masthead .search-input');
		searchResults = $('#inline-search-results');

		searchInput.keyup(handleKeyEvent);
		$(document).keyup(handleEscape);
	
		doSearch = createSearch(searchResults, 10);
	};

	return {
		init: init,
		delay: delay,
		searchUrl: App.config.url,
		createSearch: createSearch,
		executeSearch: executeSearch
	};
})();


// http://stackoverflow.com/questions/967096/using-jquery-to-test-if-an-input-has-focus
jQuery.extend(jQuery.expr[':'], {
	focus: function(element) { 
		return element == document.activeElement; 
	}
});

(function ($) {
var defaults = {
	'class': ''
};
$.fn.menuSelect = function (options) {
	options = $.extend({}, defaults, options || {});

	// Append the li & children to target.
	var appendOpt = function (li, target, prefix) {
		li = $(li);
		prefix = prefix || '';

		if (li.find('> ul').length) {
			prefix = li.find('> a').text() + ' - ';
			li.find('li').each(function () {
				appendOpt(this, target, prefix);
			});
		} else {
			var a = li.find('a');
			opt = $('<option />', {
				text: prefix + a.text(),
				value: a.attr('href')
			});
			target.append(opt);
		}
	};

	// Convert the ul + li elements into
	// an optgroup + option elements.
	var convert = function (element) {
		var select = $('<select />', options);
		select.appendTo(element);
		var option = $('<option />', {
			text: 'Go to..',
			selected: 'selected'
		});
		select.append(option);

		$(element).find('> ul > li').each(function () {
			var opt, optgroup;
			var li = $(this);
			if (li.find('ul').length > 0) {
				optgroup = $('<optgroup />', {
					label: li.find('> a').text()
				});
				select.append(optgroup);
				li.find('> ul > li').each(function () {
					appendOpt(this, optgroup);
				});
			} else {
				appendOpt(li, select);
			}
		});

		var handleChange = function (event) {
			window.location = $(this).val();
		};

		select.bind('change', handleChange);
	};

	return this.each(function () {
		convert(this);
	});
};
} (jQuery));

$(App.Book.init);
$(App.InlineSearch.init);

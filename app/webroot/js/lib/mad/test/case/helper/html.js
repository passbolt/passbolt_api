import "test/bootstrap";

describe("mad.helper.Html", function(){

    // Initialize the helper namespace for tests.
    mad.test.helper = mad.test.helper || {};

	it("create() should insert an HTML content and position it", function(){

		// Inside replace strategy
		var refElement = $('#test-html'),
			position = 'inside_replace',
			id = "inside_replace_element",
			content = '<div id="' + id + '"/>';
		var $component = mad.helper.Html.create(refElement, position, content);
		expect($('#' + id).length).to.be.not.equal(0);
		expect($('#test-html').children().length).to.be.equal(1);

		// Before strategy
		var refElement = $('#inside_replace_element'),
			position = 'before',
			id = "before_element",
			content = '<div id="' + id + '"/>';
		var $component = mad.helper.Html.create(refElement, position, content);
		expect($('#' + id).length).to.be.not.equal(0);
		expect($('#test-html').children().length).to.be.equal(2);
		expect(refElement.prev().attr('id')).to.be.equal(id);

		// After strategy
		var refElement = $('#inside_replace_element'),
			position = 'after',
			id = "after_element",
			content = '<div id="' + id + '"/>';
		var $component = mad.helper.Html.create(refElement, position, content);
		expect($('#' + id).length).to.be.not.equal(0);
		expect($('#test-html').children().length).to.be.equal(3);
		expect(refElement.next().attr('id')).to.be.equal(id);

		// First strategy
		var refElement = $('#test-html'),
			position = 'first',
			id = "first_element",
			content = '<div id="' + id + '"/>';
		var $component = mad.helper.Html.create(refElement, position, content);
		expect($('#' + id).length).to.be.not.equal(0);
		expect($('#test-html').children().length).to.be.equal(4);
		expect(refElement.children().first().attr('id')).to.be.equal(id);

		// Last strategy
		var refElement = $('#test-html'),
			position = 'last',
			id = "last_element",
			content = '<div id="' + id + '"/>';
		var $component = mad.helper.Html.create(refElement, position, content);
		expect($('#' + id).length).to.be.not.equal(0);
		expect($('#test-html').children().length).to.be.equal(5);
		expect(refElement.children().last().attr('id')).to.be.equal(id);

		$('#test-html').empty();
	});

});

module("funcunit-open")

test('Check init', function() {
	S.open('//funcunit/test/coverage/widget.html');
	S('.next a').text('Next', "Next is good")
	S('.prev a').text('Prev', "Prev is good")
	S('.current').text('1', "Value is good")
});

test('Increment', function() {
	S.open('//funcunit/test/coverage/widget.html');
	S('.next a').click();
	S('.current').text('2', "Value is good")
});

test('Decrement', function() {
	S.open('//funcunit/test/coverage/widget.html');
	S('.next a').click();
	S('.prev a').click();
	S('.current').text('1', "Value is good")
});

test('Decrement and underflow', function() {
	S.open('//funcunit/test/coverage/widget.html');
	S('.prev a').click();
	S('.current').text('10', "Value is good")
});

test('Increment to overflow', function() {
	S.open('//funcunit/test/coverage/widget.html');
	S('.next a').click();
	S('.next a').click();
	S('.next a').click();
	S('.next a').click();
	S('.next a').click();
	S('.next a').click();
	S('.next a').click();
	S('.next a').click();
	S('.next a').click();
	S('.next a').click();
	S('.current').text('1', "Value is good")
});
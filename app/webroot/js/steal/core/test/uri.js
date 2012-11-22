module('URI');
var uri = URI('http://steal.js/core/uri.js?some=argument#some_fragment');
test("URI parser", function(){
	equal(uri.host, 'steal.js', 'Host is correct')
	equal(uri.path, '/core/uri.js', 'Path is correct')
	equal(uri.protocol, 'http', 'Protocol is correct')
	equal(uri.fragment, 'some_fragment', 'Fragment is correct')
	equal(uri.query, 'some=argument', 'Query string is correct')
})

test('URI.prototype.dir', function(){
	equal(uri.dir().path, '/core')
})

test('URI.prototype.filename', function(){
	equal(uri.filename(), 'uri.js')
})

test('URI.prototype.ext', function(){
	equal(uri.ext(), 'js')
})

test('URI.prototype.domain', function(){
	equal(uri.domain(), 'http://steal.js')
})

test('URI.prototype.isCrossDomain', function(){
	ok(uri.isCrossDomain(), 'It is crossdomain to the page URL');
	equal(uri.isCrossDomain('http://steal.js/foo.bar'), false, 'It is not crossdomain to the URL on the same domain');
})

test('URI.prototype.isRelativeToDomain', function(){
	equal(uri.isRelativeToDomain(), true)
})

test('URI.prototype.hash', function(){
	equal(uri.hash(), '#some_fragment')
})

test('URI.prototype.search', function(){
	equal(uri.search(), '?some=argument')
})

test('URI.prototype.add', function(){
	equal(uri.add('../foo/bar?foo=bar'), 'http://steal.js/core/foo/bar?foo=bar')
})

test('URI.prototype.join', function(){
	var newUri = uri.join('../foo/bar?foo=bar');
	equal(newUri.host, 'steal.js', 'Host is correct')
	equal(newUri.path, '/core/foo/bar', 'Path is correct')
	equal(newUri.protocol, 'http', 'Protocol is correct')
	equal(newUri.query, 'foo=bar', 'Query string is correct');
	
	var newUri = uri.join('ftp://foo/bar');
	equal(newUri.host, 'foo', 'Host is set correctly when URL is crossdomain')
	equal(newUri.protocol, 'ftp', 'Protocol is set correctly when URL is crossdomain')
})

test('URI.prototype.normalize', function(){
	var newUri = URI('./foo/bar').normalize(uri);
	equal(newUri.host, 'steal.js', 'Host is correct')
	equal(newUri.path, '/core/foo/bar', 'Path is correct')
	equal(newUri.protocol, 'http', 'Protocol is correct')
})
test('URI.prototype.isRelative', function(){
	ok(uri.isRelative());
	ok(URI('./foo/bar').isRelative())
	equal(URI('foo/bar').isRelative(), false)
})
test('URI.prototype.pathTo', function(){
	equal(uri.pathTo('http://steal.js/foo/bar/baz') + "", '../../foo/bar/baz')
})
test('URI.prototype.addJS', function(){
	var newUri = URI('jquery');
	equal(newUri.addJS() + "", 'jquery/jquery.js')
})
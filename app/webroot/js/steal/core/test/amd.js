module("AMD");

config = new ConfigManager({});

test('st.id', function(){
	equal(st.id("foo/bar") + "", "foo/bar/bar.js");
	equal(st.id("./baz", "foo/bar") + "", "foo/baz.js");
	equal(st.id("../baz", "foo/bar") + "", "baz.js");
	config.attr({
		map: {
			'*' : {
				"foo/bar": "http://localhost/foo/foo/bar"
			}
		}
	})
	equal(st.id("foo/bar") + "", "http://localhost/foo/foo/bar/bar.js");
});

test('st.amdToId', function(){
	config.attr({
		map: {
			'*' : {
				"http://localhost/foo/foo/bar": "foo/bar"
			}
		}
	})
	equal(st.amdToId("http://localhost/foo/foo/bar") + "", "foo/bar")
});

test('st.idToUri', function(){
	config.attr({
		paths: {
			"foo/baz" : "http://localhost/foo/baz/baz.js"
		}
	})
	equal(st.idToUri("foo/baz"), "http://localhost/foo/baz/baz.js")
});

test('st.amdIdToUri', function(){
	config.attr({
		paths: {
			"baz/foo/" : "http://localhost/foo/baz/baz.js"
		}
	})
	equal(st.amdIdToUri("baz/foo/"), "http://localhost/foo/baz/baz.js")
});
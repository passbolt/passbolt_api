// Usage: node funcunit/proxy.js --port=9999

var fs = require('fs'),
	url = require('url')
	path = require('path'),
	http = require('http'),
	port = 9999;

// parse out command line options
process.argv.forEach(function(val) {
	if (val.indexOf('--port') == 0) {
		port = parseInt(val.split('=')[1])
	}
})

function serveLocalFile(req, res) {
	var parsed = url.parse(req.url),
		urlpath = parsed.pathname,
		fullurl = path.normalize(path.join('.', urlpath));
		
	if (~fullurl.indexOf('..')) {
		// don't send back any files above the directory we were invoked from
		res.writeHead(404)
		res.end()
	} else {
		// try to open the file locally
		fs.readFile(fullurl, function(err, data) {
			if (err) {
				res.writeHead(404)
			} else {
				res.writeHead(200)
				res.write(data)
			}
			res.end()
		})
	}
}

function proxyToRemote(req, res) {
	var proxy = http.createClient(80, req.headers['host']),
			preq = proxy.request(req.method, req.url, req.headers)
	
	preq.on('response', function(pres) {
		// forward events from the proxy's response back to our client
		pres.on('data', function(chunk) {
			res.write(chunk, 'binary')
		})
		pres.on('end', function() {
			res.end()
		})
		
		// write the proxy's status/headers back to our client
		res.writeHead(pres.statusCode, pres.headers)
	})	
	
	// forward events from our client's request onto the proxy
	req.on('data', function(chunk) {
		preq.write(chunk, 'binary')
	})
	req.on('end', function() {
		preq.end()
	})
}

http.createServer(function(req, res) {
	var parsed = url.parse(req.url),
		path = parsed.pathname;
		
	// anything under these directories will be served locally
	if (path.indexOf('/funcunit') == 0 ||
			path.indexOf('/steal') == 0 ||
			path.indexOf('/test') == 0) {
		serveLocalFile(req, res)
	} else {
		proxyToRemote(req, res)
	}
}).listen(port)
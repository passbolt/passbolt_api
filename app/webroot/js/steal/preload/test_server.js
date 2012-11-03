/**
 * Module dependencies.
 */

var express = require('express'),
	util = require("util"),
	app = module.exports = express.createServer(),
	path = require("path"),
	fs = require("fs"),
	exec		= require('child_process').exec;

/**/
express.static.mime.define({
	"text/plain" : ["ejs"]
});

// Configuration

app.configure(function(){
  app.use(express.bodyParser());
  app.use(express.methodOverride());
  app.use(app.router);
  app.use(express.static(__dirname + "../../.."));
  app.use(express.directory(__dirname + "../../.."));
});

/** /
app.configure('development', function(){
  app.use(express.errorHandler({ dumpExceptions: true, showStack: true })); 
});
/**/

app.configure('production', function(){
  app.use(express.errorHandler()); 
});

app.get("/steal/preload/test/mock/*", function( req, res, next ) {
	res.setHeader("Expires", "Thu, 31 Dec 2020 20:00:00 GMT");
	res.setHeader("Content-Type", "text/javascript");

	setTimeout( function() {
		res.end( ["window.", req.params[0].split(/\.|\//).shift(), " = true;"].join(""), "utf8" )
	}, req.query.sleep ? req.query.sleep * 1000 : 0 );
});

// Routes
app.listen(3001);
console.log("Express server listening on port %d in %s mode", app.address().port, app.settings.env);
exec("open http://localhost:3001/steal/preload/test/index.html");

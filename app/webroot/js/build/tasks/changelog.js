var https = require('https'),
querystring = require('querystring'),
ejs = require('ejs');

module.exports = function(grunt) {

	grunt.registerMultiTask('changelog', 'Updates changelog.md based on GitHub milestones', function() {
		var done = this.async(),

		params = querystring.stringify({
			milestone: this.data.milestone,
			state: 'closed',
			per_page: 100
		}),

		path = '/repos/' + this.data.user + '/' + this.data.repo + '/issues?' + params,

		buffer = '',
		self = this;

		var write = function() {
			var issues = JSON.parse(buffer),
			log = '';

			if(grunt.file.exists('changelog.md')) {
				log = grunt.file.read('changelog.md');
			};

			ejs.renderFile(__dirname + '/../changelog.ejs', {
				version: self.data.version,
				date: new Date(Date.now()),
				issues: issues
			}, function(e, template) {

				if(e) {
					done(e);
				}

				grunt.file.write('changelog.md', template + log);
				done();

			});
		},

		req = https.request({
			hostname: 'api.github.com',
			path: path
		}, function(res) {

			res.on('data', function(data) {
				buffer += data;
			});

			res.on('end', write);
		});

		req.end();

		req.on('error', function(e) {
			done(e);
		});

	});

}
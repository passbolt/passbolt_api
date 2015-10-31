
module.exports = function(grunt){

	grunt.initConfig({
		testee: {
			options: {
				browsers: ["firefox"]
			},
			all: [
				"test/systemjs.html",
				"test/steal.html"
			]
		}
	});

	grunt.loadNpmTasks("testee");

	grunt.registerTask("test", ["testee:all"]);
};

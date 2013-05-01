var path = require('path');

// A grunt task that writes the banner to every file
module.exports = function (grunt) {
    grunt.registerMultiTask('updateversion', 'Replaces given symbol with current version', function () {
        var options = grunt.config.process(['updateversion', this.target]);
        var version = this.data.version;
        var symbol = this.data.symbol;

        grunt.file.expand(this.data.files).forEach(function (file) {
            var outFile = options.out ? path.join(options.out, path.basename(file)) : file;
            var fileContents = grunt.file.read(file).replace(symbol, version);
            
            if(grunt.file.read(file).match(symbol)) {
                grunt.log.writeln('Updating version in ' + file);
                grunt.file.write(outFile, fileContents.replace(symbol, version));
            }
        });
    });
}

var childProcess = require('child_process'),
    fs = require('fs'),
    path = require('path');

/**
 * List directories.
 * @param srcpath
 * @returns {*}
 */
function getDemoApps(srcpath) {
    var directories = fs.readdirSync(srcpath).filter(function (file) {
        return fs.statSync(path.join(srcpath, file)).isDirectory();
    });
    return directories.map(function (value) {
        return srcpath + '/' + value + '/' + value;
    });
}

module.exports = function (grunt) {

    // ========================================================================
    // High level variables

    var config = {
        path: {
            apps: 'js/app',
            lib: 'js/lib',
            doc: 'docs'
        },
        lib: [
            'can',
            'jquery',
            'steal',
            'underscore',
            'xregexp'
        ]
    };

    // ========================================================================
    // Configure tasks options

    grunt.initConfig({
        config: config,
        pkg: grunt.file.readJSON('package.json'),
        clean: {
            mad_lib: [
                '<%= config.path.lib %>/*'
            ],
            doc: [
                '<%= config.path.doc %>/*'
            ]
        },
        shell: {
            mad_lib_patch: {
                options: {
                    stderr: false
                },
                command: [
                    '(cd ./js/lib/can; patch -p1 < ../../../patches/can-system_preload_template.patch;)',
                    '(cd ./js/lib/can; patch -p1 < ../../../patches/can-util_string_get_object_set_object.patch;)',
                    '(cd ./node_modules/documentjs; patch -p1 < ../../patches/documentjs-demo_tag_url_and_sharp.patch;)'
                ].join('&&')
            }
        },
        copy: {
            mad_lib: {
                files: [{
                    cwd: 'node_modules',
                    src: config.lib.map(function (value) {
                        return value + '/**';
                    }),
                    dest: '<%= config.path.lib %>',
                    expand: true
                }]
            }
        },
        "steal-build": {
            default: {
                options: {
                    system: {
                        config: "stealconfig.js",
                        main: getDemoApps(config.path.apps)
                    },
                    buildOptions: {
                        minify: false
                    }
                }
            }
        }
    });

    // ========================================================================
    // Initialise

    grunt.loadNpmTasks('documentjs');

    grunt.loadNpmTasks('grunt-contrib-clean');

    grunt.loadNpmTasks('grunt-shell');

    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.loadNpmTasks("steal-tools");

    // ========================================================================
    // Register Tasks

    // Deploy libs
    grunt.registerTask('lib-deploy', ['clean:mad_lib', 'copy:mad_lib', 'shell:mad_lib_patch']);

    // Clean & generate the documentation
    grunt.registerTask('mad-doc', ['clean:doc', 'documentjs']);

    // Build mad & all the demos apps to ensure that everything compile
    grunt.registerTask("build", ["steal-build"]);

};

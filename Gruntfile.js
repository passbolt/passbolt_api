/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
var path = require('path');

/**
 * This Gruntfile provides tasks and commands to build and distribute the project
 *
 * @param grunt object
 */
module.exports = function(grunt) {

  /**
   * Path shortcuts
   * @type object
   */
  var paths = {
    node_modules: 'node_modules/',
    node_modules_appjs: 'node_modules/passbolt-appjs/',
    node_modules_styleguide: 'node_modules/passbolt-styleguide/',
    webroot: 'webroot/',
    img: 'webroot/img/',
    css: 'webroot/css/',
    js: 'webroot/js/'
  };

  /**
   * Import package.json file content
   * Allow to get access to version and project name for example
   */
  var pkg = grunt.file.readJSON('package.json');

  /**
   * Load baseline NPM tasks
   */
  var root = path.resolve('node_modules');
  var pkgfile = path.join(root, 'grunt-browser-sync', 'package.json');
  if (grunt.file.exists(pkgfile)) {
    grunt.loadNpmTasks('grunt-browser-sync');
  }
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-watch');

  /**
   * Register project specific grunt tasks
   */
  grunt.registerTask('default', ['dependencies-update', 'styleguide-update', 'appjs-update']);
  grunt.registerTask('appjs-update', 'copy:appjs');
  grunt.registerTask('appjs-watch', ['watch:node-modules-appjs']);
  grunt.registerTask('appjs-watch-browser-sync', ['browserSync:appjs', 'watch:node-modules-appjs']);
  grunt.registerTask('styleguide-update', 'copy:styleguide');
  grunt.registerTask('dependencies-update', 'copy:dependencies');

  /**
   * Tasks definition
   */
  grunt.initConfig({
    pkg: pkg,

    browserSync: {
      appjs: {
        bsFiles: {
          src: paths.js + 'app/**/*'
        },
        options: {
          localOnly: true,
          watchTask: true,
          host: 'passbolt.dev',
          browser: 'google chrome'
        }
      }
    },

    copy: {
      dependencies: {
        files: [{
          // Openpgp
          cwd: paths.node_modules + 'openpgp/dist',
          src: ['openpgp.min.js'],
          dest: paths.js + 'vendors',
          expand: true
        }, {
          // jQuery
          cwd: paths.node_modules + 'jquery/dist',
          src: ['jquery.min.js'],
          dest: paths.js + 'vendors',
          expand: true
        }]
      },
      appjs: {
        files: [{
          cwd: paths.node_modules + 'babel-polyfill/dist',
          src: ['polyfill.min.js'],
          dest: paths.js + 'app',
          expand: true
        }, {
          cwd: paths.node_modules_appjs + 'dist',
          src: ['steal.production.js', 'bundles/passbolt-appjs/passbolt.js'],
          dest: paths.js + 'app',
          expand: true
        }]
      },
      styleguide: {
        files: [{
          // Fonts
          cwd: paths.node_modules_styleguide + 'src/fonts',
          src: '*',
          dest: paths.webroot + 'fonts',
          expand: true
        }, {
          // Images for webroots (favicons, etc.)
          cwd: paths.node_modules_styleguide + 'src/img/webroot',
          src: '*',
          dest: paths.webroot,
          expand: true
        }, {
          // Images
          cwd: paths.node_modules_styleguide + 'src/img',
          src: [
            // Default Avatars
            'avatar/**',
            // Passbolt logos
            'logo/icon-20_white.png', 'logo/icon-20_grey.png', 'logo/icon-20.png',
            'logo/icon-48_white.png', 'logo/icon-48.png',
            'logo/logo.png', 'logo/logo@2x.png', 'logo/logo.svg', 'logo/logo_white.svg',
            // Image for inputs and controls
            'controls/dot_white.svg',
            'controls/dot_red.svg',
            'controls/dot_black.svg',
            'controls/infinite-bar.gif',
            'controls/loading_light.svg',
            'controls/loading_dark.svg',
            'controls/overlay-opacity-50.png',
            // Background images for error pages for ex
            'background/rocket.svg',
            'illustrations/birds6_850.png',
            'illustrations/birds3_850.png',
            // Login page 3rd party logo
            'third_party/firefox_logo.png',
            'third_party/ChromeWebStore.png',
            'third_party/gnupg_logo_disabled.png', 'third_party/gnupg_logo.png'
          ],
          dest: paths.webroot + 'img',
          expand: true
        }, {
          // CSS
          cwd: paths.node_modules_styleguide + 'build/css/themes/default',
          src: ['api_login.min.css', 'api_main.min.css', 'api_setup.min.css'],
          dest: paths.webroot + 'css/themes/default',
          expand: true
        }]
      }
    },

    watch: {
      'node-modules-appjs': {
        files: [paths.node_modules_appjs + 'dist/**/*'],
        tasks: ['appjs-update']
      }
    }
   });
};

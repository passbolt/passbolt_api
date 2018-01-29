/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
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
  var path = {
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
  grunt.loadNpmTasks('grunt-browser-sync');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-watch');

  /**
   * Register project specific grunt tasks
   */
  grunt.registerTask('appjs-update', 'copy:appjs');
  grunt.registerTask('appjs-watch', ['browserSync:passbolt', 'watch:node-modules-appjs']);
  grunt.registerTask('styleguide-update', 'copy:styleguide');

  /**
   * Tasks definition
   */
  grunt.initConfig({
    pkg: pkg,

    browserSync: {
      passbolt: {
        bsFiles: {
          src: path.js + 'app/**/*'
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
      appjs: {
        files: [{
          cwd: path.node_modules_appjs + 'dist',
          src: ['steal.production.js', 'bundles/passbolt.js'],
          dest: path.js + 'app',
          expand: true
        }]
      },
      styleguide: {
        files: [{
          // Fonts
          cwd: path.node_modules_styleguide + 'src/fonts',
          src: '*',
          dest: path.webroot + 'fonts',
          expand: true
        }, {
          // Images for webroots (favicons, etc.)
          cwd: path.node_modules_styleguide + 'src/img/webroot',
          src: '*',
          dest: path.webroot,
          expand: true
        }, {
          // Images
          cwd: path.node_modules_styleguide + 'src/img',
          src: [
            // Default Avatars
            'avatar/**',
            // Passbolt logos
            'logo/icon-20_white.png', 'logo/icon-20_grey.png', 'logo/icon-20.png',
            'logo/icon-48_white.png', 'logo/icon-48.png',
            'logo/logo.png', 'logo/logo@2x.png',
            // Image for inputs and controls
            'controls/password-dots.png',
            'controls/infinite-bar.gif',
            'controls/loading.gif',
            'controls/overlay-opacity-50.png',
            'controls/tree-ctrl.png',
            // Background images for error pages for ex
            'illustrations/nest.png',
            'illustrations/birds6_850.png',
            'illustrations/birds3_850.png',
            // Login page 3rd party logo
            'third_party/firefox_logo.png',
            'third_party/ChromeWebStore.png',
            'third_party/gnupg_logo_disabled.png', 'third_party/gnupg_logo.png'
          ],
          dest: path.webroot + 'img',
          expand: true
        }, {
          // Less
          cwd: path.node_modules_styleguide + 'build/css',
          src: ['devel.min.css', 'login.min.css', 'main.min.css', 'check.min.css', 'setup.min.css'],
          dest: path.webroot + 'css',
          expand: true
        }]
      }
    },

    watch: {
      'node-modules-appjs': {
        files: [path.node_modules_appjs + 'dist/**/*'],
        tasks: ['appjs-update']
      }
    }
   });
};

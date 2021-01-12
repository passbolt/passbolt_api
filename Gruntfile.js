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
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-watch');

  /**
   * Register project specific grunt tasks
   */
  grunt.registerTask('default', ['dependencies-update', 'styleguide-update']);
  grunt.registerTask('styleguide-update', 'copy:styleguide');
  grunt.registerTask('styleguide-watch', ['watch:node-modules-styleguide']);
  grunt.registerTask('dependencies-update', 'copy:dependencies');

  /**
   * Tasks definition
   */
  grunt.initConfig({
    pkg: pkg,

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
            'logo/logo.png', 'logo/logo@2x.png', 'logo/logo.svg', 'logo/logo_white.svg', 'logo/logo_white.png',
            // Image for inputs and controls
            'controls/check_black.svg',
            'controls/check_white.svg',
            'controls/dot_white.svg',
            'controls/dot_red.svg',
            'controls/dot_black.svg',
            'controls/infinite-bar.gif',
            'controls/loading_light.svg',
            'controls/loading_dark.svg',
            'controls/overlay-opacity-50.png',
            // Background images for error pages for ex
            'illustrations/birds6_850.png',
            'illustrations/birds3_850.png',
            // Login page 3rd party logo
            'third_party/firefox_logo.png',
            'third_party/FirefoxAMO_black.svg',
            'third_party/FirefoxAMO_white.svg',
            'third_party/ChromeWebStore_black.png',
            'third_party/ChromeWebStore_white.png',
            'third_party/gnupg_logo_disabled.png',
            'third_party/gnupg_logo.png',
            'third_party/chosen-sprite.png',
            'third_party/chosen-sprite@2x.png',
            'third_party/duo.svg',
            'third_party/google-authenticator.svg',
            'third_party/yubikey.svg',
            // Setup
            'illustrations/email.png',
            // Themes preview
            'themes/*.png',
            // Background images for error pages for ex
            'diagrams/totp.svg',
          ],
          dest: paths.webroot + 'img',
          expand: true
        }, {
          // CSS
          cwd: paths.node_modules_styleguide + 'build/css/themes/default',
          src: ['api_main.min.css', 'api_webinstaller.min.css', 'api_authentication.min.css'],
          dest: paths.webroot + 'css/themes/default',
          expand: true
        }, {
          // Midgar css theme
          cwd: paths.node_modules_styleguide + 'build/css/themes/midgar',
          src: ['api_main.min.css'],
          dest: paths.webroot + 'css/themes/midgar',
          expand: true
        }, {
          // Javascript applications
          cwd: paths.node_modules_styleguide + 'build/js/dist',
          src: ['api-app.js', 'api-recover.js', 'api-setup.js', 'api-triage.js', 'api-vendors.js'],
          dest: paths.js + 'app',
          expand: true
        },]
      }
    },

    watch: {
      'node-modules-styleguide': {
        files: [paths.node_modules_styleguide + 'build/**/*'],
        tasks: ['styleguide-update']
      }
    }
   });
};

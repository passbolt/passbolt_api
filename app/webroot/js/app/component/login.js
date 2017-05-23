import 'mad/component/component';
import 'app/util/common';
import semver from 'lib/semver/semver';
import 'mad/component/dialog';
import 'mad/component/confirm';
import 'app/view/template/component/login/updatePlugin.ejs!';
import 'app/view/template/component/login/updateAPI.ejs!';

/**
 * @inherits mad.controller.AppController
 * @parent index
 *
 * The passbolt login application controller.
 */
var Login = passbolt.component.Login = mad.Component.extend('passbolt.component.Login', /** @static */ {

    defaults: {
        templateBased: false,
        checkPluginTimeout: 10000,
        checkPluginIntervalTime: 200,
        documentationUrl: 'https://www.passbolt.com/help/versions'
    }

}, /** @prototype */ {

    /**
     * After start hook.
     * Initialize the application's components.
     * @see {mad.Component}
     */
    afterStart: function () {
        this.checkPlugin();
    },

    /**
     * Check that the plugin is installed.
     * If the plugin installed, check the versions compatibility.
     */
    checkPlugin: function () {
        var self = this,
            loop = 0;

        // Check that the plugin is installed and the versions are displayed on the page.
        var intervalRef = setInterval(function () {
            loop++;
            var pluginInstalled = $('html').hasClass('passboltplugin'),
                rawVersions = $('.footer #version .tooltip-left').attr('data-tooltip'),
                versionsDisplayed = /[^.\/]+\.[^.\/]+\.[^.\/]+\/[^.\/]+\.[^.\/]+\.[^.\/]+/.test(rawVersions);

            if (pluginInstalled && versionsDisplayed) {
                clearInterval(intervalRef);
                self.checkVersion(rawVersions);
            } else if (self.options.checkPluginIntervalTime * loop > self.options.checkPluginTimeout) {
                clearInterval(intervalRef);
            }
        }, self.options.checkPluginIntervalTime);
    },

    /**
     * Check that the versions of the plugin and the API are compatible.
     * @param rawVersions {string} The raw versions as displayed in the page footer
     */
    checkVersion: function (rawVersions) {
        var versions = rawVersions.split('/'),
            apiVersion = versions[0].trim(),
            pluginVersion = versions[1].trim();

        // If the major and minor of each are not equal
        if (semver.major(apiVersion) != semver.major(pluginVersion)
            || semver.minor(apiVersion) != semver.minor(pluginVersion)) {
            this.displayWarning(apiVersion, pluginVersion);
        }
    },

    /**
     * The version of the plugin and the API are not compatible.
     * Display a warning to the user.
     *
     * @param apiVersion {string} The API version
     * @param pluginVersion {string} The plugin version
     */
    displayWarning: function (apiVersion, pluginVersion) {
        var self = this,
            dialogLabel, dialogContent;

        // api version > plugin version
        if(semver.gt(apiVersion, pluginVersion)) {
            dialogLabel = __('Version issue: update the plugin.');
            dialogContent = mad.View.render('app/view/template/component/login/updatePlugin.ejs', {apiVersion:apiVersion, pluginVersion:pluginVersion});
        }
        // api version < plugin version
        else {
            dialogLabel = __('Version issue: update the server.');
            dialogContent = mad.View.render('app/view/template/component/login/updateAPI.ejs', {apiVersion:apiVersion, pluginVersion:pluginVersion});
        }

        new mad.component.Confirm(null, {
            label: dialogLabel,
            content: dialogContent,
            submitButton: {
                label: __('Learn more'),
                cssClasses: []
            },
            cancelButton: {
                label: __('or proceed with caution')
            },
            action: function () {
                window.location.href = self.options.documentationUrl;
            }
        }).start();
    }

});

export default Login;

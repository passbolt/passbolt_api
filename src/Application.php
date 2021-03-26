<?php
declare(strict_types=1);

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
namespace App;

use App\Middleware\ContentSecurityPolicyMiddleware;
use App\Middleware\CsrfProtectionMiddleware;
use App\Middleware\GpgAuthHeadersMiddleware;
use App\Middleware\SessionPreventExtensionMiddleware;
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Notification\Email\Redactor\CoreEmailRedactorPool;
use App\Notification\EmailDigest\DigestRegister\GroupDigests;
use App\Notification\EmailDigest\DigestRegister\ResourceDigests;
use App\Notification\NotificationSettings\CoreNotificationSettingsDefinition;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\SecurityHeadersMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\I18n\I18n;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Passbolt\WebInstaller\Middleware\WebInstallerMiddleware;
use Psr\Http\Message\ServerRequestInterface;

class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * Setup the PSR-7 middleware passbolt application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $csrf = new CsrfProtectionMiddleware();
        // Token check will be skipped when callback returns `true`.
        $csrf->skipCheckCallback(function ($request) use ($csrf) {
            return $csrf->skipCsrfProtection($request);
        });

        /*
         * Default Middlewares
         * - Does not extend the session when requesting /auth/is-authenticated
         * - Catch any exceptions in the lower layers, and make an error page/response
         * - Handle plugin/theme assets like CakePHP normally does
         * - Apply routing middleware
         * - Apply the authentication middleware
         * - Apply GPG Authenticator headers
         * - Apply CSRF protection
         */
        $middlewareQueue
            ->add(ContentSecurityPolicyMiddleware::class)
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this))
            ->add(new SessionPreventExtensionMiddleware())
            ->add(new AuthenticationMiddleware($this))
            ->add(GpgAuthHeadersMiddleware::class)
            ->add(new BodyParserMiddleware())
            ->add($csrf);

        /*
         * Additional security headers
         * - Only allow assets to be loaded from the passbolt instance domain
         * - Only set the referrer header on requests to the same origin
         * - Don't allow framing the site
         * - Tell browser to block XSS attempts
         * - Don't allow
         * - Stick to the content type declared by the server
         */
        if (Configure::read('passbolt.security.setHeaders')) {
            $headers = new SecurityHeadersMiddleware();
            $headers
                ->setCrossDomainPolicy()
                ->setReferrerPolicy()
                ->setXFrameOptions()
                ->setXssProtection()
                ->noOpen()
                ->noSniff();

            $middlewareQueue->add($headers);
        }

        return $middlewareQueue;
    }

    /**
     * Load all the application configuration and bootstrap logic.
     *
     * Override this method to add additional bootstrap logic for your application.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        parent::bootstrap();

        $this->addCorePlugins()
            ->addVendorPlugins()
            ->addPassboltPlugins();

        if (PHP_SAPI === 'cli') {
            $this->addCliPlugins();
            I18n::setLocale('en_US');
        }

        $this->initEmails();
    }

    /**
     * Register core emails notification and settings
     * Register core email digests
     *
     * @return void
     */
    public function initEmails()
    {
        // Gather
        if (WebInstallerMiddleware::isConfigured()) {
            $this->getEventManager()
                ->on(new CoreEmailRedactorPool())
                ->on(new CoreNotificationSettingsDefinition());
        }

        if (PHP_SAPI === 'cli' || (Configure::read('debug') && Configure::read('passbolt.selenium.active'))) {
            // Core email digests
            $this->getEventManager()
                ->on(new GroupDigests())
                ->on(new ResourceDigests());
        }
    }

    /**
     * Bootstrap all the loaded plugins
     * Any which require the application to be fully loaded should be registered here.
     *
     * @return void
     */
    public function pluginBootstrap(): void
    {
        parent::pluginBootstrap();

        // Register the emails redactors which listen on events where emails must be sent
        // It must happens after the emails redactors have been registered in the system
        (new EmailSubscriptionDispatcher())->collectSubscribedEmailRedactors();
    }

    /**
     * Add core plugin
     * - DebugKit if debug mode is on
     * - Migration plugin
     * - Authentication
     *
     * @return $this
     */
    protected function addCorePlugins()
    {
        // Debug Kit should not be installed on a production system
        if (Configure::read('debug') && Configure::read('debugKit')) {
            $this->addPlugin('DebugKit', ['bootstrap' => true]);
        }

        return $this
            ->addPlugin('Migrations')
            ->addPlugin('Authentication');
    }

    /**
     * Add vendor plugins
     * - EmailQueue
     * - ApiPagination
     *
     * @return $this
     */
    protected function addVendorPlugins()
    {
        return $this
            ->addPlugin('EmailQueue')
            ->addPlugin('BryanCrowe/ApiPagination');
    }

    /**
     * Add passbolt plugins
     *
     * @return $this
     */
    protected function addPassboltPlugins()
    {
        if (Configure::read('debug') && Configure::read('passbolt.selenium.active')) {
            $this->addPlugin('PassboltSeleniumApi', ['bootstrap' => true, 'routes' => true]);
            $this->addPlugin('PassboltTestData', ['bootstrap' => true, 'routes' => false]);
        }

        $this->addPlugin('Passbolt/Ee', ['bootstrap' => true, 'routes' => true]);

        // Add tags plugin if not configured.
        if (!WebInstallerMiddleware::isConfigured()) {
            $this->addPlugin('Passbolt/WebInstaller', ['bootstrap' => true, 'routes' => true]);

            return $this;
        }

        // Add Common plugins.
        $this->addPlugin('Passbolt/AccountSettings', ['bootstrap' => true, 'routes' => true]);
        $this->addPlugin('Passbolt/Import', ['bootstrap' => true, 'routes' => true]);
        $this->addPlugin('Passbolt/Export', ['bootstrap' => true, 'routes' => false]);
        $this->addPlugin('Passbolt/ResourceTypes', ['bootstrap' => true, 'routes' => false]);
        $this->addPlugin('Passbolt/RememberMe', ['bootstrap' => true, 'routes' => false]);
        $this->addPlugin('Passbolt/EmailNotificationSettings', ['bootstrap' => true, 'routes' => true]);
        $this->addPlugin('Passbolt/EmailDigest', ['bootstrap' => true, 'routes' => true]);
        $this->addPlugin('Passbolt/Reports', ['bootstrap' => true, 'routes' => true]);

        $mfaEnabled = Configure::read('passbolt.plugins.multiFactorAuthentication.enabled');
        if (!isset($mfaEnabled) || $mfaEnabled) {
            $this->addPlugin('Passbolt/MultiFactorAuthentication', ['bootstrap' => true, 'routes' => true]);
        }

        $ldapEnabled = Configure::read('passbolt.plugins.directorySync.enabled');
        if (!isset($ldapEnabled) || $ldapEnabled) {
            $this->addPlugin('Passbolt/DirectorySync', ['bootstrap' => true, 'routes' => true]);
        }

        // Allow switching on / off tags plugin
        $tagsEnabled = Configure::read('passbolt.plugins.tags.enabled');
        if (!isset($tagsEnabled) || $tagsEnabled) {
            $this->addPlugin('Passbolt/Tags', ['bootstrap' => true, 'routes' => true]);
        }

        $logEnabled = Configure::read('passbolt.plugins.log.enabled');
        if (!isset($logEnabled) || $logEnabled) {
            $this->addPlugin('Passbolt/Log', ['bootstrap' => true, 'routes' => false]);
            $this->addPlugin('Passbolt/AuditLog', ['bootstrap' => true, 'routes' => true]);
        }

        $folderEnabled = Configure::read('passbolt.plugins.folders.enabled');
        if (!isset($folderEnabled) || $folderEnabled) {
            $this->addPlugin('Passbolt/Folders', ['bootstrap' => true, 'routes' => true]);
        }

        return $this;
    }

    /**
     * Add plugins relevant in CLI mode
     * - Bake
     * - Migrations
     *
     * @return $this
     */
    protected function addCliPlugins()
    {
        try {
            Application::addPlugin('Bake');
            $this->addPlugin('CakephpFixtureFactories');
        } catch (MissingPluginException $e) {
            // Do not halt if the plugin is missing
        }

        return $this;
    }

    /**
     * Returns a service provider instance.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return \Authentication\AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();

        // Define where users should be redirected to when they are not authenticated
        // The login url is provided in string format because the routes are not loaded yet
        if (!$request->is('json')) {
            $loginUrl = '/auth/login';
            $service->setConfig([
                'unauthenticatedRedirect' => $loginUrl,
                'logoutRedirect' => $loginUrl,
                'queryParam' => 'redirect',
            ]);
        }

        // Load the authenticators. Session should be first.
        $service->loadAuthenticator('Authentication.Session');
        $service->loadAuthenticator('Gpg');

        return $service;
    }
}

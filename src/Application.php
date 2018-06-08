<?php
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
namespace App;

use App\Middleware\GpgAuthHeadersMiddleware;
use App\Middleware\GpgAuthSignMiddleware;
use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\SecurityHeadersMiddleware;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

class Application extends BaseApplication
{
    /**
     * Setup the PSR-7 middleware passbolt application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middleware The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware.
     */
    public function middleware($middleware)
    {
        $middleware
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(ErrorHandlerMiddleware::class)

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(AssetMiddleware::class)

            // Apply routing
            ->add(RoutingMiddleware::class)

            // Apply GPG Auth headers
            ->add(GpgAuthHeadersMiddleware::class);

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

            $middleware->add($headers);
        }

        return $middleware;
    }
}

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
 * @since         3.1.0
 */
namespace Passbolt\MultiFactorAuthentication;

use App\Service\Command\ProcessUserService;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Duo\DuoUniversal\Client;
use Passbolt\Edition\Middleware\LogoutUsersOnEditionChangeMiddleware;
use Passbolt\JwtAuthentication\Authenticator\JwtArmoredChallengeInterface;
use Passbolt\MultiFactorAuthentication\Authenticator\MfaJwtArmoredChallengeService;
use Passbolt\MultiFactorAuthentication\Command\MfaUserSettingsDisableCommand;
use Passbolt\MultiFactorAuthentication\Event\AddIsMfaEnabledColumnToUsersGrid;
use Passbolt\MultiFactorAuthentication\Event\AddMfaCookieOnSuccessfulRefreshTokenCreation;
use Passbolt\MultiFactorAuthentication\Event\ClearMfaCookieOnSetupAndRecover;
use Passbolt\MultiFactorAuthentication\Event\UsersModelInitializeEventListener;
use Passbolt\MultiFactorAuthentication\Middleware\InjectMfaFormMiddleware;
use Passbolt\MultiFactorAuthentication\Middleware\MfaRequiredCheckMiddleware;
use Passbolt\MultiFactorAuthentication\Notification\Email\MfaRedactorPool;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultRememberAMonthSettingService;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnableService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnStartRegistrationService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService;

class MultiFactorAuthenticationPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $this->registerListeners($app);
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        // Anchor after LogoutUsersOnEditionChangeMiddleware (not directly after
        // SetUserIdentityInRequestMiddleware) so a pre-change session is
        // terminated before MFA enforcement evaluates it against post-change
        // policy state. The Edition plugin is loaded unconditionally by
        // BaseSolutionBootstrapper, so the anchor is always present.
        return $middlewareQueue
            ->insertAfter(LogoutUsersOnEditionChangeMiddleware::class, MfaRequiredCheckMiddleware::class)
            ->insertAfter(MfaRequiredCheckMiddleware::class, InjectMfaFormMiddleware::class);
    }

    /**
     * Register MFA related listeners.
     *
     * @param \Cake\Core\PluginApplicationInterface $app App
     * @return void
     */
    public function registerListeners(PluginApplicationInterface $app): void
    {
        $app->getEventManager()
            ->on(new UsersModelInitializeEventListener()) // Decorate the users table class to add the MFA settings association
            // Decorate the users grid and add the column "is_mfa_enabled"
            ->on(new AddIsMfaEnabledColumnToUsersGrid()) // decorate the query to add the new property on the User entity
            ->on(new MfaRedactorPool()) // Register email redactors
            ->on(new ClearMfaCookieOnSetupAndRecover()); // Some end points should have a cleared MFA

        if ($this->isFeaturePluginEnabled('JwtAuthentication')) {
            // If a JWT login or refresh token is successful, and a valid MFA cookie was sent, pass it to the response
            $app->getEventManager()->on(new AddMfaCookieOnSuccessfulRefreshTokenCreation());
        }
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->add(RememberAMonthSettingInterface::class)
            ->setConcrete(DefaultRememberAMonthSettingService::class);

        $container
            ->add(MfaWebauthnPolicyInterface::class)
            ->setConcrete(DefaultMfaWebauthnPolicyService::class);

        $container
            ->add(MfaWebauthnRelyingPartyService::class)
            ->addArgument(MfaWebauthnPolicyInterface::class);

        $container
            ->add(MfaWebauthnEnableService::class)
            ->addArgument(MfaWebauthnPolicyInterface::class);

        $container
            ->add(MfaWebauthnVerifyAttestationService::class)
            ->addArguments([
                MfaWebauthnRelyingPartyService::class,
                MfaWebauthnEnableService::class,
            ]);

        $container
            ->add(MfaWebauthnStartRegistrationService::class)
            ->addArguments([
                MfaWebauthnRelyingPartyService::class,
                MfaWebauthnPolicyInterface::class,
            ]);

        if ($this->isFeaturePluginEnabled('JwtAuthentication')) {
            $container
                ->extend(JwtArmoredChallengeInterface::class)
                ->setConcrete(MfaJwtArmoredChallengeService::class)
                ->addArgument(RememberAMonthSettingInterface::class);
        }

        $container->add(Client::class)->setConcrete(null);

        $container
            ->add(MfaUserSettingsDisableCommand::class)
            ->addArguments([
                ProcessUserService::class,
            ]);
    }
}

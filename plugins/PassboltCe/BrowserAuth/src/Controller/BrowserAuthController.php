<?php
declare(strict_types=1);

namespace Passbolt\BrowserAuth\Controller;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Controller\AppController;
use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use App\Utility\UserAccessControl;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use InvalidArgumentException;
use Passbolt\BrowserAuth\Service\BrowserGrantBinding;
use Passbolt\BrowserAuth\Service\BrowserGrantService;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class BrowserAuthController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['exchange']);
        $this->assertJson();
        $this->response = $this->response->withHeader('Cache-Control', 'no-store');
        if ($this->request->getUri()->getScheme() !== 'https') {
            throw new ForbiddenException('Browser authorization requires HTTPS.');
        }
    }

    /**
     * Authorize browser session for CLI access.
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface|null $sessions Session service
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface|null $policy Policy
     * @return void
     */
    public function authorize(
        ?SessionIdentificationServiceInterface $sessions = null,
        ?RememberAMonthSettingInterface $policy = null
    ): void {
        try {
            $binding = BrowserGrantBinding::validate((array)$this->request->getData());
        } catch (InvalidArgumentException $e) {
            throw new BadRequestException($e->getMessage());
        }
        $uac = $this->User->getAccessControl();
        $user = $this->fetchTable('Users')->get($uac->getId(), contain: ['Gpgkeys']);
        if (!hash_equals(strtoupper($user->gpgkey->fingerprint), $binding['fingerprint'])) {
            throw new ForbiddenException('The browser account does not match the CLI account.');
        }
        $mfa = null;
        if (MfaSettings::get($uac)->hasEnabledProviders()) {
            $mfa = $this->request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
            if (!is_string($mfa) || !MfaVerifiedToken::check($uac, $mfa, $sessions, $this->request, $policy)) {
                throw new ForbiddenException('Complete browser MFA before authorizing the CLI.');
            }
        }
        $grant = (new BrowserGrantService())->create($uac->getId(), $binding, $mfa);
        $this->success('CLI authorization granted.', ['token' => $grant->token, 'user_id' => $uac->getId()]);
    }

    /**
     * Exchange browser authorization grant for CLI session.
     *
     * @return void
     */
    public function exchange(): void
    {
        // Never replace an existing browser session, even if presented with a valid grant.
        if ($this->Authentication->getIdentity() !== null || $this->request->getHeaderLine('Origin') !== '') {
            throw new ForbiddenException('Exchange requires a new CLI session.');
        }
        $input = $this->request->getData();
        foreach (['token', 'user_id', 'verifier'] as $field) {
            if (!isset($input[$field]) || !is_string($input[$field]) || strlen($input[$field]) > 128) {
                throw new BadRequestException('Invalid browser authorization.');
            }
        }
        $service = new BrowserGrantService();
        [$user, $data] = $service->inspect($input['token'], $input['user_id'], $input['verifier']);
        $uac = new UserAccessControl($user->role->name, $user->id);
        $provider = null;
        if (MfaSettings::get($uac)->hasEnabledProviders()) {
            $token = (new AuthenticationTokenGetService())->getActiveNotExpiredOrFail(
                $data['mfa_token'] ?? '',
                $user->id,
                AuthenticationToken::TYPE_MFA,
                MfaVerifiedCookie::MAX_DURATION
            );
            $provider = $token->getJsonDecodedData()['provider'] ?? null;
            if (!is_string($provider) || !MfaSettings::get($uac)->isProviderEnabled($provider)) {
                throw new ForbiddenException('Browser MFA authorization is no longer valid.');
            }
        }
        $service->consume($input['token'], $input['user_id']);
        $this->request->getSession()->renew();
        $this->Authentication->setIdentity(['user' => ['id' => $user->id]]);
        if ($provider !== null) {
            $mfa = MfaVerifiedToken::get($uac, $provider, $this->request->getSession()->id(), false);
            $this->response = $this->response->withCookie(MfaVerifiedCookie::get($this->request, $mfa));
        }
        $this->success('CLI session created.', ['user_id' => $user->id]);
    }
}

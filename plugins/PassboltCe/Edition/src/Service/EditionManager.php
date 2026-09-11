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
 * @since         5.12.0
 */

namespace Passbolt\Edition\Service;

use App\BaseSolutionBootstrapper;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Database\Exception\DatabaseException;
use Cake\Database\Exception\MissingConnectionException;
use Cake\Database\Exception\MissingDriverException;
use Cake\Database\Exception\MissingExtensionException;
use Cake\Database\Exception\QueryException;
use Cake\Datasource\Exception\MissingDatasourceConfigException;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\DateTime;
use Cake\Log\Log;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Ee\EeSolutionBootstrapper;

class EditionManager
{
    use FeaturePluginAwareTrait;

    /**
     * Configure key holding the timestamp of the last edition change
     * (CE → PRO or PRO → CE), or `null` when the edition has never been
     * modified since creation. Read by middlewares that invalidate sessions
     * or refresh tokens issued prior to the change.
     *
     * @var string
     */
    public const CONFIGURE_KEY_LAST_CHANGE_DATETIME = 'passbolt.editionLastChangeDateTime';

    private bool $booted = false;

    private static ?EditionManager $instance = null;

    /**
     * @var string
     */
    private string $edition = EditionDto::EDITION_CE;

    /**
     * @var \Cake\I18n\DateTime|null
     */
    private ?DateTime $lastEditionChangeDateTime = null;

    private ?string $solutionBootstrapperClass = null;

    /**
     * @var array<string, class-string>
     */
    private array $editionBootstrapperMapping = [
        EditionDto::EDITION_CE => BaseSolutionBootstrapper::class,
        EditionDto::EDITION_PRO => EeSolutionBootstrapper::class,
    ];

    /**
     * Boot edition manager: resolve the edition and write it to Configure.
     * Called once from Application::bootstrap().
     *
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException When bootstrapper for edition not found.
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        $dto = $this->resolveEditionDto();
        $this->edition = $dto->getEdition();
        $this->lastEditionChangeDateTime = $dto->getLastEditionChangeDateTime();

        Configure::write('passbolt.edition', $this->edition);
        Configure::write(self::CONFIGURE_KEY_LAST_CHANGE_DATETIME, $this->lastEditionChangeDateTime);

        $this->disableProPluginsIfNotPro();

        $this->setSolutionBootstrapperClass();

        $this->booted = true;
    }

    /**
     * Resolve the edition DTO by reading from the DB via EditionGetService.
     * Falls back to a default CE DTO (no downgrade timestamp) when the DB
     * is unavailable — keeps the application bootable on a broken connection.
     *
     * @return \Passbolt\Edition\Model\Dto\EditionDto
     */
    protected function resolveEditionDto(): EditionDto
    {
        try {
            return (new EditionGetService())->get();
        } catch (
            DatabaseException
            | MissingConnectionException
            | MissingDatasourceConfigException
            | MissingDriverException
            | MissingExtensionException
            | QueryException $e
        ) {
            $msg = 'EditionManager: DB unavailable during boot, falling back to CE.';
            if (Configure::read('debug')) {
                $msg .= ' ' . $e->getMessage();
            }
            Log::error($msg);

            return new EditionDto(EditionDto::EDITION_CE);
        }
    }

    /**
     * Disable each PRO-only plugin if edition is CE.
     *
     * Each plugin is listed explicitly — when a new PRO plugin is introduced,
     * add a line here and a matching assertion in EditionManagerTest.
     *
     * @return void
     */
    private function disableProPluginsIfNotPro(): void
    {
        if ($this->edition === EditionDto::EDITION_PRO) {
            return;
        }

        $this->disableFeaturePlugin('AccountRecovery');
        $this->disableFeaturePlugin('AuditLog');
        $this->disableFeaturePlugin('DirectorySync');
        $this->disableFeaturePlugin('Ee');
        $this->disableFeaturePlugin('MfaPolicies');
        $this->disableFeaturePlugin('OfflineModePolicies');
        $this->disableFeaturePlugin('PasswordExpiryPolicies');
        $this->disableFeaturePlugin('PasswordPoliciesUpdate');
        $this->disableFeaturePlugin('Scim');
        $this->disableFeaturePlugin('Sso');
        $this->disableFeaturePlugin('SsoRecover');
        $this->disableFeaturePlugin('Subscription');
        $this->disableFeaturePlugin('Tags');
        $this->disableFeaturePlugin('UserPassphrasePolicies');
    }

    /**
     * @return string
     */
    public function getEdition(): string
    {
        return $this->edition;
    }

    /**
     * @return \Cake\I18n\DateTime|null Timestamp of the last edition change
     *   (CE → PRO or PRO → CE), or `null` when the edition has never been
     *   modified since creation.
     */
    public function getLastEditionChangeDateTime(): ?DateTime
    {
        return $this->lastEditionChangeDateTime;
    }

    /**
     * @return bool
     */
    public function isPro(): bool
    {
        return $this->edition === EditionDto::EDITION_PRO;
    }

    /**
     * @return string|null
     */
    public function getSolutionBootstrapperClass(): ?string
    {
        return $this->solutionBootstrapperClass;
    }

    /**
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException When bootstrapper for edition not found.
     */
    private function setSolutionBootstrapperClass(): void
    {
        if (!isset($this->editionBootstrapperMapping[$this->edition])) {
            throw new InternalErrorException('Edition "' . $this->edition . '" does not exist');
        }

        $this->solutionBootstrapperClass = $this->editionBootstrapperMapping[$this->edition];
    }

    /**
     * Returns the singleton EditionManager. Constructs a default instance on first call.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Overrides the singleton instance.
     * Pass null to clear the singleton so the next `getInstance()` rebuilds it.
     *
     * @param \Passbolt\Edition\Service\EditionManager|null $instance Instance to install, or null to clear.
     * @return void
     */
    public static function setInstance(?EditionManager $instance): void
    {
        self::$instance = $instance;
    }
}

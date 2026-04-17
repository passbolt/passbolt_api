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

namespace App\Service\Subscriptions;

use App\BaseSolutionBootstrapper;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\Ee\EeSolutionBootstrapper;

class EditionManager
{
    private bool $booted = false;

    public const EDITION_CE = 'ce';
    public const EDITION_PRO = 'pro';

    /**
     * @var string
     */
    private string $edition = self::EDITION_CE;

    private ?string $solutionBootstrapperClass = null;

    /**
     * @var array<string, class-string>
     */
    private array $editionBootstrapperMapping = [
        self::EDITION_CE => BaseSolutionBootstrapper::class,
        self::EDITION_PRO => EeSolutionBootstrapper::class,
    ];

    /**
     * Boot edition manager to determine instance's feature capabilities.
     * Called once from Application::bootstrap().
     *
     * For cloud, overwrite this to hard-code it to set 'cloud' edition.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        $this->edition = Configure::readOrFail('passbolt.edition');

        $this->setSolutionBootstrapperClass();

        $this->booted = true;
    }

    /**
     * @return string
     */
    public function getEdition(): string
    {
        return $this->edition;
    }

    /**
     * @return bool
     */
    public function isPro(): bool
    {
        return $this->edition === self::EDITION_PRO;
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
}

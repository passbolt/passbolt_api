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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Model\Dto;

use Cake\Chronos\Chronos;

abstract class AbstractSsoSettingsDto implements SsoSettingsDtoInterface
{
    /**
     * @var array SsoSetting::ALLOWED_PROVIDERS
     */
    public array $providers;

    /**
     * @var string|null $provider
     */
    public ?string $provider = null;

    /**
     * @var string|null $id uuid
     */
    public ?string $id = null;

    /**
     * @var string|null $status
     */
    public ?string $status = null;

    /**
     * @var \Passbolt\Sso\Model\Dto\SsoSettingsDataDtoInterface|null $data
     */
    public ?SsoSettingsDataDtoInterface $data = null;

    /**
     * @var \Cake\Chronos\Chronos|string|null
     */
    public Chronos|string|null $created = null;

    /**
     * @var \Cake\Chronos\Chronos|string|null
     */
    public Chronos|string|null $modified = null;

    /**
     * @var string|null $created_by uuid
     */
    public ?string $created_by = null;

    /**
     * @var string|null $modified_by uuid
     */
    public ?string $modified_by = null;

    /**
     * @return string|null
     */
    public function getProvider(): ?string
    {
        return $this->provider;
    }

    /**
     * @return array provider strings
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    /**
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDataDtoInterface|null
     */
    public function getData(): ?SsoSettingsDataDtoInterface
    {
        return $this->data;
    }
}

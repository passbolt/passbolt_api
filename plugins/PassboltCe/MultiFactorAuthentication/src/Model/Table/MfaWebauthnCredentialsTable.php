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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Model\Table;

use App\Model\Table\WebauthnCredentialsTable;

/**
 * @method \App\Model\Entity\WebauthnCredential newEmptyEntity()
 * @method \App\Model\Entity\WebauthnCredential newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\WebauthnCredential[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WebauthnCredential get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 */
class MfaWebauthnCredentialsTable extends WebauthnCredentialsTable
{
    public const TYPE = 'mfa';

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}

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
 * @since         5.16.0
 */
namespace Passbolt\Metadata\Controller\Attribute;

use App\Model\Entity\User;
use Attribute;
use Authentication\IdentityInterface;
use Cake\Controller\Attribute\RequestToDto;
use Cake\Http\ServerRequest;
use Passbolt\Metadata\Utility\MetadataPopulateUserKeyIdTrait;

/**
 * Maps the request to a DTO, populating the metadata key id of the user beforehand.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class MetadataRequestToDto extends RequestToDto
{
    use MetadataPopulateUserKeyIdTrait;

    /**
     * @inheritDoc
     */
    protected function extractData(ServerRequest $request): array
    {
        $data = parent::extractData($request);
        $userId = $this->getAuthenticatedUserId($request);
        if ($userId === null) {
            return $data;
        }

        return $this->populatedMetadataUserKeyId($userId, $data);
    }

    /**
     * @param \Cake\Http\ServerRequest $request Server request.
     * @return string|null The identifier of the authenticated user, null if not authenticated.
     */
    private function getAuthenticatedUserId(ServerRequest $request): ?string
    {
        $identity = $request->getAttribute('identity');
        if ($identity instanceof User) {
            return $identity->id;
        }
        if ($identity instanceof IdentityInterface) {
            $identifier = $identity->getIdentifier();

            return is_string($identifier) ? $identifier : null;
        }

        return null;
    }
}

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
 * @since         3.3.0
 */
namespace Passbolt\Mobile\Test\Factory;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cake\Utility\Security;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Mobile\Model\Entity\Transfer;

/**
 * TransferFactory
 *
 * @method \Passbolt\Mobile\Model\Entity\Transfer|\Passbolt\Mobile\Model\Entity\Transfer[] persist()
 * @method \Passbolt\Mobile\Model\Entity\Transfer getEntity()
 * @method \Passbolt\Mobile\Model\Entity\Transfer[] getEntities()
 */
class TransferFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Mobile.Transfers';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $userId = UuidFactory::uuid();
        $this->setDefaultData(function (Generator $faker) use ($userId) {
            return [
                'user_id' => $userId,
                'total_pages' => 2,
                'current_page' => 0,
                'status' => Transfer::TRANSFER_STATUS_START,
                'hash' => hash('sha512', Security::randomBytes(16), false),
                'created' => Chronos::now(),
                'modified' => Chronos::now(),
            ];
        });

        $this->with(
            'AuthenticationTokens',
            AuthenticationTokenFactory::make()
                ->userId($userId)
                ->type(AuthenticationToken::TYPE_MOBILE_TRANSFER)
                ->active()
        );
    }

    /**
     * @param AuthenticationTokenFactory $token authentication token
     * @return $this
     */
    public function withAuthenticationToken(AuthenticationTokenFactory $token)
    {
        return $this->with('AuthenticationTokens', $token);
    }

    /**
     * @param int $totalPages total pages
     * @return $this
     */
    public function totalPages(int $totalPages)
    {
        return $this->patchData(['total_pages' => $totalPages]);
    }

    /**
     * @param int $currentPage current page
     * @return $this
     */
    public function currentPage(int $currentPage)
    {
        return $this->patchData(['current_page' => $currentPage]);
    }

    /**
     * @param ChronosInterface $modified token type
     * @return $this
     */
    public function modified(ChronosInterface $modified)
    {
        return $this->patchData(compact('modified'));
    }

    /**
     * @param ChronosInterface $created token type
     * @return $this
     */
    public function created(ChronosInterface $created)
    {
        return $this->patchData(compact('created'));
    }

    /**
     * @param string $status status
     * @return $this
     */
    public function status(string $status)
    {
        return $this->setField('status', $status);
    }

    /**
     * @param string $userId user id
     * @return $this
     */
    public function userId(string $userId)
    {
        return $this->setField('user_id', $userId);
    }
}

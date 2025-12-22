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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.7.0
 */

namespace Passbolt\Log\Test\Factory;

use App\Model\Entity\Resource;
use App\Test\Factory\ResourceFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Log\Model\Entity\ActionLog;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Model\Entity\SecretHistory;
use Passbolt\SecretRevisions\Model\Entity\SecretRevision;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

/**
 * ActionLogFactory
 *
 * @method \Passbolt\Log\Model\Entity\EntityHistory|\Passbolt\Log\Model\Entity\EntityHistory[] persist()
 * @method \Passbolt\Log\Model\Entity\EntityHistory getEntity()
 * @method \Passbolt\Log\Model\Entity\EntityHistory[] getEntities()
 * @method static \Passbolt\Log\Model\Entity\EntityHistory get($primaryKey, array $options = [])
 */
class EntitiesHistoryFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Log.EntitiesHistory';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'action_log_id' => $faker->uuid(),
                'foreign_model' => $faker->word(),
                'foreign_key' => $faker->uuid(),
                'crud' => $faker->randomLetter(),
                'created' => Chronos::now()->subMinutes($faker->randomNumber(8)),
            ];
        });
    }

    /**
     * @param \Passbolt\Log\Test\Factory\ActionLogFactory|array $actionLog ActionLog factory
     * @return $this
     */
    public function withActionLog(array|ActionLogFactory|ActionLog $actionLog = [])
    {
        return $this->with('ActionLogs', $actionLog);
    }

    /**
     * @param \Passbolt\Folders\Test\Factory\ResourceFactory|array $resource Resource
     * @return $this
     */
    public function withResource(array|ResourceFactory|Resource $resource = [])
    {
        return $this->resources()->with('Resources', $resource);
    }

    /**
     * @param \Passbolt\Folders\Test\Factory\ResourceFactory|array $resourceFactory Resource factory
     * @return $this
     */
    public function withSecretAccessOnResource(array|ResourceFactory $resourceFactory = [])
    {
        return $this->secretAccesses()->with('SecretAccesses.Resources', $resourceFactory);
    }

    /**
     * @param \Passbolt\Log\Test\Factory\SecretsHistoryFactory|SecretHistory|array $secretHistory Secret history factory
     * @return $this
     */
    public function withSecretsHistory(array|SecretsHistoryFactory|SecretHistory $secretHistory = [])
    {
        return $this->secretsHistory()->with('SecretsHistory', $secretHistory);
    }

    /**
     * @param \Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory|SecretRevision|array $secretRevisions Secret revisions.
     * @return $this
     */
    public function withSecretRevisions(array|SecretRevisionFactory|SecretRevision $secretRevisions = [])
    {
        return $this->secretRevisions()->with('SecretRevisions', $secretRevisions);
    }

    /**
     * @return $this
     */
    public function users()
    {
        return $this->setField('foreign_model', 'Users');
    }

    /**
     * @return $this
     */
    public function resources()
    {
        return $this->setField('foreign_model', 'Resources');
    }

    /**
     * @return $this
     */
    public function secretAccesses()
    {
        return $this->setField('foreign_model', 'SecretAccesses');
    }

    /**
     * @return $this
     */
    public function secretsHistory()
    {
        return $this->setField('foreign_model', 'SecretsHistory');
    }

    /**
     * @return $this
     */
    public function secretRevisions()
    {
        return $this->setField('foreign_model', 'SecretRevisions');
    }

    /**
     * @return $this
     */
    public function create()
    {
        return $this->setField('crud', EntityHistory::CRUD_CREATE);
    }

    /**
     * @return $this
     */
    public function update()
    {
        return $this->setField('crud', EntityHistory::CRUD_UPDATE);
    }
}

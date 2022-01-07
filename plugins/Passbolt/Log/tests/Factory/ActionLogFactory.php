<?php
declare(strict_types=1);

namespace Passbolt\Log\Test\Factory;

use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ProfileFactory
 */
class ActionLogFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Log.ActionLogs';
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
                'user_id' => $faker->uuid(),
                'action_id' => $faker->uuid(),
                'context' => $faker->text(255),
                'status' => 1,
                'created' => Chronos::now()->subMinute($faker->randomNumber(8)),
            ];
        });
    }

    /**
     * @return $this
     */
    public function loginAction()
    {
        return $this->patchData(['action_id' => UuidFactory::uuid('AuthLogin.loginPost')]);
    }
}

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
 * @since         3.0.0
 */
namespace App\Test\Factory;

use App\Model\Entity\Comment;
use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Test\Factory\Traits\FactoryDeletedTrait;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * CommentFactory
 *
 * @method \App\Model\Entity\Comment getEntity()
 * @method \App\Model\Entity\Comment[] getEntities()
 * @method \App\Model\Entity\Comment persist()
 */
class CommentFactory extends CakephpBaseFactory
{
    use FactoryDeletedTrait;

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Comments';
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
                'parent_id' => null,
                'user_id' => $faker->uuid(),
                'foreign_key' => $faker->uuid(),
                'foreign_model' => $faker->uuid(),
                'content' => $faker->text(256),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
            ];
        });
    }

    public function withUser(?User $user = null)
    {
        if (!isset($user)) {
            $user = UserFactory::make()->getEntity();
        }

        return $this->with('Users', $user);
    }

    public function withResource(?Resource $resource = null)
    {
        if (!isset($resource)) {
            $resource = ResourceFactory::make()->getEntity();
        }

        return $this->with('Resources', $resource)->setField('foreign_model', 'Resource');
    }

    public function withParent(?Comment $comment = null)
    {
        if (!isset($comment)) {
            $comment = CommentFactory::make()->getEntity();
        }

        return $this->with('Parents', $comment)->setField('foreign_model', 'Resource')->setField('parent_id', $comment->get('id'));
    }

    public function withCreatorAndModifier(?User $user = null)
    {
        return $this->withModifier($user)->withCreator($user);
    }

    public function withModifier(?User $user = null)
    {
        if (!isset($user)) {
            $user = UserFactory::make()->getEntity();
        }

        return $this->with('Modifier', $user)->setField('modified_by', $user->get('id'));
    }

    public function withCreator(?User $user = null)
    {
        if (!isset($user)) {
            $user = UserFactory::make()->getEntity();
        }

        return $this->with('Creator', $user)->setField('created_by', $user->get('id'));
    }
}

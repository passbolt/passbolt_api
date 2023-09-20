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
 * @since         3.4.0
 */
namespace Passbolt\Folders\Test\Factory;

use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;

/**
 * FoldersRelationFactory
 *
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation|\Passbolt\Folders\Model\Entity\FoldersRelation[] persist()
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation getEntity()
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] getEntities()
 */
class FoldersRelationFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Folders.FoldersRelations';
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
                'foreign_model' => $faker->text(30),
                'foreign_id' => $faker->uuid(),
                'user_id' => $faker->uuid(),
                'folder_parent_id' => $faker->uuid(),
                'created' => Chronos::now()->subDays($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDays($faker->randomNumber(4)),
            ];
        });
    }

    /**
     * Define the associated foreign model resource
     *
     * @param ResourceFactory|null $factory
     * @return FoldersRelationFactory
     */
    public function withForeignModelResource(?ResourceFactory $factory = null): self
    {
        $this->foreignModelResource();

        return $this->with('Resources', $factory);
    }

    /**
     * Define the associated foreign model folder
     *
     * @param FolderFactory|null $factory
     * @return FoldersRelationFactory
     */
    public function withForeignModelFolder(?FolderFactory $factory = null): self
    {
        $this->foreignModelFolder();

        return $this->with('Folders', $factory);
    }

    /**
     * Define the associated folder parent
     *
     * @param FolderFactory|null $factory
     * @return FoldersRelationFactory
     */
    public function withFolderParent(?FolderFactory $factory = null): self
    {
        return $this->with('FoldersParents', $factory);
    }

    /**
     * Define the associated user
     *
     * @param UserFactory|null $factory
     * @return FoldersRelationFactory
     */
    public function withUser(?UserFactory $factory = null): self
    {
        return $this->with('Users', $factory);
    }

    /**
     * Define the resource foreign model
     *
     * @param Resource|null $resource
     * @return FoldersRelationFactory
     */
    public function foreignModelResource(?Resource $resource = null): self
    {
        $this->patchData(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE]);

        if (isset($resource)) {
            $this->patchData(['foreign_id' => $resource->id]);
        }

        return $this;
    }

    /**
     * Define the folder foreign model
     *
     * @param Folder|null $folder
     * @return FoldersRelationFactory
     */
    public function foreignModelFolder(?Folder $folder = null): self
    {
        $this->patchData(['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER]);

        if (isset($folder)) {
            $this->patchData(['foreign_id' => $folder->id]);
        }

        return $this;
    }

    /**
     * Define the associated folder parent.
     *
     * @param Folder|null $folderParent
     * @return FoldersRelationFactory
     */
    public function folderParent(?Folder $folderParent): self
    {
        if (isset($folderParent)) {
            $this->patchData(['folder_parent_id' => $folderParent->id]);
        } else {
            $this->patchData(['folder_parent_id' => FoldersRelation::ROOT]);
        }

        return $this;
    }

    /**
     * Define the associated user.
     *
     * @param User $user
     * @return FoldersRelationFactory
     */
    public function user(User $user): self
    {
        return $this->patchData(['user_id' => $user->id]);
    }
}

<?php
declare(strict_types=1);

use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Migrations\AbstractMigration;
use Passbolt\ResourceTypes\Model\Definition\SlugDefinition;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class V560AddStandaloneNoteResourceType extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $data = [
            [
                'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_NOTE),
                'slug' => ResourceType::SLUG_V5_NOTE,
                'name' => 'Standalone note',
                'description' => 'A resource with standalone notes.',
                'definition' => SlugDefinition::v5Note(),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
        ];

        $resourceTypesTable = $this->table('resource_types');
        $resourceTypesTable->insert($data)->saveData();
    }
}

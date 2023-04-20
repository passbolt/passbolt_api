<?php
declare(strict_types=1);

namespace App\Test\Factory;

use App\Model\Entity\ResourceType;
use App\Model\Table\ResourceTypesTable;
use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ResourceFactory
 *
 * @method \App\Model\Entity\ResourceType|\App\Model\Entity\ResourceType[] persist()
 * @method \App\Model\Entity\ResourceType getEntity()
 * @method \App\Model\Entity\ResourceType[] getEntities()
 * @method static \App\Model\Entity\ResourceType get($primaryKey, array $options = [])
 */
class ResourceTypeFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'ResourceTypes';
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
                'slug' => $faker->slug(3),
                'name' => $faker->words(3, true),
                'description' => $faker->text(64),
                'definition' => json_encode([
                    'resource' => [
                        'type' => 'object',
                        'required' => ['name'],
                        'properties' => [
                            'name' => [
                                'type' => 'string',
                                'maxLength' => 255,
                            ],
                            'username' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'maxLength' => 255,
                                    ],
                                    [
                                        'type' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'secret' => [
                        'type' => 'object',
                        'required' => ['password'],
                        'properties' => [
                            'password' => [
                                'type' => 'string',
                                'maxLength' => 4096,
                            ],
                            'description' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'maxLength' => 10000,
                                    ],
                                    [
                                        'type' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]),
                'created' => FrozenDate::now()->subDay($faker->randomNumber(4)),
                'modified' => FrozenDate::now()->subDay($faker->randomNumber(4)),
            ];
        });
    }

    public function default(): self
    {
        return $this->patchData(['id' => ResourceTypesTable::getDefaultTypeId()]);
    }

    public static function makeTotp(): self
    {
        return self::make([
            ['slug' => ResourceType::SLUG_STANDALONE_TOTP],
            ['slug' => ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP],
        ]);
    }
}

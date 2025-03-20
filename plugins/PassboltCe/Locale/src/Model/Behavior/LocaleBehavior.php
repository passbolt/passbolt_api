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
 * @since         3.2.0
 */

namespace Passbolt\Locale\Model\Behavior;

use Cake\Core\InstanceConfigTrait;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Passbolt\Locale\Service\GetOrgLocaleService;

/**
 * Decorate a Table class to add the "locale" property on its entities.
 */
class LocaleBehavior extends Behavior
{
    use InstanceConfigTrait;

    /**
     * Name of the property added to entities.
     */
    public const LOCALE_PROPERTY = 'locale';

    /**
     * Name of the finder to use with Query::find()
     *
     * @see Query::find()
     */
    public const FINDER_NAME = 'locale';

    /**
     * Default config
     *
     * These are merged with user-provided config when the behavior is used.
     *
     * events - an event-name array.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'implementedFinders' => [
            /** @uses findLocale() */
            self::FINDER_NAME => 'findLocale', // Make the finder available with the name "locale"
        ],
    ];

    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->table()->hasOne('Locale', [
            'className' => 'Passbolt/AccountSettings.AccountSettings',
            'foreignKey' => 'user_id',
            'conditions' => ['Locale.property' => self::LOCALE_PROPERTY],
        ]);
    }

    /**
     * Finder to find the locale associated to the users.
     *
     * @param \Cake\ORM\Query $query The target query.
     * @return \Cake\ORM\Query
     */
    public function findLocale(Query $query): Query
    {
        $query->leftJoinWith('Locale', function (Query $q) {
            $organizationLocale = GetOrgLocaleService::getLocale();
            $locale = $q->expr()->case()
                ->when($q->expr()->isNotNull('Locale.value'))
                ->then($q->identifier('Locale.value'))
                ->else($organizationLocale);

            return $q->select([
                self::LOCALE_PROPERTY => $locale,
            ]);
        });

        return $query;
    }
}

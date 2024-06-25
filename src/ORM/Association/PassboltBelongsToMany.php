<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         4.9.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\ORM\Association;

use Cake\ORM\Association\BelongsToMany;
use Cake\Utility\Hash;
use Closure;

/**
 * This class extends the CakePHP belongs to many class and add to it additional behavior required by passbolt.
 *
 * For performance reason, eagerLoader can take care of removing any _joinData from the result returned. This spares an
 * iteration on the complete result set as well as having to treat the resulting data.
 *
 * Add the following option to the ORM query to remove any _jointData from the returned result.
 *
 *   $query->applyOptions(["excludeJunctionProperty" => true]);
 */
class PassboltBelongsToMany extends BelongsToMany
{
    /**
     * @var string Option used to exclude junction property from query result set.
     */
    public const QUERY_OPTION_EXCLUDE_JUNCTION_PROPERTY = 'excludeJunctionProperty';

    /**
     * @inheritDoc
     */
    public function eagerLoader(array $options): Closure
    {
        $closure = parent::eagerLoader($options);

        /** @var \Cake\ORM\Query $query */
        $query = $options['query'];
        $excludeJunctionProperty = Hash::get($query->getOptions(), self::QUERY_OPTION_EXCLUDE_JUNCTION_PROPERTY, false);
        if (!$excludeJunctionProperty) {
            return $closure;
        }

        return function ($row) use ($closure) {
            $row = $closure($row);
            foreach ($row[$this->getAlias()] ?? [] as $i => $subRow) {
                unset($row[$this->getAlias()][$i][$this->_junctionProperty]);
            }

            return $row;
        };
    }
}

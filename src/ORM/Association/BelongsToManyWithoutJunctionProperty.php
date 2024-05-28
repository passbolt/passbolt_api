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
use Closure;

/**
 * This class extends the CakePHP belongs to many class,
 * but in addition removes the _joinData from the result
 * returned. This spares an iteration on the complete result set.
 */
class BelongsToManyWithoutJunctionProperty extends BelongsToMany
{
    /**
     * @inheritDoc
     */
    public function eagerLoader(array $options): Closure
    {
        $closure = parent::eagerLoader($options);

        return function ($row) use ($closure) {
            $row = $closure($row);
            foreach ($row[$this->getAlias()] ?? [] as $i => $subRow) {
                unset($row[$this->getAlias()][$i][$this->_junctionProperty]);
            }

            return $row;
        };
    }
}

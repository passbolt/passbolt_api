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
 * @since         4.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\Type\UuidType;

/**
 * Provides behavior for the UUID type
 */
class LowerCaseUuidType extends UuidType
{
    /**
     * @inheritDoc
     */
    public function toDatabase($value, DriverInterface $driver): ?string
    {
        return $this->lowerIfNotNull(parent::toDatabase($value, $driver));
    }

    /**
     * @inheritDoc
     */
    public function marshal($value): ?string
    {
        return $this->lowerIfNotNull(parent::marshal($value));
    }

    /**
     * @inheritDoc
     */
    public function toPHP($value, DriverInterface $driver): ?string
    {
        return $this->lowerIfNotNull(parent::toPHP($value, $driver));
    }

    /**
     * @param string|null $uuid UUID marshalled or being saved
     * @return string|null
     */
    protected function lowerIfNotNull(?string $uuid): ?string
    {
        if (is_null($uuid)) {
            return null;
        }

        return strtolower($uuid);
    }
}

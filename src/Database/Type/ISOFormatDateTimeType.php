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
namespace App\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\Type\DateTimeFractionalType;
use Cake\Database\Type\DateTimeType;
use Cake\Database\TypeFactory;

/**
 * Formats dates in ISO format.
 *
 * This type is used in replacement of CakePHP DateTimeType and DateTimeFractionalType to
 * improve the performances of large queries treatment.
 */
class ISOFormatDateTimeType extends DateTimeType
{
    /**
     * @inheritDoc
     */
    public function manyToPHP(array $values, array $fields, DriverInterface $driver): array
    {
        foreach ($fields as $field) {
            if (!isset($values[$field])) {
                continue;
            }

            $values[$field] = date(\DateTimeInterface::ATOM, strtotime($values[$field]));
        }

        return $values;
    }

    /**
     * Helper method remap the date time types to the present type
     * This avoids time stamps to be converted in objects, thus enhancing
     * performance
     *
     * @return void
     */
    public static function mapDatetimeTypesToMe(): void
    {
        TypeFactory::map('datetime', self::class);
        TypeFactory::map('timestampfractional', self::class);
    }

    /**
     * Helper method for tests to remap the date time types to the default ones
     *
     * @deprecated this can be removed once the whole API uses the present class
     * @return void
     */
    public static function remapDatetimeTypesToDefault(): void
    {
        TypeFactory::map('datetime', DateTimeType::class);
        TypeFactory::map('timestampfractional', DateTimeFractionalType::class);
    }
}

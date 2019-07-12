<?php
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
 * @since         2.0.0
 */
namespace App\View\Helper;

use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Cake\View\Helper;

use InvalidArgumentException;

class LegacyApiHelper extends Helper
{
    /**
     * Return entity name
     *
     * @param Entity $entity Cake\ORM\Entity
     * @return bool|string
     */
    public static function getEntityName(Entity $entity)
    {
        // example: App\Model\User becomes User
        return substr(get_class($entity), strrpos(get_class($entity), '\\') + 1);
    }

    /**
     * Format a model name
     *
     * @param string $name the model name
     * @return string new name
     */
    public static function formatModelName($name)
    {
        $new_name = null;
        if (strpos($name, '_') !== false) {
            // example: groups_users becomes GroupUser
            $names = explode('_', $name);
            foreach ($names as $n) {
                $new_name .= self::formatModelName($n);
            }
        } else {
            // example: groups becomes Group
            $new_name = ucfirst(Inflector::singularize($name));
        }

        return $new_name;
    }

    /**
     * Format an entity to an array
     *
     * @param Entity $entity Cake\ORM\Entity
     * @param string $name see getEntityName
     * @return array new entity
     */
    public static function formatEntity(Entity $entity, string $name)
    {
        $result = [];
        foreach ($entity->getVisible() as $property) {
            $value = $entity->get($property);
            if (is_string($value) || is_bool($value) || is_numeric($value) || is_null($value)) {
                // example: id
                $result[$name][$property] = $value;
            } elseif (is_object($value) && is_a($value, 'Cake\Chronos\ChronosInterface')) {
                // example: modified
                $result[$name][$property] = $value->toDateTimeString();
            } elseif (is_object($value) && $value instanceof Entity) {
                // example: gpgkey, scafolded model
                $subEntityName = self::formatModelName($property);
                $formattedEntity = self::formatEntity($value, $subEntityName);
                //pr($formattedEntity);
                $result[$subEntityName] = $formattedEntity[$subEntityName];
                unset($formattedEntity[$subEntityName]);
                if (!empty($formattedEntity)) {
                    $result[$subEntityName] = array_merge($result[$subEntityName], $formattedEntity);
                }
            } elseif ($property == 'children' && is_array($value)) {
                $result[$property] = self::formatResultSet($value);
            } elseif (is_array($value)) {
                $subEntityName = self::formatModelName($property);
                // example: avatar.url (array of strings).
                if (!empty($value) && is_scalar($value[key($value)])) {
                    $result[$name][$property] = $value;
                    continue;
                } elseif (count($value) === 0) {
                    // if array is empty mark it as such
                    $result[$subEntityName] = [];
                } else {
                    // example: groups_users
                    foreach ($value as $i => $entity2) {
                        $formattedEntity = self::formatEntity($entity2, $subEntityName);
                        $result[$subEntityName][$i] = $formattedEntity[$subEntityName];
                        unset($formattedEntity[$subEntityName]);
                        if (!empty($formattedEntity)) {
                            $result[$subEntityName][$i] = array_merge($result[$subEntityName][$i], $formattedEntity);
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Format a result set to an array
     *
     * @param Query|array $resultSet Cake\ORM\Query
     * @return array new result set
     */
    public static function formatResultSet($resultSet)
    {
        $i = 0;
        $results = [];
        foreach ($resultSet as $entity) {
            $name = self::getEntityName($entity);
            $results[$i] = self::formatEntity($entity, $name);
            $i++;
        }

        return $results;
    }

    /**
     * Format a result set to an array
     *
     * @param array $errors Errors
     * @param \Cake\ORM\Table $table table
     * @return array new result set
     */
    public static function formatErrors($errors, Table $table)
    {
        $results = [];

        // If the error data is a result set / the result of a find
        // Render normally
        if ($errors instanceof Query) {
            $results[$table->getTable()] = LegacyApiHelper::formatResultSet($errors);

            return $results;
        }

        foreach ($errors as $property => $propertyErrors) {
            // If the property is an integer, it means the function is treating the validation errors
            // of an association with a multiple cardinality.
            // Example: Groups.groups_users[]
            if (is_int($property)) {
                $results[$property] = self::formatErrors($propertyErrors, $table);
                continue;
            }

            // If the property is an association, retrieve the table.
            // Example: Groups.groups_users
            try {
                $association = $table->getAssociation(Inflector::camelize($property));
            } catch (InvalidArgumentException $exception) {
                // In the case the property is not an association.
                // Example: Groups.name
                $className = substr($table->getEntityClass(), strrpos($table->getEntityClass(), '\\') + 1);
                $resultKey = self::formatModelName(Inflector::underscore($className));
                $results[$resultKey][$property] = $propertyErrors;
                continue;
            }

            // In case the association is a HasMany.
            // Example: Groups.groups_users
            if (get_class($association) === 'Cake\ORM\Association\HasMany') {
                $associationName = self::formatModelName($property);
                $associationTable = $association->getTarget();
                $result = self::formatErrors($propertyErrors, $associationTable);
                $resultKey = Inflector::pluralize($associationName);
                $results[$resultKey] = [];
                if (isset($result[$associationName])) {
                    $results[$resultKey] = $result[$associationName];
                    unset($result[$associationName]);
                }
                $results[$resultKey] = $results[$resultKey] + $result;
            }
        }

        return $results;
    }
}

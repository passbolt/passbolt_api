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
 * @since         4.1.0
 */

namespace Passbolt\Scim\Resources\ResourceType;

use Cake\Datasource\EntityInterface;
use Passbolt\Scim\Resources\ResourceTypeInterface;

/**
 * Abstract BaseResourceType class
 */
abstract class BaseResourceType implements ResourceTypeInterface
{
    /**
     * Resource type schemas
     *
     * @var array|string[]
     */
    protected array $schemas = [];

    /**
     * Field mapping to fill the object properties from SCIM data
     *
     * NOTE:
     *   You can use `Hash::get` compatible paths for the maps
     *   For a value in an array with multiple elements, use `Hash::extract` compatible paths.
     *   Will get the first matching result
     *
     * Ex:
     *  [
     *      'email' => 'emails.{n}[type=work][primary=1].value',
     *  ]
     *
     * @var array
     */
    protected array $fieldMappings = [];

    /**
     * Entity
     *
     * @var \Cake\Datasource\EntityInterface|null
     */
    protected ?EntityInterface $entity = null;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get property
     *
     * @param string $property Property
     * @return mixed
     */
    public function get(string $property)
    {
        return $this->{$property} ?? null;
    }
}

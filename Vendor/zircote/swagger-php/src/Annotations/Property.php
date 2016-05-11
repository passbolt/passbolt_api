<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 */
class Property extends Schema
{
    /**
     * The key into Schema->properties array.
     * @var string
     */
    public $property;

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Definition',
        'Swagger\Annotations\Schema',
        'Swagger\Annotations\Property',
    ];
}

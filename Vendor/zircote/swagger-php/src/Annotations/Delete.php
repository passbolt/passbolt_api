<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 */
class Delete extends Operation
{
    /** @inheritdoc */
    public $method = 'delete';

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Path'
    ];
}

<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 */
class Put extends Operation
{
    /** @inheritdoc */
    public $method = 'put';

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Path'
    ];
}

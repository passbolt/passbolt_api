<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 */
class Head extends Operation
{
    /** @inheritdoc */
    public $method = 'head';

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Path'
    ];
}

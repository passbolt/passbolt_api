<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 */
class Patch extends Operation
{
    /** @inheritdoc */
    public $method = 'patch';

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Path'
    ];
}

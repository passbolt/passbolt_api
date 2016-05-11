<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 */
class Options extends Operation
{
    /** @inheritdoc */
    public $method = 'options';

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Path'
    ];
}

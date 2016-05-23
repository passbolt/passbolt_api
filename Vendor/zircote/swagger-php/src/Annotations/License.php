<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 * License information for the exposed API.
 *
 * A Swagger "License Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#licenseObject
 */
class License extends AbstractAnnotation
{
    /**
     * The license name used for the API.
     * @var string
     */
    public $name;

    /**
     * A URL to the license used for the API.
     * @var string
     */
    public $url;

    /** @inheritdoc */
    public static $_types = [
        'name' => 'string',
        'url' => 'string',
    ];

    /** @inheritdoc */
    public static $_required = ['name'];

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Info'
    ];
}

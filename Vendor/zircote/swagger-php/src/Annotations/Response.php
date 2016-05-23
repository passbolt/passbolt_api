<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 *
 * A Swagger "Response Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#responseObject
 */
class Response extends AbstractAnnotation
{
    /**
     * $ref See http://json-schema.org/latest/json-schema-core.html#rfc.section.7
     * @var string
     */
    public $ref;

    /**
     * The key into Operations->responses array.
     *
     * @var string a HTTP Status Code or "default"
     */
    public $response;

    /**
     * A short description of the response. GFM syntax can be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * A definition of the response structure. It can be a primitive, an array or an object. If this field does not exist, it means no content is returned as part of the response. As an extension to the Schema Object, its root type value may also be "file". This SHOULD be accompanied by a relevant produces mime-type.
     * @var Schema
     */
    public $schema;

    /**
     * A list of headers that are sent with the response.
     * @var Header[]
     */
    public $headers;

    /**
     * An example of the response message.
     * @var array
     */
    public $examples;

    /** @inheritdoc */
    public static $_required = ['description'];

    /** @inheritdoc */
    public static $_types = [
        'description' => 'string',
    ];

    /** @inheritdoc */
    public static $_nested = [
        'Swagger\Annotations\Schema' => 'schema',
        'Swagger\Annotations\Header' => ['headers', 'header']
    ];

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Operation',
        'Swagger\Annotations\Get',
        'Swagger\Annotations\Post',
        'Swagger\Annotations\Put',
        'Swagger\Annotations\Patch',
        'Swagger\Annotations\Delete',
        'Swagger\Annotations\Head',
        'Swagger\Annotations\Options',
        'Swagger\Annotations\Swagger'
    ];
}

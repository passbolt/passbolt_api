<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 * Allows referencing an external resource for extended documentation.
 *
 * A Swagger "External Documentation Object":  * https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#external-documentation-object
 */
class ExternalDocumentation extends AbstractAnnotation
{
    /**
     * A short description of the target documentation. GFM syntax can be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * The URL for the target documentation.
     * @var string
     */
    public $url;

    /** @inheritdoc */
    public static $_types = [
        'description' => 'string',
        'url' => 'string',
    ];

    /** @inheritdoc */
    public static $_required = ['url'];

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Swagger',
        'Swagger\Annotations\Tag',
        'Swagger\Annotations\Schema',
        'Swagger\Annotations\Definition',
        'Swagger\Annotations\Property',
        'Swagger\Annotations\Operation',
        'Swagger\Annotations\Get',
        'Swagger\Annotations\Post',
        'Swagger\Annotations\Put',
        'Swagger\Annotations\Delete',
        'Swagger\Annotations\Patch',
        'Swagger\Annotations\Head',
        'Swagger\Annotations\Options'
    ];
}

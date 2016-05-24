<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 *
 * A Swagger "Info Object":  https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#infoObject
 */
class Info extends AbstractAnnotation
{
    /**
     * The title of the application.
     * @var string
     */
    public $title;

    /**
     * A short description of the application. GFM syntax can be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * The Terms of Service for the API.
     * @var string
     */
    public $termsOfService;

    /**
     * The contact information for the exposed API.
     * @var Contact
     */
    public $contact;

    /**
     * The license information for the exposed API.
     * @var License
     */
    public $license;

    /**
     * Provides the version of the application API (not to be confused by the specification version).
     * @var string
     */
    public $version;

    /** @inheritdoc */
    public static $_required = ['title', 'version'];

    /** @inheritdoc */
    public static $_types = [
        'title' => 'string',
        'description' => 'string',
        'termsOfService' => 'string'
    ];

    /** @inheritdoc */
    public static $_nested = [
        'Swagger\Annotations\Contact' => 'contact',
        'Swagger\Annotations\License' => 'license'
    ];

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Swagger'
    ];
}

<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 *
 * A Swagger "Header Object" https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#headerObject
 */
class Header extends AbstractAnnotation
{
    /**
     * @var string
     */
    public $header;

    /**
     * @var string
     */
    public $description;

    /**
     * The type of the parameter. Since the parameter is not located at the request body, it is limited to simple types (that is, not an object). The value MUST be one of "string", "number", "integer", "boolean", "array" or "file". If type is "file", the consumes MUST be either "multipart/form-data" or " application/x-www-form-urlencoded" and the parameter MUST be in "formData".
     * @var string
     */
    public $type;

    /**
     * The extending format for the previously mentioned type. See Data Type Formats for further details.
     * @var string
     */
    public $format;

    /**
     * Required if type is "array". Describes the type of items in the array.
     * @var Items
     */
    public $items;

    /**
     * @var string Determines the format of the array if type array is used. Possible values are: csv - comma separated values foo,bar. ssv - space separated values foo bar. tsv - tab separated values foo\tbar. pipes - pipe separated values foo|bar. multi - corresponds to multiple parameter instances instead of multiple values for a single instance foo=bar&foo=baz. This is valid only for parameters in "query" or "formData". Default value is csv.
     */
    public $collectionFormat;

    /**
     * Sets a default value to the parameter. The type of the value depends on the defined type. See http://json-schema.org/latest/json-schema-validation.html#anchor101.
     * @var mixed
     */
    public $default = UNDEFINED;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor17.
     * @var number
     */
    public $maximum;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor17.
     * @var boolean
     */
    public $exclusiveMaximum;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor21.
     * @var number
     */
    public $minimum;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor21.
     * @var boolean
     */
    public $exclusiveMinimum;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor26.
     * @var integer
     */
    public $maxLength;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor29.
     * @var integer
     */
    public $minLength;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor33.
     * @var string
     */
    public $pattern;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor42.
     * @var integer
     */
    public $maxItems;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor45.
     * @var integer
     */
    public $minItems;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor49.
     * @var boolean
     */
    public $uniqueItems;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor76.
     * @var array
     */
    public $enum;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor14.
     * @var number
     */
    public $multipleOf;

    /** @inheritdoc */
    public static $_required = ['type'];

    /** @inheritdoc */
    public static $_types = [
        'header' => 'string',
        'description' => 'string',
        'format' => 'string',
        'collectionFormat' => ['csv', 'ssv', 'tsv', 'pipes', 'multi'],
        'maximum' => 'number',
        'exclusiveMaximum' => 'boolean',
        'minimum' => 'number',
        'exclusiveMinimum' => 'boolean',
        'maxLength' => 'integer',
        'minLength' => 'integer',
        'pattern' => 'string',
        'maxItems' => 'integer',
        'minItems' => 'integer',
        'uniqueItems' => 'boolean',
        'multipleOf' => 'integer',
    ];

    /** @inheritdoc */
    public static $_nested = [
        'Swagger\Annotations\Items' => 'items'
    ];

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Response'
    ];
}

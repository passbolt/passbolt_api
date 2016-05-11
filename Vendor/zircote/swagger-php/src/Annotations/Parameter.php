<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

use \Swagger\Logger;

/**
 * @Annotation
 * Describes a single operation parameter.
 *
 * A Swagger "Parameter Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#parameterObject
 */
class Parameter extends AbstractAnnotation
{
    /**
     * $ref See http://json-schema.org/latest/json-schema-core.html#rfc.section.7
     * @var string
     */
    public $ref;

    /**
     * The key into Swagger->parameters or Path->parameters array.
     * @var string
     */
    public $parameter;

    /**
     * The name of the parameter. Parameter names are case sensitive. If in is "path", the name field MUST correspond to the associated path segment from the path field in the Paths Object. See Path Templating for further information. For all other cases, the name corresponds to the parameter name used based on the in property.
     * @var string
     */
    public $name;

    /**
     * The location of the parameter. Possible values are "query", "header", "path", "formData" or "body".
     * @var string
     */
    public $in;

    /**
     * A brief description of the parameter. This could contain examples of use. GFM syntax can be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * Determines whether this parameter is mandatory. If the parameter is in "path", this property is required and its value MUST be true. Otherwise, the property MAY be included and its default value is false.
     * @var boolean
     */
    public $required;

    /**
     * The schema defining the type used for the body parameter.
     * @var Schema
     */
    public $schema;

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
     * @var array
     */
    public $items;

    /**
     * Determines the format of the array if type array is used. Possible values are: csv - comma separated values foo,bar. ssv - space separated values foo bar. tsv - tab separated values foo\tbar. pipes - pipe separated values foo|bar. multi - corresponds to multiple parameter instances instead of multiple values for a single instance foo=bar&foo=baz. This is valid only for parameters in "query" or "formData". Default value is csv.
     * @var string
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
    public static $_required = ['name', 'in'];

    /** @inheritdoc */
    public static $_types = [
        'name' => 'string',
        'in' => ['query', 'header', 'path', 'formData', 'body'],
        'description' => 'string',
        'required' => 'boolean',
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
        'Swagger\Annotations\Items' => 'items',
        'Swagger\Annotations\Schema' => 'schema'
    ];

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Operation',
        'Swagger\Annotations\Get',
        'Swagger\Annotations\Post',
        'Swagger\Annotations\Put',
        'Swagger\Annotations\Delete',
        'Swagger\Annotations\Patch',
        'Swagger\Annotations\Path',
        'Swagger\Annotations\Head',
        'Swagger\Annotations\Options',
        'Swagger\Annotations\Swagger'
    ];

    /** @inheritdoc */
    public function validate($parents = [], $skip = [])
    {
        if (in_array($this, $skip, true)) {
            return true;
        }
        $valid = parent::validate($parents, $skip);
        if (empty($this->ref)) {
            if ($this->in === 'body') {
                if ($this->schema === null) {
                    Logger::notice('Field "schema" is required when ' . $this->identity() . ' is in "' . $this->in . '" in ' . $this->_context);
                    $valid = false;
                }
            } else {
                $validTypes = ['string', 'number', 'integer', 'boolean', 'array', 'file'];
                if ($this->type === null) {
                    Logger::notice($this->identity() . '->type is required when ' . $this->_identity([]) . '->in == "' . $this->in . '" in ' . $this->_context);
                    $valid = false;
                } elseif ($this->type === 'array' && $this->items === null) {
                    Logger::notice($this->identity() . '->items required when ' . $this->_identity([]) . '->type == "array" in ' . $this->_context);
                    $valid = false;
                } elseif (in_array($this->type, $validTypes) === false) {
                    $valid = false;
                    Logger::notice($this->identity() . '->type must be "' . implode('", "', $validTypes) . '" when ' . $this->_identity([]) . '->in != "body" in ' . $this->_context);
                }
            }
        }
        return $valid;
    }

    /** @inheritdoc */
    public function identity()
    {
        return parent::_identity(['name', 'in']);
    }
}

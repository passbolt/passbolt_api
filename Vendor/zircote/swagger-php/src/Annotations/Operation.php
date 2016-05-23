<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

use Swagger\Logger;

/**
 * @Annotation
 * Base class for the @SWG\Get(),  @SWG\Post(),  @SWG\Put(),  @SWG\Delete(), @SWG\Patch()
 *
 * A Swagger "Operation Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#operationObject
 */
abstract class Operation extends AbstractAnnotation
{
    /**
     * key in the Swagger "Paths Object" for this operation
     * @var string
     */
    public $path;

    /**
     * Key in the Swagger "Path Item Object" for this operation.
     * Allowed values: 'get', 'post', put', 'delete', 'options', 'head' and 'patch'
     * @var string
     */
    public $method;

    /**
     * A list of tags for API documentation control. Tags can be used for logical grouping of operations by resources or any other qualifier.
     * @var array
     */
    public $tags;

    /**
     * A short summary of what the operation does. For maximum readability in the swagger-ui, this field SHOULD be less than 120 characters.
     * @var string
     */
    public $summary;

    /**
     * A verbose explanation of the operation behavior. GFM syntax can be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * Additional external documentation for this operation.
     * @var ExternalDocumentation
     */
    public $externalDocs;

    /**
     * A friendly name for the operation.
     * The id MUST be unique among all operations described in the API.
     * Tools and libraries MAY use the operation id to uniquely identify an operation.
     * @var string
     */
    public $operationId;

    /**
     * A list of MIME types the operation can consume.
     * This overrides the [consumes](#swaggerConsumes) definition at the Swagger Object.
     * An empty value MAY be used to clear the global definition.
     * Value MUST be as described under Mime Types.
     * @var array
     */
    public $consumes;

    /**
     * A list of MIME types the operation can produce.
     * This overrides the [produces](#swaggerProduces) definition at the Swagger Object.
     * An empty value MAY be used to clear the global definition.
     * Value MUST be as described under Mime Types.
     * @var array
     */
    public $produces;

    /**
     * A list of parameters that are applicable for this operation.
     * If a parameter is already defined at the Path Item, the new definition will override it, but can never remove it. The list MUST NOT include duplicated parameters. A unique parameter is defined by a combination of a name and location.
     * The list can use the Reference Object to link to parameters that are defined at the Swagger Object's parameters.
     * There can be one "body" parameter at most.
     * @var Parameter[]
     */
    public $parameters;

    /**
     * The list of possible responses as they are returned from executing this operation.
     * @var array
     */
    public $responses;

    /**
     * The transfer protocol for the operation.
     * Values MUST be from the list: "http", "https", "ws", "wss".
     * The value overrides the Swagger Object schemes definition.
     * @var array
     */
    public $schemes;

    /**
     * Declares this operation to be deprecated.
     * Usage of the declared operation should be refrained. Default value is false.
     * @var boolean
     */
    public $deprecated;

    /**
     * A declaration of which security schemes are applied for this operation.
     * The list of values describes alternative security schemes that can be used (that is, there is a logical OR between the security requirements).
     * This definition overrides any declared top-level security.
     * To remove a top-level security declaration, an empty array can be used.
     * @var array
     */
    public $security;

    /** @inheritdoc */
    public static $_required = ['responses'];

    /** @inheritdoc */
    public static $_types = [
        'path' => 'string',
        'method' => 'string',
        'tags' => '[string]',
        'summary' => 'string',
        'description' => 'string',
        'consumes' => '[string]',
        'produces' => '[string]',
        'schemes' => '[scheme]',
        'deprecated' => 'boolean'
    ];

    /** @inheritdoc */
    public static $_nested = [
        'Swagger\Annotations\Parameter' => ['parameters'],
        'Swagger\Annotations\Response' => ['responses', 'response'],
        'Swagger\Annotations\ExternalDocumentation' => 'externalDocs'
    ];

    /** @inheritdoc */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        unset($data->method);
        unset($data->path);
        return $data;
    }

    public function validate($parents = [], $skip = [])
    {
        if (in_array($this, $skip, true)) {
            return true;
        }
        $valid = parent::validate($parents, $skip);
        if ($this->responses !== null) {
            foreach ($this->responses as $response) {
                if ($response->response !== 'default' && preg_match('/^[12345]{1}[0-9]{2}$/', $response->response) === 0) {
                    Logger::notice('Invalid value "' . $response->response . '" for ' . $response->_identity([]) . '->response, expecting "default" or a HTTP Status Code in ' . $response->_context);
                }
            }
        }
        return $valid;
    }
}

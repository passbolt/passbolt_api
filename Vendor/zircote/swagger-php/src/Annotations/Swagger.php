<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

use Exception;
use Swagger\Analysis;

/**
 * @Annotation
 * This is the root document object for the API specification.
 *
 * A Swagger "Swagger Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#swagger-object-
 */
class Swagger extends AbstractAnnotation
{
    /**
     * Specifies the Swagger Specification version being used. It can be used by the Swagger UI and other clients to interpret the API listing.
     * @var string
     */
    public $swagger = '2.0';

    /**
     * Provides metadata about the API. The metadata can be used by the clients if needed.
     * @var Info
     */
    public $info;

    /**
     * The host (name or ip) serving the API. This MUST be the host only and does not include the scheme nor sub-paths. It MAY include a port. If the host is not included, the host serving the documentation is to be used (including the port). The host does not support path templating.
     * @var string
     */
    public $host;

    /**
     * The base path on which the API is served, which is relative to the host. If it is not included, the API is served directly under the host. The value MUST start with a leading slash (/). The basePath does not support path templating.
     * @var string
     */
    public $basePath;

    /**
     * The transfer protocol of the API. Values MUST be from the list: "http", "https", "ws", "wss". If the schemes is not included, the default scheme to be used is the one used to access the specification.
     * @var array
     */
    public $schemes;

    /**
     * A list of MIME types the APIs can consume. This is global to all APIs but can be overridden on specific API calls. Value MUST be as described under Mime Types.
     * @var array
     */
    public $consumes;

    /**
     * A list of MIME types the APIs can produce. This is global to all APIs but can be overridden on specific API calls. Value MUST be as described under Mime Types.
     * @var array
     */
    public $produces;

    /**
     * The available paths and operations for the API.
     * @var Path[]
     */
    public $paths = [];

    /**
     * An object to hold data types produced and consumed by operations.
     * @var Definition[]
     */
    public $definitions = [];

    /**
     * An object to hold parameters that can be used across operations. This property does not define global parameters for all operations.
     * @var Parameter[]
     */
    public $parameters;

    /**
     * An object to hold responses that can be used across operations. This property does not define global responses for all operations.
     * @var Response[]
     */
    public $responses;

    /**
     * Security scheme definitions that can be used across the specification.
     * @var SecurityScheme[]
     */
    public $securityDefinitions;

    /**
     * A declaration of which security schemes are applied for the API as a whole. The list of values describes alternative security schemes that can be used (that is, there is a logical OR between the security requirements). Individual operations can override this definition.
     * @var array
     */
    public $security;

    /**
     * A list of tags used by the specification with additional metadata. The order of the tags can be used to reflect on their order by the parsing tools. Not all tags that are used by the Operation Object must be declared. The tags that are not declared may be organized randomly or based on the tools' logic. Each tag name in the list MUST be unique.
     * @var Tag[]
     */
    public $tags;

    /**
     * Additional external documentation.
     * @var ExternalDocumentation
     */
    public $externalDocs;
    
    /**
     * @var Analysis
     */
    public $_analysis;

    /** @inheritdoc */
    public static $_blacklist = ['_context', '_unmerged', '_analysis'];
    
    /** @inheritdoc */
    public static $_required = ['swagger', 'info', 'paths'];

    /** @inheritdoc */
    public static $_nested = [
        'Swagger\Annotations\Info' => 'info',
        'Swagger\Annotations\Path' => ['paths', 'path'],
        'Swagger\Annotations\Definition' => ['definitions', 'definition'],
        'Swagger\Annotations\Tag' => ['tags'],
        'Swagger\Annotations\Parameter' => ['parameters', 'parameter'],
        'Swagger\Annotations\Response' => ['responses', 'response'],
        'Swagger\Annotations\ExternalDocumentation' => 'externalDocs',
        'Swagger\Annotations\SecurityScheme' => ['securityDefinitions', 'securityDefinition']
    ];

    /** @inheritdoc */
    public static $_types = [
        'host' => 'string',
        'basePath' => 'string',
        'schemes' => '[scheme]',
        'consumes' => '[string]',
        'produces' => '[string]',
    ];

    /**
     * Save the swagger documentation to a file.
     * @param string $filename
     * @throws Exception
     */
    public function saveAs($filename)
    {
        if (file_put_contents($filename, $this) === false) {
            throw new Exception('Failed to saveAs("' . $filename . '")');
        }
    }
}

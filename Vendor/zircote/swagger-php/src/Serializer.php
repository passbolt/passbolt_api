<?php

namespace Swagger;

/**
 * Class AnnotationDeserializer is used to deserialize a json string
 * to a specific Swagger PHP Annotation class and vice versa.
 *
 * @link https://github.com/zircote/swagger-php
 *
 */
class Serializer
{
    const    CONTACT = 'Swagger\Annotations\Contact';
    const    DEFINITION = 'Swagger\Annotations\Definition';
    const    DELETE = 'Swagger\Annotations\Delete';
    const    EXTERNALDOCUMENTATION = 'Swagger\Annotations\ExternalDocumentation';
    const    GET = 'Swagger\Annotations\Get';
    const    HEAD = 'Swagger\Annotations\Head';
    const    HEADER = 'Swagger\Annotations\Header';
    const    INFO = 'Swagger\Annotations\Info';
    const    ITEMS = 'Swagger\Annotations\Items';
    const    LICENSE = 'Swagger\Annotations\License';
    const    OPERATION = 'Swagger\Annotations\Operation';
    const    OPTIONS = 'Swagger\Annotations\Options';
    const    PARAMETER = 'Swagger\Annotations\Parameter';
    const    PATCH = 'Swagger\Annotations\Patch';
    const    PATH = 'Swagger\Annotations\Path';
    const    POST = 'Swagger\Annotations\Post';
    const    PROPERTY = 'Swagger\Annotations\Property';
    const    PUT = 'Swagger\Annotations\Put';
    const    RESPONSE = 'Swagger\Annotations\Response';
    const    SCHEMA = 'Swagger\Annotations\Schema';
    const    SECURITYSCHEME = 'Swagger\Annotations\SecurityScheme';
    const    SWAGGER = 'Swagger\Annotations\Swagger';
    const    TAG = 'Swagger\Annotations\Tag';
    const    XML = 'Swagger\Annotations\Xml';

    private static $cachedNames;

    private static function getDefinedNames()
    {
        if (static::$cachedNames === null) {
            static::$cachedNames = [];
            $reflection = new \ReflectionClass(__CLASS__);
            static::$cachedNames = $reflection->getConstants();
        }

        return static::$cachedNames;
    }

    public static function isValidClassName($className)
    {
        return in_array($className, static::getDefinedNames());
    }

    /**
     * Serialize.
     *
     * @param Annotations\AbstractAnnotation $annotation
     * @return string
     */
    public function serialize(Annotations\AbstractAnnotation $annotation)
    {
        return json_encode($annotation);
    }

    /**
     * Deserialize
     *
     * @param $jsonString
     * @param $className
     *
     * @return Annotations\AbstractAnnotation
     *
     * @throws \Exception
     */
    public function deserialize($jsonString, $className)
    {
        if (!$this->isValidClassName($className)) {
            throw new \Exception($className.' is not defined in Swagger PHP Annotations');
        }

        return $this->doDeserialize(json_decode($jsonString), $className);
    }

    /**
     * Do deserialization.
     *
     * @param \stdClass $c
     * @param string    $class The class name of annotation.
     *
     * @return Annotations\AbstractAnnotation
     */
    private function doDeserialize(\stdClass $c, $class)
    {
        $annotation = new $class([]);
        foreach ($c as $property => $value) {
            if (substr($property, 0, 2) === 'x-') {
                $custom = substr($property, 2);
                $annotation->x[$custom] = $value;
            } else {
                $annotation->$property = $this->doDeserializeProperty($annotation, $property, $value);
            }
        }

        return $annotation;
    }

    /**
     * Deserialize the annotation's property.
     *
     * @param Annotations\AbstractAnnotation $annotation
     * @param string                         $property
     * @param mixed                          $value
     *
     * @return mixed
     */
    private function doDeserializeProperty(Annotations\AbstractAnnotation $annotation, $property, $value)
    {
        // property is primitive type
        if (array_key_exists($property, $annotation::$_types)) {
            return $value;
        }

        // property is embedded annotation
        foreach ($annotation::$_nested as $class => $declaration) {
            // property is an annotation
            if (is_string($declaration) && $declaration === $property) {
                return $this->doDeserialize($value, $class);
            }

            // property is an annotation array
            if (is_array($declaration) && count($declaration) === 1 && $declaration[0] === $property) {
                $annotationArr = [];
                foreach ($value as $v) {
                    $annotationArr[] = $this->doDeserialize($v, $class);
                }

                return $annotationArr;
            }

            // property is an annotation hash map
            if (is_array($declaration) && count($declaration) === 2 && $declaration[0] === $property) {
                $key = $declaration[1];
                $annotationHash = [];
                foreach ($value as $k => $v) {
                    $annotation = $this->doDeserialize($v, $class);
                    $annotation->$key = $k;
                    $annotationHash[$k] = $annotation;
                }

                return $annotationHash;
            }
        }

        return $value;
    }
}

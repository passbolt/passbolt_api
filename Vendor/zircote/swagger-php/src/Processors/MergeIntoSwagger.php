<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Processors;

use Swagger\Annotations\Swagger;
use Swagger\Analysis;
use Swagger\Context;

/**
 * Merge all @SWG\Swagger annotations into one.
 */
class MergeIntoSwagger
{
    public function __invoke(Analysis $analysis)
    {
        // Set the first Swagger annotation as target.
        $swagger = $analysis->swagger;
        if (!$swagger) {
            foreach ($analysis->annotations as $annotation) {
                if ($annotation instanceof Swagger) {
                    $swagger = $annotation;
                    $annotation->_context->analysis = $analysis;
                    break;
                }
            }
            if (!$swagger) {
                $swagger = new Swagger(['_context' => new Context(['analysis' => $analysis])]);
                $analysis->annotations->attach($swagger);
            }
        }
        $analysis->swagger = $swagger;
        $analysis->swagger->_analysis = $analysis;

        // Merge all annotations into the target swagger
        $remaining = [];
        foreach ($analysis->annotations as $annotation) {
            if ($annotation === $swagger) {
                continue;
            }
            if ($annotation instanceof Swagger) {
                $paths = $annotation->paths;
                $definitions = $annotation->definitions;
                unset($annotation->paths);
                unset($annotation->definitions);
                $swagger->mergeProperties($annotation);
                foreach ($paths as $path) {
                    $swagger->paths[] = $path;
                }
                foreach ($definitions as $definition) {
                    $swagger->definitions[] = $definition;
                }
            } elseif (property_exists($annotation, '_context') && $annotation->_context->is('nested') === false) { // A top level annotation.
                $remaining[] = $annotation;
            }
        }
        $swagger->merge($remaining, true);
    }
}

<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Processors;

use Swagger\Analysis;
use Swagger\Annotations\Definition;

/**
 * Use the definition context to extract useful information and inject that into the annotation.
 * Merges properties into
 */
class AugmentDefinitions
{
    public function __invoke(Analysis $analysis)
    {
        $definitions = $analysis->getAnnotationsOfType('\Swagger\Annotations\Definition');
        // Use the class names for @SWG\Definition()
        foreach ($definitions as $definition) {
            if ($definition->definition === null) {
                if ($definition->_context->is('class')) {
                    $definition->definition = $definition->_context->class;
                } elseif ($definition->_context->is('trait')) {
                    $definition->definition = $definition->_context->trait;
                }
                // if ($definition->type === null) {
                //     $definition->type = 'object';
                // }
            }
        }
        // Merge unmerged @SWG\Property annotations into the @SWG\Definition of the class
        $unmergedProperties = $analysis->unmerged()->getAnnotationsOfType('\Swagger\Annotations\Property');
        foreach ($unmergedProperties as $property) {
            $definitonContext = $property->_context->with('class') ?: $property->_context->with('trait');
            if ($definitonContext->annotations) {
                $definition = false;
                foreach ($definitonContext->annotations as $annotation) {
                    if ($annotation instanceof Definition) {
                        $definition = $annotation;
                    }
                }
                if ($definition) {
                    $definition->merge([$property], true);
                }
            }
        }
    }
}

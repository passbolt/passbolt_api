<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Processors;

use Swagger\Analysis;
use SplObjectStorage;

/**
 *
 */
class CleanUnmerged
{
    public function __invoke(Analysis $analysis)
    {
        $split = $analysis->split();
        $merged = $split->merged->annotations;
        $unmerged = $split->unmerged->annotations;

        foreach ($analysis->annotations as $annotation) {
            if (property_exists($annotation, '_unmerged')) {
                foreach ($annotation->_unmerged as $i => $item) {
                    if ($merged->contains($item)) {
                        unset($annotation->_unmerged[$i]); // Property was merged
                    }
                }
            }
        }
        $analysis->swagger->_unmerged = [];
        foreach ($unmerged as $annotation) {
            $analysis->swagger->_unmerged[] = $annotation;
        }
    }
}

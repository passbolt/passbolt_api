<?php

namespace Gaufrette\Adapter;

/**
 * Interface which add supports for metadata
 *
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
interface MetadataSupporter
{
    /**
     * @param string $key
     * @param array  $content
     */
    public function setMetadata($key, $content);

    /**
     * @param  string $key
     * @return array
     */
    public function getMetadata($key);
}

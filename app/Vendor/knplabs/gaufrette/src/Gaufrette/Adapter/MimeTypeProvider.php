<?php

namespace Gaufrette\Adapter;

/**
 * Interface which add mime type provider support to adapter
 *
 * @author Gildas Quemener <gildas.quemener@gmail.com>
 */
interface MimeTypeProvider
{
    /**
     * Returns the mime type of the specified key
     *
     * @param string $key
     *
     * @return string
     */
    public function mimeType($key);
}

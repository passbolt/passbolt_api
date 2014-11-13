<?php

namespace Gaufrette\Util;

/**
 * Checksum utils
 *
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Checksum
{
    /**
     * Returns the checksum of the given content
     *
     * @param string $content
     *
     * @return string
     */
    public static function fromContent($content)
    {
        return md5($content);
    }

    /**
     * Returns the checksum of the specified file
     *
     * @param string $filename
     *
     * @return string
     */
    public static function fromFile($filename)
    {
        return md5_file($filename);
    }
}

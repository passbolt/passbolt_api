<?php

namespace Gaufrette\Adapter;

/**
 * interface that adds support of native listKeys to adapter
 *
 * @author Andrew Tch <andrew.tchircoff@gmail.com>
 */
interface ListKeysAware
{
    /**
     * Lists keys beginning with pattern given
     * (no wildcard / regex matching)
     *
     * @param string $prefix
     * @return array
     */
    public function listKeys($prefix = '');
}
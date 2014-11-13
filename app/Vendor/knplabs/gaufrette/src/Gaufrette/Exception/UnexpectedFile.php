<?php

namespace Gaufrette\Exception;

use Gaufrette\Exception;

/**
 * Exception to be thrown when an unexpected file exists
 *
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class UnexpectedFile extends \RuntimeException implements Exception
{
    private $key;

    public function __construct($key, $code = 0, \Exception $previous = null)
    {
        $this->key = $key;

        parent::__construct(
            sprintf('The file "%s" was not supposed to exist.', $key),
            $code,
            $previous
        );
    }

    public function getKey()
    {
        return $this->key;
    }
}

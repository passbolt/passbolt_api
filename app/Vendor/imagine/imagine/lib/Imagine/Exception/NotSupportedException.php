<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Exception;

/**
 * Should be used when a driver does not support an operation.
 */
class NotSupportedException extends RuntimeException implements Exception
{
}

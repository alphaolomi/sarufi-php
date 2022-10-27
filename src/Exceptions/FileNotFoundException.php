<?php

namespace Alphaolomi\Sarufi\Exceptions;


/**
 * Exception class thrown when a file couldn't be found.
 *
 * @author Alpha Olomi
 
 */
class FileNotFoundException extends \RuntimeException
{
    public function __construct(string $message = null, int $code = 0, \Throwable $previous = null, string $path = null)
    {
        if (null === $message) {
            if (null === $path) {
                $message = 'File could not be found.';
            } else {
                $message = sprintf('File "%s" could not be found.', $path);
            }
        }

        parent::__construct($message, $code, $previous, $path);
    }
}

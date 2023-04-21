<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserException extends Exception
{
    public static function cantSendTransaction(): UserException
    {
        return new self('Shopkeepers cannot send transactions!', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

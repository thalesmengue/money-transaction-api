<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class WalletException extends Exception
{
    public static function insufficientBalance(): WalletException
    {
        return new self('Insufficient balance!', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

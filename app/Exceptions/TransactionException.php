<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TransactionException extends Exception
{
    public static function unavailabilityToSendEmail(): TransactionException
    {
        return new self('The system is unstable! Unavailable to send the confirmation e-mail at the moment', Response::HTTP_REQUEST_TIMEOUT);
    }

    public static function transactionUnauthorized(): TransactionException
    {
        return new self('The current transaction isn\'t authorized!', Response::HTTP_UNAUTHORIZED);
    }

    public static function cantSendTransactionToYourself(): TransactionException
    {
        return new self('You cannot send transaction to yourself!', Response::HTTP_BAD_REQUEST);
    }
}

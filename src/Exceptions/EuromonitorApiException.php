<?php

namespace Bernskiold\LaravelEuromonitor\Exceptions;

use Exception;

class EuromonitorApiException extends Exception
{
    public static function noAccessToken(Exception $e): self
    {
        return new static('The access token could not be retrieved from Euromonitor.', previous: $e);
    }
}

<?php

use Bernskiold\LaravelEuromonitor\Exceptions\EuromonitorApiException;
use Exception;

it('throws an exception with the correct message and previous exception when access token cannot be retrieved', function () {
    $previousException = new Exception('Previous error');
    $exception = EuromonitorApiException::noAccessToken($previousException);

    expect($exception)
        ->toBeInstanceOf(EuromonitorApiException::class)
        ->getMessage()->toBe('The access token could not be retrieved from Euromonitor.')
        ->getPrevious()->toBe($previousException);
});

<?php

namespace Tests\Concerns\Api;

use Bernskiold\LaravelEuromonitor\Tests\Doubles\FakeResource;
use Bernskiold\LaravelEuromonitor\Concerns\Api\SupportsShareType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;
use Mockery;

beforeEach(function () {
    $this->trait = new class extends FakeResource {
        use SupportsShareType;
    };
});

afterEach(function () {
    Mockery::close();
});

it('sets share type', function () {
    $this->trait->shareType(1);
    expect($this->trait->shareType)->toBe(1);

    $this->trait->shareType('value');
    expect($this->trait->shareType)->toBe('value');
});

<?php

use Bernskiold\LaravelEuromonitor\Enum\MeasureType;
use Bernskiold\LaravelEuromonitor\Enum\UnitType;
use Bernskiold\LaravelEuromonitor\Exceptions\InvalidEuromonitorQueryException;

it('throws an exception with the correct message when no unit is selected', function () {
    $exception = InvalidEuromonitorQueryException::noUnit();
    $expectedUnits = collect(UnitType::cases())->map(fn (UnitType $unit) => $unit->value)->implode(', ');

    expect($exception)
        ->toBeInstanceOf(InvalidEuromonitorQueryException::class)
        ->getMessage()->toBe('You must select one of the following units: '.$expectedUnits)
        ->errors->toBe([]);
});

it('throws an exception with the correct message when no measure type is selected', function () {
    $exception = InvalidEuromonitorQueryException::noMeasureType();
    $expectedMeasureTypes = collect(MeasureType::cases())->map(fn (MeasureType $measureType) => $measureType->value)->implode(', ');

    expect($exception)
        ->toBeInstanceOf(InvalidEuromonitorQueryException::class)
        ->getMessage()->toBe('You must select one of the following measure types: '.$expectedMeasureTypes)
        ->errors->toBe([]);
});

it('throws an exception with custom message and validation errors', function () {
    $errors = ['error1' => 'message1', 'error2' => 'message2'];
    $exception = new InvalidEuromonitorQueryException('Custom message', $errors);

    expect($exception)
        ->toBeInstanceOf(InvalidEuromonitorQueryException::class)
        ->getMessage()->toBe('Custom message')
        ->errors->toBe($errors);
});

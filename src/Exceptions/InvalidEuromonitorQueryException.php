<?php

namespace Bernskiold\LaravelEuromonitor\Exceptions;

use Bernskiold\LaravelEuromonitor\Enum\MeasureType;
use Bernskiold\LaravelEuromonitor\Enum\UnitType;
use Exception;

use function collect;

class InvalidEuromonitorQueryException extends Exception
{
    public array $errors = [];

    public function __construct(string $message = '', array $errors = [])
    {
        parent::__construct($message);

        $this->errors = $errors;
    }

    public static function noUnit(): self
    {
        $values = collect(UnitType::cases())->map(fn (UnitType $unit) => $unit->value)->implode(', ');

        return new self('You must select one of the following units: '.$values);
    }

    public static function noMeasureType(): self
    {
        $values = collect(MeasureType::cases())->map(fn (MeasureType $measureType) => $measureType->value)->implode(', ');

        return new self('You must select one of the following measure types: '.$values);
    }
}

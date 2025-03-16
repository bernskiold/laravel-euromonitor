<?php

use Bernskiold\LaravelEuromonitor\Support\Arguments;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

it('returns empty array when value is null', function () {
    expect(Arguments::parseSingularOrArrayToArray(null))->toBe([]);
});

it('returns array with model key when value is a model', function () {
    $model = Mockery::mock(Model::class);
    $model->shouldReceive('getKey')->once()->andReturn(1);

    expect(Arguments::parseSingularOrArrayToArray($model))->toBe([1]);
});

it('splits string by comma when value is a string', function () {
    expect(Arguments::parseSingularOrArrayToArray('1,2,3'))->toBe(['1', '2', '3']);
});

it('returns array with single integer when value is an integer', function () {
    expect(Arguments::parseSingularOrArrayToArray(42))->toBe([42]);
});

it('converts arrayable to array when value implements Arrayable', function () {
    $arrayable = Mockery::mock(Arrayable::class);
    $arrayable->shouldReceive('toArray')->once()->andReturn(['a', 'b', 'c']);

    expect(Arguments::parseSingularOrArrayToArray($arrayable))->toBe(['a', 'b', 'c']);
});

it('returns array as is when value is already an array', function () {
    $array = [1, 2, 3];
    expect(Arguments::parseSingularOrArrayToArray($array))->toBe($array);
});

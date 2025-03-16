<?php

namespace Tests\Unit\Data\ApiResources;

use Bernskiold\LaravelEuromonitor\Data\ApiResources\Category;

it('can create from response', function () {
    $response = (object) [
        'id' => 1,
        'name' => 'Test Category',
        'level' => 2,
        'industryCode' => 'TEST123',
        'industryName' => 'Test Industry',
        'industryOrder' => 1,
        'parentId' => 0,
        'parentName' => 'Parent Category',
    ];

    $category = Category::fromResponse($response);

    expect($category->id)->toBe(1);
    expect($category->name)->toBe('Test Category');
    expect($category->level)->toBe(2);
    expect($category->industryCode)->toBe('TEST123');
    expect($category->industryName)->toBe('Test Industry');
    expect($category->industryOrder)->toBe(1);
    expect($category->parentId)->toBe(0);
    expect($category->parentName)->toBe('Parent Category');
});

it('can create with null parent', function () {
    $response = (object) [
        'id' => 1,
        'name' => 'Test Category',
        'level' => 1,
        'industryCode' => 'TEST123',
        'industryName' => 'Test Industry',
        'industryOrder' => 1,
        'parentId' => null,
        'parentName' => null,
    ];

    $category = Category::fromResponse($response);

    expect($category->parentId)->toBeNull();
    expect($category->parentName)->toBeNull();
});

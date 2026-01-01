<?php

use Ten\Phpregex\Regex;

test('between method works', function (): void {
    $regex = Regex::build()->between('a', 'z', true);
    expect($regex->getPattern())->toBe('[a-z]');
    expect($regex->match('m'))->toBeTrue()
        ->and($regex->match('1'))->toBeFalse();
});

test('notBetween method works', function (): void {
    $regex = Regex::build()->notBetween('0', '9');
    expect($regex->getPattern())->toBe('[^0-9]');
    expect($regex->match('a'))->toBeTrue()
        ->and($regex->match('5'))->toBeFalse();
});

test('beginsWith method works', function (): void {
    $regex = Regex::build()->beginsWith('hello');
    expect($regex->getPattern())->toBe('^hello');
    expect($regex->match('hello world'))->toBeTrue()
        ->and($regex->match('say hello'))->toBeFalse();
});

test('endsWith method works', function (): void {
    $regex = Regex::build()->endsWith('world');
    expect($regex->getPattern())->toBe('.*world$');
    expect($regex->match('hello world'))->toBeTrue()
        ->and($regex->match('world is round'))->toBeFalse();
});

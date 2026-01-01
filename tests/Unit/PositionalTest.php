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
test('chains all positional methods', function (): void {
    $regex = Regex::build()
        ->beginsWith('A')
        ->between('B', 'D')
        ->between('m', 'q', caseSensitive: false)
        ->notBetween('E', 'G', caseSensitive: false)
        ->notBetween('r', 't')
        ->endsWith('Z');

    expect($regex->getPattern())->toBe('^A[B-D][m-qM-Q][^e-gE-G][^r-t].*Z$');
    expect($regex->match('ABmPHZ'))->toBeTrue()
        ->and($regex->match('ACNyfZ'))->toBeTrue()
        ->and($regex->match('ACpysZ'))->toBeFalse()
        ->and($regex->match('ACpySZ'))->toBeTrue()
        ->and($regex->match('AEHZ'))->toBeFalse()
        ->and($regex->match('ACnfDZ'))->toBeFalse()
        ->and($regex->match('XBZ'))->toBeFalse();
});

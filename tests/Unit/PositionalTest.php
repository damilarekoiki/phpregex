<?php

use DamilareKoiki\PhpRegex\Regex;

test('between method works', function (): void {
    $regex = Regex::build()->between(['a' => 'z']);
    expect($regex->getPattern())->toBe('[a-z]');
    expect($regex->matches('m'))->toBeTrue()
        ->and($regex->matches('1'))->toBeFalse();

    expect($regex->count('abc 123 def'))->toBe(6);
    expect($regex->replace('abc', 'X'))->toBe('XXX');
});

test('notBetween method works', function (): void {
    $regex = Regex::build()->notBetween(['0' => '9']);
    expect($regex->getPattern())->toBe('[^0-9]');
    expect($regex->matches('a'))->toBeTrue()
        ->and($regex->matches('5'))->toBeFalse();
});

test('beginsWith method works', function (): void {
    $regex = Regex::build()->beginsWith('hello');
    expect($regex->getPattern())->toBe('^hello');
    expect($regex->matches('hello world'))->toBeTrue()
        ->and($regex->matches('say hello'))->toBeFalse();

    expect($regex->replace('hello world', 'Hi'))->toBe('Hi world');
});

test('endsWith method works', function (): void {
    $regex = Regex::build()->endsWith('world');
    expect($regex->getPattern())->toBe('.*world$');
    expect($regex->matches('hello world'))->toBeTrue()
        ->and($regex->matches('world is round'))->toBeFalse();
});
test('chains all positional methods', function (): void {
    $regex = Regex::build()
        ->beginsWith('A')
        ->between(['B' => 'D'])
        ->between(['m' => 'q'], caseSensitive: false)
        ->notBetween(['E' => 'G'], caseSensitive: false)
        ->notBetween(['r' => 't'])
        ->endsWith('Z');

    expect($regex->getPattern())->toBe('^A[B-D][m-qM-Q][^e-gE-G][^r-t].*Z$');
    expect($regex->matches('ABmPHZ'))->toBeTrue()
        ->and($regex->matches('ACNyfZ'))->toBeTrue()
        ->and($regex->matches('ACpysZ'))->toBeFalse()
        ->and($regex->matches('ACpySZ'))->toBeTrue()
        ->and($regex->matches('AEHZ'))->toBeFalse()
        ->and($regex->matches('ACnfDZ'))->toBeFalse()
        ->and($regex->matches('XBZ'))->toBeFalse();
});

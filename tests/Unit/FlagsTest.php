<?php

use Ten\Phpregex\Regex;

test('global ignoreCase flag', function (): void {
    $regex = Regex::build()->addPattern('abc')->ignoreCase();
    expect($regex->get())->toBe('/abc/i');
    expect($regex->matches('ABC'))->toBeTrue();
    expect($regex->count('abc ABC def'))->toBe(2);
    expect($regex->replace('abc ABC', 'X'))->toBe('X X');
});

test('global multiline flag', function (): void {
    $regex = Regex::build()->addPattern('abc')->multiline();
    expect($regex->get())->toBe('/abc/m');
});

test('multiple global flags', function (): void {
    $regex = Regex::build()->addPattern('abc')->ignoreCase()->multiline();
    $pattern = $regex->get();
    expect($pattern)->toContain('/abc/')
        ->and($pattern)->toContain('i')
        ->and($pattern)->toContain('m');
});

test('local ignoreCase flag with string', function (): void {
    $regex = Regex::build()->ignoreCaseFor('abc')->addPattern('DEF');
    expect($regex->getPattern())->toBe('(?i:abc)DEF');
    expect($regex->matches('abcDEF'))->toBeTrue()
        ->and($regex->matches('ABCDEF'))->toBeTrue()
        ->and($regex->matches('abcdef'))->toBeFalse();

    expect($regex->count('abcDEF ABCDEF abcdef'))->toBe(2);
    expect($regex->replace('ABCDEF', 'matched'))->toBe('matched');
});

test('local ignoreCase flag with closure', function (): void {
    $regex = Regex::build()->ignoreCaseFor(fn(Regex $r): Regex => $r->addPattern('abc'))->addPattern('DEF');
    expect($regex->getPattern())->toBe('(?i:abc)DEF');
    expect($regex->matches('ABCDEF'))->toBeTrue();
});

test('complex combined flags', function (): void {
    $regex = Regex::build()
        ->ignoreCaseFor('head')
        ->addPattern('Body')
        ->multiline();
    
    expect($regex->get())->toBe('/(?i:head)Body/m');
    expect($regex->matches('HEADBody'))->toBeTrue()
        ->and($regex->matches('headbody'))->toBeFalse();
});

test('utf8 flag', function (): void {
    $regex = Regex::build()->addPattern('🚀')->utf8();
    expect($regex->get())->toBe('/🚀/u');
});

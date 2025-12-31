<?php

use Ten\Phpregex\Regex;

test('global ignoreCase flag', function () {
    $regex = Regex::build()->addPattern('abc')->ignoreCase();
    expect($regex->get())->toBe('/abc/i');
    expect($regex->match('ABC'))->toBeTrue();
});

test('global multiline flag', function () {
    $regex = Regex::build()->addPattern('abc')->multiline();
    expect($regex->get())->toBe('/abc/m');
});

test('multiple global flags', function () {
    $regex = Regex::build()->addPattern('abc')->ignoreCase()->multiline();
    $pattern = $regex->get();
    expect($pattern)->toContain('/abc/')
        ->and($pattern)->toContain('i')
        ->and($pattern)->toContain('m');
});

test('local ignoreCase flag with string', function () {
    $regex = Regex::build()->ignoreCaseFor('abc')->addPattern('DEF');
    expect($regex->getPattern())->toBe('(?i:abc)DEF');
    expect($regex->match('abcDEF'))->toBeTrue()
        ->and($regex->match('ABCDEF'))->toBeTrue()
        ->and($regex->match('abcdef'))->toBeFalse();
});

test('local ignoreCase flag with closure', function () {
    $regex = Regex::build()->ignoreCaseFor(fn(Regex $r) => $r->addPattern('abc'))->addPattern('DEF');
    expect($regex->getPattern())->toBe('(?i:abc)DEF');
    expect($regex->match('ABCDEF'))->toBeTrue();
});

test('complex combined flags', function () {
    $regex = Regex::build()
        ->ignoreCaseFor('head')
        ->addPattern('Body')
        ->multiline();
    
    expect($regex->get())->toBe('/(?i:head)Body/m');
    expect($regex->match('HEADBody'))->toBeTrue()
        ->and($regex->match('headbody'))->toBeFalse();
});

test('utf8 flag', function () {
    $regex = Regex::build()->addPattern('🚀')->utf8();
    expect($regex->get())->toBe('/🚀/u');
});

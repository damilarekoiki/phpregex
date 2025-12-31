<?php

use Ten\Phpregex\Regex;

test('or method works', function (): void {
    $regex = Regex::build()->addPattern('apple')->or()->addPattern('banana');
    expect($regex->getPattern())->toBe('apple|banana');
    expect($regex->match('apple'))->toBeTrue()
        ->and($regex->match('banana'))->toBeTrue()
        ->and($regex->match('cherry'))->toBeFalse();
});

test('or property works', function (): void {
    $regex = Regex::build()->addPattern('apple')->or->addPattern('banana');
    expect($regex->getPattern())->toBe('apple|banana');
    expect($regex->match('apple'))->toBeTrue()
        ->and($regex->match('banana'))->toBeTrue();
});

test('and method works as a bridge', function (): void {
    $regex = Regex::build()->addPattern('apple')->and()->addPattern('banana');
    expect($regex->getPattern())->toBe('applebanana');
});

test('and property works as a bridge', function (): void {
    $regex = Regex::build()->addPattern('apple')->and->addPattern('banana');
    expect($regex->getPattern())->toBe('applebanana');
});

test('and method with argument works as a lookahead', function (): void {
    $regex = Regex::build()->and('cherry');
    expect($regex->getPattern())->toBe('(?=.*cherry)');
});

test('not method works as a negative lookahead', function (): void {
    $regex = Regex::build()->beginsWith('')->not('cherry');
    expect($regex->getPattern())->toBe('^(?!cherry)');
    expect($regex->match('applebanana'))->toBeTrue()
        ->and($regex->match('cherryapple'))->toBeFalse();
});

test('not method with closure works', function (): void {
    $regex = Regex::build()->not(fn(Regex $r): Regex => $r->addPattern('cherry'));
    expect($regex->getPattern())->toBe('(?!cherry)');
});

test('complex boolean combination', function (): void {
    $regex = Regex::build()
        ->addPattern('apple')
        ->and
        ->not('banana')
        ->or
        ->addPattern('cherry');
    
    expect($regex->getPattern())->toBe('apple(?!banana)|cherry');
    expect($regex->match('apple'))->toBeTrue()
        ->and($regex->match('applebanana'))->toBeFalse()
        ->and($regex->match('cherry'))->toBeTrue();
});

test('when method works with true condition', function (): void {
    $condition = true;
    $regex = Regex::build()
        ->addPattern('start')
        ->when($condition, function (Regex $r): void {
            $r->addPattern('-middle');
        })
        ->addPattern('-end');
    
    expect($regex->getPattern())->toBe('start-middle-end');
});

test('when method works with false condition', function (): void {
    $condition = false;
    $regex = Regex::build()
        ->addPattern('start')
        ->when($condition, function (Regex $r): void {
            $r->addPattern('-middle');
        })
        ->addPattern('-end');
    
    expect($regex->getPattern())->toBe('start-end');
});

test('flags are restored in resolve', function (): void {
    $regex = Regex::build()->addPattern('abc')->ignoreCase();
    expect($regex->get())->toBe('/abc/i');
});

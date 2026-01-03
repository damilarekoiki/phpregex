<?php

use Ten\Phpregex\Regex;

test('or method works', function (): void {
    $regex = Regex::build()->addPattern('apple')->or()->addPattern('banana');
    expect($regex->getPattern())->toBe('apple|banana');
    expect($regex->matches('apple'))->toBeTrue()
        ->and($regex->matches('banana'))->toBeTrue()
        ->and($regex->matches('cherry'))->toBeFalse();

    expect($regex->count('apple banana cherry'))->toBe(2);
    expect($regex->replace('apple banana', 'fruit'))->toBe('fruit fruit');
});

test('or property works', function (): void {
    $regex = Regex::build()->addPattern('apple')->or->addPattern('banana');
    expect($regex->getPattern())->toBe('apple|banana');
    expect($regex->matches('apple'))->toBeTrue()
        ->and($regex->matches('banana'))->toBeTrue();
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
    $regex = Regex::build()->beginsWith('2')->not('cherry');
    expect($regex->getPattern())->toBe('^2(?!cherry)');
    expect($regex->matches('2applebanana'))->toBeTrue()
        ->and($regex->matches('2cherryapple'))->toBeFalse();

    expect($regex->count('2apple 2cherry'))->toBe(1);
    expect($regex->replace('2apple', 'number'))->toBe('numberapple');
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
    expect($regex->matches('apple'))->toBeTrue()
        ->and($regex->matches('applebanana'))->toBeFalse()
        ->and($regex->matches('cherry'))->toBeTrue();

    expect($regex->count('apple applebanana cherry'))->toBe(2);
    expect($regex->replace('apple cherry', 'fruit'))->toBe('fruit fruit');
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
test('chains all boolean methods', function (): void {
    $regex = Regex::build()
        ->addPattern('A')
        ->and('B')
        ->not(fn(Regex $r): Regex => $r->contains('C'))
        ->or()
        ->addPattern('D')
        ->when(true, fn(Regex $r): Regex => $r->addPattern('E'));

    expect($regex->getPattern())->toBe('A(?=.*B)(?!(?=.*C))|DE');
    expect($regex->matches('AB'))->toBeTrue()
        ->and($regex->matches('ABC'))->toBeFalse()
        ->and($regex->matches('DE'))->toBeTrue();
});

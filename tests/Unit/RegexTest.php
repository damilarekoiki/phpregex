<?php

declare(strict_types=1);

use DamilareKoiki\PhpRegex\Regex;

test('overridePattern with string', function (): void {
    $regex = Regex::build()->addPattern('foo');
    expect($regex->getPattern())->toBe('foo');

    $regex->overridePattern('bar')
        ->addPattern('baz');
    expect($regex->getPattern())->toBe('bar');
});

test('overridePattern with Closure', function (): void {
    $regex = Regex::build()->addPattern('foo');
    expect($regex->getPattern())->toBe('foo');

    $regex->overridePattern(fn (Regex $r): Regex => $r->addPattern('bar')->addPattern('baz'))
        ->alphanumeric();
    expect($regex->getPattern())->toBe('barbaz');
});

test('isEmpty returns true for new instance', function (): void {
    $regex = new Regex();
    expect($regex->isEmpty())->toBeTrue();
});

test('isEmpty returns false after adding pattern', function (): void {
    $regex = Regex::build()->addPattern('foo');
    expect($regex->isEmpty())->toBeFalse();
});

test('replace with string', function (): void {
    $regex = Regex::build()->addPattern('foo');
    expect($regex->replace('foobar', 'baz'))->toBe('bazbar');
});

test('replace with Closure', function (): void {
    $regex = Regex::build()->addPattern('foo');
    $result = $regex->replace('foobar', fn(array $matches) => strtoupper($matches[0]));
    expect($result)->toBe('FOObar');
});

test('replace with invokable object', function (): void {
    $regex = Regex::build()->addPattern('foo');
    $invokable = new class {
        /**
         * @param array<int|string, string> $matches
         */
        public function __invoke(array $matches): string
        {
            return 'INVOKED';
        }
    };
    expect($regex->replace('foobar', $invokable(...)))->toBe('INVOKEDbar');
});

test('count matches', function (): void {
    $regex = Regex::build()->addPattern('a');
    expect($regex->count('banana'))->toBe(3);
});

test('group method works', function (): void {
    $regex = Regex::build()->group(fn (Regex $r): Regex => $r->addPattern('foo')->or()->addPattern('bar'));
    expect($regex->getPattern())->toBe('(foo|bar)');
    expect($regex->matches('foo'))->toBeTrue();
    expect($regex->matches('bar'))->toBeTrue();
});

test('magic __get access to or and and', function (): void {
    $regex = Regex::build()->addPattern('foo');
    
    $regex->or->addPattern('bar');
    expect($regex->getPattern())->toBe('foo|bar');
    
    $regex2 = Regex::build()->addPattern('foo')->and;
    expect($regex2->getPattern())->toBe('foo');
});

test('addPattern updates isConsuming', function (): void {
    $regex = Regex::build(true)->addPattern('(?=foo)', false);
    expect($regex->getPattern())->toBe('^(?=foo).*$');

    $regex2 = Regex::build(true)->addPattern('foo', true);
    expect($regex2->getPattern())->toBe('^foo$');
});

test('get method returns resolved regex', function (): void {
    $regex = Regex::build()->addPattern('foo')->ignoreCase();
    expect($regex->get())->toBe('/foo/i');
});

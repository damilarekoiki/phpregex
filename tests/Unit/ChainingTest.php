<?php

use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

test('chaining beginsWith and endsWith', function (): void {
    $regex = Regex::build()
        ->beginsWith('HTTP')
        ->addPattern('\/')
        ->addPattern('\d.\d')
        ->endsWith('')
        ;
    
    expect($regex->getPattern())->toBe('^HTTP\/\d.\d.*$');
    expect($regex->match('HTTP/1.1'))->toBeTrue()
        ->and($regex->match(' HTTP/1.1'))->toBeFalse()
        ->and($regex->match('HTTP/1.1 '))->toBeTrue();
});

test('chaining contains and or', function (): void {
    $regex = Regex::build()
        ->contains('apple')
        ->or()
        ->contains('banana');
    
    expect($regex->getPattern())->toBe('(?=.*apple)|(?=.*banana)');
    expect($regex->match('I have an apple'))->toBeTrue()
        ->and($regex->match('I have a banana'))->toBeTrue()
        ->and($regex->match('I have a cherry'))->toBeFalse();
});

test('complex sequence chaining', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then('foo')
              ->then(fn (Regex $r): Regex => $r->containsAnyOf(['bar', 'baz']))
              ->then('qux');
        });
    
    expect($regex->getPattern())->toBe('(?=.*(foo(.*(bar|baz))qux))');
    expect($regex->match('foobarqux'))->toBeTrue()
        ->and($regex->match('foobazqux'))->toBeTrue()
        ->and($regex->match('fooqux'))->toBeFalse();
});

test('chaining with flags', function (): void {
    $regex = Regex::build()
        ->beginsWith('abc')
        ->addPattern('xyz')
        ->ignoreCase()
        ->endsWith('');
    
    expect($regex->get())->toBe('/^abcxyz.*$/i');
    expect($regex->match('ABCXYZ'))->toBeTrue();
});

test('chaining when with complex logic', function (): void {
    $isEnabled = true;
    $regex = Regex::build()
        ->addPattern('prefix')
        ->when($isEnabled, function (Regex $r): void {
            $r->addPattern('-middle')->ignoreCase();
        })
        ->addPattern('-suffix');
    
    expect($regex->get())->toBe('/prefix-middle-suffix/i');
});

test('chaining beginsWith, contains, and endsWith for validation', function (): void {
    $regex = Regex::build()
        ->beginsWith('INV-')
        ->containsDigit()
        ->containsAtleastOne('')
        ->endsWith('.pdf');
    
    expect($regex->getPattern())->toBe('^INV\-(?=.*\d)(?=.*+).*\.pdf$');
    expect($regex->match('INV-123.pdf'))->toBeTrue()
        ->and($regex->match('INV-abc.pdf'))->toBeFalse()
        ->and($regex->match('INV-123.jpg'))->toBeFalse()
        ->and($regex->match('A-INV-123.pdf'))->toBeFalse();
});

test('chaining between and containsAtleastOne', function (): void {
    $regex = Regex::build()
        ->between('A', 'Z', caseSensitive: true)
        ->containsAtleastOne('0');
    
    expect($regex->getPattern())->toBe('[A-Z](?=.*0+)');
    expect($regex->match('A0'))->toBeTrue()
        ->and($regex->match('Z000'))->toBeTrue()
        ->and($regex->match('Z0'))->toBeTrue()
        ->and($regex->match('z000'))->toBeFalse()
        ->and($regex->match('A'))->toBeFalse()
        ->and($regex->match('0'))->toBeFalse();
});

test('grouping with quantifiers and or', function (): void {
    $regex = Regex::build()
        ->group(fn (Regex $r): Regex => $r->addPattern('abc'))
        ->containsAtleastOne('n')
        ->or()
        ->group(fn (Regex $r): Regex => $r->addPattern('xyz'))
        ->containsZeroOrMore('m');
    
    expect($regex->getPattern())->toBe('(abc)(?=.*n+)|(xyz)(?=.*m*)');
    expect($regex->match('abcabcn'))->toBeTrue()
        ->and($regex->match('xyzxyzm'))->toBeTrue()
        ->and($regex->match('abc'))->toBeFalse()
        ->and($regex->match('xyz'))->toBeTrue()
        ->and($regex->match('abcn'))->toBeTrue()
        ->and($regex->match('xyzm'))->toBeTrue();
});

test('complex sequence with not lookahead trailing', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then('user_')
              ->then('123')
              ->then(fn (Regex $r): Regex => $r->not('_admin'));
        });
    
    expect($regex->match('user_123'))->toBeTrue()
        ->and($regex->match('user_123_admin'))->toBeFalse();
});

test('chaining with multiple lookaheads and consuming patterns', function (): void {
    $regex = Regex::build()
        ->contains('apple')
        ->contains('banana')
        ->addPattern('.*')
        ->addPattern('cherrydog');
    
    expect($regex->getPattern())->toBe('(?=.*apple)(?=.*banana).*cherrydog');
    expect($regex->match('I have an apple and a banana cherrydog'))->toBeTrue()
        ->and($regex->match('apple banana cherry'))->toBeFalse();
});

test('nested grouping and complex quantifiers', function (): void {
    $regex = Regex::build()
        ->group(function (Regex $r): void {
            $r->addPattern('a')
              ->group(fn (Regex $r2): Regex => $r2->addPattern('b')->or()->addPattern('c'))
              ->addPattern('d');
        })
        ->containsAtleastSequencesOf('', 2);
    
    expect($regex->getPattern())->toBe('(a(b|c)d){2,}');
    expect($regex->match('abdacd'))->toBeTrue()
        ->and($regex->match('abdacdabdbcd'))->toBeTrue()
        ->and($regex->match('abd'))->toBeFalse();
});

test('wholeString with sequence and booleans', function (): void {
    $regex = Regex::build(true)
        ->sequence(function (Sequence $s): void {
            $s->then('foo')->then('bar');
        }, true)
        ->or()
        ->addPattern('baz');
    
    expect($regex->getPattern())->toBe('^(^(foo.*bar))|baz$');
    expect($regex->match('foobar'))->toBeTrue()
        ->and($regex->match('baz'))->toBeTrue()
        ->and($regex->match('foobarbaz'))->toBeTrue()
        ->and($regex->match('bar'))->toBeFalse()
        ->and($regex->match('foo'))->toBeFalse()
        ->and($regex->match('barfoo'))->toBeFalse();
});

test('arrow function works with when method', function (): void {
    $regex = Regex::build()
        ->addPattern('prefix')
        ->when(true, fn(Regex $r): Regex => $r->addPattern('-middle'))
        ->addPattern('-suffix');
    
    expect($regex->getPattern())->toBe('prefix-middle-suffix');
});

test('arrow function works with sequence method', function (): void {
    $regex = Regex::build()
        ->sequence(fn(Sequence $s): Sequence => $s->then('a')->then('b'));
    
    expect($regex->getPattern())->toBe('(?=.*(ab))');
});

test('arrow function works with inclusive methods', function (): void {
    $regex = Regex::build()
        ->addPattern('apple')
        ->or()
        ->addPattern('banana');
    
    expect($regex->getPattern())->toBe('apple|banana');
});

test('arrow function works with not method', function (): void {
    $regex = Regex::build()
        ->not(fn(Regex $r): Regex => $r->addPattern('secret'));
    
    expect($regex->getPattern())->toBe('(?!secret)');
});

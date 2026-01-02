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

    expect($regex->replace('HTTP/1.1 ', 'X'))->toBe('X');
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
    expect($regex->replace('ABCXYZ foo', 'matched'))->toBe('matched');
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
        ->between(['A' => 'Z'])
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
test('massive chaining: positional and booleans coverage', function (): void {
    $regex = Regex::build()
        ->beginsWith('A')
        ->between(['B' => 'D'])
        ->notBetween(['X' => 'Z'])
        ->and('test')
        ->not('fail')
        ->when(true, fn (Regex $r): Regex => $r->contains('ok'))
        ->or->contains('alt')
        ->endsWith('Z');

    expect($regex->match('ACMtestokZ'))->toBeTrue()
        ->and($regex->match('AYMtestokZ'))->toBeFalse()
        ->and($regex->match('ACMfailokZ'))->toBeFalse();
});

test('massive chaining: contains methods coverage part 1', function (): void {
    $regex = Regex::build()
        ->contains('hello')
        ->doesntContain('bye')
        ->containsAnyOf(['a', 'e', 'i'])
        ->containsDigit()
        ->containsNonDigit()
        ->containsBetween(['a' => 'z']);

    expect($regex->match('hello123world'))->toBeTrue()
        ->and($regex->match('hello123bye'))->toBeFalse()
        ->and($regex->match('hxllo123world'))->toBeFalse();
});

test('massive chaining: contains methods coverage part 2', function (): void {
    $regex = Regex::build()
        ->containsAlphaNumeric()
        ->containsWordsThatBeginWith('pre')
        ->containsWordsThatEndWith('fix');

    expect($regex->match('prefix is good'))->toBeTrue()
        ->and($regex->match('the prefix works'))->toBeTrue()
        ->and($regex->match('nothing here'))->toBeFalse();
});

test('massive chaining: contains methods coverage part 3', function (): void {
    $regex = Regex::build()
        ->containsLetter()
        ->containsLowercaseLetter()
        ->containsUppercaseLetter()
        ->containsWhitespace()
        ->containsNonWhitespace()
        ->containsWordCharacter()
        ->containsAnything();

    expect($regex->match('Hello World'))->toBeTrue()
        ->and($regex->match('hello world'))->toBeFalse()
        ->and($regex->match('HELLO WORLD'))->toBeFalse();
});

test('massive chaining: quantifiers coverage', function (): void {
    $regex = Regex::build()
        ->containsAtleastOne('A')
        ->containsZeroOrMore('B')
        ->containsZeroOrOne('C');

    expect($regex->match('AAABBBCCC'))->toBeTrue()
        ->and($regex->match('AAA'))->toBeTrue()
        ->and($regex->match('BBB'))->toBeFalse();
});

test('massive chaining: sequential coverage', function (): void {
    $regex = Regex::build()
        ->containsExactSequencesOf('A', 3)
        ->containsSequencesOf('B', 2, 4)
        ->containsAtleastSequencesOf('C', 2);

    expect($regex->match('AAABBBBCC'))->toBeTrue()
        ->and($regex->match('AABBCC'))->toBeFalse()
        ->and($regex->match('AAABCC'))->toBeFalse();
});

test('massive chaining: helpers coverage part 1', function (): void {
    $regex = Regex::build()
        ->email();

    expect($regex->match('test@example.com'))->toBeTrue()
        ->and($regex->match('invalid-email'))->toBeFalse();

    $regex2 = Regex::build()->url();
    expect($regex2->match('https://example.com/path?query=1'))->toBeTrue();

    $regex3 = Regex::build()->uuid();
    expect($regex3->match('550e8400-e29b-41d4-a716-446655440000'))->toBeTrue();

    $regex4 = Regex::build()->ipv4();
    expect($regex4->match('192.168.1.1'))->toBeTrue();
});

test('massive chaining: helpers coverage part 2', function (): void {
    $regex = Regex::build()
        ->alpha();
    expect($regex->match('HelloWorld'))->toBeTrue();

    $regex2 = Regex::build()->alphanumeric();
    expect($regex2->match('Test123'))->toBeTrue();

    $regex3 = Regex::build()->digits();
    expect($regex3->match('12345'))->toBeTrue();

    $regex4 = Regex::build()->hexColor();
    expect($regex4->match('#FF5733'))->toBeTrue();

    $regex5 = Regex::build()->slug();
    expect($regex5->match('my-awesome-slug'))->toBeTrue();
});

test('massive chaining: helpers coverage part 3', function (): void {
    $regex = Regex::build()->creditCard();
    expect($regex->match('4111-1111-1111-1111'))->toBeTrue();

    $regex2 = Regex::build()->ssn();
    expect($regex2->match('123-45-6789'))->toBeTrue();

    $regex3 = Regex::build()->zipCode();
    expect($regex3->match('12345'))->toBeTrue();

    $regex4 = Regex::build()->macAddress();
    expect($regex4->match('00:1A:2B:3C:4D:5E'))->toBeTrue();

    $regex5 = Regex::build()->date();
    expect($regex5->match('2024-01-15'))->toBeTrue();

    $regex6 = Regex::build()->time();
    expect($regex6->match('14:30:00'))->toBeTrue();

    $regex7 = Regex::build()->handle();
    expect($regex7->match('@username'))->toBeTrue();

    $regex8 = Regex::build()->hex();
    expect($regex8->match('DEADBEEF'))->toBeTrue();
});

test('massive chaining: flags coverage', function (): void {
    $regex = Regex::build()
        ->beginsWith('hello')
        ->ignoreCase()
        ->multiline();

    expect($regex->match('HELLO world'))->toBeTrue();

    $regex2 = Regex::build()
        ->ignoreCaseFor('test')
        ->utf8();

    expect($regex2->match('TEST'))->toBeTrue();
});

test('massive chaining: doesnt methods coverage', function (): void {
    $regex = Regex::build()
        ->containsOnlyAlphaNumeric();

    expect($regex->match('HelloWorld123'))->toBeTrue()
        ->and($regex->match('Hello World!'))->toBeFalse();

    $regex2 = Regex::build()
        ->doesntContainAnyOf(['x', 'y', 'z']);

    expect($regex2->match('hello world'))->toBeTrue()
        ->and($regex2->match('xyz'))->toBeFalse();

    $regex3 = Regex::build()
        ->doesntContainBetween(['0' => '9']);

    expect($regex3->match('hello'))->toBeTrue()
        ->and($regex3->match('hello123'))->toBeFalse();
});

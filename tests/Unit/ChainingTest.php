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
    expect($regex->matches('HTTP/1.1'))->toBeTrue()
        ->and($regex->matches(' HTTP/1.1'))->toBeFalse()
        ->and($regex->matches('HTTP/1.1 '))->toBeTrue();

    expect($regex->replace('HTTP/1.1 ', 'X'))->toBe('X');
});

test('chaining contains and or', function (): void {
    $regex = Regex::build()
        ->contains('apple')
        ->or()
        ->contains('banana');
    
    expect($regex->getPattern())->toBe('(?=.*apple)|(?=.*banana)');
    expect($regex->matches('I have an apple'))->toBeTrue()
        ->and($regex->matches('I have a banana'))->toBeTrue()
        ->and($regex->matches('I have a cherry'))->toBeFalse();
});

test('complex sequence chaining', function (): void {
    $regex = Regex::build()
        ->containsSequence(function (Sequence $s): void {
            $s->then('foo')
              ->then(fn (Regex $r): Regex => $r->containsAnyOf(['bar', 'baz']))
              ->then('qux');
        });
    
    expect($regex->getPattern())->toBe('(?=.*(foo(.*(bar|baz))qux))');
    expect($regex->matches('foobarqux'))->toBeTrue()
        ->and($regex->matches('foobazqux'))->toBeTrue()
        ->and($regex->matches('fooqux'))->toBeFalse();
});

test('chaining with flags', function (): void {
    $regex = Regex::build()
        ->beginsWith('abc')
        ->addPattern('xyz')
        ->ignoreCase()
        ->endsWith('');
    
    expect($regex->get())->toBe('/^abcxyz.*$/i');
    expect($regex->matches('ABCXYZ'))->toBeTrue();
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
    expect($regex->matches('INV-123.pdf'))->toBeTrue()
        ->and($regex->matches('INV-abc.pdf'))->toBeFalse()
        ->and($regex->matches('INV-123.jpg'))->toBeFalse()
        ->and($regex->matches('A-INV-123.pdf'))->toBeFalse();
});

test('chaining between and containsAtleastOne', function (): void {
    $regex = Regex::build()
        ->between(['A' => 'Z'])
        ->containsAtleastOne('0');
    
    expect($regex->getPattern())->toBe('[A-Z](?=.*0+)');
    expect($regex->matches('A0'))->toBeTrue()
        ->and($regex->matches('Z000'))->toBeTrue()
        ->and($regex->matches('Z0'))->toBeTrue()
        ->and($regex->matches('z000'))->toBeFalse()
        ->and($regex->matches('A'))->toBeFalse()
        ->and($regex->matches('0'))->toBeFalse();
});

test('grouping with quantifiers and or', function (): void {
    $regex = Regex::build()
        ->group(fn (Regex $r): Regex => $r->addPattern('abc'))
        ->containsAtleastOne('n')
        ->or()
        ->group(fn (Regex $r): Regex => $r->addPattern('xyz'))
        ->containsZeroOrMore('m');
    
    expect($regex->getPattern())->toBe('(abc)(?=.*n+)|(xyz)(?=.*m*)');
    expect($regex->matches('abcabcn'))->toBeTrue()
        ->and($regex->matches('xyzxyzm'))->toBeTrue()
        ->and($regex->matches('abc'))->toBeFalse()
        ->and($regex->matches('xyz'))->toBeTrue()
        ->and($regex->matches('abcn'))->toBeTrue()
        ->and($regex->matches('xyzm'))->toBeTrue();
});

test('complex sequence with not lookahead trailing', function (): void {
    $regex = Regex::build()
        ->containsSequence(function (Sequence $s): void {
            $s->then('user_')
              ->then('123')
              ->then(fn (Regex $r): Regex => $r->not('_admin'));
        });
    
    expect($regex->matches('user_123'))->toBeTrue()
        ->and($regex->matches('user_123_admin'))->toBeFalse();
});

test('chaining with multiple lookaheads and consuming patterns', function (): void {
    $regex = Regex::build()
        ->contains('apple')
        ->contains('banana')
        ->addPattern('.*')
        ->addPattern('cherrydog');
    
    expect($regex->getPattern())->toBe('(?=.*apple)(?=.*banana).*cherrydog');
    expect($regex->matches('I have an apple and a banana cherrydog'))->toBeTrue()
        ->and($regex->matches('apple banana cherry'))->toBeFalse();
});

test('nested grouping and complex quantifiers', function (): void {
    $regex = Regex::build()
        ->group(function (Regex $r): void {
            $r->addPattern('a')
              ->group(fn (Regex $r2): Regex => $r2->addPattern('b')->or()->addPattern('c'))
              ->addPattern('d');
        })
        ->containsAtleastSequencesOf('a', 2);
    
    expect($regex->getPattern())->toBe('(a(b|c)d)(?=.*a{2,})');
    expect($regex->matches('abdaacd'))->toBeTrue()
        ->and($regex->matches('abdaacdabdbcd'))->toBeTrue()
        ->and($regex->matches('abd'))->toBeFalse();
});

test('fullStringMatch with sequence and booleans', function (): void {
    $regex = Regex::build(true)
        ->containsSequence(function (Sequence $s): void {
            $s->then('foo')->then('bar');
        }, true)
        ->or()
        ->addPattern('baz');
    
    expect($regex->getPattern())->toBe('^(^(foo.*bar))|baz$');
    expect($regex->matches('foobar'))->toBeTrue()
        ->and($regex->matches('baz'))->toBeTrue()
        ->and($regex->matches('foobarbaz'))->toBeTrue()
        ->and($regex->matches('bar'))->toBeFalse()
        ->and($regex->matches('foo'))->toBeFalse()
        ->and($regex->matches('barfoo'))->toBeFalse();
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
        ->containsSequence(fn(Sequence $s): Sequence => $s->then('a')->then('b'));
    
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

    expect($regex->matches('ACMtestokZ'))->toBeTrue()
        ->and($regex->matches('AYMtestokZ'))->toBeFalse()
        ->and($regex->matches('ACMfailokZ'))->toBeFalse();
});

test('massive chaining: contains methods coverage part 1', function (): void {
    $regex = Regex::build()
        ->contains('hello')
        ->doesntContain('bye')
        ->containsAnyOf(['a', 'e', 'i'])
        ->containsDigit()
        ->containsNonDigit()
        ->containsBetween(['a' => 'z']);

    expect($regex->matches('hello123world'))->toBeTrue()
        ->and($regex->matches('hello123bye'))->toBeFalse()
        ->and($regex->matches('hxllo123world'))->toBeFalse();
});

test('massive chaining: contains methods coverage part 2', function (): void {
    $regex = Regex::build()
        ->containsAlphaNumeric()
        ->containsWordsThatBeginWith('pre')
        ->containsWordsThatEndWith('fix');

    expect($regex->matches('prefix is good'))->toBeTrue()
        ->and($regex->matches('the prefix works'))->toBeTrue()
        ->and($regex->matches('nothing here'))->toBeFalse();
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

    expect($regex->matches('Hello World'))->toBeTrue()
        ->and($regex->matches('hello world'))->toBeFalse()
        ->and($regex->matches('HELLO WORLD'))->toBeFalse();
});

test('massive chaining: quantifiers coverage', function (): void {
    $regex = Regex::build()
        ->containsAtleastOne('A')
        ->containsZeroOrMore('B')
        ->containsZeroOrOne('C');

    expect($regex->matches('AAABBBCCC'))->toBeTrue()
        ->and($regex->matches('AAA'))->toBeTrue()
        ->and($regex->matches('BBB'))->toBeFalse();
});

test('massive chaining: sequential coverage', function (): void {
    $regex = Regex::build()
        ->containsExactSequencesOf('A', 3)
        ->containsSequencesOf('B', 2, 4)
        ->containsAtleastSequencesOf('C', 2);

    expect($regex->matches('AAABBBBCC'))->toBeTrue()
        ->and($regex->matches('AABBCC'))->toBeFalse()
        ->and($regex->matches('AAABCC'))->toBeFalse();
});

test('massive chaining: helpers coverage part 1', function (): void {
    $regex = Regex::build()
        ->email();

    expect($regex->matches('test@example.com'))->toBeTrue()
        ->and($regex->matches('invalid-email'))->toBeFalse();

    $regex2 = Regex::build()->url();
    expect($regex2->matches('https://example.com/path?query=1'))->toBeTrue();

    $regex3 = Regex::build()->uuid();
    expect($regex3->matches('550e8400-e29b-41d4-a716-446655440000'))->toBeTrue();

    $regex4 = Regex::build()->ipv4();
    expect($regex4->matches('192.168.1.1'))->toBeTrue();
});

test('massive chaining: helpers coverage part 2', function (): void {
    $regex = Regex::build()
        ->alpha();
    expect($regex->matches('HelloWorld'))->toBeTrue();

    $regex2 = Regex::build()->alphanumeric();
    expect($regex2->matches('Test123'))->toBeTrue();

    $regex3 = Regex::build()->digits();
    expect($regex3->matches('12345'))->toBeTrue();

    $regex4 = Regex::build()->hexColor();
    expect($regex4->matches('#FF5733'))->toBeTrue();

    $regex5 = Regex::build()->slug();
    expect($regex5->matches('my-awesome-slug'))->toBeTrue();
});

test('massive chaining: helpers coverage part 3', function (): void {
    $regex = Regex::build()->creditCard();
    expect($regex->matches('4111-1111-1111-1111'))->toBeTrue();

    $regex2 = Regex::build()->ssn();
    expect($regex2->matches('123-45-6789'))->toBeTrue();

    $regex3 = Regex::build()->zipCode();
    expect($regex3->matches('12345'))->toBeTrue();

    $regex4 = Regex::build()->macAddress();
    expect($regex4->matches('00:1A:2B:3C:4D:5E'))->toBeTrue();

    $regex5 = Regex::build()->date();
    expect($regex5->matches('2024-01-15'))->toBeTrue();

    $regex6 = Regex::build()->time();
    expect($regex6->matches('14:30:00'))->toBeTrue();

    $regex7 = Regex::build()->socialHandle();
    expect($regex7->matches('@username'))->toBeTrue();

    $regex8 = Regex::build()->hex();
    expect($regex8->matches('DEADBEEF'))->toBeTrue();
});

test('massive chaining: flags coverage', function (): void {
    $regex = Regex::build()
        ->beginsWith('hello')
        ->ignoreCase()
        ->multiline();

    expect($regex->matches('HELLO world'))->toBeTrue();

    $regex2 = Regex::build()
        ->ignoreCaseFor('test')
        ->utf8();

    expect($regex2->matches('TEST'))->toBeTrue();
});

test('massive chaining: doesnt methods coverage', function (): void {
    $regex = Regex::build()
        ->containsOnlyAlphaNumeric();

    expect($regex->matches('HelloWorld123'))->toBeTrue()
        ->and($regex->matches('Hello World!'))->toBeFalse();

    $regex2 = Regex::build()
        ->doesntContainAnyOf(['x', 'y', 'z']);

    expect($regex2->matches('hello world'))->toBeTrue()
        ->and($regex2->matches('xyz'))->toBeFalse();

    $regex3 = Regex::build()
        ->doesntContainBetween(['0' => '9']);

    expect($regex3->matches('hello'))->toBeTrue()
        ->and($regex3->matches('hello123'))->toBeFalse();
});
test('complex chain: contains, exactly, quantifiers and booleans', function (): void {
    $regex = Regex::build()
        ->containsDigit()
        ->digit()
        ->atLeastOne('a')
        ->or()
        ->alpha();
    
    expect($regex->getPattern())->toBe('(?=.*\d)\da+|[a-zA-Z]+');
    expect($regex->matches('12aaa'))->toBeTrue()
        ->and($regex->matches('abc'))->toBeTrue()
        ->and($regex->matches('1'))->toBeFalse();
    
    expect($regex->count('12aaa big apple 34bb'))->toBe(4);
});

test('complex chain: sequential, helpers and flags', function (): void {
    $regex = Regex::build()
        ->exactSequencesOf(fn (Regex $r): Regex => $r->digit(), 2)
        ->slug()
        ->ignoreCase();
    
    expect($regex->getPattern())->toBe('(?:\d){2}[a-z0-9]+(?:-[a-z0-9]+)*');
    expect($regex->matches('12MY-SLUG'))->toBeTrue()
        ->and($regex->matches('1MY-SLUG'))->toBeFalse();
});

test('complex chain: multiple contains and replace', function (): void {
    $regex = Regex::build()
        ->contains('apple')
        ->containsDigit()
        ->containsOnlyAlphaNumeric();
    
    expect($regex->matches('apple123'))->toBeTrue()
        ->and($regex->matches('apple 123'))->toBeFalse();
    
    expect($regex->count('apple123'))->toBe(1);
    expect($regex->replace('apple123', 'X'))->toBe('X');
});

<?php

use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

test('then method is order-dependent', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then('banana');
        });

    expect($regex->match('applebanana'))->toBeTrue();
    expect($regex->match('bananaapple'))->toBeFalse();
});

test('then method is sequential', function (): void {
    $regex1 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then('banana');
        });
    $regex2 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then('2');
        });

    expect($regex1->match('apple2banana'))->toBeFalse();
    expect($regex2->match('apple2banana'))->toBeTrue();
});

test('then method scans from the beginning when startFromBeginning is true', function (): void {
    $regex1 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then('banana');
        }, startFromBeginning: true);

    $regex2 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then(fn (Regex $regex): Regex => $regex->contains('apple'))
                ->then('banana');
        }, startFromBeginning: true);

    $regex3 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then(fn (Regex $regex): Regex => $regex->contains('banana'));
        }, startFromBeginning: true);

    $regex4 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then(fn (Regex $regex): Regex => $regex->contains('apple'))
                ->then(fn (Regex $regex): Regex => $regex->contains('banana'));
        }, startFromBeginning: true);

    expect($regex1->match('aapplebanana'))->toBeFalse();
    expect($regex1->match('applebanana'))->toBeTrue();

    expect($regex2->match('aapplebanana'))->toBeTrue();
    expect($regex2->match('applebanana'))->toBeTrue();

    expect($regex3->match('aapplebanana'))->toBeFalse();
    expect($regex3->match('applebanana'))->toBeTrue();

    expect($regex4->match('aapplebanana'))->toBeTrue();
    expect($regex4->match('applebanana'))->toBeTrue();
});

test('then method scans from anywhere when startFromBeginning is false', function (): void {
    $regex1 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then('banana');
        }, startFromBeginning: false);

    $regex2 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then(fn (Regex $regex): Regex => $regex->contains('apple'))
                ->then('banana');
        }, startFromBeginning: false);

    $regex3 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then(fn (Regex $regex): Regex => $regex->contains('banana'));
        }, startFromBeginning: false);

    $regex4 = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then(fn (Regex $regex): Regex => $regex->contains('apple'))
                ->then(fn (Regex $regex): Regex => $regex->contains('banana'));
        }, startFromBeginning: false);

    expect($regex1->match('aapplebanana'))->toBeTrue();
    expect($regex1->match('applebanana'))->toBeTrue();

    expect($regex2->match('aapplebanana'))->toBeTrue();
    expect($regex2->match('applebanana'))->toBeTrue();

    expect($regex3->match('aapplebanana'))->toBeTrue();
    expect($regex3->match('applebanana'))->toBeTrue();

    expect($regex4->match('aapplebanana'))->toBeTrue();
    expect($regex4->match('applebanana'))->toBeTrue();
});

test('not works in sequence', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then(fn (Regex $regex): Regex => $regex->not('banana'));
        });

    expect($regex->match('apple fruit'))->toBeTrue();
    expect($regex->match('applebanana'))->toBeFalse();
});

test('containsExactSequencesOf method works', function (): void {
    $regex = Regex::build()->containsExactSequencesOf('a', 3);
    expect($regex->getPattern())->toBe('a{3}');
    expect($regex->match('aaa'))->toBeTrue()
        ->and($regex->match('aa'))->toBeFalse()
        ->and($regex->match('aaaa'))->toBeTrue();
});

test('containsSequencesOf method works', function (): void {
    $regex = Regex::build()->containsSequencesOf('a', 2, 4);
    expect($regex->getPattern())->toBe('a{2,4}');
    expect($regex->match('aa'))->toBeTrue()
        ->and($regex->match('aaa'))->toBeTrue()
        ->and($regex->match('aaaa'))->toBeTrue()
        ->and($regex->match('a'))->toBeFalse();
});

test('containsAtleastSequencesOf method works', function (): void {
    $regex = Regex::build()->containsAtleastSequencesOf('a', 2);
    expect($regex->getPattern())->toBe('a{2,}');
    expect($regex->match('aa'))->toBeTrue()
        ->and($regex->match('aaaaa'))->toBeTrue()
        ->and($regex->match('a'))->toBeFalse();
});

test('chains all sequential methods', function (): void {
    $regex = Regex::build()
        ->containsExactSequencesOf('a', 2)
        ->containsSequencesOf('b', 1, 2)
        ->containsAtleastSequencesOf('c', 1);

    expect($regex->getPattern())->toBe('a{2}b{1,2}c{1,}');
    expect($regex->match('aabbc'))->toBeTrue()
        ->and($regex->match('abc'))->toBeFalse()
        ->and($regex->match('aabbcc'))->toBeTrue();
});

test('massive sequence: positional and contains coverage', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then(fn (Regex $r): Regex => $r->beginsWith('A')->containsDigit())
              ->then(fn (Regex $r): Regex => $r->between(['B' => 'D'])->notBetween(['X' => 'Z']))
              ->then(fn (Regex $r): Regex => $r->containsLetter())
              ->then(fn (Regex $r): Regex => $r->endsWith('Z'));
        }, startFromBeginning: true);

    expect($regex->match('A1BmtestZ'))->toBeTrue()
        ->and($regex->match('A1YmtestZ'))->toBeFalse()
        ->and($regex->match('1BmtestZ'))->toBeFalse();
});

test('massive sequence: booleans and quantifiers coverage', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then(fn (Regex $r): Regex => $r->containsAtleastOne('A'))
              ->then(fn (Regex $r): Regex => $r->and('B')->not('X'))
              ->then(fn (Regex $r): Regex => $r->containsZeroOrMore('C'))
              ->then(fn (Regex $r): Regex => $r->containsZeroOrOne('D'));
        });

    expect($regex->match('AAABBCCCD'))->toBeTrue()
        ->and($regex->match('AAAB'))->toBeTrue()
        ->and($regex->match('BBB'))->toBeFalse();
});

test('massive sequence: sequential methods coverage', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then(fn (Regex $r): Regex => $r->containsExactSequencesOf('A', 2))
              ->then(fn (Regex $r): Regex => $r->containsSequencesOf('B', 2, 4))
              ->then(fn (Regex $r): Regex => $r->containsAtleastSequencesOf('C', 3));
        }, startFromBeginning: true);

    expect($regex->match('AABBBBCCC'))->toBeTrue()
        ->and($regex->match('AABBCC'))->toBeFalse()
        ->and($regex->match('AAABBCCC'))->toBeTrue();
});

test('massive sequence: helpers coverage', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then(fn (Regex $r): Regex => $r->beginsWith('ID:'))
              ->then(fn (Regex $r): Regex => $r->digits())
              ->then(fn (Regex $r): Regex => $r->alphanumeric())
              ->then(fn (Regex $r): Regex => $r->email())
              ->then(fn (Regex $r): Regex => $r->endsWith('.txt'));
        }, startFromBeginning: true);

    expect($regex->match('ID:123ABCtest@example.com.txt'))->toBeTrue()
        ->and($regex->match('ID:123ABCtest@example.com.pdf'))->toBeFalse()
        ->and($regex->match('123ABCtest@example.com.txt'))->toBeFalse();
});

test('massive sequence: contains methods coverage', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then(fn (Regex $r): Regex => $r->contains('start'))
              ->then(fn (Regex $r): Regex => $r->containsAlphaNumeric())
              ->then(fn (Regex $r): Regex => $r->containsBetween(['a' => 'z']))
              ->then(fn (Regex $r): Regex => $r->containsAnyOf(['x', 'y', 'z']))
              ->then(fn (Regex $r): Regex => $r->contains('end'));
        });

    expect($regex->match('start123xyzend'))->toBeTrue()
        ->and($regex->match('start123end'))->toBeFalse()
        ->and($regex->match('begin123xyzend'))->toBeFalse();
});

test('massive sequence: flags coverage', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then(fn (Regex $r): Regex => $r->beginsWith('Hello'))
              ->then(fn (Regex $r): Regex => $r->containsLetter())
              ->then(fn (Regex $r): Regex => $r->endsWith('World'));
        }, startFromBeginning: true)
        ->ignoreCase()
        ->utf8();

    expect($regex->match('HelloTestWorld'))->toBeTrue()
        ->and($regex->match('HELLOTESTWORLD'))->toBeTrue()
        ->and($regex->match('hello test world'))->toBeTrue();
});

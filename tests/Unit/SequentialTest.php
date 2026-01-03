<?php

use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

test('then method is order-dependent', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then('banana');
        });

    expect($regex->matches('applebanana'))->toBeTrue();
    expect($regex->matches('bananaapple'))->toBeFalse();

    $consuming = Regex::build()->then('apple')->then('banana');
    expect($consuming->count('applebananaapplebanana'))->toBe(2);
    expect($consuming->replace('applebananaapplebanana', 'fruit'))->toBe('fruitfruit');
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

    expect($regex1->matches('apple2banana'))->toBeFalse();
    expect($regex2->matches('apple2banana'))->toBeTrue();

    $consuming = Regex::build()->then('apple')
        ->then(fn(Regex $regex): Regex => $regex->digit());
    expect($consuming->count('apple2apple2'))->toBe(2);
    expect($consuming->replace('apple2apple2', 'fruit'))->toBe('fruitfruit');
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

    expect($regex1->matches('aapplebanana'))->toBeFalse();
    expect($regex1->matches('applebanana'))->toBeTrue();

    expect($regex2->matches('aapplebanana'))->toBeTrue();
    expect($regex2->matches('applebanana'))->toBeTrue();

    expect($regex3->matches('aapplebanana'))->toBeFalse();
    expect($regex3->matches('applebanana'))->toBeTrue();

    expect($regex4->matches('aapplebanana'))->toBeTrue();
    expect($regex4->matches('applebanana'))->toBeTrue();
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

    expect($regex1->matches('aapplebanana'))->toBeTrue();
    expect($regex1->matches('applebanana'))->toBeTrue();

    expect($regex2->matches('aapplebanana'))->toBeTrue();
    expect($regex2->matches('applebanana'))->toBeTrue();

    expect($regex3->matches('aapplebanana'))->toBeTrue();
    expect($regex3->matches('applebanana'))->toBeTrue();

    expect($regex4->matches('aapplebanana'))->toBeTrue();
    expect($regex4->matches('applebanana'))->toBeTrue();
});

test('not works in sequence', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then(fn (Regex $regex): Regex => $regex->not('banana'));
        });

    expect($regex->matches('apple fruit'))->toBeTrue();
    expect($regex->matches('applebanana'))->toBeFalse();

    $consuming = Regex::build()->then('apple')->not('banana');
    expect($consuming->count('apple fruit apple pie'))->toBe(2);
    expect($consuming->replace('apple fruit apple pie', 'orange'))->toBe('orange fruit orange pie');
});

test('exactSequencesOf method works', function (): void {
    $regex = Regex::build()->exactSequencesOf('a', 3);
    expect($regex->getPattern())->toBe('a{3}');
    expect($regex->matches('aaa'))->toBeTrue()
        ->and($regex->matches('aa'))->toBeFalse()
        ->and($regex->matches('aaaa'))->toBeTrue();

    expect($regex->count('aaaaaa'))->toBe(2);
    expect($regex->replace('aaaaaa', 'X'))->toBe('XX');
});

test('containsExactSequencesOf method works', function (): void {
    $regex = Regex::build()->containsExactSequencesOf('a', 3);
    expect($regex->getPattern())->toBe('(?=.*a{3})');
    expect($regex->matches('aaa'))->toBeTrue()
        ->and($regex->matches('appleaaa'))->toBeTrue()
        ->and($regex->matches('aa'))->toBeFalse();
});

test('sequencesOf method works', function (): void {
    $regex = Regex::build()->sequencesOf('a', 2, 4);
    expect($regex->getPattern())->toBe('a{2,4}');
    expect($regex->matches('aa'))->toBeTrue()
        ->and($regex->matches('aaa'))->toBeTrue()
        ->and($regex->matches('aaaa'))->toBeTrue()
        ->and($regex->matches('a'))->toBeFalse();

    expect($regex->count('aaaaaaaa'))->toBe(2); // 4 + 4
    expect($regex->replace('aaaaaaaa', 'X'))->toBe('XX');
});

test('containsSequencesOf method works', function (): void {
    $regex = Regex::build()->containsSequencesOf('a', 2, 4);
    expect($regex->getPattern())->toBe('(?=.*a{2,4})');
    expect($regex->matches('aa'))->toBeTrue()
        ->and($regex->matches('appleaa'))->toBeTrue();
});

test('atLeastSequencesOf method works', function (): void {
    $regex = Regex::build()->atLeastSequencesOf('a', 2);
    expect($regex->getPattern())->toBe('a{2,}');
    expect($regex->matches('aa'))->toBeTrue()
        ->and($regex->matches('aaaaa'))->toBeTrue()
        ->and($regex->matches('aba'))->toBeFalse()
        ->and($regex->matches('a'))->toBeFalse();

    expect($regex->count('aaabaaa'))->toBe(2);
    expect($regex->replace('aaabaaa', 'X'))->toBe('XbX');
});

test('containsAtleastSequencesOf method works', function (): void {
    $regex = Regex::build()->containsAtleastSequencesOf('a', 2);
    expect($regex->getPattern())->toBe('(?=.*a{2,})');
    expect($regex->matches('aa'))->toBeTrue()
        ->and($regex->matches('appleaa'))->toBeTrue();
});

test('containsExactSequencesOf with closure works', function (): void {
    $regex = Regex::build()->containsExactSequencesOf(fn (Regex $r): Regex => $r->addPattern('a')->or()->addPattern('b'), 3);
    expect($regex->getPattern())->toBe('(?=.*(?:a|b){3})');
    expect($regex->matches('aba'))->toBeTrue()
        ->and($regex->matches('appleaba'))->toBeTrue();
});

test('containsSequencesOf with closure works', function (): void {
    $regex = Regex::build()->containsSequencesOf(fn (Regex $r): Regex => $r->digit(), 2, 3);
    expect($regex->getPattern())->toBe('(?=.*(?:\d){2,3})');
    expect($regex->matches('12'))->toBeTrue()
        ->and($regex->matches('apple123'))->toBeTrue();
});

test('containsAtleastSequencesOf with closure works', function (): void {
    $regex = Regex::build()->containsAtleastSequencesOf(fn (Regex $r): Regex => $r->alpha(), 2);
    expect($regex->getPattern())->toBe('(?=.*(?:[a-zA-Z]+){2,})');
    expect($regex->matches('ab'))->toBeTrue()
        ->and($regex->matches('1a34b'))->toBeFalse()
        ->and($regex->matches('appleabcd'))->toBeTrue();
});

test('exactSequencesOf with closure works', function (): void {
    $regex = Regex::build()->exactSequencesOf(fn (Regex $r): Regex => $r->addPattern('a')->or()->addPattern('b'), 3);
    expect($regex->getPattern())->toBe('(?:a|b){3}');
    expect($regex->matches('aba'))->toBeTrue()
        ->and($regex->matches('abc'))->toBeFalse();
});

test('sequencesOf with closure works', function (): void {
    $regex = Regex::build()->sequencesOf(fn (Regex $r): Regex => $r->digit(), 2, 3);
    expect($regex->getPattern())->toBe('(?:\d){2,3}');
    expect($regex->matches('12'))->toBeTrue()
        ->and($regex->matches('123'))->toBeTrue()
        ->and($regex->matches('1'))->toBeFalse();
});

test('atLeastSequencesOf with closure works', function (): void {
    $regex = Regex::build()->atLeastSequencesOf(fn (Regex $r): Regex => $r->alpha(), 2);
    expect($regex->getPattern())->toBe('(?:[a-zA-Z]+){2,}');
    expect($regex->matches('ab'))->toBeTrue()
        ->and($regex->matches('abcd'))->toBeTrue()
        ->and($regex->matches('a'))->toBeFalse();
});

test('chains all sequential methods', function (): void {
    $regex = Regex::build()
        ->exactSequencesOf('a', 2)
        ->sequencesOf('b', 1, 2)
        ->atLeastSequencesOf('c', 1);

    expect($regex->getPattern())->toBe('a{2}b{1,2}c{1,}');
    expect($regex->matches('aabbc'))->toBeTrue()
        ->and($regex->matches('abc'))->toBeFalse()
        ->and($regex->matches('aabbcc'))->toBeTrue();
});

test('massive sequence: positional and contains coverage', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then(fn (Regex $r): Regex => $r->beginsWith('A')->containsDigit())
              ->then(fn (Regex $r): Regex => $r->between(['B' => 'D'])->notBetween(['X' => 'Z']))
              ->then(fn (Regex $r): Regex => $r->containsLetter())
              ->then(fn (Regex $r): Regex => $r->endsWith('Z'));
        }, startFromBeginning: true);

    expect($regex->matches('A1BmtestZ'))->toBeTrue()
        ->and($regex->matches('A1YmtestZ'))->toBeFalse()
        ->and($regex->matches('1BmtestZ'))->toBeFalse();
});

test('massive sequence: booleans and quantifiers coverage', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then(fn (Regex $r): Regex => $r->containsAtleastOne('A'))
              ->then(fn (Regex $r): Regex => $r->and('B')->not('X'))
              ->then(fn (Regex $r): Regex => $r->containsZeroOrMore('C'))
              ->then(fn (Regex $r): Regex => $r->containsZeroOrOne('D'));
        });

    expect($regex->matches('AAABBCCCD'))->toBeTrue()
        ->and($regex->matches('AAAB'))->toBeTrue()
        ->and($regex->matches('BBB'))->toBeFalse();
});

test('massive sequence: sequential methods coverage', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $s): void {
            $s->then(fn (Regex $r): Regex => $r->exactSequencesOf('A', 2))
              ->then(fn (Regex $r): Regex => $r->sequencesOf('B', 2, 4))
              ->then(fn (Regex $r): Regex => $r->atLeastSequencesOf('C', 3));
        }, startFromBeginning: true);

    expect($regex->matches('AABBBBCCC'))->toBeTrue()
        ->and($regex->matches('AABBCC'))->toBeFalse()
        ->and($regex->matches('AAABBCCC'))->toBeTrue();
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

    expect($regex->matches('ID:123ABCtest@example.com.txt'))->toBeTrue()
        ->and($regex->matches('ID:123ABCtest@example.com.pdf'))->toBeFalse()
        ->and($regex->matches('123ABCtest@example.com.txt'))->toBeFalse();
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

    expect($regex->matches('start123xyzend'))->toBeTrue()
        ->and($regex->matches('start123end'))->toBeFalse()
        ->and($regex->matches('begin123xyzend'))->toBeFalse();
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

    expect($regex->matches('HelloTestWorld'))->toBeTrue()
        ->and($regex->matches('HELLOTESTWORLD'))->toBeTrue()
        ->and($regex->matches('hello test world'))->toBeTrue();
});

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
        ->and($regex->match('aaaa'))->toBeTrue(); // matches aaa within aaaa
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
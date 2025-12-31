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

    // expect($regex2->match('aapplebanana'))->toBeTrue();
    expect($regex2->match('applebanana'))->toBeTrue();

    expect($regex3->match('aapplebanana'))->toBeFalse();
    expect($regex3->match('applebanana'))->toBeTrue();

    // expect($regex4->match('aapplebanana'))->toBeTrue();
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

    // It should match 'apple cherry' because it doesn't have 'banana' after 'apple'
    expect($regex->match('apple cherry'))->toBeTrue();
    
    // It should NOT match 'applebanana' because it has 'banana' after 'apple'
    expect($regex->match('applebanana'))->toBeFalse();
});
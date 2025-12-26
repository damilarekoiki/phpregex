<?php

use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

test('it can check if a string contains another string anywhere', function (): void {
    $regex = Regex::build()->contains('apple')->contains('banana');

    expect($regex->match('I have an apple and a banana'))->toBeTrue();
    expect($regex->match('I have a banana and an apple'))->toBeTrue();
    expect($regex->match('I have an apple'))->toBeFalse();
});

test('then method is order-dependent', function (): void {
    $regex = Regex::build()
        ->sequence(function (Sequence $sequence): void {
            $sequence->then('apple')
                ->then('banana');
        });

    expect($regex->match('applebanana'))->toBeTrue();
    expect($regex->match('bananaapple'))->toBeFalse();
});

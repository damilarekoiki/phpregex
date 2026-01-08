<?php

use DamilareKoiki\PhpRegex\Regex;
use DamilareKoiki\PhpRegex\Sequence;

test('fullStringMatch works with contains (literal when first)', function (): void {
    $regex = Regex::build(fullStringMatch: true)->contains('a');
    
    expect($regex->getPattern())->toBe('^(?=.*a).*$');
    expect($regex->matches('a'))->toBeTrue();
    expect($regex->matches('apple'))->toBeTrue();
});

test('fullStringMatch works with consuming patterns', function (): void {
    $regex = Regex::build(fullStringMatch: true)->addPattern('apple');
    
    expect($regex->getPattern())->toBe('^apple$');
    expect($regex->matches('apple'))->toBeTrue();
    expect($regex->matches('apple pie'))->toBeFalse();

    expect($regex->count('apple'))->toBe(1);
    expect($regex->replace('apple', 'orange'))->toBe('orange');
});

test('fullStringMatch works with sequence (lookahead)', function (): void {
    $regex = Regex::build(fullStringMatch: true)
        ->containsSequence(function (Sequence $sequence): void {
            $sequence->then('a')->then('p');
        }, startFromBeginning: false);
    
    expect($regex->getPattern())->toBe('^(?=.*(ap)).*$');
    expect($regex->matches('apple'))->toBeTrue();
    expect($regex->matches('grape'))->toBeTrue();
    expect($regex->matches('banana'))->toBeFalse();
});

test('fullStringMatch works with sequence (consuming)', function (): void {
    $regex = Regex::build(fullStringMatch: true)
        ->containsSequence(function (Sequence $sequence): void {
            $sequence->then('a')->then('p');
        }, startFromBeginning: true);
    
    expect($regex->getPattern())->toBe('^(^(a.*p))$');
    expect($regex->matches('ap'))->toBeTrue();
    expect($regex->matches('app'))->toBeTrue();
    expect($regex->matches('apple'))->toBeFalse();

    expect($regex->count('app'))->toBe(1);
    expect($regex->replace('app', 'X'))->toBe('X');
});

test('fullStringMatch works with doesntContain', function (): void {
    $regex = Regex::build(fullStringMatch: true)->doesntContain('a');
    
    expect($regex->getPattern())->toBe('^(?!.*a).*$');
    expect($regex->matches('cherry'))->toBeTrue();
    expect($regex->matches('apple'))->toBeFalse();
});

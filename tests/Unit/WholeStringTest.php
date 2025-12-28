<?php

use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

test('wholeString works with contains (literal when first)', function () {
    $regex = Regex::build(wholeString: true)->contains('a');
    
    // Pattern should be ^a$
    expect($regex->getPattern())->toBe('^(?=.*a).*$');
    expect($regex->match('a'))->toBeTrue();
    expect($regex->match('apple'))->toBeTrue();
});

test('wholeString works with consuming patterns', function () {
    $regex = Regex::build(wholeString: true)->addPattern('apple');
    
    expect($regex->getPattern())->toBe('^apple$');
    expect($regex->match('apple'))->toBeTrue();
    expect($regex->match('apple pie'))->toBeFalse();
});

test('wholeString works with sequence (lookahead)', function () {
    $regex = Regex::build(wholeString: true)
        ->sequence(function (Sequence $sequence) {
            $sequence->then('a')->then('p');
        }, startFromBeginning: false);
    
    expect($regex->getPattern())->toBe('^(?=.*(ap)).*$');
    expect($regex->match('apple'))->toBeTrue();
    expect($regex->match('grape'))->toBeTrue();
    expect($regex->match('banana'))->toBeFalse();
});

test('wholeString works with sequence (consuming)', function () {
    $regex = Regex::build(wholeString: true)
        ->sequence(function (Sequence $sequence) {
            $sequence->then('a')->then('p');
        }, startFromBeginning: true);
    
    expect($regex->getPattern())->toBe('^(^(a.*p))$');
    expect($regex->match('ap'))->toBeTrue();
    expect($regex->match('app'))->toBeTrue();
    expect($regex->match('apple'))->toBeFalse();
});

test('wholeString works with doesntContain', function () {
    $regex = Regex::build(wholeString: true)->doesntContain('a');
    
    expect($regex->getPattern())->toBe('^(?!.*a).*$');
    expect($regex->match('cherry'))->toBeTrue();
    expect($regex->match('apple'))->toBeFalse();
});

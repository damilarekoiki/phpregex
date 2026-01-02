<?php

use Ten\Phpregex\Regex;

test('containsAtleastOne method works', function (): void {
    $regex = Regex::build()->containsAtleastOne('a');
    expect($regex->getPattern())->toBe('(?=.*a+)');
    expect($regex->match('apple'))->toBeTrue()
        ->and($regex->match('cherry'))->toBeFalse();
    
    expect(Regex::build()->containsAtleastOne('')->getPattern())->toBe('(?=.*+)');
});

test('containsZeroOrMore method works', function (): void {
    $regex = Regex::build()->containsZeroOrMore('a');
    expect($regex->getPattern())->toBe('(?=.*a*)');
    expect($regex->match('anything'))->toBeTrue();
    
    expect(Regex::build()->containsZeroOrMore('')->getPattern())->toBe('(?=.**)');
});

test('containsZeroOrOne method works', function (): void {
    $regex = Regex::build()->containsZeroOrOne('a');
    expect($regex->getPattern())->toBe('(?=.*a?)');
    expect($regex->match('anything'))->toBeTrue();
    
    expect(Regex::build()->containsZeroOrOne('')->getPattern())->toBe('(?=.*?)');
});
test('chains all quantifier methods', function (): void {
    $regex = Regex::build()
        ->containsAtleastOne('a')
        ->containsZeroOrMore('b')
        ->containsZeroOrOne('c');

    expect($regex->getPattern())->toBe('(?=.*a+)(?=.*b*)(?=.*c?)');
    expect($regex->match('apple'))->toBeTrue()
        ->and($regex->match('ab'))->toBeTrue()
        ->and($regex->match('bc'))->toBeFalse();
});

test('lookahead checks position, not value', function (): void {
    $regex = Regex::build()->containsAtleastOne('a');
    expect($regex->getPattern())->toBe('(?=.*a+)');
    expect($regex->count('apple'))->toBe(1);
    expect($regex->replace('apple', 'X'))->toBe('Xapple');
});

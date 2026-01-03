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

test('atLeastOne method works', function (): void {
    $regex = Regex::build()->atLeastOne('a');
    expect($regex->getPattern())->toBe('a+');
    expect($regex->match('apple'))->toBeTrue()
        ->and($regex->match('cherry'))->toBeFalse();
    
    expect(Regex::build()->atLeastOne('')->getPattern())->toBe('+');
});

test('zeroOrMore method works', function (): void {
    $regex = Regex::build()->zeroOrMore('a');
    expect($regex->getPattern())->toBe('a*');
    expect($regex->match('anything'))->toBeTrue();
    
    expect(Regex::build()->zeroOrMore('')->getPattern())->toBe('*');
});

test('zeroOrOne method works', function (): void {
    $regex = Regex::build()->zeroOrOne('a');
    expect($regex->getPattern())->toBe('a?');
    expect($regex->match('apple'))->toBeTrue()
        ->and($regex->match('cherry'))->toBeTrue();
    
    expect(Regex::build()->zeroOrOne('')->getPattern())->toBe('?');
});

test('chains non-lookahead quantifier methods', function (): void {
    $regex = Regex::build()
        ->atLeastOne('a')
        ->zeroOrMore('b')
        ->zeroOrOne('c');

    expect($regex->getPattern())->toBe('a+b*c?');
    expect($regex->match('ab'))->toBeTrue()
        ->and($regex->match('a'))->toBeTrue()
        ->and($regex->match('ac'))->toBeTrue()
        ->and($regex->match('abc'))->toBeTrue()
        ->and($regex->match('bc'))->toBeFalse();
});

test('non-lookahead quantifiers consume characters', function (): void {
    $regex = Regex::build()->atLeastOne('a');
    expect($regex->getPattern())->toBe('a+');
    expect($regex->count('aaaaa'))->toBe(1); // 'aaaaa' is matched as a whole
    expect($regex->replace('aaaaaa', 'X'))->toBe('X');
    expect($regex->replace('aaaaaba', 'X'))->toBe('XbX');

});

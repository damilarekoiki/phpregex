<?php

use Ten\Phpregex\Regex;

test('contains method works', function (): void {
    $regex = Regex::build()->contains('apple');
    expect($regex->getPattern())->toBe('(?=.*apple)');
    expect($regex->match('sweet apple'))->toBeTrue();
});

test('doesntContain method works', function (): void {
    $regex = Regex::build(true)->doesntContain('apple');
    expect($regex->getPattern())->toBe('^(?!.*apple).*$');
    expect($regex->match('sweet banana'))->toBeTrue()
        ->and($regex->match('sweet apple'))->toBeFalse();
});

test('containsAnyOf method works with array', function (): void {
    $regex = Regex::build()->containsAnyOf(['apple', 'banana']);
    expect($regex->getPattern())->toBe('(?=.*(apple|banana))');
    expect($regex->match('apple pie'))->toBeTrue()
        ->and($regex->match('banana split'))->toBeTrue()
        ->and($regex->match('cherry cake'))->toBeFalse();
});

test('containsAnyOf method works with string', function (): void {
    $regex = Regex::build()->containsAnyOf('abc');
    expect($regex->getPattern())->toBe('(?=.*[abc])');
    expect($regex->match('apple'))->toBeTrue()
        ->and($regex->match('dog'))->toBeFalse();
});

test('doesntContainAnyOf method works with array', function (): void {
    $regex = Regex::build()->doesntContainAnyOf(['apple', 'banana']);
    expect($regex->getPattern())->toBe('^(?!.*(apple|banana)).*$');
    expect($regex->match('cherry'))->toBeTrue()
        ->and($regex->match('apple'))->toBeFalse();
});

test('doesntContainAnyOf method works with string', function (): void {
    $regex = Regex::build()->doesntContainAnyOf('abc');
    expect($regex->getPattern())->toBe('^[^abc]*$');
    expect($regex->match('xyz'))->toBeTrue()
        ->and($regex->match('apple'))->toBeFalse();
});

test('digit methods work', function (): void {
    expect(Regex::build()->containsDigit()->getPattern())->toBe('(?=.*\d)');
    expect(Regex::build()->doesntContainDigit()->getPattern())->toBe('(?!.*\d)');
    expect(Regex::build()->containsOnlyDigits()->getPattern())->toBe('^\d+$');
    expect(Regex::build()->doesntContainOnlyDigits()->getPattern())->toBe('^(?!\d+$).+');
    expect(Regex::build()->containsNonDigit()->getPattern())->toBe('(?=.*\D)');
});

test('alphanumeric methods work', function (): void {
    expect(Regex::build()->containsAlphaNumeric()->getPattern())->toBe('(?=.*[A-Za-z0-9])');
    expect(Regex::build()->doesntContainAlphaNumeric()->getPattern())->toBe('^(?!.*[A-Za-z0-9]).*$');
    expect(Regex::build()->containsOnlyAlphaNumeric()->getPattern())->toBe('^[A-Za-z0-9]+$');
    expect(Regex::build()->doesntContainOnlyAlphaNumeric()->getPattern())->toBe('[^A-Za-z0-9]');
});

test('word boundary methods work', function (): void {
    expect(Regex::build()->containsWordsThatBeginWith('start')->getPattern())->toBe('(?=.*\bstart)');
    expect(Regex::build()->containsWordsThatEndWith('end')->getPattern())->toBe('(?=.*end\b)');
});

test('letter and whitespace methods work', function (): void {
    expect(Regex::build()->containsLetter()->getPattern())->toBe('(?=.*[a-zA-Z])');
    expect(Regex::build()->containsLowercaseLetter()->getPattern())->toBe('(?=.*[a-z])');
    expect(Regex::build()->containsUppercaseLetter()->getPattern())->toBe('(?=.*[A-Z])');
    expect(Regex::build()->containsWhitespace()->getPattern())->toBe('(?=.*\s)');
    expect(Regex::build()->containsNonWhitespace()->getPattern())->toBe('(?=.*\S)');
    expect(Regex::build()->containsWordCharacter()->getPattern())->toBe('(?=.*\w)');
    expect(Regex::build()->containsNonWordCharacter()->getPattern())->toBe('(?=.*\W)');
    expect(Regex::build()->containsAnything()->getPattern())->toBe('(?=.*.)');
    expect(Regex::build()->containsAnything()->match('abc'))->toBeTrue();
});

<?php

use Ten\Phpregex\Regex;

test('contains method vs then method', function (): void {
    $regex = Regex::build()->contains('apple');
    expect($regex->getPattern())->toBe('(?=.*apple)');
    expect($regex->matches('apple fruit'))->toBeTrue();
    expect($regex->matches('applebanana'))->toBeTrue();

    $consuming = Regex::build()->then('apple')->then(fn(Regex $regex): Regex=> $regex->between(['f'=> 'h']));
    expect($consuming->count('applefruit apple pie appleg'))->toBe(2);
});

test('doesntContain method works', function (): void {
    $regex = Regex::build(true)->doesntContain('apple');
    expect($regex->getPattern())->toBe('^(?!.*apple).*$');
    expect($regex->matches('sweet banana'))->toBeTrue()
        ->and($regex->matches('sweet apple'))->toBeFalse();
    expect($regex->count('sweet banana'))->toBe(1);
    expect($regex->replace('sweet banana', 'X'))->toBe('X');
});

test('containsAnyOf method works with array', function (): void {
    $regex = Regex::build()->containsAnyOf(['apple', 'banana']);
    expect($regex->getPattern())->toBe('(?=.*(apple|banana))');
    expect($regex->matches('apple pie'))->toBeTrue()
        ->and($regex->matches('banana split'))->toBeTrue()
        ->and($regex->matches('cherry cake'))->toBeFalse();
});

test('containsAnyOf method works with string', function (): void {
    $regex = Regex::build()->containsAnyOf('abc');
    expect($regex->getPattern())->toBe('(?=.*[abc])');
    expect($regex->matches('apple'))->toBeTrue()
        ->and($regex->matches('dog'))->toBeFalse();
});

test('doesntContainAnyOf method works with array', function (): void {
    $regex = Regex::build()->doesntContainAnyOf(['apple', 'banana']);
    expect($regex->getPattern())->toBe('^(?!.*(apple|banana)).*$');
    expect($regex->matches('cherry'))->toBeTrue()
        ->and($regex->matches('apple'))->toBeFalse();
});

test('doesntContainAnyOf method works with string', function (): void {
    $regex = Regex::build()->doesntContainAnyOf('abc');
    expect($regex->getPattern())->toBe('^[^abc]*$');
    expect($regex->matches('xyz'))->toBeTrue()
        ->and($regex->matches('apple'))->toBeFalse();
});

test('digit methods work', function (): void {
    expect(Regex::build()->containsDigit()->getPattern())->toBe('(?=.*\d)');
    expect(Regex::build()->containsDigit()->matches('abc1def'))->toBeTrue()
        ->and(Regex::build()->containsDigit()->matches('abcdef'))->toBeFalse();

    expect(Regex::build()->doesntContainDigit()->getPattern())->toBe('^(?!.*\d).+$');
    expect(Regex::build()->doesntContainDigit()->matches('abcdef'))->toBeTrue()
        ->and(Regex::build()->doesntContainDigit()->matches('abc1def'))->toBeFalse()
        ->and(Regex::build()->doesntContainDigit()->matches('1'))->toBeFalse()
        ->and(Regex::build()->doesntContainDigit()->matches(' 1'))->toBeFalse();


    expect(Regex::build()->containsOnlyDigits()->getPattern())->toBe('^\d+$');
    expect(Regex::build()->containsOnlyDigits()->matches('12345'))->toBeTrue()
        ->and(Regex::build()->containsOnlyDigits()->matches('123a45'))->toBeFalse();
    expect(Regex::build()->containsOnlyDigits()->count('12345'))->toBe(1);
    expect(Regex::build()->containsOnlyDigits()->replace('12345', 'X'))->toBe('X');

    expect(Regex::build()->doesntContainOnlyDigits()->getPattern())->toBe('^(?!\d+$).+');
    expect(Regex::build()->doesntContainOnlyDigits()->matches('123a45'))->toBeTrue()
        ->and(Regex::build()->doesntContainOnlyDigits()->matches('12345'))->toBeFalse();
    expect(Regex::build()->doesntContainOnlyDigits()->count('123a45'))->toBe(1);
    expect(Regex::build()->doesntContainOnlyDigits()->replace('123a45', 'X'))->toBe('X');

    expect(Regex::build()->containsNonDigit()->getPattern())->toBe('(?=.*\D)');
    expect(Regex::build()->containsNonDigit()->matches('123a45'))->toBeTrue()
        ->and(Regex::build()->containsNonDigit()->matches('12345'))->toBeFalse();
});

test('containsBetween and doesntContainBetween methods work', function (): void {
    expect(Regex::build()->containsBetween(['a' => 'z'])->getPattern())->toBe('(?=.*[a-z])');
    expect(Regex::build()->containsBetween(['a' => 'z'])->matches('hello123'))->toBeTrue()
        ->and(Regex::build()->containsBetween(['a' => 'z'])->matches('12345'))->toBeFalse();

    expect(Regex::build()->containsBetween(['a' => 'z', '0' => '9'])->getPattern())->toBe('(?=.*[a-z0-9])');
    expect(Regex::build()->containsBetween(['a' => 'z', '0' => '9'])->matches('hello123'))->toBeTrue()
        ->and(Regex::build()->containsBetween(['a' => 'z', '0' => '9'])->matches('!@#$%'))->toBeFalse();

    expect(Regex::build()->doesntContainBetween(['0' => '9'])->getPattern())->toBe('^(?!.*[0-9]).+$');
    expect(Regex::build()->doesntContainBetween(['0' => '5'])->matches('hello6'))->toBeTrue()
        ->and(Regex::build()->doesntContainBetween(['0' => '9'])->matches('hello123'))->toBeFalse();
});

test('containsBetween throws exception for mismatched range types', function (): void {
    expect(fn (): Regex => Regex::build()->containsBetween(['a' => 1]))
        ->toThrow(Exception::class, "Range end '1' must be a letter because range start 'a' is a letter.");

    expect(fn (): Regex => Regex::build()->containsBetween(['1' => 'a']))
        ->toThrow(Exception::class, "Range end 'a' must be a digit because range start '1' is a digit.");
});

test('alphanumeric methods work', function (): void {
    expect(Regex::build()->containsAlphaNumeric()->getPattern())->toBe('(?=.*[A-Za-z0-9])');
    expect(Regex::build()->containsAlphaNumeric()->matches('!@#a'))->toBeTrue()
        ->and(Regex::build()->containsAlphaNumeric()->matches('!@#$'))->toBeFalse();

    expect(Regex::build()->doesntContainAlphaNumeric()->getPattern())->toBe('^(?!.*[A-Za-z0-9]).*$');
    expect(Regex::build()->doesntContainAlphaNumeric()->matches('!@#$'))->toBeTrue()
        ->and(Regex::build()->doesntContainAlphaNumeric()->matches('!@#a'))->toBeFalse();

    expect(Regex::build()->containsOnlyAlphaNumeric()->getPattern())->toBe('^[A-Za-z0-9]+$');
    expect(Regex::build()->containsOnlyAlphaNumeric()->matches('abc123'))->toBeTrue()
        ->and(Regex::build()->containsOnlyAlphaNumeric()->matches('abc-123'))->toBeFalse();
    expect(Regex::build()->containsOnlyAlphaNumeric()->count('abc123'))->toBe(1);
    expect(Regex::build()->containsOnlyAlphaNumeric()->replace('abc123', 'X'))->toBe('X');

    expect(Regex::build()->doesntContainOnlyAlphaNumeric()->getPattern())->toBe('[^A-Za-z0-9]');
    expect(Regex::build()->doesntContainOnlyAlphaNumeric()->matches('abc-123'))->toBeTrue()
        ->and(Regex::build()->doesntContainOnlyAlphaNumeric()->matches('abc123'))->toBeFalse();
    expect(Regex::build()->doesntContainOnlyAlphaNumeric()->count('abc-123'))->toBe(1);
    expect(Regex::build()->doesntContainOnlyAlphaNumeric()->replace('abc-123', 'X'))->toBe('abcX123');
});

test('word boundary methods work', function (): void {
    expect(Regex::build()->containsWordsThatBeginWith('start')->getPattern())->toBe('(?=.*\bstart)');
    expect(Regex::build()->containsWordsThatBeginWith('start')->matches('the start event'))->toBeTrue()
        ->and(Regex::build()->containsWordsThatBeginWith('start')->matches('restarting'))->toBeFalse();

    expect(Regex::build()->containsWordsThatEndWith('end')->getPattern())->toBe('(?=.*end\b)');
    expect(Regex::build()->containsWordsThatEndWith('end')->matches('the weekend'))->toBeTrue()
        ->and(Regex::build()->containsWordsThatEndWith('end')->matches('ending soon'))->toBeFalse();
});

test('letter and whitespace methods work', function (): void {
    expect(Regex::build()->containsLetter()->getPattern())->toBe('(?=.*[a-zA-Z])');
    expect(Regex::build()->containsLetter()->matches('123a456'))->toBeTrue()
        ->and(Regex::build()->containsLetter()->matches('123456'))->toBeFalse();

    expect(Regex::build()->containsLowercaseLetter()->getPattern())->toBe('(?=.*[a-z])');
    expect(Regex::build()->containsLowercaseLetter()->matches('ABCdEF'))->toBeTrue()
        ->and(Regex::build()->containsLowercaseLetter()->matches('ABCDEF'))->toBeFalse();

    expect(Regex::build()->containsUppercaseLetter()->getPattern())->toBe('(?=.*[A-Z])');
    expect(Regex::build()->containsUppercaseLetter()->matches('abcDef'))->toBeTrue()
        ->and(Regex::build()->containsUppercaseLetter()->matches('abcdef'))->toBeFalse();

    expect(Regex::build()->containsWhitespace()->getPattern())->toBe('(?=.*\s)');
    expect(Regex::build()->containsWhitespace()->matches('hello world'))->toBeTrue()
        ->and(Regex::build()->containsWhitespace()->matches('helloworld'))->toBeFalse();

    expect(Regex::build()->containsNonWhitespace()->getPattern())->toBe('(?=.*\S)');
    expect(Regex::build()->containsNonWhitespace()->matches('   a   '))->toBeTrue()
        ->and(Regex::build()->containsNonWhitespace()->matches('   '))->toBeFalse();

    expect(Regex::build()->containsWordCharacter()->getPattern())->toBe('(?=.*\w)');
    expect(Regex::build()->containsWordCharacter()->matches('!@#a$'))->toBeTrue()
        ->and(Regex::build()->containsWordCharacter()->matches('!@#$%'))->toBeFalse();

    expect(Regex::build()->containsNonWordCharacter()->getPattern())->toBe('(?=.*\W)');
    expect(Regex::build()->containsNonWordCharacter()->matches('abc!def'))->toBeTrue()
        ->and(Regex::build()->containsNonWordCharacter()->matches('abcdef'))->toBeFalse();

    expect(Regex::build()->containsAnything()->getPattern())->toBe('(?=.*.)');
    expect(Regex::build()->containsAnything()->matches('abc'))->toBeTrue()
        ->and(Regex::build()->containsAnything()->matches(''))->toBeFalse();
});
test('chains various contains and doesnt methods', function (): void {
    $regex = Regex::build()
        ->containsDigit()
        ->doesntContainDigit()
        ->containsLowercaseLetter()
        ->containsUppercaseLetter()
        ->containsWhitespace()
        ->containsNonWhitespace()
        ->containsWordCharacter()
        ->containsNonWordCharacter()
        ->containsAnything();
    
    expect($regex->getPattern())->toBe('(?=.*\d)^(?!.*\d).+$(?=.*[a-z])(?=.*[A-Z])(?=.*\s)(?=.*\S)(?=.*\w)(?=.*\W)(?=.*.)');
    
    $regex2 = Regex::build()
        ->containsDigit()
        ->containsLowercaseLetter()
        ->containsUppercaseLetter()
        ->containsWhitespace();
    
    expect($regex2->matches('a B 1'))->toBeTrue()
        ->and($regex2->matches('a B'))->toBeFalse()
        ->and($regex2->matches('a1'))->toBeFalse();
});

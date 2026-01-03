<?php

use Ten\Phpregex\Regex;

test('contains method vs then method', function (): void {
    $regex = Regex::build()->contains('apple');
    expect($regex->getPattern())->toBe('(?=.*apple)');
    expect($regex->match('apple fruit'))->toBeTrue();
    expect($regex->match('applebanana'))->toBeTrue();

    $consuming = Regex::build()->then('apple')->then(fn(Regex $regex): Regex=> $regex->between(['f'=> 'h']));
    expect($consuming->count('applefruit apple pie appleg'))->toBe(2);
});

test('doesntContain method works', function (): void {
    $regex = Regex::build(true)->doesntContain('apple');
    expect($regex->getPattern())->toBe('^(?!.*apple).*$');
    expect($regex->match('sweet banana'))->toBeTrue()
        ->and($regex->match('sweet apple'))->toBeFalse();
    expect($regex->count('sweet banana'))->toBe(1);
    expect($regex->replace('sweet banana', 'X'))->toBe('X');
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
    expect(Regex::build()->containsDigit()->match('abc1def'))->toBeTrue()
        ->and(Regex::build()->containsDigit()->match('abcdef'))->toBeFalse();

    expect(Regex::build()->doesntContainDigit()->getPattern())->toBe('^(?!.*\d).+$');
    expect(Regex::build()->doesntContainDigit()->match('abcdef'))->toBeTrue()
        ->and(Regex::build()->doesntContainDigit()->match('abc1def'))->toBeFalse()
        ->and(Regex::build()->doesntContainDigit()->match('1'))->toBeFalse()
        ->and(Regex::build()->doesntContainDigit()->match(' 1'))->toBeFalse();


    expect(Regex::build()->containsOnlyDigits()->getPattern())->toBe('^\d+$');
    expect(Regex::build()->containsOnlyDigits()->match('12345'))->toBeTrue()
        ->and(Regex::build()->containsOnlyDigits()->match('123a45'))->toBeFalse();
    expect(Regex::build()->containsOnlyDigits()->count('12345'))->toBe(1);
    expect(Regex::build()->containsOnlyDigits()->replace('12345', 'X'))->toBe('X');

    expect(Regex::build()->doesntContainOnlyDigits()->getPattern())->toBe('^(?!\d+$).+');
    expect(Regex::build()->doesntContainOnlyDigits()->match('123a45'))->toBeTrue()
        ->and(Regex::build()->doesntContainOnlyDigits()->match('12345'))->toBeFalse();
    expect(Regex::build()->doesntContainOnlyDigits()->count('123a45'))->toBe(1);
    expect(Regex::build()->doesntContainOnlyDigits()->replace('123a45', 'X'))->toBe('X');

    expect(Regex::build()->containsNonDigit()->getPattern())->toBe('(?=.*\D)');
    expect(Regex::build()->containsNonDigit()->match('123a45'))->toBeTrue()
        ->and(Regex::build()->containsNonDigit()->match('12345'))->toBeFalse();
});

test('containsBetween and doesntContainBetween methods work', function (): void {
    expect(Regex::build()->containsBetween(['a' => 'z'])->getPattern())->toBe('(?=.*[a-z])');
    expect(Regex::build()->containsBetween(['a' => 'z'])->match('hello123'))->toBeTrue()
        ->and(Regex::build()->containsBetween(['a' => 'z'])->match('12345'))->toBeFalse();

    expect(Regex::build()->containsBetween(['a' => 'z', '0' => '9'])->getPattern())->toBe('(?=.*[a-z0-9])');
    expect(Regex::build()->containsBetween(['a' => 'z', '0' => '9'])->match('hello123'))->toBeTrue()
        ->and(Regex::build()->containsBetween(['a' => 'z', '0' => '9'])->match('!@#$%'))->toBeFalse();

    expect(Regex::build()->doesntContainBetween(['0' => '9'])->getPattern())->toBe('^(?!.*[0-9]).+$');
    expect(Regex::build()->doesntContainBetween(['0' => '5'])->match('hello6'))->toBeTrue()
        ->and(Regex::build()->doesntContainBetween(['0' => '9'])->match('hello123'))->toBeFalse();
});

test('alphanumeric methods work', function (): void {
    expect(Regex::build()->containsAlphaNumeric()->getPattern())->toBe('(?=.*[A-Za-z0-9])');
    expect(Regex::build()->containsAlphaNumeric()->match('!@#a'))->toBeTrue()
        ->and(Regex::build()->containsAlphaNumeric()->match('!@#$'))->toBeFalse();

    expect(Regex::build()->doesntContainAlphaNumeric()->getPattern())->toBe('^(?!.*[A-Za-z0-9]).*$');
    expect(Regex::build()->doesntContainAlphaNumeric()->match('!@#$'))->toBeTrue()
        ->and(Regex::build()->doesntContainAlphaNumeric()->match('!@#a'))->toBeFalse();

    expect(Regex::build()->containsOnlyAlphaNumeric()->getPattern())->toBe('^[A-Za-z0-9]+$');
    expect(Regex::build()->containsOnlyAlphaNumeric()->match('abc123'))->toBeTrue()
        ->and(Regex::build()->containsOnlyAlphaNumeric()->match('abc-123'))->toBeFalse();
    expect(Regex::build()->containsOnlyAlphaNumeric()->count('abc123'))->toBe(1);
    expect(Regex::build()->containsOnlyAlphaNumeric()->replace('abc123', 'X'))->toBe('X');

    expect(Regex::build()->doesntContainOnlyAlphaNumeric()->getPattern())->toBe('[^A-Za-z0-9]');
    expect(Regex::build()->doesntContainOnlyAlphaNumeric()->match('abc-123'))->toBeTrue()
        ->and(Regex::build()->doesntContainOnlyAlphaNumeric()->match('abc123'))->toBeFalse();
    expect(Regex::build()->doesntContainOnlyAlphaNumeric()->count('abc-123'))->toBe(1);
    expect(Regex::build()->doesntContainOnlyAlphaNumeric()->replace('abc-123', 'X'))->toBe('abcX123');
});

test('word boundary methods work', function (): void {
    expect(Regex::build()->containsWordsThatBeginWith('start')->getPattern())->toBe('(?=.*\bstart)');
    expect(Regex::build()->containsWordsThatBeginWith('start')->match('the start event'))->toBeTrue()
        ->and(Regex::build()->containsWordsThatBeginWith('start')->match('restarting'))->toBeFalse();

    expect(Regex::build()->containsWordsThatEndWith('end')->getPattern())->toBe('(?=.*end\b)');
    expect(Regex::build()->containsWordsThatEndWith('end')->match('the weekend'))->toBeTrue()
        ->and(Regex::build()->containsWordsThatEndWith('end')->match('ending soon'))->toBeFalse();
});

test('letter and whitespace methods work', function (): void {
    expect(Regex::build()->containsLetter()->getPattern())->toBe('(?=.*[a-zA-Z])');
    expect(Regex::build()->containsLetter()->match('123a456'))->toBeTrue()
        ->and(Regex::build()->containsLetter()->match('123456'))->toBeFalse();

    expect(Regex::build()->containsLowercaseLetter()->getPattern())->toBe('(?=.*[a-z])');
    expect(Regex::build()->containsLowercaseLetter()->match('ABCdEF'))->toBeTrue()
        ->and(Regex::build()->containsLowercaseLetter()->match('ABCDEF'))->toBeFalse();

    expect(Regex::build()->containsUppercaseLetter()->getPattern())->toBe('(?=.*[A-Z])');
    expect(Regex::build()->containsUppercaseLetter()->match('abcDef'))->toBeTrue()
        ->and(Regex::build()->containsUppercaseLetter()->match('abcdef'))->toBeFalse();

    expect(Regex::build()->containsWhitespace()->getPattern())->toBe('(?=.*\s)');
    expect(Regex::build()->containsWhitespace()->match('hello world'))->toBeTrue()
        ->and(Regex::build()->containsWhitespace()->match('helloworld'))->toBeFalse();

    expect(Regex::build()->containsNonWhitespace()->getPattern())->toBe('(?=.*\S)');
    expect(Regex::build()->containsNonWhitespace()->match('   a   '))->toBeTrue()
        ->and(Regex::build()->containsNonWhitespace()->match('   '))->toBeFalse();

    expect(Regex::build()->containsWordCharacter()->getPattern())->toBe('(?=.*\w)');
    expect(Regex::build()->containsWordCharacter()->match('!@#a$'))->toBeTrue()
        ->and(Regex::build()->containsWordCharacter()->match('!@#$%'))->toBeFalse();

    expect(Regex::build()->containsNonWordCharacter()->getPattern())->toBe('(?=.*\W)');
    expect(Regex::build()->containsNonWordCharacter()->match('abc!def'))->toBeTrue()
        ->and(Regex::build()->containsNonWordCharacter()->match('abcdef'))->toBeFalse();

    expect(Regex::build()->containsAnything()->getPattern())->toBe('(?=.*.)');
    expect(Regex::build()->containsAnything()->match('abc'))->toBeTrue()
        ->and(Regex::build()->containsAnything()->match(''))->toBeFalse();
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
    
    expect($regex2->match('a B 1'))->toBeTrue()
        ->and($regex2->match('a B'))->toBeFalse()
        ->and($regex2->match('a1'))->toBeFalse();
});

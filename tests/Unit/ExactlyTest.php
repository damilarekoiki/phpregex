<?php

use Ten\Phpregex\Regex;

test('anyOf method works in Exactly trait', function (): void {
    $regex = Regex::build()->anyOf(['apple', 'banana']);
    expect($regex->getPattern())->toBe('(apple|banana)');
    expect($regex->count('apple banana cherry'))->toBe(2);
    expect($regex->replace('apple banana', 'fruit'))->toBe('fruit fruit');
});

test('digit method works in Exactly trait', function (): void {
    $regex = Regex::build()->digit();
    expect($regex->getPattern())->toBe('\d');
    expect($regex->count('1a2b3'))->toBe(3);
});

test('onlyDigits method works in Exactly trait', function (): void {
    $regex = Regex::build()->onlyDigits();
    expect($regex->getPattern())->toBe('^\d+$');
    expect($regex->match('123'))->toBeTrue()
        ->and($regex->match('123a'))->toBeFalse();
});

test('between method works in Exactly trait', function (): void {
    $regex = Regex::build()->between(['a' => 'z']);
    expect($regex->getPattern())->toBe('[a-z]');
    expect($regex->count('abc123xyz'))->toBe(6);
});

test('nonDigit method works in Exactly trait', function (): void {
    $regex = Regex::build()->nonDigit();
    expect($regex->getPattern())->toBe('\D');
    expect($regex->count('1a2b'))->toBe(2);
});

test('alphaNumeric method works in Exactly trait', function (): void {
    $regex = Regex::build()->alphaNumeric();
    expect($regex->getPattern())->toBe('[a-zA-Z0-9]');
    expect($regex->count('a1!'))->toBe(2);
});

test('onlyAlphaNumeric method works in Exactly trait', function (): void {
    $regex = Regex::build()->onlyAlphaNumeric();
    expect($regex->getPattern())->toBe('^[A-Za-z0-9]+$');
    expect($regex->match('abc123'))->toBeTrue()
        ->and($regex->match('abc-123'))->toBeFalse();
});

test('wordsThatBeginWith method works in Exactly trait', function (): void {
    $regex = Regex::build()->wordsThatBeginWith('apple');
    expect($regex->getPattern())->toBe('\bapple');
    expect($regex->count('apple pineapple'))->toBe(1);
});

test('wordsThatEndWith method works in Exactly trait', function (): void {
    $regex = Regex::build()->wordsThatEndWith('apple');
    expect($regex->getPattern())->toBe('apple\b');
    expect($regex->count('apple pineapple'))->toBe(2);
});

test('letter method works in Exactly trait', function (): void {
    $regex = Regex::build()->letter();
    expect($regex->getPattern())->toBe('[a-zA-Z]');
    expect($regex->count('a1B2'))->toBe(2);
});

test('whitespace method works in Exactly trait', function (): void {
    $regex = Regex::build()->whitespace();
    expect($regex->getPattern())->toBe('\s');
    expect($regex->count('a b  c'))->toBe(3);
});

test('wordCharacter method works in Exactly trait', function (): void {
    $regex = Regex::build()->wordCharacter();
    expect($regex->getPattern())->toBe('\w');
    expect($regex->count('a1_!'))->toBe(3);
});

test('anything method works in Exactly trait', function (): void {
    $regex = Regex::build()->anything();
    expect($regex->getPattern())->toBe('.');
    expect($regex->count('abc'))->toBe(3);
});

test('combination in Exactly trait works', function (): void {
    $regex = Regex::build()->digit()->letter();
    expect($regex->getPattern())->toBe('\d[a-zA-Z]');
    expect($regex->count('1a2b3#'))->toBe(2);
    expect($regex->replace('1a2b', 'X'))->toBe('XX');
});

test('helper patterns count and replace in Exactly context', function (): void {
    // email helper
    $emailRegex = Regex::build()->email();
    $emailSubject = 'Contact us at info@example.com or support@test.org';
    expect($emailRegex->count($emailSubject))->toBe(2);
    expect($emailRegex->replace($emailSubject, 'HIDDEN'))->toBe('Contact us at HIDDEN or HIDDEN');

    // ipv4 helper
    $ipRegex = Regex::build()->ipv4();
    $ipSubject = 'Servers: 192.168.1.1, 10.0.0.1';
    expect($ipRegex->count($ipSubject))->toBe(2);
    expect($ipRegex->replace($ipSubject, 'IP'))->toBe('Servers: IP, IP');

    // digits helper (consuming more than one digit)
    $digitsRegex = Regex::build()->digits();
    $digitsSubject = 'Codes: 123 4567 89';
    expect($digitsRegex->count($digitsSubject))->toBe(3);
    expect($digitsRegex->replace($digitsSubject, 'NUM'))->toBe('Codes: NUM NUM NUM');

    // hex helper
    $hexRegex = Regex::build()->hex();
    $hexSubject = 'AF01 22BE';
    expect($hexRegex->count($hexSubject))->toBe(2);
    expect($hexRegex->replace($hexSubject, 'HEX'))->toBe('HEX HEX');
});

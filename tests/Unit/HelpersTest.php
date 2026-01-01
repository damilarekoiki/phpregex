<?php

use Ten\Phpregex\Regex;

test('email helper', function (): void {
    $regex = Regex::build(wholeString: true)->email();
    expect($regex->match('test@example.com'))->toBeTrue()
        ->and($regex->match('user.name+tag@domain.co.uk'))->toBeTrue()
        ->and($regex->match('invalid-email'))->toBeFalse();
});

test('ipv4 helper', function (): void {
    $regex = Regex::build(wholeString: true)->ipv4();
    expect($regex->match('127.0.0.1'))->toBeTrue()
        ->and($regex->match('192.168.1.1'))->toBeTrue()
        ->and($regex->match('256.256.256.256'))->toBeFalse();
});

test('ipv6 helper', function (): void {
    $regex = Regex::build(wholeString: true)->ipv6();
    expect($regex->match('2001:0db8:85a3:0000:0000:8a2e:0370:7334'))->toBeTrue()
        ->and($regex->match('127.0.0.1'))->toBeFalse();
});

test('uuid helper', function (): void {
    $regex = Regex::build(wholeString: true)->uuid();
    expect($regex->match('550e8400-e29b-41d4-a716-446655440000'))->toBeTrue()
        ->and($regex->match('not-a-uuid'))->toBeFalse();
});

test('url helper', function (): void {
    $regex = Regex::build(wholeString: true)->url();
    expect($regex->match('https://google.com'))->toBeTrue()
        ->and($regex->match('http://www.test.io/path?query=1'))->toBeTrue()
        ->and($regex->match('google.com'))->toBeFalse();
});

test('alpha helper', function (): void {
    $regex = Regex::build(wholeString: true)->alpha();
    expect($regex->match('abcABC'))->toBeTrue()
        ->and($regex->match('abc123'))->toBeFalse();
});

test('alphanumeric helper', function (): void {
    $regex = Regex::build(wholeString: true)->alphanumeric();
    expect($regex->match('abc123ABC'))->toBeTrue()
        ->and($regex->match('abc-123'))->toBeFalse();
});

test('digits helper', function (): void {
    $regex = Regex::build(wholeString: true)->digits();
    expect($regex->match('123456'))->toBeTrue()
        ->and($regex->match('123a45'))->toBeFalse();
});

test('hex color helper', function (): void {
    $regex = Regex::build(wholeString: true)->hexColor();
    expect($regex->match('#fff'))->toBeTrue()
        ->and($regex->match('#ffffff'))->toBeTrue()
        ->and($regex->match('fff'))->toBeFalse();
});

test('slug helper', function (): void {
    $regex = Regex::build(wholeString: true)->slug();
    expect($regex->match('this-is-a-slug'))->toBeTrue()
        ->and($regex->match('slug123'))->toBeTrue()
        ->and($regex->match('Not-A-Slug'))->toBeFalse()
        ->and($regex->match('not a slug'))->toBeFalse();
});

test('credit card helper', function (): void {
    $regex = Regex::build(wholeString: true)->creditCard();
    expect($regex->match('1234-5678-9012-3456'))->toBeTrue()
        ->and($regex->match('1234 5678 9012 3456'))->toBeTrue()
        ->and($regex->match('1234567890123456'))->toBeTrue();
});

test('ssn helper', function (): void {
    $regex = Regex::build(wholeString: true)->ssn();
    expect($regex->match('123-45-6789'))->toBeTrue()
        ->and($regex->match('123456789'))->toBeFalse();
});

test('zip code helper', function (): void {
    $regex = Regex::build(wholeString: true)->zipCode();
    expect($regex->match('12345'))->toBeTrue()
        ->and($regex->match('12345-6789'))->toBeTrue()
        ->and($regex->match('1234'))->toBeFalse();
});

test('mac address helper', function (): void {
    $regex = Regex::build(wholeString: true)->macAddress();
    expect($regex->match('00:1A:2B:3C:4D:5E'))->toBeTrue()
        ->and($regex->match('00-1A-2B-3C-4D-5E'))->toBeTrue()
        ->and($regex->match('001A2B3C4D5E'))->toBeFalse();
});

test('date helper', function (): void {
    $regex = Regex::build(wholeString: true)->date();
    expect($regex->match('2023-12-27'))->toBeTrue()
        ->and($regex->match('27-12-2023'))->toBeFalse();
});

test('time helper', function (): void {
    $regex = Regex::build(wholeString: true)->time();
    expect($regex->match('14:30:15'))->toBeTrue()
        ->and($regex->match('14:30'))->toBeFalse();
});

test('handle helper', function (): void {
    $regex = Regex::build(wholeString: true)->handle();
    expect($regex->match('@user_name'))->toBeTrue()
        ->and($regex->match('user_name'))->toBeFalse();
});

test('hex helper', function (): void {
    $regex = Regex::build(wholeString: true)->hex();
    expect($regex->match('abcDEF0123'))->toBeTrue()
        ->and($regex->match('ghijk'))->toBeFalse();
});

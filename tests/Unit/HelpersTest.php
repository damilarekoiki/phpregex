<?php

use Ten\Phpregex\Regex;

test('email helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->email();
    expect($regex->match('test@example.com'))->toBeTrue()
        ->and($regex->match('user.name+tag@domain.co.uk'))->toBeTrue()
        ->and($regex->match('invalid-email'))->toBeFalse();

    expect($regex->count('test@example.com'))->toBe(1);
    expect($regex->replace('test@example.com', 'REPLACED'))->toBe('REPLACED');
});

test('ipv4 helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->ipv4();
    expect($regex->match('127.0.0.1'))->toBeTrue()
        ->and($regex->match('192.168.1.1'))->toBeTrue()
        ->and($regex->match('256.256.256.256'))->toBeFalse();

    $nonFullString = Regex::build()->ipv4();
    expect($nonFullString->count('IPs: 1.1.1.1, 2.2.2.2'))->toBe(2);
    expect($nonFullString->replace('IPs: 1.1.1.1, 2.2.2.2', 'X'))->toBe('IPs: X, X');
});

test('ipv6 helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->ipv6();
    expect($regex->match('2001:0db8:85a3:0000:0000:8a2e:0370:7334'))->toBeTrue()
        ->and($regex->match('127.0.0.1'))->toBeFalse();
});

test('uuid helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->uuid();
    expect($regex->match('550e8400-e29b-41d4-a716-446655440000'))->toBeTrue()
        ->and($regex->match('not-a-uuid'))->toBeFalse();
});

test('url helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->url();
    expect($regex->match('https://google.com'))->toBeTrue()
        ->and($regex->match('http://www.test.io/path?query=1'))->toBeTrue()
        ->and($regex->match('google.com'))->toBeFalse();

    $nonFullString = Regex::build()->url();
    expect($nonFullString->count('Visit https://a.com or http://b.org'))->toBe(2);
    expect($nonFullString->replace('Visit https://a.com or http://b.org', 'LINK'))->toBe('Visit LINK or LINK');
});

test('alpha helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->alpha();
    expect($regex->match('abcABC'))->toBeTrue()
        ->and($regex->match('abc123'))->toBeFalse();

    expect($regex->count('abcABC'))->toBe(1);
    expect($regex->replace('abcABC', 'letters'))->toBe('letters');
});

test('digits helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->digits();
    expect($regex->match('123456'))->toBeTrue()
        ->and($regex->match('123a45'))->toBeFalse();

    $nonFullString = Regex::build()->digits();
    expect($nonFullString->count('123 abc 456'))->toBe(2);
    expect($nonFullString->replace('123 abc 456', 'X'))->toBe('X abc X');
});

test('hex color helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->hexColor();
    expect($regex->match('#fff'))->toBeTrue()
        ->and($regex->match('#ffffff'))->toBeTrue()
        ->and($regex->match('fff'))->toBeFalse();

    $nonFullString = Regex::build()->hexColor();
    expect($nonFullString->count('Colors: #abc, #defg, #123456'))->toBe(3);
    expect($nonFullString->replace('Colors: #abc, #defg, #123456', 'COLOR'))->toBe('Colors: COLOR, COLORg, COLOR');
});

test('slug helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->slug();
    expect($regex->match('this-is-a-slug'))->toBeTrue()
        ->and($regex->match('slug123'))->toBeTrue()
        ->and($regex->match('Not-A-Slug'))->toBeFalse()
        ->and($regex->match('not a slug'))->toBeFalse();

    $nonFullString = Regex::build()->slug();
    expect($nonFullString->count('slugs: first-post, second-post'))->toBe(3);
    expect($nonFullString->replace('slugs: first-post, second-post', 'X'))->toBe('X: X, X');
});

test('credit card helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->creditCard();
    expect($regex->match('1234-5678-9012-3456'))->toBeTrue()
        ->and($regex->match('1234 5678 9012 3456'))->toBeTrue()
        ->and($regex->match('1234567890123456'))->toBeTrue();
});

test('ssn helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->ssn();
    expect($regex->match('123-45-6789'))->toBeTrue()
        ->and($regex->match('123456789'))->toBeFalse();
});

test('zip code helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->zipCode();
    expect($regex->match('12345'))->toBeTrue()
        ->and($regex->match('12345-6789'))->toBeTrue()
        ->and($regex->match('1234'))->toBeFalse();
});

test('mac address helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->macAddress();
    expect($regex->match('00:1A:2B:3C:4D:5E'))->toBeTrue()
        ->and($regex->match('00-1A-2B-3C-4D-5E'))->toBeTrue()
        ->and($regex->match('001A2B3C4D5E'))->toBeFalse();
});

test('date helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->date();
    expect($regex->match('2023-12-27'))->toBeTrue()
        ->and($regex->match('27-12-2023'))->toBeFalse();
});

test('time helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->time();
    expect($regex->match('14:30:15'))->toBeTrue()
        ->and($regex->match('14:30'))->toBeFalse();
});

test('handle helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->handle();
    expect($regex->match('@user_name'))->toBeTrue()
        ->and($regex->match('user_name'))->toBeFalse();
});

test('hex helper', function (): void {
    $regex = Regex::build(fullStringMatch: true)->hex();
    expect($regex->match('abcDEF0123'))->toBeTrue()
        ->and($regex->match('ghijk'))->toBeFalse();
});

<?php

use Ten\Phpregex\Regex;

test('email helper', function (): void {
    $regex = Regex::build()->beginsWith('')->email()->endsWith('');
    expect($regex->match('test@example.com'))->toBeTrue()
        ->and($regex->match('user.name+tag@domain.co.uk'))->toBeTrue()
        ->and($regex->match('invalid-email'))->toBeFalse();
});

test('ipv4 helper', function (): void {
    $regex = Regex::build()->beginsWith('')->ipv4()->endsWith('');
    expect($regex->match('127.0.0.1'))->toBeTrue()
        ->and($regex->match('192.168.1.1'))->toBeTrue()
        ->and($regex->match('256.256.256.256'))->toBeFalse();
});

test('ipv6 helper', function (): void {
    $regex = Regex::build()->beginsWith('')->ipv6()->endsWith('');
    expect($regex->match('2001:0db8:85a3:0000:0000:8a2e:0370:7334'))->toBeTrue()
        ->and($regex->match('127.0.0.1'))->toBeFalse();
});

test('uuid helper', function (): void {
    $regex = Regex::build()->beginsWith('')->uuid()->endsWith('');
    expect($regex->match('550e8400-e29b-41d4-a716-446655440000'))->toBeTrue()
        ->and($regex->match('not-a-uuid'))->toBeFalse();
});

test('url helper', function (): void {
    $regex = Regex::build()->beginsWith('')->url()->endsWith('');
    expect($regex->match('https://google.com'))->toBeTrue()
        ->and($regex->match('http://www.test.io/path?query=1'))->toBeTrue()
        ->and($regex->match('google.com'))->toBeFalse();
});

test('alpha helper', function (): void {
    $regex = Regex::build()->beginsWith('')->alpha()->endsWith('');
    expect($regex->match('abcABC'))->toBeTrue()
        ->and($regex->match('abc123'))->toBeFalse();
});

test('alphanumeric helper', function (): void {
    $regex = Regex::build()->beginsWith('')->alphanumeric()->endsWith('');
    expect($regex->match('abc123ABC'))->toBeTrue()
        ->and($regex->match('abc-123'))->toBeFalse();
});

test('digits helper', function (): void {
    $regex = Regex::build()->beginsWith('')->digits()->endsWith('');
    expect($regex->match('123456'))->toBeTrue()
        ->and($regex->match('123a45'))->toBeFalse();
});

test('hex color helper', function (): void {
    $regex = Regex::build()->beginsWith('')->hexColor()->endsWith('');
    expect($regex->match('#fff'))->toBeTrue()
        ->and($regex->match('#ffffff'))->toBeTrue()
        ->and($regex->match('fff'))->toBeFalse();
});

test('slug helper', function (): void {
    $regex = Regex::build()->beginsWith('')->slug()->endsWith('');
    expect($regex->match('this-is-a-slug'))->toBeTrue()
        ->and($regex->match('slug123'))->toBeTrue()
        ->and($regex->match('Not-A-Slug'))->toBeFalse();
});

test('credit card helper', function (): void {
    $regex = Regex::build()->beginsWith('')->creditCard()->endsWith('');
    expect($regex->match('1234-5678-9012-3456'))->toBeTrue()
        ->and($regex->match('1234 5678 9012 3456'))->toBeTrue()
        ->and($regex->match('1234567890123456'))->toBeTrue();
});

test('ssn helper', function (): void {
    $regex = Regex::build()->beginsWith('')->ssn()->endsWith('');
    expect($regex->match('123-45-6789'))->toBeTrue()
        ->and($regex->match('123456789'))->toBeFalse();
});

test('zip code helper', function (): void {
    $regex = Regex::build()->beginsWith('')->zipCode()->endsWith('');
    expect($regex->match('12345'))->toBeTrue()
        ->and($regex->match('12345-6789'))->toBeTrue()
        ->and($regex->match('1234'))->toBeFalse();
});

test('mac address helper', function (): void {
    $regex = Regex::build()->beginsWith('')->macAddress()->endsWith('');
    expect($regex->match('00:1A:2B:3C:4D:5E'))->toBeTrue()
        ->and($regex->match('00-1A-2B-3C-4D-5E'))->toBeTrue()
        ->and($regex->match('001A2B3C4D5E'))->toBeFalse();
});

test('date helper', function (): void {
    $regex = Regex::build()->beginsWith('')->date()->endsWith('');
    expect($regex->match('2023-12-27'))->toBeTrue()
        ->and($regex->match('27-12-2023'))->toBeFalse();
});

test('time helper', function (): void {
    $regex = Regex::build()->beginsWith('')->time()->endsWith('');
    expect($regex->match('14:30:15'))->toBeTrue()
        ->and($regex->match('14:30'))->toBeFalse();
});

test('handle helper', function (): void {
    $regex = Regex::build()->beginsWith('')->handle()->endsWith('');
    expect($regex->match('@user_name'))->toBeTrue()
        ->and($regex->match('user_name'))->toBeFalse();
});

test('hex helper', function (): void {
    $regex = Regex::build()->beginsWith('')->hex()->endsWith('');
    expect($regex->match('abcDEF0123'))->toBeTrue()
        ->and($regex->match('ghijk'))->toBeFalse();
});

<?php

use Ten\Phpregex\Regex;

test('it can check if a string contains another string anywhere', function (): void {
    $regex = Regex::build()->contains('apple')->contains('banana');

    expect($regex->match('I have an apple and a banana'))->toBeTrue();
    expect($regex->match('I have a banana and an apple'))->toBeTrue();
    expect($regex->match('I have an apple'))->toBeFalse();
});

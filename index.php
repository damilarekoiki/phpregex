
<?php
require_once 'vendor/autoload.php';

use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

$regex = Regex::build()
    ->contains('a')
    ->contains('b')
    ->sequence(function (Sequence $sequence): void {
        $sequence->then('d')
            ->then('e');
    });

echo $regex;

$regexMatch = $regex->match('abcde');

echo $regexMatch ? 'Match' : 'No Match';

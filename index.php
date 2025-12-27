
<?php
require_once 'vendor/autoload.php';

use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

$regex = Regex::build()
    // ->contains('a')
    // ->contains('b')
    ->sequence(function (Sequence $sequence): void {
        $sequence->then(fn (Regex $regex) => $regex->containsLowercaseLetter())
            ->then(fn (Regex $regex) => $regex->contains('apple'))
            ;
    }, startFromBeginning: false)
    // ->containsNonDigit()
    // ->addPattern('(?=.*a)(?=.*b)(?=.*(.*(.*apple).*(?=.*banana)))')
    ;

echo $regex->get();

$regexMatch = $regex->match('bbanana2apple');

echo $regexMatch ? 'Match' : 'No Match';

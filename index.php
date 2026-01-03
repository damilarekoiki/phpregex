
<?php
require_once 'vendor/autoload.php';

use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

$regex = Regex::build(fullStringMatch: true)
    // ->contains('nana')
    // ->not('bbanana2apple')
    // ->doesntContain('z')
    // ->addPattern('^apple$')
    // // ->addPattern('^(?=.*a)(?!.*c).*$')
    ->sequence(function (Sequence $sequence): void {
        $sequence->then('banana')
            ->then(fn (Regex $regex) => $regex->not('d'))
        ;
    }, startFromBeginning: false)
    // ->or
    // ->group(function (Regex $regex): void {
    //     $regex->contains('a')
    //         ->doesntContain('z');
    // })
    // ->and
    // ->group(function (Regex $regex): void {
    //     $regex->contains('a')
    //         ->doesntContain('z');
    // })
    // ->or
    // ->doesntContain('apples')
    // ->contains('apple')
    // ->containsNonDigit()
    // ->addPattern('(?=.*a)(?=.*b)(?=.*(.*(.*apple).*(?=.*banana)))')
;

$regex = Regex::build(fullStringMatch: true)
        ->containsOnlyDigits();

echo $regex->get();

$regexMatch = $regex->match('this-slug');

echo $regexMatch ? 'Match' : 'No Match';

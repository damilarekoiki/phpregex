
<?php
require_once 'vendor/autoload.php';

use Ten\Phpregex\Regex;
use Ten\Phpregex\Sequence;

$regex = Regex::build(wholeString: true)
    // ->contains('nana')
    // ->not('bbanana2apple')
    // ->doesntContain('z')
    // ->addPattern('^apple$')
    // ->wholeString()
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

echo $regex->get();

$regexMatch = $regex->match('bbanana2apple');

echo $regexMatch ? 'Match' : 'No Match';

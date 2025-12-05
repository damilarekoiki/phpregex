<?php

namespace Ten\Phpregex;

interface RegexContract
{
    public function parse($expression);

    public function match($subject);

    public function replace($subject);

    public function replaceAll($subject);

    public function get($subject);

    public function count($subject);

    public function or();

    public function and();

    public function not();

    // $subject is a string of characters. Contains any of the characters in the string|Array
    public function containsAny(string|array $subject);

    // Contains exactly the whole string or number
    public function contains(string|int $subject);

    // Contains case insensitive
    public function containsCaseInsensitive(string|int $subject);

    // Contains any string or int that is alphabetically or numerically between subject1 and subject2
    public function between($subject1, $subject2, $caseSensitive);

    public function notBetween($subject1, $subject2);

    public function beginsWith($subject);

    public function endsWith($subject);

    public function containsDigit();

    public function containsNonDigit();

    public function containsAlphaNumeric();

    public function containsNonAlphaNumeric();

    public function beginsOrEndsWith($subject);

    public function containsUnicode($subject);

    public function containsAtleastOne($subject);

    public function containsZeroOrMore($subject);

    public function containsZeroOrOne($subject);

    public function containsExactSequencesOf($subject, $occurences);

    public function containsSequencesOf($subject, $minOcurrences, $maxOccurrences);

    public function containsAtleastSequencesOf($subject, $minOcurrences);

}
<?php

namespace Ten\Phpregex;

interface RegexContract
{
    public function parse(string $expression): self;

    public function match(string $subject): bool;

    public function replace(string $subject): string;

    public function replaceAll(string $subject): string;

    public function get(string $subject): string;

    public function count(string $subject): int;

    public function or(): self;

    public function and(): self;

    public function not(): self;

    /**
     * @param string|array<int|string> $subject
     */
    public function containsAny(string|array $subject): self;

    public function contains(string|int $subject): self;

    public function containsCaseInsensitive(string|int $subject): self;

    public function between(string|int $subject1, string|int $subject2, bool $caseSensitive): self;

    public function notBetween(string|int $subject1, string|int $subject2): self;

    public function beginsWith(string|int $subject): self;

    public function endsWith(string|int $subject): self;

    public function containsDigit(): self;

    public function containsNonDigit(): self;

    public function containsAlphaNumeric(): self;

    public function containsNonAlphaNumeric(): self;

    public function beginsOrEndsWith(string|int $subject): self;

    public function containsUnicode(string $subject): self;

    public function containsAtleastOne(string|int $subject): self;

    public function containsZeroOrMore(string|int $subject): self;

    public function containsZeroOrOne(string|int $subject): self;

    public function oneOrMore(string|int $subject): self;

    public function zeroOrMore(string|int $subject): self;

    public function maybe(string|int $subject): self;

    public function containsExactSequencesOf(string|int $subject, int $occurences): self;

    public function containsSequencesOf(string|int $subject, int $minOcurrences, int $maxOccurrences): self;

    public function containsAtleastSequencesOf(string|int $subject, int $minOcurrences): self;

    public function then(string|int $subject): self;

    public function repeat(string|int $subject, int $times): self;

    public function repeatAtLeast(string|int $subject, int $times): self;

    public function repeatBetween(string|int $subject, int $min, int $max): self;

    public function containsLetter(): self;

    public function containsLowercaseLetter(): self;

    public function containsUppercaseLetter(): self;

    public function containsWhitespace(): self;

    public function containsNonWhitespace(): self;

    public function containsWordCharacter(): self;

    public function containsNonWordCharacter(): self;

    public function containsAnything(): self;

    public function followedBy(string|int $subject): self;

    public function notFollowedBy(string|int $subject): self;

    public function precededBy(string|int $subject): self;

    public function notPrecededBy(string|int $subject): self;


}
